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
});