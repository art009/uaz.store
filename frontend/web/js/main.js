/**
 * Detects mobile type
 *
 * @returns {boolean}
 */
function isMobile() {

	var media = window
		.getComputedStyle(document.querySelector('body'), '::after')
		.getPropertyValue('content')
		.replace(/"/g, "")
		.replace(/'/g, "");

    return (media === 'mobile')
}

/**
 * Init/Destroy sections animation
 */
function initAnimatedSections() {
	var cont = $('main.site-index'),
		instance = $(cont).animateSection('instance');

	if (isMobile()) {
		(typeof instance !== 'undefined') && $(cont).animateSection('destroy');
	} else {
		(typeof instance === 'undefined') && $(cont).animateSection();
	}
}

(function($) {

	/**
	 * Sections widget
	 */
	$.widget('main.animateSection', {

		index: 0,
		max: 2,
		animated: false,

		options: {
			animatedEl: null,
			bullets: null,
			isMobile: false
		},

		_create: function() {

			self = this;

			self.element.find('section').wrapAll('<div class="sections-container"><div class="sections-inner"></div></div>');
			self.option('animatedEl', self.element.find('.sections-inner'));
			self.option('bullets', self.element.find('nav ul li a'));

			$(self.option('bullets')).on('click touchend', function () {
				self.to($(this).index('.nav-bullet'));
				return false;
			});

			$(window).on('DOMMouseScroll mousewheel', self.scroll);

			$(document).on('keydown', function(event){
				if (event.which == '39' || event.which == '40') {
					event.preventDefault();
					self.next();
				} else if(event.which == '37' || event.which == '38') {
					event.preventDefault();
					self.prev();
				}
			});

			self.change();
		},

		_destroy: function ()
		{
			$(window).off('DOMMouseScroll mousewheel', self.scroll);
			$(window).off('scroll', self.scroll);
			$(self.option('bullets')).off('click touchend');
			$(document).off('keydown');

			self.element.find('section').unwrap().unwrap();
		},

		canAnimate: function() {
			return !self.animated;
		},

		change: function () {
			var h = self.index * -100;
			self.animated = true;
			$(self.option('bullets')).removeClass('active');
			$(self.option('bullets')).eq(self.index).addClass('active');
			$(self.option('animatedEl')).stop().animate({marginTop: h +'vh'}, 900, 'easeOutCubic', function () {
				self.animated = false;
			});
		},

		next: function () {
			self.to(this.index + 1);
		},

		prev: function () {
			self.to(this.index - 1);
		},

		to: function (section) {
			if (section >= 0 && section <= this.max) {
				this.index = section;
				this.change();
			}
		},

		scroll: function (event) {
			if ($(event.target).closest('.main-about').length > 0) {
				return true;
			}
			if (!self.canAnimate()) {
				return false;
			}

			if (event.originalEvent.detail < 0 || event.originalEvent.wheelDelta > 0) {
				self.prev();
			} else {
				self.next();
			}
		}

	});

	/**
	 * Cart plugin
	 *
	 * @returns {jQuery}
	 */
	$.fn.cart = function () {

		function alert(type, message) {
			showAlert(type, message);
		}

		function request(action, productId) {
			$.ajax('/cart/' + action, {
				data: {productId: productId},
				dataType: 'json',
				success: function (data) {
					if (data.type === 'success') {
						if (action === 'add') {
							alert(data.type, data.message);
						}
						$('nav a.cart-link').attr('data-count', data.count);
						if (data.total) {
							$('.cart-index .summary .total b').html(data.total);
						}
						var tr = $('.cart-index table tr[data-id="' + productId + '"]');
						if (data.sum && data.quantity) {
							$(tr).find('.quantity > div').html(data.quantity);
							$(tr).find('.total').html(data.sum);
						}
						if (action === 'del') {
							$(tr).remove();
						}
					} else {
						alert(data.type, data.message);
					}
				},
				error: function (error) {
					self.alert('danger', 'Ошибка корзины: ' + error.responseText);
				}
			});
		}

		// Получение идентификатора товара
		function productId(element) {
			return $(element).data('id');
		}

		// Получение кол-ва товара
		function productQuantity(element) {
			var tr = $('.cart-index table tr[data-id="' + $(element).data('id') + '"]');
			return parseInt($(tr).find('.quantity > div').html());
		}

		$(this)
			.on('click', '.add-cart-product', function () {
				// Добавление товара в корзину
				request('add', productId(this));
				return false;
			})
			.on('click', '.inc-cart-product', function () {
				// Прибавление единицы товара в корзине
				request('inc', productId(this));
				return false;
			})
			.on('click', '.dec-cart-product', function () {
				// Убавление единицы товара в корзине
				var quantity = productQuantity(this);
				if (quantity <= 1) {
					if (confirm('Вы действительно хотите удалить товар из корзины?')) {
						request('del', productId(this));
					}
				} else {
					request('dec', productId(this));
				}
				return false;
			});

		return this;
	};

})(jQuery);

(function($) {
	/**
	 * Image view widget
	 */
	$.widget('main.imageViewer', {

		scale: 1,
		minScale: 0.25,
		maxScale: 2,
		offsetX: 0,
		offsetY: 0,
		maxOffsetX: 0,
		maxOffsetY: -100,
		container: null,
		drag: false,
		coordinates: {
			x: null,
			y: null
		},

		_create: function() {

			self = this;
			self.container = $(this.element).parent();

			self.resolveOffset();
			//self.initScale();

			$(self.container).on('mousedown touchstart', function(event){
				self.drag = true;
				self.coordinates = self.getCoordinates(event);
			}).on('mousemove touchmove', function(event){
				event.preventDefault();
				event.stopPropagation();
				if (self.drag && self.coordinates.x !== null && self.coordinates.y !== null) {
					var c = self.getCoordinates(event),
						offsetX = self.offsetX + Math.round(c.x - self.coordinates.x),
						offsetY = self.offsetY + Math.round(c.y - self.coordinates.y)
					;
					if (offsetX < self.maxOffsetX) {
						offsetX = self.maxOffsetX;
					}
					if (offsetX > 0) {
						offsetX = 0;
					}
					if (offsetY < self.maxOffsetY) {
						offsetY = self.maxOffsetY;
					}
					if (offsetY > 0) {
						offsetY = 0;
					}
					if (self.offsetX !== offsetX || self.offsetY !== offsetY) {
						self.offsetX = offsetX;
						self.offsetY = offsetY;
						self.move();

					}
					self.coordinates = c;
				}
			}).on('mouseup touchend mouseleave', function(event){
				self.drag = false;
				self.coordinates = { x: null, y: null };
			});

			$(document).on('click touchend', '.tool-zoom-minus', function () {
				self.zoomOut();
				return false;
			}).on('click touchend', '.tool-zoom-original', function () {
				self.zoom(1);
				return false;
			}).on('click touchend', '.tool-zoom-plus', function () {
				self.zoomIn();
				return false;
			}).on('keydown', function(event){
				if (event.which === 37) {
					self.left();
					event.preventDefault();
				} else if(event.which === 38) {
					self.top();
					event.preventDefault();
				} else if(event.which === 39) {
					self.right();
					event.preventDefault();
				} else if(event.which === 40) {
					self.bottom();
					event.preventDefault();
				}
			});
		},

		_destroy: function ()
		{
			$(document).off('click touchend', '.tool-zoom-minus');
			$(document).off('click touchend', '.tool-zoom-original');
			$(document).off('click touchend', '.tool-zoom-plus');
		},

		zoomIn: function () {
			self.zoom((this.scale * 10 + 2) / 10);
		},

		zoomOut: function () {
			self.zoom((this.scale * 10 - 2) / 10);
		},

		zoom: function (scale) {
			if (scale >= self.minScale && scale <= self.maxScale) {
				self.center();
				self.scale = scale;
				$('.tool-zoom-label b').html(scale * 100);
				$(self.element).parent().css({
					'-webkit-transform' : 'scale(' + scale + ')',
					'-moz-transform'    : 'scale(' + scale + ')',
					'-ms-transform'     : 'scale(' + scale + ')',
					'-o-transform'      : 'scale(' + scale + ')',
					'transform'         : 'scale(' + scale + ')'
				});
				self.resolveOffset();
			}
		},

		resolveOffset: function () {
			var contRect = $(self.container)[0].getBoundingClientRect();
			self.maxOffsetX = -1 * (contRect.width - $(self.container).parent().outerWidth() + 10);
			self.maxOffsetY = -1 * (contRect.height - $(self.container).parent().outerHeight() + 60);
		},

		initScale: function () {
			var scale = $(self.container).parent().outerWidth() / $(self.container).outerWidth();
			if (scale < 1) {
				if (scale > 0.8) {
					scale = 0.8;
				} else if (scale > 0.6) {
					scale = 0.6;
				} else if (scale > 0.4) {
					scale = 0.4;
				} else {
					scale = self.minScale;
				}
				self.zoom(scale);
			}
		},

		left: function () {
			if (self.offsetX > self.maxOffsetX) {
				self.offsetX -= 10;
				self.move();
			}
		},

		right: function () {
			self.offsetX += 10;
			if (self.offsetX > 0) {
				self.offsetX = 0;
			}
			self.move();
		},

		top: function () {
			if (self.offsetY > self.maxOffsetY) {
				self.offsetY -= 10;
				self.move();
			}
		},

		bottom: function () {
			self.offsetY += 10;
			if (self.offsetY > 0) {
				self.offsetY = 0;
			}
			self.move();
		},

		center: function () {
			self.offsetX = 0;
			self.offsetY = 0;
			self.move();
		},

		move: function() {
			var cont = $(self.element).parent();
			return $(cont).css({
				'left': self.offsetX + 'px',
				'top': self.offsetY + 'px'
			});
		},

		getCoordinates: function (event) {
			if (typeof event.originalEvent.touches !== 'undefined' && event.originalEvent.touches.length) {
				return {
					x: event.originalEvent.touches[0].pageX,
					y: event.originalEvent.touches[0].pageY
				}
			} else {
				return {
					x: (event.pageX || event.clientX),
					y: (event.pageY || event.clientY)
				}
			}
		}

	});
})(jQuery);

/**
 * Вывод сообщения
 *
 * @param type
 * @param text
 */
function showAlert(type, text) {

	var cont = $('#alert-container'),
		k = $(cont).children().length,
		template = document.getElementById('alert-template').innerHTML;

	Mustache.parse(template);

	var	output = Mustache.render(template, {
			type: type,
			body: text,
			k: k++
		}),
		alert = $(output);

	$(cont).prepend($(alert));

	setTimeout(function () {
		$(alert).fadeOut('slow', function(){
			$(this).remove();
		});
	}, 30000);

	if (k > 3) {
		$(cont).children().slice(3).fadeOut('slow', function(){
			$(this).remove();
		});
	}
}

/**
 * Document READY
 */
$(document).ready(function($){

	/**
	 * Bootstrap tooltips
	 */
	$('[data-tooltip="tooltip"]').tooltip();
	$('[data-tooltip="tooltip-image"]').tooltip({
		animated: 'fade',
		placement: 'bottom',
		html: true
	});

	initAnimatedSections();

	$(window).on('resize', function(){

		initAnimatedSections();

	});

	$(document).cart();

	$(document).on('submit', 'form.modal-form', function () {
		var form = $(this),
			modal = '#' + $(form).prop('id') + '-modal';

		$.ajax($(form).prop('action'), {
			data: $(form).serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function(){
				$(form).find(':input').prop('disabled', true);
			},
			success: function (data) {
				if (data.errors) {
					$(form).find(':input').prop('disabled', false);
					$.each(data.errors, function (attribute, error) {
						var el = $(form).find('[name $= "[' + attribute + ']"]');
						if (el.length) {
							var parent = $(el).closest('.form-group');
							$(parent).addClass('has-error');
							$(parent).find('.help-block-error').html(error);
						}
					});
				} else {
					$(form).find('.form-group').removeClass('has-error');
					$(form).find('.help-block-error').html('');
				}
				if (data.success) {
					$(modal).modal('hide');
					showAlert('success', data.success);
				}
			},
			error: function (error) {
				showAlert('danger', 'Ошибка отправки формы: ' + error.responseText);
				$(form).find(':input').prop('disabled', false);
			}
		});

		return false;
	});

	$(document).on('click', '.category-tree .toggle-area', function () {
		var li = $(this).closest('li');

		if ($(li).hasClass('expanded')) {
			$(li).removeClass('expanded');
		} else {
			$(li).addClass('expanded');
		}

		return false;
	});

	$(document).on('click', '.image-product', function (event) {

        event.preventDefault();
        $('.chosen').removeClass('chosen');

        $(this).addClass('chosen');
        var number = $(this).attr('id'),
			section = $('tr#row'+number+''),
       	    pos = section.offset().top - $('#w1-collapse').height();

        $('html, body').animate({scrollTop: pos}, 1000);
        $(section).animate({'opacity':'0'},200,function(){
            $(this).addClass('chosen');
            $(this).animate({'opacity':'1'},200);
        });
    });

    $(document).on('click', '.manual-product-row', function (event) {

        event.preventDefault();
        $('.chosen').removeClass('chosen');

        $(this).addClass('chosen');

        var number = $(this).attr('id').substr(3),
			section = $('.manual-page-container'),
			pos = section.offset().top;

        $('html, body').animate({scrollTop: pos }, 1000);
        $('#' + number + '.image-product').animate({'opacity':'0'},200,function(){
            $(this).addClass('chosen');
            $(this).animate({'opacity':'1'},200);
        });
    });

	$("[data-fancybox]").fancybox({
		'padding': 0
	});
});

$(window).load(function() {
	$('.manual-page-image img').imageViewer();
});