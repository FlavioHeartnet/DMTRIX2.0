var Script = function () {


    var detalhes1 = {

        labels: [
            "Realizado",
            "Pendente"

        ],

        datasets: [
            {
                data: [45, 55],
                backgroundColor: [
                    "#7acfe3",
                    "#ffffff"
                ],
                hoverBackgroundColor: [
                    "#7acfe3",
                    "#ffffff"
                ],
                borderColor: [

                    "#797979",
                    "#797979"
                ],

                borderWidth: [

                    1,1
                ]
            }]


    };
    

    new Chart(document.getElementById("detalhes1").getContext("2d"),
        {
            type: 'doughnut',
            data: detalhes1,
            options: {
                animation:
                {
                    animateRotate: true,
                    responsive: false
                }

        }});

    new Chart(document.getElementById("detalhes2").getContext("2d"),
        {
            type: 'doughnut',
            data: detalhes1,
            options: {
                animation:
                {
                    animateRotate: true,
                    responsive: false
                }

            }});

    new Chart(document.getElementById("detalhes3").getContext("2d"),
        {
            type: 'doughnut',
            data: detalhes1,
            options: {
                animation:
                {
                    animateRotate: true,
                    responsive: false
                }

            }});

    new Chart(document.getElementById("detalhes4").getContext("2d"),
        {
            type: 'doughnut',
            data: detalhes1,
            options: {
                animation:
                {
                    animateRotate: true,
                    responsive: false
                }

            }});


}();