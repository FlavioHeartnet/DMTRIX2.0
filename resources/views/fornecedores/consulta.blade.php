

@extends('master')


@section('content')

    <h1><i class="fa fa-industry"></i>Gestão de Fornecimento</h1>


<div ng-controller="filaFornecedor">


    <div class="col-lg-4">
        <label>
            <input type="text" ng-model="busca" class="form-control" placeholder="pesquise aqui">
        </label>
    </div>
    <div class="col-lg-8">

        <div class="col-lg-2">
            <a href="" class="colorAzul">
                <i class="fa fa-search"></i>
                <p style="font-size: 15px">Disponiveis para fabricação</p>
            </a>
        </div>
        <div class="col-lg-2">
            <a href="" class="colorVerde">
                <i class="li_stack"></i>

                <p style="font-size: 15px">Enviados para Fabricação</p>
            </a>
        </div>
        <div class="col-lg-4">
            <a href="" class="colorRosa">
                <i class="fa fa-star"></i>

                <p style="font-size: 15px">Disponiveis para Retirada<br> <b>(Clique no icone semelhante a esse abaixo abaixo para finalizar o pedido de uma vez só)</b></p>

            </a>
        </div>


    </div>


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

            <td >

                <i style="font-size: 30px" ng-if="x.status == 'aprovado'" class="fa fa-search colorAzul"></i>
                <i style="font-size: 30px" ng-if="x.status == 'fornecedor'" class="li_stack colorVerde"></i>
                <i style="font-size: 30px" ng-if="x.status == 'finalizado'" class="fa fa-check-circle-o colorVerdeClaro"></i>
                <a ng-if="x.status == 'aguadando'" class="colorRosa"  href="#" ng-click="modalFinalizar(x.idCompra)">
                    <i style="font-size: 30px"  class="fa fa-star"></i>
                    <p>Clique aqui!</p>
                </a>



            </td>

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


    <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Finalizar compra</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(array( 'method' => 'post', 'action' => 'FornecedorController@finalizarCompra')) !!}
                    {!! Form::hidden('token','<% idCompra %>') !!}

                    <i style="font-size: 30px" class="fa fa-check-circle-o colorVerdeClaro"></i>
                    <div class="form-group">
                        <label>Data da entrega
                            <input type="date" class="form-control" name="data" >
                        </label>
                    </div>
                    <div class="form-group">

                        <label>Quem retirou
                            <input type="text"  class="form-control" name="retirou" ><br>
                        </label>
                    </div>
                    <div class="form-group">

                        <label>Quem entregou
                            <input type="text"  class="form-control" name="entregou" ><br>
                            <input type="submit" class="btn btn-warning backgroundLaranja" value="Salvar">

                        </label>


                    </div>

                    {!! Form::close()!!}
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>


@endsection