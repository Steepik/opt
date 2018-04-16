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
                    var cnum = $( this ).parent().find('.order-cnum').val();
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
});