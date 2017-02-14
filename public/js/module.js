var app = angular.module('app', ["xeditable", "checklist-model","ngRoute", "ngResource","ngMessages"], function($interpolateProvider) {
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
        .when('/usuario/cad', { templateUrl: '/usuarios/gestao/cad'})
        .when('/usuario/edit/:id', { templateUrl: '/usuarios/gestao/edit/'})
        .when('/usuario/consulta', { templateUrl: '/usuarios/gestao/consulta/mostrar'})

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

                            url: '/fornecedores/gestao/pedidos/detalhes/enviar/:id'
                        }
                    ,PedidoEntregue:
                            {
                                method: 'post',

                                url: '/fornecedores/gestao/pedidos/detalhes/entregue/:id'
                            }
                    ,finalizar:
                            {
                                method: 'post',

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
            },
            cancelarPedido:{

                method: 'get',
                url: '/pedidos/atualizacao/cancelar/:id/:obs'

            }

        }


    );
});
app.factory('UsuariosSrv', function($resource) {
    return $resource(
        '/usuarios/gestao/users', {
            id: '@id'

        },
        {
            consulta: {
                method: 'GET',
                url: '/usuarios/gestao/consulta/:id'
            },
            index:{

                method: 'get',
                isArray:true,
                url: '/usuarios/gestao/users'

            },
            edit:{

                method: 'get',
                url: '/usuarios/gestao/edit/:id'

            },
            create:{

                method: 'get',
                url: '/usuarios/gestao/cad'

            },
            supervisores:{

                method: 'get',
                isArray:true,
                url:'/usuarios/gestao/consulta/supervisores'

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

            },
            
            nortificacoes: {
                
                method:'get',
                url: '/pedidos/nortificacao'
                
            },msgTopo:
                {

                    method:'get',
                    isArray: true,
                    url: '/pedidos/msgTopo'

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
app.service('Services', function ($http, $location, detalhesPedidos, criacao, AtualizarValorPedidosSrv) {

   this.aprovar = function (idPedido) {

       return criacao.aprovar({ id: idPedido }).$promise.then(function(data) {
           return data;

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

        jQuery(function($) {
            var motivo = 'motivo' + id;
            motivo = $("textarea[name=" + motivo + "]").val();

        });

       var resp = AtualizarValorPedidosSrv.cancelarPedido({id:id, obs:obs}).$promise.then(function (data) {

           return data;

       });


      return resp.then(function(d) {
            return d
        });


    };

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
    this.gerarChartBar = function (atualizacao,aprovacao) {
        Morris.Bar({
            element: 'hero-bar',
            data: [
                {device: 'Atualização', Pedidos: atualizacao},
                {device: 'Aprovação', Pedidos: aprovacao}

            ],
            xkey: 'device',
            ykeys: ['Pedidos'],
            labels: ['Pedidos'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            barColors: ['#f68660']
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
        $scope.servico.gerarChartBar($scope.criacao[0].atualizacaoGeral,$scope.criacao[0].aprovacaoOrc)
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


app.controller('detalhesPedido',function($scope,$http,Services){

    $scope.botoes = Services;

    $scope.cancelarPedido = function(id){

        $scope.botoes.cancelarPedido(id);

        $scope.pesquisar($scope.idCompra);
    };





    $scope.submit = function() {

        $scope.obs= angular.element('#text').val();

        if ($scope.obs) {

            $scope.botoes.addHistorico( $scope.idCompra, $scope.obs);
            $scope.pesquisar($scope.idCompra);
        }

    };

});

app.controller('todosPedidos', function($scope,$http,Services)
{
    $scope.botoes = Services;
    $scope.pedidos = [];
    $scope.idCompra = '';
    $scope.obs = '';
    

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

    $scope.cancelarItem = function(id, idCompra)
    {
        jQuery(function($) {
            var motivo = 'motivo' + id;
            motivo = $("textarea[name="+motivo+"]").val();
            

        var resp = AtualizarValorPedidosSrv.cancelarPedido({ id: id, obs: motivo}).$promise.then(function (data) {

            return data;

        });

            resp.then(function (d) {
                $scope.resp = d;
                alert($scope.resp.resp);
            });

        $scope.pequisar(idCompra);

        });

    };

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
        $scope.total = [];
        $scope.result = [];
        $scope.val = 0;
        
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

        return data;

    });

    resp.then(function(d){

        if(d == null){

        }else{
            $scope.revisao.push(d);
            $scope.revisao = $scope.revisao[0];



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
    
    $scope.modal = function (img) {
        $scope.foto = '';
        $scope.foto = img;
        
    };
    
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
    
    $scope.entrega = function (id) {
        
        var resp = $scope.fornecedor.PedidoEntregue({ id: id}).$promise.then(function (data) {

            return data;

        });

        resp.then(function (d) {

            alert(d.msg);
            $scope.init();

        });
          
    };

    $scope.init();



});

app.controller('users', function ($scope,$http,Services,UsuariosSrv) {

    $scope.service = Services;
    $scope.usuario = [];
    $scope.init = function(){
        $scope.supervisor = [];

        var supervisor = UsuariosSrv.supervisores().$promise.then(function(data){

            return data;
        });
        supervisor.then(function(d){

            $scope.supervisor.push(d);
            $scope.supervisor = $scope.supervisor[0];
            console.log($scope.supervisor);

        });

       var resp =  UsuariosSrv.index().$promise.then(function (data) {

            return data;

        });

        resp.then(function (d) {
            
            $scope.usuario.push(d);
            $scope.usuario = $scope.usuario[0];


        });

    };



    $scope.consulta = function(id){


        var resp = UsuariosSrv.consulta({id:id}).$promise.then(function (data) {

            return data;

        });

        resp.then(function (d) {
            $scope.user = [];
            $scope.user.push(d);
            $scope.user = $scope.user[0];
            

        });


    };
    
    $scope.init();

});

app.controller('master', function ($scope,$http,Services, detalhesPedidos){

    $scope.service = Services;

    $scope.modal = function (img) {
        $scope.foto = '';
        $scope.foto = img;

    };
    
    $scope.init = function() {
        $scope.nortificacoes = [];
        $scope.msg=[];
        $scope.aprovados = [];
        $scope.revisao = [];
        var resp = detalhesPedidos.nortificacoes().$promise.then(function (data) {

            return data;

        });
        resp.then(function (d) {

            $scope.nortificacoes.push(d);
            $scope.nortificacoes = $scope.nortificacoes[0];


        });

        $scope.msgTopo = [];

        var topo = detalhesPedidos.msgTopo().$promise.then(function (data) {

            return data;

        });
        topo.then(function (d) {

            $scope.msgTopo.push(d);
            $scope.msgTopo = $scope.msgTopo[0];


            $scope.aprovados = $scope.msgTopo[0];
            $scope.revisao =$scope.msgTopo[1];
            $scope.msg=$scope.msgTopo[2];



        });

    };
    
    $scope.init();


});

app.controller('mensagem',function ($scope,$http,Services, detalhesPedidos){


    $scope.service = Services;

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
