var Script = function () {


    var doughnutData =
        {
            labels: [
                "Realizado",
                "Pendente"

            ],

            datasets: [
                {
                    data: [45, 55],
                    backgroundColor: [
                        "#68dff0",
                        "#ffffff"
                    ],
                    hoverBackgroundColor: [
                        "#9FF0EE",
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


        }

    ;
    var doughnutData1 = {

        labels: [
            "Realizado",
            "Pendente"

        ],

        datasets: [
            {
                data: [45, 55],
                backgroundColor: [
                    "#f9941e",
                    "#ffffff"
                ],
                hoverBackgroundColor: [
                    "#f9941e",
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


    var doughnutData2 = {

        labels: [
            "Realizado",
            "Pendente"

        ],

        datasets: [
            {
                data: [45, 55],
                backgroundColor: [
                    "#3ab54b",
                    "#ffffff"
                ],
                hoverBackgroundColor: [
                    "#3ab54b",
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


    var doughnutData3 = {

        labels: [
            "Realizado",
            "Pendente"

        ],

        datasets: [
            {
                data: [45, 55],
                backgroundColor: [
                    "#15a8a0",
                    "#ffffff"
                ],
                hoverBackgroundColor: [
                    "#15a8a0",
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

    var doughnutData4 = {

        labels: [
            "Aprovados",
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


    var ctx = document.getElementById("doughnut").getContext("2d");


    gerar(ctx, doughnutData);


    function gerar(ctx, data)
    {

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                animation: {
                    animateRotate: true
                }

            }
        });
    }


}();