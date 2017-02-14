var Script = function () {

    //morris chart

    $(function () {
      // data stolen from http://howmanyleft.co.uk/vehicle/jaguar_'e'_type



      Morris.Area({
        element: 'hero-area',
        data: [
          {period: '01/07 Q1', iphone: 2666, ipad: null, itouch: 2647},
          {period: '02/07 Q2', iphone: 2778, ipad: 2294, itouch: 2441},
          {period: '03/07 Q3', iphone: 4912, ipad: 1969, itouch: 2501},
          {period: '04/07 Q4', iphone: 3767, ipad: 3597, itouch: 5689},
          {period: '05/07 Q1', iphone: 6810, ipad: 1914, itouch: 2293},
          {period: '06/07 Q2', iphone: 5670, ipad: 4293, itouch: 1881}

        ],

        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['Revisão interna', 'Aprovação de arte solicitante', 'Artes aprovadas'],
        hideHover: 'auto',
        lineWidth: 1,
        pointSize: 5,
        lineColors: ['#4a8bc2', '#ff6c60', '#a9d86e'],
        fillOpacity: 0.5,
        smooth: true
      });


     

      $('.code-example').each(function (index, el) {
        eval($(el).text());
      });
    });

}();




