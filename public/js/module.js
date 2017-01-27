var app = angular.module('app', ["xeditable", "checklist-model","ngRoute", "ngResource"], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.config(['$routeProvider', function($routeProvider){
    
    $routeProvider.when('/cadFornecedor',{ templateUrl: '/fornecedores/gestao/cad/',controller: 'fornecedor' })
        .when('/consultar/:id',{ templateUrl: '/fornecedores/gestao/consulta',controller: 'fornecedor' })
        .when('/edit/:id', { templateUrl: '/fornecedores/gestao/edit/', controller: 'fornecedor'})
        .when('/loader', {templateUrl: '/loader' })
        .when('/pedidos/atualizacao/detalhes/:id', { templateUrl: '/pedidos/atualizacao/detalhes', controller: 'fornecedor'})
        .when('/pedidos/detalhes/mostrar', { templateUrl: '/pedidos/detalhes/mostrar'})
        .when('/criacao/mostrar', { templateUrl: '/producao/fila/criacao/mostrar'})
        .when('/delegar/detalhes', { templateUrl: '/pedidos/aprovado/delegar/detalhes'})
        .when('/detalhes/pedidos/fornecedor', { templateUrl: '/fornecedores/gestao/pedidos/mostrar'})
}]);
//serviço que conecta com o back
app.factory('FornecedorSrv', function($resource) {
        return $resource(
            '/fornecedores/gestao/consultaFornecedor/:id', {
                id: '@id'
            },
            {
                    consulta:
                    {
                        method: 'GET',
                        url: '/fornecedores/gestao/consultaFornecedor/:id'
                    }
                    ,pedidosFornecedor:
                        {
                            method: 'GET',
                            isArray: true,
                            url: '/fornecedores/gestao/pedidos'
                        }
                    ,detalhesPedido:
                        {
                            method: 'GET',
                            isArray: true,
                            url: '/fornecedores/gestao/pedidos/detalhes/:id'
                        }
                    ,EnviarFornecedor:
                        {
                            method: 'POST',
                            isArray: true,
                            url: '/fornecedores/gestao/pedidos/detalhes/enviar/:id'
                        }
                    ,PedidoEntregue:
                            {
                                method: 'get',
                                isArray: true,
                                url: '/fornecedores/gestao/pedidos/detalhes/entregue/:id'
                            }
                    ,finalizar:
                            {
                                method: 'get',
                                isArray: true,
                                url: '/fornecedores/gestao/pedidos/detalhes/finalizar/:id'
                            }

            }


        );
    });
app.factory('AtualizarValorPedidosSrv', function($resource) {
    return $resource(
        '/pedidos/atualizacao/detalhes/:id/:status', {
            id: '@id',
            status: '@status'
        },
        {
            consulta: {
                method: 'GET',
                isArray:true,
                url: '/pedidos/atualizacao/detalhes/:id/:status'
            }

        }


    );
});
app.factory('detalhesPedidos', function($resource) {
    return $resource(
        '/pedidos/todos/detalhes/:id', {
            id: '@id',
            obs: '@obs'

        },
        {
            consulta: {
                method: 'GET',

                url:  '/pedidos/todos/detalhes/:id'
            },

            addObs: {

                method:'POST',
                url: '/pedidos/todos/detalhes/:id/add/:obs'

            }
        });

   });
app.factory('criacao', function($resource){
    
    return $resource('/producao/fila/:id',{

        id: '@id'
    },
        {
            consulta: {

                method: 'GET',
                isArray: true,
                url: '/producao/fila/:id'
            },

            aprovacao: {

                method: 'GET',
                isArray: true,
                url: '/producao/fila/aprovacao'

            },

            revisao:{

                    method: 'GET',
                    isArray: true,
                    url: '/producao/fila/revisao'

            },

            aprovar:{

                method: 'GET',
                url: '/producao/fila/aprovar/:id'

            }


        }
    );
    
});

//serviço de funcções genericas usadas no sistema
app.service('Services', function ($http, $location, detalhesPedidos, criacao) {

   this.aprovar = function (idPedido) {

       return criacao.aprovar({ id: idPedido }).$promise.then(function(data) {
           return data.resp;

       });
       
       
   };
    
    this.filaIndividual = function(id){

        return criacao.consulta({ id: id }).$promise.then(function(data) {

            return data;
        });

    };

    this.addHistorico = function (idCompra, obs) {

        return detalhesPedidos.addObs({ id: idCompra, obs: obs}).$promise.then(function(data) {
            return data.array;

        });

    };

   this.perfil =  function (idCompra){

           jQuery(function($)
           {
               $('#drop-area').addClass('show');

           });

         return detalhesPedidos.consulta({ id: idCompra}).$promise.then(function(data) {
             return data.array;

       });

    };

    this.mostrarModal =  function (){

            jQuery(function($)
            {

                $('#drop-area').addClass('show');

            });

    };

    this.voltar = function ()
    {

        jQuery(function($)
        {
            $('#drop-area').removeClass('show');
        });

    };
    
    this.cep = function(){

        jQuery(function($)
        {
            var cep = $('#cep').val();
            var dados=[];

            if(cep.length >= 8) {

                var obj={};
                obj.cep = function() { return $http.get('http://viacep.com.br/ws/' + cep + '/json/'); };
                obj.cep().then( function(data) {

                    dados = data.data;

                    $("#estado").val(dados['uf']);
                    $("#cidade").val(dados['localidade']);
                    $("#endereco").val(dados['logradouro']+" "+dados['bairro']);

                });



            }else{ alert('CEP Inválido') }

        });
        
    }

    this.requestHTTP = function(url) {

        var request = {};
        request.get = function() { return $http.get(url); };
       return request.get().then(function(data){

            return data.data;

        });


    };

    this.cancelarPedido = function(id){

       var resp = this.requestHTTP('/pedidos/cancelar/'+id);


        resp.then(function(d) {
            alert(d);
        });


    }

    this.gerarCharts = function(id, aprovados, pedidos,reprovados, pendente,revisao){

        var dados = {

            labels: [
                "Aprovado",
                "Reprovado",
                "Pendente aprovação",
                "Revisão de arte",
                "Fila"

            ],

            datasets: [
                {
                    data: [aprovados, reprovados ,pendente,revisao ,pedidos],
                    backgroundColor: [
                        "#7acfe3",
                        "#f12231",
                        "#f68660",
                        "#3e6b8c",
                        "#ffffff"
                    ],
                    hoverBackgroundColor: [
                        "#7acfe3",
                        "#f12231",
                        "#f68660",
                        "#3e6b8c",
                        "#ffffff"
                    ],
                    borderColor: [

                        "#797979",
                        "#f12231",
                        "#f68660",
                        "#3e6b8c",
                        "#797979"
                    ],

                    borderWidth: [

                        1,1,1,1,1
                    ]
                }]


        };

        var ctx = document.getElementById(id).getContext("2d");
        new Chart(ctx, {
            type: 'doughnut',
            data: dados,
            options: {
                animation: {
                    animateRotate: true
                }

            }
        });


    }



});

//directives
app.directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit(attr.onFinishRender);
                });
            }
        }
    }
});


//Controllers
app.controller('home', function($scope, $http, Services)
{
    $scope.criacao = [];
    $scope.criacaoUser = [];
    $scope.servico = Services;
    $scope.porcentagem = 0;
    var obj = {};
    obj.triagem = function () {
        return $http.get('/producao/fila');
    };

    obj.triagem().then(function (data) {

        for(var i = 0; i <= data.data.length; i++)
        {
            $scope.criacao = data.data;
        }

    });

    $scope.$on('ngRepeatFinished', function() {

        angular.forEach($scope.criacao, function(value){

            $scope.servico.gerarCharts("chart"+value.idUsuario, value.aprovados, value.pedidos, value.reprovados, value.pendente,  value.revisao);

        });

        $scope.servico.gerarCharts("chartGeral", $scope.criacao[0].aprovadosGeral, $scope.criacao[0].criacaoGeral,$scope.criacao[0].reprovadosGeral, $scope.criacao[0].pendenteGeral, $scope.criacao[0].revisaoGeral );
    });

    $scope.$on('ngRepeatFinishedDetails', function() {
        var total=0;
        var aprovados = 0;
        angular.forEach($scope.criacaoUser, function(value){

            $scope.servico.gerarCharts("detalhes"+value.idCompra, value.aprovados, value.fila, value.reprovados, value.pendente, value.revisao);
            
            total+= parseInt(value.aprovados) + parseInt(value.fila) + parseInt(value.reprovados) + parseInt(value.pendente) + parseInt(value.revisao);
            aprovados+=parseInt(value.aprovados);

        });

        console.log(aprovados);
        console.log("total "+total);
        $scope.porcentagem  = (aprovados/total) * 100;

    });
    
    $scope.mostrarFila = function (id) {
        
        var resp = $scope.servico.filaIndividual(id);

        $scope.servico.mostrarModal();

        resp.then(function(d) {
            $scope.criacaoUser = d;
        });

        
    }
    

});

app.controller('mainCusto', function($scope, $http, Services)
{

    $scope.botoes = Services;
    $scope.feedSource = [];
    $scope.selectedIds = [];
    var obj = {};
    obj.triagem = function () {
        return $http.get('/pedidos/aprovado/show');
    };
    
    obj.triagem().then(function (data) {

        for(var i = 0; i <= data.data.length; i++)
        {
            $scope.feedSource = data.data;
        }

    });


    $scope.delegar = {};

    $scope.filterStatus = function (obj) {
        return $scope.user.status.indexOf(obj.value) > -1;
    };

    $scope.userFeeds = {
        feeds: []
    };
    $scope.pesquisar = function(id){
        $scope.idCompra = id;
        $scope.array = [];
        $scope.pedido = [];
        $scope.timeline = [];

        var resp1 = Services.perfil(id);

        resp1.then(function(d) {
            $scope.array = d[0];
            $scope.pedido = d[1];
            $scope.timeline = d[2];
        });

    };



});

app.controller('todosPedidos', function($scope,$http,Services)
{
    $scope.botoes = Services;
    $scope.pedidos = [];
    $scope.idCompra = '';
    $scope.obs = '';


    $scope.submit = function() {

        $scope.obs= angular.element('#text').val();

        if ($scope.obs) {

            $scope.botoes.addHistorico( $scope.idCompra, $scope.obs);
            $scope.pesquisar($scope.idCompra);
        }

    };


    var resp = Services.requestHTTP('/pedidos/todos/carregar');

    $scope.pesquisar = function(id){
        $scope.idCompra = id;
        $scope.array = [];
        $scope.pedido = [];
        $scope.timeline = [];

        var resp1 = Services.perfil(id);

        resp1.then(function(d) {
            $scope.array = d[0];
            $scope.pedido = d[1];
            $scope.timeline = d[2];
        });

    };


    resp.then(function(d) {
        $scope.pedidos = d;
    });

});

app.controller('fornecedor', function($scope,$http,Services, $routeParams, FornecedorSrv, $location)
{

    var forncedor = {};
    $scope.fornecedor=[];

    forncedor.show = function() { return $http.get('/fornecedores/gestao/show'); };
    forncedor.show().then(function(data){



        for(var i = 0; i <= data.data.length; i++)
        {
            $scope.fornecedor = data.data;
        }

    });

    $scope.cep = Services.cep;
    
    $scope.consulta = function(id){

        $location.path('/loader');
        $scope.result = FornecedorSrv.consulta({ id: id});//consulta de fornecedor
        
    };
    
    


});

app.controller('custoAprovar', function ($scope, Services, AtualizarValorPedidosSrv, $http, $filter) {

    $scope.botoes = Services; // funcções do DOM
    $scope.result = []; // pedidos da Compra
    $scope.total = [];


    $scope.soma = function(id){

        var total =0;
        var largura =0;
        var altura =0;
        var valor =0;
        var quantidade =0;

        jQuery(function($) {
            largura = $('#Largura' + id).val() / 100;
            altura = $('#Altura' + id).val() / 100;
            valor = $('#custo' + id).val();
            quantidade = $('#Quantidade' + id).val();


            total = (largura * altura) * valor * quantidade;
            total = parseFloat(total);

            document.getElementById('custoTotal' + id).innerHTML = total;
            $('#custoInput' + id).val(total);


                $scope.AtualizaArray(id, total);
                $scope.somaTotal();

        });


    };

    $scope.AtualizaArray = function (idPedido, total) {
            var x = 0;
            var tamanho = $scope.total.length;

            if (tamanho == 0) {

                $scope.total.push({id: idPedido, valor: total});

            } else
            {

                angular.forEach($scope.total, function (value) {
                    console.log(value.id);
                    if (value.id == idPedido) {

                        value.valor = total;

                    }else{

                        x++;

                    }

                });

                if(x==tamanho){

                    $scope.total.push({id: idPedido, valor: total});

                }


            }


    };

    $scope.somaTotal = function () {

        $scope.val = 0;
        angular.forEach($scope.total, function(value) {
            $scope.val += value.valor;
            console.log($filter('number')($scope.val,3))
        })

    };



    var resp = Services.requestHTTP('/pedidos/atualizacao/show/2');

    resp.then(function (d) {
        $scope.pedidos = d;
    });


    
    $scope.pequisar = function (id) {
        
        $scope.botoes.mostrarModal();
        $scope.result = AtualizarValorPedidosSrv.consulta({id: id, status: 2});
        
    };

    //soma de valores



});


app.controller('avaliacaoPedidos', function ($scope,$http,Services, AtualizarValorPedidosSrv, $filter) {


    $scope.botoes = Services;
    var resp = Services.requestHTTP('/pedidos/atualizacao/show/25');

    resp.then(function (d) {
        $scope.pedidos = d;
    });

    $scope.total = [];


    $scope.soma = function(id){

        var total =0;
        var largura =0;
        var altura =0;
        var valor =0;
        var quantidade =0;

        jQuery(function($) {
            largura = $('#Largura' + id).val() / 100;
            altura = $('#Altura' + id).val() / 100;
            valor = $('#custo' + id).val();
            quantidade = $('#Quantidade' + id).val();


            total = (largura * altura) * valor * quantidade;
            total = parseFloat(total);

            document.getElementById('custoTotal' + id).innerHTML = total;
            $('#custoInput' + id).val(total);


            $scope.AtualizaArray(id, total);
            $scope.somaTotal();

        });


    };

    $scope.AtualizaArray = function (idPedido, total) {
        var x = 0;
        var tamanho = $scope.total.length;

        if (tamanho == 0) {

            $scope.total.push({id: idPedido, valor: total});

        } else
        {

            angular.forEach($scope.total, function (value) {
                console.log(value.id);
                if (value.id == idPedido) {

                    value.valor = total;

                }else{

                    x++;

                }

            });

            if(x==tamanho){

                $scope.total.push({id: idPedido, valor: total});

            }


        }


    };

    $scope.somaTotal = function () {

        $scope.val = 0;
        angular.forEach($scope.total, function(value) {
            $scope.val += value.valor;
            console.log($filter('number')($scope.val,3))
        })

    };

    $scope.pequisar = function (id) {

        $scope.botoes.mostrarModal();
        $scope.result = AtualizarValorPedidosSrv.consulta({id: id, status: 25});

    };


});

app.controller('cancelamento', function ($scope,$http,Services) {
    
    $scope.botoes = Services;
    $scope.pedidos = [];
    $scope.mostrar = '';
    $scope.array = [];
    $scope.pedido = [];

    $scope.pesquisar = function(id){
        $scope.idCompra = id;
        $scope.array = [];
        $scope.pedido = [];
        $scope.timeline = [];

        var resp1 = Services.perfil(id);

        resp1.then(function(d) {
            $scope.array = d[0];
            $scope.pedido = d[1];
            $scope.timeline = d[2];
        });

    };
    $scope.idCompra = '';
    $scope.obs = '';


    $scope.submit = function() {

        $scope.obs= angular.element('#text').val();

        if ($scope.obs) {

            $scope.botoes.addHistorico( $scope.idCompra, $scope.obs);
            $scope.pesquisar($scope.idCompra);
        }

    };


    var resp = Services.requestHTTP('/pedidos/cancelados');
    resp.then(function (d) {
        $scope.pedidos = d;
    });



    
});

app.controller('producao-revisao', function($scope,$http,Services, criacao){

    $scope.servico = Services;
    $scope.revisao = [];
    $scope.criacao = criacao;
    

    var resp = $scope.criacao.revisao().$promise.then(function(data) {

        return data[0];

    });

    resp.then(function(d){

        if(d == null){

        }else{
            $scope.revisao.push(d);

        }


    });




});

app.controller('aprovacao-arte', function ($scope,$http,Services, criacao) {

    $scope.servico = Services;
   

    $scope.init= function() {
        $scope.aprovacao = [];
        $scope.criacao = criacao;
            var resp = $scope.criacao.aprovacao().$promise.then(function (data) {

                return data;

            });

            resp.then(function (d) {

                if (d == null) {

                } else {
                    $scope.aprovacao.push(d);
                    $scope.aprovacao = $scope.aprovacao[0];

                }

            });
    };

    $scope.init();
    
    $scope.aprovar = function(id){
        
       var resp =  $scope.servico.aprovar(id);

        resp.then(function(d) {
            if(d == 'sucesso'){

                alert('Aprovado com sucesso');
                $scope.init();

            }
        });
        
    };
    

    $scope.pesquisar = function(id){
        $scope.idCompra = id;
        $scope.array = [];
        $scope.pedido = [];
        $scope.timeline = [];

        var resp1 = Services.perfil(id);

        resp1.then(function(d) {
            $scope.array = d[0];
            $scope.pedido = d[1];
            $scope.timeline = d[2];
        });

    };


});

app.controller('filaFornecedor',function ($scope,$http,Services, FornecedorSrv)
{

    $scope.servico = Services;



    $scope.init= function() {
        $scope.pedidos = [];

        $scope.fornecedor = FornecedorSrv;
        var resp = $scope.fornecedor.pedidosFornecedor().$promise.then(function (data) {

            return data;

        });

        resp.then(function (d) {

            if (d == null) {

            } else {
                $scope.pedidos.push(d);
                $scope.pedidos = $scope.pedidos[0];


            }

        });
    };

    $scope.pesquisar = function(id){

        $scope.detalhes = [];
        $scope.servico.mostrarModal();

        var resp = $scope.fornecedor.detalhesPedido({ id: id}).$promise.then(function (data) {

            return data;

        });

        resp.then(function (d) {

            if (d == null) {

            } else {
                $scope.detalhes.push(d);
                $scope.detalhes = $scope.detalhes[0];
                console.log($scope.detalhes);

            }

        });
        

    };

    $scope.enviarFornecedor = function (id) {

        var resp = $scope.fornecedor.enviarFornecedor({id: id}).$promise.then(function (data) {

            return data;

        });

        resp.then(function (d) {

            console.log(d);
        });

    };

    $scope.init();



});
