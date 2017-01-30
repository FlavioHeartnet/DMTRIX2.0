

@extends('master')


@section('content')

    <div ng-controller="mensagem">


        <div class="container-fluid" >
            <div class="page-header">
                <h1 id="timeline"><i class="fa fa-envelope"></i> Mensagens (<% nortificacoes.msg %>)</h1>
            </div>
            <ul class="timeline">
                <li class="timeline-inverted" ng-repeat="a in msg">
                    <div class="timeline-badge"><i class="fa fa-user"></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-body">

                            <div class="col-lg-12" >
                                <div class="col-lg-10">
                                    <p><% a.solicitante %></p>
                                        <p class="colorLaranja">Pedido: <% a.idCompra %> - <% a.loja %></p>
                                    <p><% a.obs %></p>
                                </div>
                                <div class="col-lg-2">
                                    <a class="btn btn-warning backgroundLaranja" ng-href="#/pedidos/detalhes/mostrar" ng-click="pesquisar(a.idCompra)">Analisar Pedido</a>
                                </div>

                            </div>

                        </div>
                    </div>
                </li>



            </ul>

        </div>




    <div id="drop-area" class="drop-area detailsPedido">

        <div>
            <button class="btn btn-primary voltar" ng-click="service.voltar()">voltar</button>
            <p></p>

            <div ng-view>



            </div>



        </div>
    </div>
    <div class="drop-overlay"></div>

    </div>

    <script src="{{ asset('js/jquery.js')  }}"></script>
    <script type="text/javascript">


        $('.perfil').click(function () {
            $('#drop-area').addClass('show');
        });

        $('.voltar').click(function () {
            $('#drop-area').removeClass('show');
        });



    </script>


@endsection
