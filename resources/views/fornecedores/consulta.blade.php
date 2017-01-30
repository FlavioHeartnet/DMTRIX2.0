

@extends('master')


@section('content')

    <h1><i class="fa fa-industry"></i>Gestão de Fornecimento</h1>


<div ng-controller="filaFornecedor">
    @if(isset($resp))

        <p class="{{ $resp['class'] }}"><b>{{ $resp['msg'] }}</b></p>

    @endif

    <label>
        <input type="text" ng-model="busca" class="form-control" placeholder="pesquise aqui">
    </label>

    <table class="table table-responsive table-bordered" >

        <tr style="font-weight: bold" class="colorAzul">

            <td>Filtro</td>
            <td>Nº</td>
            <td>Loja</td>
            <td>Solicitante</td>
            <td>
                Valor da Compra
            </td>
            <td>Orçamento Atualizado</td>
            <td>Prioridade</td>
            <td>Responsável</td>


        </tr>

        <tr ng-repeat="x in pedidos | filter: busca">

            <td ><i style="font-size: 30px" ng-if="x.status == 'aprovado'" class="fa fa-search colorAzul"></i>
                <i style="font-size: 30px" ng-if="x.status == 'fornecedor'" class="li_stack colorVerde"></i>
                <i style="font-size: 30px" ng-if="x.status == 'finalizado'" class="fa fa-check-circle-o colorVerdeClaro"></i>
                <i style="font-size: 30px" ng-if="x.status == 'aguadando'" class="fa fa-clock-o colorRosa"></i></td>

            <td><a ng-href="#/detalhes/pedidos/fornecedor" ng-click="pesquisar(x.idCompra)"><% x.idCompra %></a></td>
            <td><% x.loja %></td>
            <td><% x.solicitante %></td>
            <td><% x.valorTotal %></td>
            <td><% x.dataOrcAtualizado %></td>
            <td><% x.prioridade %></td>
            <td><% x.criacao %></td>


        </tr>


    </table>

    <div id="drop-area" class="drop-area detailsPedido">

        <div>
            <button class="btn btn-primary voltar" ng-click="servico.voltar()">voltar</button>
            <p></p>

            <div ng-view>



            </div>



        </div>

    </div>
    <div class="drop-overlay"></div>

</div>


@endsection