<button class="btn btn-primary voltar" ng-click="botoes.voltar()">voltar</button>
<p></p>
<div class="container-fluid">
<div class="col-lg-9">
    <div class="col-lg-1"><img src="{{ url('img/sem-foto.png') }}" class="img-responsive"></div>
    <div class="col-lg-3">
        <h3>Pedido Nº <% result[0].idCompra %></h3>
        <p><% result[0].nomeLoja %></p>
    </div>
    <div class="col-lg-3">
        <h3><% result[0].titulo %></h3>
        <p>Entrada: <% result[0].Data_do_Pedido %></p>
    </div>
    <div class="col-lg-3">
        <p>Solicitante: </p>
        <h3><% result[0].solicitante %></h3>
        <p>Forma de Pagamento: <b><% result[0].formaPagamento %></b></p>
        <p>Custeio: <b><% result[0].custeio %></b></p>
    </div>
    <div class="col-lg-4">

    {!! Form::open(array( 'method' => 'post', 'action' => 'PedidosController@finalizarCompra')) !!}

        {!! Form::hidden('token','<% result[0].idCompra %>') !!}
        <div class="panel panel-danger">
            <div class="panel-heading">Deseja finalizar a compra?</div>
            <div class="panel-body">
                <textarea class="form-control" name="motivoFinalizar" placeholder="Motivo de finalizar"></textarea><br>
                <label > Cancelar compra?
                    <input  type="checkbox" class="checkbox" name="cancelar" value="1">
                </label><br>
                <button  class="btn btn-danger"><i class="fa fa-check"></i> Finalizar Pedido</button>
            </div>
        </div>


    {!! Form::close()!!}
    </div>

</div>
    <img src="{{url('img/divider.png')}}" class=" img-responsive"><br><br>

            <div class="col-lg-9" >
                <div class="content-panel">
                <div class="panel-body text-center">
                    <div class="detailsPedido">
                        <form method="post" action="{{ url('/pedidos/atualizacao/update/') }}" >
                            {!! Form::token() !!}

                        <div class="container-fluid" >
                            <div class="page-header">
                                <h1 id="timeline">Dados da Compra</h1>
                            </div>

                            <ul class="timeline">
                                <li class="timeline-inverted"  ng-repeat="x in result">

                                    {!! Form::hidden('token[]', '<% x.idPedido %>') !!}
                                    <div class="timeline-badge"><i class="fa fa-check"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-body">

                                            <div class="col-lg-12">
                                                <p style="font-size: 20px; color: deepskyblue"><b>Situação do Pedido: <% x.situacao %></b></p>

                                                <table class="table table-responsive" cellpadding="1" cellspacing="1" border="0" style="text-align: left">

                                                    <tr>
                                                        <td>
                                                            <b>Material</b><br>
                                                            <% x.material %>
                                                        </td>
                                                        <td>
                                                            <b>Nº</b><br>
                                                            <% x.idPedido %>
                                                        </td>
                                                        <td><b>Loja:</b><br>
                                                            <% x.nomeLoja %></td>
                                                        <td>
                                                            <div class="form-group" ng-if="x.formaCalculo == 1">
                                                                <b>Descrição</b></br>
                                                                <label for="Altura<% x.idPedido %>">Altura:</label><input Required id="Altura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"   type="text" ng-value="0" class="form-control" name="altura[]">
                                                                <label for="Largura<% x.idPedido %>">Largura:</label><input Required id="Largura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"   type="text" ng-value="0" class="form-control" name="largura[]">
                                                            </div>
                                                            <div class="form-group" ng-if="x.formaCalculo == 2">
                                                                <b>Descrição</b></br>
                                                                <label for="Largura<% x.idPedido %>">Largura:</label><input Required id="Largura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="text" ng-value=" x.largura " class="form-control" name="largura[]">
                                                                <label for="Altura<% x.idPedido %>">Altura:</label><input Required id="Altura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="text" ng-value="x.altura" class="form-control" name="altura[]">

                                                            </div>
                                                            <div class="form-group" ng-if="x.formaCalculo == 3">
                                                                <b>Descrição</b></br>
                                                                <label for="Largura<% x.idPedido %>">Largura:</label><input Required id="Largura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="text" ng-value="100"  class="form-control" name="largura[]">
                                                                <label for="Altura<% x.idPedido %>">Altura:</label><input Required id="Altura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="text" ng-value="100"  class="form-control" name="altura[]">

                                                            </div>
                                                            <div class="form-group" ng-if="x.formaCalculo == 4">
                                                                <b>Descrição</b></br>
                                                                <label for="Largura<% x.idPedido %>">Largura:</label><input Required id="Largura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="text" ng-value="100"  class="form-control" name="largura[]">
                                                                <label for="Altura<% x.idPedido %>">Altura:</label><input Required id="Altura<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="text" ng-value="100"  class="form-control" name="altura[]">

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label for="Quantidade<% x.idPedido %>">Quantidade</label><input Required id="Quantidade<% x.idPedido %>" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)"  type="number"  ng-value=" x.quantidade " class="form-control" name="quantidade[]">
                                                        </td>
                                                        <td>
                                                            <label for="custo<% x.idPedido %>">Custo Unitario</label><input Required id="custo<% x.idPedido %>" type="text" ng-blur="soma(x.idPedido)" ng-click="soma(x.idPedido)" ng-value=" x.valorUnitario " class="form-control" name="custoUnitario[]">

                                                        </td>
                                                        <td>
                                                            <b>Custo Total:</b></br>
                                                            R$ <span  id="custoTotal<% x.idPedido %>"></span>
                                                            <input Required  type="hidden" id="custoInput<% x.idPedido %>" value="" name="custoTotal[]">
                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td colspan="7">
                                                            <b>Observações</b><br>
                                                            <label for="observacao"></label><textarea Required id="observacao" class="form-control" name="observacao[]"><% x.observacao %></textarea>
                                                        </td>
                                                    </tr>

                                                </table>


                                                    <textarea class="form-control" id="motivo<% x.idPedido %>" placeholder="Motivo do cancelamento ou Recusa"></textarea><br>

                                                <div class="col-lg-6">
                                                    <input type="button" ng-click="cancelarItem(x.idPedido, result[0].idCompra )" class="btn btn-danger" value="Cancelar este item?"><p></p>
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="button" ng-click="devolverItem(x.idPedido)" class="btn btn-danger" value="Recusar item?"><p></p>
                                                    </div>





                                            </div>

                                        </div>
                                    </div>
                                </li>


                            </ul>

                            <div class="form-group">

                                <div class="col-lg-5" ng-if="result[0].status_pedido != 25">
                                    <label for="tipo">Enviar para aprovação do Trade.
                                        <input id="tipo" type="checkbox" class="checkbox checkbox-inline" name="tipoAprovacao" value="1">
                                    </label>
                                </div>

                                <div class="col-lg-3" ng-if="result[0].status_pedido == 25">
                                    <label>
                                        <textarea placeholder="Motivo da reprova" name="orcReprovado" class="form-control"></textarea>
                                    </label>
                                    <label for="tipo">
                                        <input  type="submit" class="btn btn-danger" name="reprovar" value="reprovar">
                                    </label>
                                </div>


                                <div class="col-lg-3">

                                    <input type="submit" class="btn btn-warning backgroundLaranja" value="Atualizar">
                                </div>
                                <div class="col-lg-4">
                                    <h3>Custo total atualizado: <b>R$<% val %></b></h3>
                                </div>

                            </div>
                        </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>


</div>