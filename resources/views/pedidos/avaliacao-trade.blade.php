

@extends('master')


@section('content')

    <h1><i class="fa fa-star-o"></i> Avaliação de pedidos do Trade Marketing</h1>

    @if(isset($resp))

        <p class="{{ $resp['class'] }}"><b>{{ $resp['msg'] }}</b></p>

    @endif


    <div ng-controller="avaliacaoPedidos">

        <table class="table table-responsive">
            <tr style="font-weight: bold">
                <td>Nº</td>
                <td>Titulo</td>
                <td>Solicitante</td>
                <td>Data do Pedido</td>
                <td>Situação</td>
            </tr>
            <tr ng-repeat="x in pedidos">
                <td><a ng-href="#/pedidos/atualizacao/detalhes/x.idCompra" ng-click="pequisar(x.idCompra)"><% x.idCompra %></a></td>
                <td><% x.titulo %></td>
                <td><% x.solicitante %></td>
                <td><% x.dataCompra %></td>
                <td>Aguardadndo analise</td>
            </tr>
        </table>





        <div id="drop-area" class="drop-area detailsPedido">

            <div ng-view>


            </div>

        </div>

        <div class="drop-overlay"></div>
    </div>
    <script src="{{ asset('js/jquery.js')  }}"></script>
    <script>
        jQuery(function($)
        {

            $('.voltar').click(function(){
                $('#drop-area').removeClass('show');

            });

        });

    </script>





@endsection