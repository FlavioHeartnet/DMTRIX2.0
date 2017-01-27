var Script = function () {

    //morris chart

    $(function () {

        Morris.Area({
            element: 'chartAnalise1',
            data: [
                {period: '2010 Q1', iphone: 2666, ipad: null},
                {period: '2010 Q2', iphone: 2778, ipad: 2294},
                {period: '2011 Q3', iphone: 4912, ipad: 1969},
                {period: '2012 Q4', iphone: 3767, ipad: 3597},
                {period: '2012 Q1', iphone: 6810, ipad: 1914 },
                {period: '2013 Q2', iphone: 5670, ipad: 4293}

            ],

            xkey: 'period',
            ykeys: ['iphone', 'ipad'],
            labels: ['Cancelados', 'Compras'],
            hideHover: 'auto',
            lineWidth: 1,
            pointSize: 5,
            lineColors: ['#ff6c60', '#a9d86e', '#4a8bc2'],
            fillOpacity: 0.5,
            smooth: true
        });

        Morris.Area({
            element: 'chartAnalise2',
            data: [
                {period: '2010 Q1', iphone: 2666, ipad: null},
                {period: '2010 Q2', iphone: 2778, ipad: 2294},
                {period: '2011 Q3', iphone: 4912, ipad: 1969},
                {period: '2012 Q4', iphone: 3767, ipad: 3597},
                {period: '2012 Q1', iphone: 6810, ipad: 1914 },
                {period: '2013 Q2', iphone: 5670, ipad: 4293}

            ],

            xkey: 'period',
            ykeys: ['iphone', 'ipad'],
            labels: ['Cancelados', 'Compras'],
            hideHover: 'auto',
            lineWidth: 1,
            pointSize: 5,
            lineColors: ['#ff6c60', '#a9d86e', '#4a8bc2'],
            fillOpacity: 0.5,
            smooth: true
        });

       
    });

}();




