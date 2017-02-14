


<div class="container-fluid col-lg-12" >
    <div class="page-header">
        <h1 id="timeline">Dados da Compra</h1>
    </div>
    <ul class="timeline timelinePedidos" ng-repeat="d in detalhes">
        <li class="timeline-inverted">
            <div class="timeline-badge"><i class="fa fa-check"></i></div>
            <div class="timeline-panel">
                <div class="timeline-body">
                    <div class="col-lg-3">
                        <img class="img-responsive" src="{{url('img/sem-arte.png')}}" alt="">
                    </div>
                    <div class="col-lg-9">

                        <table class="table-responsive" cellpadding="0" cellspacing="0" border="0" style="text-align: left;    line-height: 23px;">
                            <tr>
                                <td colspan="3">
                                    <b>Material</b><br>
                                    <% d.material %>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Nº</b><br>
                                    <% d.idPedido %>
                                </td>
                                <td><b>Loja:</b><br>
                                    <% d.loja %></td>
                                <td>
                                    <b>Descrição</b></br>
                                    Altura: <% d.altura %> | Largura: <% d.largura %>

                                </td>
                                <td>
                                    <b>Quantidade:</b></br>
                                    <% d.quantidade %>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><b>Data Orçamento foi aprovado</b></td>
                                <td class=""><b>Data de entrega ideal da arte</b></td>
                                <td class="" ><b>Data da arte postada</b></td>
                                <td class=""><b>Data da revisão</b></td>
                                <td class=""><b>Data que a arte foi aprovada</b></td>

                            </tr>
                            <tr>
                                <td ><% d.dataOrcAprovado %></td>
                                <td class=""><% d.dataIdeal %></td>
                                <td class=""><% d.dataArtePostada %></td>
                                <td class=""><% d.dataRevisao %></td>
                                <td class=""><% d.data_aprovado_arte %></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <b>Observações</b><br>
                                    <% d.observacao %>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="row">





                        <table class="table-responsive table" style="text-align: left">
                            <tr>
                                <td>
                                    <b>Fornecedor</b><br>
                                    <% d.razao %>
                                </td>
                                <td>
                                    <b>Custo unitátio</b><br>
                                    <% d.valorProduto %>
                                </td>
                                <td>
                                    <b>Custo Total</b><br>
                                    R$ <% d.valorTotal %>
                                </td>
                                <td>
                                    <b>Data de entrega</b><br>
                                    <% d.dataPrevista %>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><b>Enviado para produção</b></td>
                                <td class="" ><b>Data estimada pelo fornecedor</b></td>
                                <td class=""><b>Recebimento do material</b></td>
                            </tr>
                            <tr>
                                <td ><% d.dataSaida %></td>
                                <td class=""><% d.dataPrevista %></td>
                                <td class=""><% d.dataEntrada %></td>
                            </tr>

                            <tr>
                                <td ng-if="d.status_pedido == '6'">
                                    {!! Form::open(array( 'method' => 'post', 'action' => 'FornecedorController@enviarFornecedor')) !!}
                                            <i style="font-size: 30px" class="fa fa-search colorAzul"></i>
                                            <div class="form-group">
                                                {!! Form::hidden('token','<% d.idPedido %>') !!}

                                                <label>Atribuir Fornecedor
                                                    <select name="fornecedor" class="form-control dropdown" ng-options="x as x.nome for x in d.fornecedor track by x.id" ng-model="selectedFornecedor" >

                                                    </select>
                                                </label>
                                            </div>


                                            <div class="form-group">
                                                <label>Estimativa de entrega
                                                    <input type="date" class="form-control" name="data" ng-model="data" >
                                                </label>

                                            </div>
                                            <div class="form-group">

                                                <input type="submit"  class="btn btn-warning backgroundLaranja" value="Salvar">

                                            </div>
                                    {!! Form::close()!!}


                                </td>
                                <td ng-if="d.status_pedido == '8' || d.status_pedido == '82'">



                                            <i style="font-size: 30px" class="li_stack colorVerde"></i>

                                            <div class="form-group">

                                                <input type="button" ng-click="entrega(d.idPedido)" class="btn btn-warning backgroundLaranja" name="data" value="Produto chegou?">

                                            </div>



                                </td>
                                <td class="" colspan="2" ng-if="d.status_pedido == '81'">

                                    {!! Form::open(array( 'method' => 'post', 'action' => 'FornecedorController@finalizar')) !!}
                                    {!! Form::hidden('token','<% d.idPedido %>') !!}

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


                                </td>
                                <td> <h3><% d.status %></h3></td>
                            </tr>
                        </table>

                    </div>

                </div>
            </div>
        </li>



    </ul>
</div>