$(document).ready(function(){
    $('.menu .item').tab();

    // Get the modal
    var modal = $('#myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
    var modalImg = $("#img01");
    var captionText = document.getElementById("caption");

    $('.light').click(function(){
        $('body').css('overflow', 'hidden');
        modal.css('display', 'block');
        modalImg.attr('src', $(this).attr('src'));
    });

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("closes")[0];

// When the user clicks on <span> (x), close the modal
    $(span).click(function(){
        modal.css('display', 'none');
        $('body').css('overflow', 'auto');
    });

    $('.ui.modal').modal('show');

    //add product to cart

    $('.add-to-cart').click(function(){
        var form = $( this ).parent().parent();
        var count = parseInt(form.find('#count').val());
            $.ajax({
                method: 'POST',
                url: "/add_to_cart",
                data: form.serialize(),
            })
                .done(function (data) {
                    if(count > 0 && count <= data.quantity) {
                        var get_current_cart_price = $('#cart_total_price').text();
                        var get_price = parseInt(get_current_cart_price) + parseInt(data.price * count);
                        $('#cart_total_price').text(get_price);
                        $('.cart-products-count').text(data.cart_products + ' шт.');
                            //success msg
                            swal({
                                title: "Товар добавлен",
                                text: "Ваш товар был успешно добавлен в корзину",
                                icon: "success",
                                button: "Продолжить",
                            });
                    } else if(count > data.quantity) {
                            swal({
                                title: "Внимание!",
                                text: "Количество покупаемого товара не должно превышать остаток",
                                icon: "warning",
                                button: "Продолжить",
                            });
                    }
                    });
    });

    $('.refresh').click(function(){
        var form = $( this ).parent().parent().parent().find('.cart_form_action');
        var product_id = form.find('.pid').val();
        var count = form.find('.p-count').val();

        form.find('.btn-action').val('ref');

        form.submit();
    });
    $('.delete-prod').click(function(){
        var form = $( this ).parent().parent().parent().find('.cart_form_action');
        var product_id = form.find('.pid').val();
        var count = form.find('.p-count').val();

        form.find('.btn-action').val('del');

        form.submit();
    });

    $('.checkAllBox').click(function(){
        if(!$(this).find('input:checkbox').is(':checked')) {
            $('.checkbox-prod').prop('checked', false);
        }
        else {
            $('.checkbox-prod').prop('checked', true);
        }
    });

    $('#btn_del_prod').click(function(){
        $('#action_btn').val('delete');
        $('.form-action-prod').submit();
    });
    $('#btn_cancel_prod').click(function(){
        $('#action_btn').val('cancel');
        $('.form-action-prod').submit();
    });
    $('#btn_merge_prod').click(function(){
        $('#action_btn').val('merge');
        $('.form-action-prod').submit();
    });
    $('#btn_ready_prod').click(function(){
        $('#action_btn').val('ready');
        $('.form-action-prod').submit();
    });
    $('#btn_archive_prod').click(function(){
        $('#action_btn').val('archive');
        $('.form-action-prod').submit();
    });

    //tabs
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").hide();
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).fadeIn(400);
    });
});

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

$('.message .close')
    .on('click', function() {
        $(this)
            .closest('.message')
            .transition('fade')
        ;
    });

//BY CAR
$('#fvendor').change(function(){
    var fvendor = $(this).val();
    var _token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
        method: 'POST',
        url: "/fvendor",
        data: {fvendor:fvendor, _token:_token},
    })
        .done(function (data) {
            $('#fcar option').remove();
            $('#fcar').append('<option>Выберите</option>');
            $.each(data, function(i, val){
                $('#fcar').append('<option value="' + val.fcar + '">' + val.fcar + '</option>');
            });
        })
});

$('#fcar').change(function(){
    var fcar = $(this).val();
    var _token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
        method: 'POST',
        url: "/fcar",
        data: {fcar:fcar, _token:_token},
    })
        .done(function (data) {
            $('#fyear option').remove();
            $('#fyear').append('<option>Выберите</option>');
            $.each(data, function(i, val){
                $('#fyear').append('<option value="' + val.fyear + '">' + val.fyear + '</option>');
            });
        })
});

$('#fyear').change(function(){
    var fcar = $('#fcar').val();
    var fyear = $(this).val();
    var _token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
        method: 'POST',
        url: "/fmod",
        data: {fyear:fyear, fcar:fcar, _token:_token},
    })
        .done(function (data) {
            $('#fmod option').remove();
            $('#fmod').append('<option>Выберите</option>');
            $.each(data, function(i, val){
                $('#fmod').append('<option value="' + val.fmodification + '">' + val.fmodification + '</option>');
            });
        })
});

$('#fmod').change(function(){
    var fvendor = $('#fvendor').val();
    var fcar = $('#fcar').val();
    var fyear = $('#fyear').val();
    var fmod = $('#fmod').val();
    var url = '/car/' + encodeURIComponent(fvendor) + '/' + encodeURIComponent(fcar) + '/' + encodeURIComponent(fyear) + '/' + encodeURIComponent(fmod);

    window.location.replace(url);
});