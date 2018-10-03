$( document ).ready(function() {
    $.instance;
    $('.alert.status').on('click', function() {
        $.oid = $( this ).parent().find("#oid").val();
        $('.current_status').text($( this ).text());
        $('#statusModal').modal('show');
        $.instance = $( this );
    });

    $('.save-status').on('click', function(){
        var _token = $("meta[name=csrf-token]").attr('content');
        var sid = $('.status-list').val(); //status id
        $.ajax({
            method: 'POST',
            url: "orders/changeOrderStatus",
            data: {_token:_token, oid:$.oid, sid:sid},
        })
            .done(function (data) {
                if(data.success == true) {
                    $('#statusModal').modal('hide');
                    var msg = "Статус был успешно изменен";
                    var icon = 'ti-shopping-cart';
                    var type = 'success';
                    var iclass = 'alert status';
                    chart.showNotification(msg, icon, type, 'top', 'right');

                    if(data.sid == 4) {
                        $.instance.attr('class', iclass + ' alert-warning text-center');
                        $.instance.find('span').text(data.text);
                    } else if(data.sid == 6) {
                        $.instance.attr('class', iclass + ' alert-success text-center');
                        $.instance.find('span').text(data.text);
                    } else if(data.sid == 7) {
                        $.instance.attr('class', iclass + ' alert-danger text-center');
                        $.instance.find('span').text(data.text);
                    } else {
                        $.instance.attr('class', iclass + ' alert-info text-center');
                        $.instance.find('span').text(data.text);
                    }
                }
            });
    });

    //change order count
    $('.order-item-count').on('click', function(e){
        e.preventDefault();
        $.singleOrder = $( this );

        swal("Введите количество:", {
            content: "input",
        })
            .then((value) => {
                if($.isNumeric(value) ) {
                    var _token = $("meta[name=csrf-token]").attr('content');
                    var cnum = $('.order-cnum').val();
                    var tcae = $( this ).parent().find('.product-tcae').val();

                    $.ajax({
                        method: 'POST',
                        url: "/control/changeOrderCount",
                        data: {_token:_token, value:value, cnum:cnum, tcae:tcae},
                    })
                        .done(function (data) {
                            if(data.type == 0) {
                                $('.order-item-count, .order-total-count').text(value);
                                $('.order-total-sum').text(new Intl.NumberFormat().format(data.price_opt * value));
                            } else {
                                var total_sum = 0;
                                var total_count = 0;

                                $.singleOrder.parent().find('.order-total-sum').text(new Intl.NumberFormat().format(data.price_opt * value));
                                $.singleOrder.text(value);

                                $('.order-total-sum').each(function(){
                                    var priceOpt = parseInt($( this ).text().replace(/\s/g, ''));
                                    total_sum += priceOpt;
                                });

                                $('.order-item-count').each(function(){
                                    var count = parseInt($( this ).text());
                                    total_count += count;
                                });

                                $('.order-total-sum-top').text(new Intl.NumberFormat().format(total_sum));
                                $('.order-total-count').text(new Intl.NumberFormat().format(total_count));
                            }
                        });
                } else {
                    swal("Введите число", "Вы ввели буквы или другие знаки", "warning");
                }
            });
    });

    //delete position from merged order
    $('.merged-btn-del').on('click', function(){
        var pName = $( this ).parent().parent().find('.order-product-name-inside').text();
        var _token = $("meta[name=csrf-token]").attr('content');
        var cnum = $('.order-cnum').val();
        var tcae = $( this ).parent().parent().find('.product-tcae').val();

        swal({
            title: pName,
            text: "",
            icon: "warning",
            buttons:  ["Отмена", "Удалить"],
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        method: 'POST',
                        url: "/control/delOrderInMerged",
                        data: {_token:_token, cnum:cnum, tcae:tcae},
                    })
                        .done(function (data) {
                            if(data.success == true) {
                                swal("Позиция была успешно удалена", {
                                    icon: "success",
                                });

                                setTimeout(function(){ location.reload(true) }, 1000);
                            } else {
                                swal("Что-то пошло не так", {
                                    title: "Ошибка удаления",
                                    icon: "warning",
                                });
                            }
                        });
                } else {
                    swal.close();
                }
            });
    });

    $('.btn.accesss_brand').on('click', function(){
        $('body').css('padding', 0);
        let legal_name = $(this).parent().parent().find('.legal_name').text();
        let user_id = $(this).attr('data-userid');
        let _token = $('input[name="_token"]').val();

        $('.modal-title').find('#legal_name').text(legal_name);
        $('.modal-body').find('#userId').val(user_id);

        $('#brandList li').remove();

        $.ajax({
            method: 'POST',
            url: "/control/settings/getBannedBrandView",
            data: {user_id:user_id, _token:_token},
        })
            .done(function (data) {
                $.each(data, function(i, item){
                    $('#brandList').hide();
                    $('#brandList').append('<li style="margin:2rem;padding:3rem;" class="list-group-item brand'+item.id+'"><span class="text-left">'+item.name+'</span><button style="margin-top:-1rem;" data-brandId="'+item.id+'" class="pull-right btn btn-fill btn-danger deleteBrandAccess">Удалить</button></li>');
                    $('#brandList').slideDown(200);
                });
            });
    });

    $(document).on("click",".btn.deleteBrandAccess",function() {
        let user_id = $('form .modal-body #userId').val();
        let brand_id = $(this).attr('data-brandid');
        let _token = $('input[name="_token"]').val();
        let _this = $(this);

        $.ajax({
            method: 'POST',
            content: _this,
            url: "/control/settings/deleteFromBrandAccess",
            data: {user_id:user_id, brand_id:brand_id, _token:_token},
        })
            .done(function (data) {
                _this.parent().parent().find('.brand'+brand_id).remove();
            });
    });

    $('.btn.percent_brand').on('click', function(){
        $('body').css('padding-right', 0);
        let legal_name = $(this).parent().parent().find('.legal_name').text();
        let user_id = $(this).attr('data-userid');
        let _token = $('input[name="_token"]').val();

        $('.modal-title').find('#legal_name').text(legal_name);
        $('.modal-body').find('#userId').val(user_id);

        $('#brandList li').remove();

        $.ajax({
            method: 'POST',
            url: "/control/settings/getPercentBrandView",
            data: {user_id:user_id, _token:_token},
        })
            .done(function (data) {
                console.log(data);
                $.each(data, function(i, item){
                    $('#brandList').hide();
                    $('#brandList').append('<li style="margin:2rem;padding:3rem;" class="list-group-item brand'+item.id+'"><span class="text-left">'+item.brand_name+' <b>('+item.percent+'%)</b></span><button style="margin-top:-1rem;" data-brandId="'+item.id+'" class="pull-right btn btn-fill btn-danger deleteBrandPercent">Удалить</button></li>');
                    $('#brandList').slideDown(200);
                });
            });
    });

    $(document).on("click",".btn.deleteBrandPercent",function() {
        let user_id = $('form .modal-body #userId').val();
        let brand_id = $(this).attr('data-brandid');
        let _token = $('input[name="_token"]').val();
        let _this = $(this);

        $.ajax({
            method: 'POST',
            content: _this,
            url: "/control/settings/deleteFromBrandPercent",
            data: {user_id:user_id, brand_id:brand_id, _token:_token},
        })
            .done(function (data) {
                _this.parent().parent().find('.brand'+brand_id).remove();
            });
    });
});