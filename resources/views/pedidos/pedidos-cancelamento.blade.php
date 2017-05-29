

@extends('master')


@section('content')

    <h1><i class="fa fa-ban"></i>Pedidos Proximo ao cancelamento</h1>

    <div ng-controller="cancelamento">

        <table class="table table-responsive table-bordered">
            <tr>
                <td>Nº</td>
                <td>Titulo</td>
                <td>Solicitante</td>
                <td>Data de Entrada</td>
                <td>Orçamento atualizado</td>
                <td>Orçamento aprovado</td>
                <td>Prioridade</td>
                <td>Responsavel</td>
                <td>Status do Pedido</td>
                <td>Situação</td>
            </tr>
            <tr ng-repeat="x in pedidos">
                <td><a ng-href="#/pedidos/detalhes/mostrar" ng-click="pesquisar(x.idCompra)"><% x.idCompra %></a></td>
                <td><% x.titulo %></td>
                <td><% x.solicitante %></td>
                <td><% x.dataCompra %></td>
                <td><% x.dataOrcAtualizado %></td>
                <td><% x.dataAprovado %></td>
                <td><% x.prioridade %></td>
                <td><% x.criacao %></td>
                <td><% x.status %></td>
                <td><% x.dias %></td>
            </tr>
        </table>




    <div id="drop-area" class="drop-area detailsPedido ">

        <div>
            <button class="btn btn-primary voltar" ng-click="botoes.voltar()">voltar</button>
            <p></p>

            <div ng-view>



            </div>



        </div>
    </div>

    </div>
    <div class="drop-overlay"></div>


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
