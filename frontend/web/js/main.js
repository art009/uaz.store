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
	})
});
