(function () {
	function PopupWindow() {

		this.window = null;
		this.name = "popup-window";
		this.messageData = null;

		var MAX_WIDTH = 980;
		var MAX_HEIGHT = 600;

		this.customWidth = null;
		this.customHeight = null;

		this.getWidth = function () {
			var width = this.customWidth ? this.customWidth : window.innerWidth;
			return width > MAX_WIDTH ? MAX_WIDTH : width;
		};

		this.getHeight = function () {
			var height = this.customHeight ? this.customHeight : window.innerWidth * 0.8;
			return height > MAX_HEIGHT ? MAX_HEIGHT : height;
		};

		this.getOptions = function () {
			var width = this.getWidth(),
				height = this.getHeight(),
				centerWidth = (window.screen.width - width) / 2,
				centerHeight = (window.screen.height - height) / 2;

			return "width=" + width + ",height=" + height + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes";
		};

		this.open = function (url) {
			this.window = window.open('', this.name, this.getOptions());
			if (this.window.document.location.href !== url) {
				this.window = window.open(url, this.name, this.getOptions());
			} else {
				this.window.postMessage(this.messageData, url);
				this.messageData = null;
			}

			this.window.focus();
		};

		this.message = function (url, data) {
			if (this.window && !this.window.closed && this.window.document.location.href === url) {
				this.window.postMessage(data, url);
				this.window.focus();
			} else {
				this.messageData = data;
				this.open(url);
			}
		};

		this.receiveMessage = function(event) {
			if (event.source.name === this.name && event.data === 'ready' && this.messageData) {
				this.window.postMessage(this.messageData, event.source.location.href);
				this.messageData = null;
			}
		}
	}

	window.popup = new PopupWindow();
	window.addEventListener("message", function(event) {
		window.popup.receiveMessage(event);
	}, false);
})();

function showProviderPosition(providerId, key) {
	var url = window.location.origin + '/pms/shop-item/list?providerId=' + providerId;
	window.popup.message(url, {goTo: key});
}

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

function getCoordinates(event) {
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

// READY
jQuery(document).ready(function () {

    $("[data-fancybox]").fancybox({
        'padding': 0
    });

    $(document).on('click', 'div.product-image a.set-image', function(){
        // Установка основной картинки
        var url = $(this).prop('href'),
            parent = $(this).parent();

        $.ajax({
            url: url,
            success: function (data) {
                if (data) {
                	var mainImage = $('#product-main-image');
                    $(mainImage).prop('src', data);
                    $(mainImage).parent().prop('href', data.replace('/m/', '/'));
                }
                $('div.product-image').removeClass('main');
                $(parent).addClass('main');
            },
            error: function(error) {
                alert(error.responseText);
            }
        });

        return false;
    }).on('click', 'div.product-image a.delete-image', function(){
        // Удаление картинки
        var url = $(this).prop('href'),
            image = $(this).closest('div.product-image');

        if (confirm('Вы действительно хотите удалить картинку?')) {
            $.ajax({
                url: url,
                success: function (data) {
                    if (data) {
	                    var mainImage = $('#product-main-image');
                        $(mainImage).prop('src', data);
                        $(mainImage).parent().prop('href', data.replace('/m/', '/'));
                        var addImage = $('div.product-image').find('img[src="' + data + '"]');
                        if (addImage.length) {
                            $(addImage).closest('div.product-image').addClass('main');
                        }
                    } else {
                        $('#product-main-image').remove();
                    }
                    $(image).remove();
                },
                error: function(error) {
                    alert(error.responseText);
                }
            });
        }

        return false;
    }).on('click', 'a.btn-show-in-list', function () {
    	var row = $(this).closest('tr'),
		    table = $(row).closest('table'),
		    providerId = $(table).data('provider-id'),
		    key = $(row).data('key');

	    showProviderPosition(providerId, key);

    	return false;
    }).on('pjax:beforeSend', '#shop-item-bind-search', function () {
    	$(this).find('form input, form button').prop('disabled', true);
    	$(this).find('table').css('opacity', 0.4);
    }).on('pjax:error', '#shop-item-bind-search', function () {
	    $(this).find('form input, form button').prop('disabled', false);
	    $(this).find('table').css('opacity', 1);

    }).on('click', 'a.btn-link-item', function () {
    	var url = $(this).prop('href'),
		    currentRow = $(this).closest('tr'),
        	row = $(currentRow).clone(),
    		table = $('#shop-item-link-table'),
		    exists = $(table).find('tr[data-key="' + $(row).data('key') + '"]').length,
	        rowsCount = $(table).find('tr[data-key]').length,
		    link = $(row).find('a.btn-link-item'),
		    query = $(link).prop('href').split('?')[1];

	    if (!exists) {
		    $.ajax({
				url: url,
			    success: function () {
				    $(row).find('td').first().html(rowsCount + 1);
				    $(row).find('td').last().html(
				    	'<input type="number" class="form-control quantity" name="quantity" value="1" title="Количество" step="1" min="1">' +
					    '<a class="btn-unlink-item" href="/pms/shop-item/unlink?' + query + '" title="Отвязать">' +
					    '<span class="glyphicon glyphicon-remove-sign"></span>' +
					    '</a>'
				    );
				    $(currentRow).find('a.btn-link-item').addClass('hidden');
				    if (rowsCount === 0) {
					    $(table).find('tbody').html(row);
				    } else {
					    $(table).find('tbody').append(row);
				    }
			    },
			    error: function(error) {
					alert('Невозможно установить связь: ' + error.responseText);
			    }
		    });
	    }

        return false;
    }).on('click', 'a.btn-unlink-item', function () {
        var url = $(this).prop('href'),
	        currentRow = $(this).closest('tr'),
	        table = $('#shop-item-link-table'),
	        rowsCount = $(table).find('tr[data-key]').length,
	        key = $(currentRow).data('key'),
	        row = $('#shop-item-provider-item-table').find('tr[data-key="' + key + '"]');

        $.ajax({
            url: url,
            success: function () {
            	$(currentRow).remove();
                if (row) {
                	$(row).find('a.btn-link-item').removeClass('hidden');
                }
                if (rowsCount <= 1) {
	                $(table).find('tbody').html('<tr><td colspan="7"><div class="empty">Ничего не найдено.</div></td></tr>');
                }
            },
            error: function(error) {
	            alert('Невозможно разорвать связь: ' + error.responseText);
            }
        });

        return false;
    }).on('click', 'a.btn-compare', function () {
        var url = $(this).prop('href');
	    window.popup.open(url);

        return false;
    }).on('change', 'input.quantity', function () {
    	var input = $(this),
		    quantity = $(input).val(),
		    tr = $(input).closest('tr'),
		    table = $(tr).closest('table'),
		    shopItemId = $(table).data('item-id'),
		    providerItemId = $(tr).data('key');

	    $.ajax({
		    url: '/pms/shop-item/quantity?shopItemId=' + shopItemId + '&providerItemId=' + providerItemId + '&quantity=' + quantity,
		    beforeSend: function () {
			    $(input).prop('disabled', true);
		    },
		    success: function () {},
		    error: function(error) {
			    alert('Невозможно изменить количество: ' + error.responseText);
		    },
		    complete: function () {
			    $(input).prop('disabled', false);
		    }
	    });
    });

    var dragContainer = $('.manual-page-container'),
	    dragEl = null,
	    dragSize = 0,
	    dragContainerCoordinates = { left: null, top: null };

	$(document).on('click touchend', '.manual-category-view .image-product', function(event) {
		dragContainerCoordinates = $(dragContainer).offset();
		if (dragEl !== null) {
			$(dragEl).removeClass('draggable');
			if (dragSize === 0) {
				dragSize = 1;

				$(dragEl).addClass('sizable');

				console.log(123);
			} else {
				dragSize = 0;

				$(dragEl).removeClass('sizable');

				var id = $(dragEl).data('id'),
					positions = [];

				$('.image-product[data-id="' + id + '"]').each(function (i, item) {
					var position = {
						left: $(item).position().left,
						top: $(item).position().top,
						width: $(item).outerWidth(),
						height: $(item).outerHeight()
					};

					positions.push(position);
				});

				$.ajax({
					url: '/manual-product/save-positions',
					type: 'post',
					data: {
						id: id,
						positions: positions
					},
					success: function () {
						console.log('Позиции успешно сохранены.');
					},
					error: function (error) {
						alert('Ошибка при сохранении позиций: ' + error.responseText);
					}
				});

				dragEl = null;
			}
		} else {
			dragSize = 0;

			dragEl = $(this);
			$(dragEl).addClass('draggable');
			$('.image-product').removeClass('chosen');
			$('.image-product[data-id="' + $(dragEl).data('id') + '"]').addClass('chosen');
		}
	});

	$(dragContainer).on('mousemove touchmove', function(event){
		event.preventDefault();
		event.stopPropagation();
		if (dragEl) {
			var c = getCoordinates(event),
				width = Math.round(c.x - dragContainerCoordinates.left - $(dragEl).position().left + $(dragEl).outerWidth() / 2),
				height = Math.round(c.y - dragContainerCoordinates.top - $(dragEl).position().top + $(dragEl).outerHeight() / 2),
				left = Math.round(c.x - dragContainerCoordinates.left - $(dragEl).outerWidth() / 2),
				top = Math.round(c.y - dragContainerCoordinates.top - $(dragEl).outerHeight() / 2);



			if ($(dragEl).hasClass('draggable')) {
				if (left < 0) {
					left = 0;
				}

				if (top < 0) {
					top = 0;
				}

				$(dragEl).css({
					'left': left + 'px',
					'top': top + 'px'
				});
			} else {
				if (width < 60) {
					width = 60;
				}

				if (height < 20) {
					height = 20;
				}

				$(dragEl).css({
					'width': width + 'px',
					'height': height + 'px'
				});
			}
		}
	});

	$(document).on('click touchend', 'a.choose-area', function(event){
		event.preventDefault();
		event.stopPropagation();

		var areaId = $(this).data('id');

		if (areaId) {
			$('.image-product').removeClass('chosen');
			$('.image-product[data-id="' + areaId + '"]').addClass('chosen');
		}
	});

	$(document).on('click touchend', 'a.add-area', function(event){
		event.preventDefault();
		event.stopPropagation();

		var areaId = $(this).data('id');

		if (areaId) {
			var image = $('<div class="image-product" data-id="' + areaId + '" style="left:0;top:0;width:60px;height:20px;">' + $(this).data('number') + '</div>');
			$(dragContainer).append(image);
			$(image).addClass('chosen').click();
		}
	});
});

$(window).load(function() {
	$('.manual-page-image img').imageViewer();
});
