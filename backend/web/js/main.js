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
			if (this.window && !this.window.closed) {
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
});
