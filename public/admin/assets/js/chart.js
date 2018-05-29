type = ['','info','success','warning','danger'];


chart = {

    initChartist: function(data){

        var dataSales = {
            labels: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            series: [
                data,
            ]
        };

        var optionsSales = {
            seriesBarDistance: 30,
            axisX: {
                showGrid: true,
            },
            axisY: {
                onlyInteger: true,
            },
            low: 0,
            chartPadding:20,
        };

        var responsiveSales = [
          ['screen and (max-width: 640px)', {
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];
        Chartist.Bar('#chartHours', dataSales, optionsSales, responsiveSales);
    },
    showNotification: function(msg, icon, type, from, align){
        $.notify({
            icon: icon,
            message: msg,

        },{
            type: type,
            timer: 2000,
            placement: {
                from: from,
                align: align
            }
        });
    }
}
