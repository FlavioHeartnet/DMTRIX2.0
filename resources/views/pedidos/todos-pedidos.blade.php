@extends('master')


@section('content')



    <div ng-controller="todosPedidos">
        <div class="form-group">

            <input type="text" class="form-control"  placeholder="Pesquise aqui...." ng-model="busca">
        </div>
    <table class="table table-responsive table-bordered" >
        <tr>
            <td>Nº</td>
            <td>Loja</td>
            <td>Solicitante</td>
            <td>Prioridade</td>
            <td>Data da Compra</td>
            <td>Situação da compra</td>
            <td>Produção</td>
            <td>Revisão interna de arte</td>
            <td>Aprovação de arte</td>
            <td>Artes Aprovadas</td>
            <td>Em fabricação</td>
            <td>Disponivel para retirada do solicitante</td>
            <td>Pedido entregue</td>
            <td>Situação compra</td>
        </tr>

        <tr ng-repeat="x in pedidos | filter: busca">
            <td><a ng-href="#/pedidos/detalhes/mostrar" ng-click="pesquisar(x.idCompra)"><% x.idCompra %></a></td>
            <td><% x.loja %></td>
            <td><% x.nome %></td>
            <td style="font-weight: bold">
                <p ng-if=" x.prioridade == 0" >Sem categoria</p>
                <p  ng-if=" x.prioridade == 1" >Baixa</p>
                <p ng-if=" x.prioridade == 2">Media</p>
                <p  ng-if=" x.prioridade == 3" >Alta</p>
            </td>
            <td><% x.dataCompra %></td>
            <td>

                <% x.situacao %>

            </td>
            <td>

                <% x.criacao %>



            </td>
            <td>
                <% x.numRevisao %>



            </td>
            <td>
                <% x.numAprovacao %>


            </td>
            <td>
                <% x.numAprovados %>


            </td>
            <td>
                <% x.numFornecedor %>


            </td>
            <td>
                <% x.numDisponivel %>


            </td>
            <td>
                <% x.numFinalizado %>


            </td>
            <td>
                <% x.status_compra %>


            </td>
        </tr>

    </table>


    <div id="drop-area" class="drop-area detailsPedido">

        <div>
            <button class="btn btn-primary voltar" ng-click="botoes.voltar()">voltar</button>
            <p></p>

            <div ng-view>



            </div>



        </div>
    </div>


        <div class="drop-overlay"></div>
    </div>


@endsection
