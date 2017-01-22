// Common function

// ...

// READY
jQuery(document).ready(function () {

    $(document).on('click', 'div.product-image a.set-image', function(){
        // Установка основной картинки
        var url = $(this).prop('href'),
            parent = $(this).parent();

        $.ajax({
            url: url,
            success: function (data) {
                if (data) {
                    $('#product-main-image').prop('src', data);
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
                        $('#product-main-image').prop('src', data);
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
    });

});
