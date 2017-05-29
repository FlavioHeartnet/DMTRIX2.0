<div ng-controller="detalhesPedido">
        <div class="container-fluid" >

            <div class="col-lg-9">
            <div style="font-size: 25px">
                <div class="col-lg-1"></div>
                <div class="col-lg-3">Nº <% array.idCompra %></div>
                <!--<div class="col-lg-3"><% array.Titulo %></div>-->
            </div>
            <div class="col-lg-4">
                {!! Form::open(array( 'method' => 'post', 'action' => 'PedidosController@finalizarCompra')) !!}
                <div class="form-group" ng-if="array.status_compra != 'Finalizado'">
                    {!! Form::hidden('token', '<% array.idCompra %>') !!}
                    <div class="panel panel-danger" ng-if="array.status_compra != 'Finalizado'">
                        <div class="panel-heading">Deseja finalizar a compra?</div>
                        <div class="panel-body">
                            <textarea class="form-control" name="motivoFinalizar" placeholder="Motivo de finalizar"></textarea><br>
                            <label > Cancelar compra?
                                <input  type="checkbox" class="checkbox" name="cancelar" value="1">
                            </label><br>
                            <button  class="btn btn-danger"><i class="fa fa-check"></i> Finalizar Pedido</button>
                        </div>
                    </div>

                </div>
                {!! Form::close()!!}
            </div>
                </div>

            <div class="col-lg-9" style="margin-top: 20px">


                   <!-- <div class="col-lg-3">Foto</div>-->


                <div class="col-lg-9" style="text-align: left;     line-height: 27px;">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Informações da compra</div>
                        <div class="panel-body">
                            <div class="row titulos">
                                <div class="col-lg-3">
                                    <p><b>Nome da loja:</b><br> <% array.Loja %></p>
                                    <p><b>Custo total:</b><br> R$ <% array.valorTotal | number: 2 %></p>
                                </div>
                                <div class="col-lg-4">
                                    <p><b>Data da Compra:</b> <br><% array.dataCompra %></p>
                                    <p><b>Pagamento:</b> <br><% array.formaPagamento %></p>
                                    <p><b>Custeio:</b> <br><% array.custeio %></p>
                                    <p><b>Segmento:</b> <br><% array.segmento %></p>
                                </div>
                                <div class="col-lg-5">
                                    <p><b>Solicitante:</b><br> <% array.nome %></p>
                                    <p><b>Entrega ideial:</b><br> <% array.dataIdeal %></p>
                                    <p><b>Fornecedor entregou :</b><br>  <% array.dataEntrada %></p>
                                    <p><b>Pedido finalizado:</b> <br> <% array.dataSaida %> </p>
                                </div>
                            </div><br>
                        </div>
                    </div>

                </div>

                    <div class="col-lg-3">
                        {!! Form::open(array( 'method' => 'post', 'action' => 'PedidosController@redelegarTarefas')) !!}
                        {!! Form::hidden('token','<% array.idCompra %>') !!}
                        <div class="panel panel-success" ng-if="array.status_compra == 'criacao'">
                            <div class="panel-heading">Criação</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Redelegar
                                        <select name="criacao" class="form-control dropdown"
                                                ng-options="x as x.criacao for x in array.criacao track by x.id" ng-model="selectedCriacao">

                                        </select>
                                    </label>
                                    <label>Prioridade
                                        <select required name="prioridade" class="form-control dropdown">
                                            <option value="3">Baixa</option>
                                            <option value="2">Media</option>
                                            <option value="1">Alta</option>
                                            <option value="0">Sem categoria</option>
                                        </select>
                                    </label>
                                    <label>Estimativa de entrega
                                        <input class="form-control" name="dataEstimada" type="date">
                                    </label>

                                    <input type="submit" class="btn btn-success" value="Redelegar">
                                </div>
                            </div>
                        </div>


                        {!! Form::close()!!}




                    </div>

            </div>
            <div class="col-lg-9" style="margin-top: 20px">
                <img src="{{ url('img/divider.png') }}" class="img-responsive center-block">
            </div>

        </div><br>

        <div class="col-lg-9 titulos">
            <div class="col-lg-7">

                <div class="content-panel">
                    <div class="panel-body text-center">
                        <div class="timelinePedidos">
                            <div class="container-fluid" >
                                <div class="page-header">
                                    <h1 id="timeline">Dados da Compra</h1>
                                </div>
                                <ul class="timeline">

                                    <li class="timeline-inverted" ng-repeat="x in pedido">
                                        <div class="timeline-badge"><i class="fa fa-check"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-body">
                                                <div class="col-lg-2">
                                                    <a ng-if="x.fotoArte != ''" data-toggle="modal" data-target="#myModal" ng-click="modal(x.fotoArte)" class="btn btn-primary"  >Foto</a><br><br>
                                                </div>
                                                <div class="col-lg-10">

                                                    <div ng-if="x.status_pedido !='Finalizado'">

                                                    <textarea class="form-control" id="motivo<% x.idPedido %>" placeholder="Motivo do cancelamento"></textarea><br>

                                                    <button class="btn btn-primary" ng-click="cancelarPedido(x.idPedido)"><i class="fa fa-trash-o"></i> Cancelar Pedido</button><p></p>


                                                    <input ng-if="x.tipo == 'Produto sem custo'" type="button" ng-click="devolverItem(x.idPedido)" class="btn btn-danger" value="Recusar item?"><p></p>

                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <table  class="table table-responsive table-bordered" cellpadding="0" cellspacing="0" border="0" style="text-align: center; ">
                                                        <tr>
                                                            <td>
                                                                <b>Material</b><br>
                                                            </td>
                                                            <td>
                                                                <b>Nº</b><br>

                                                            </td>
                                                            <td>
                                                                <b>Loja:</b><br>

                                                            </td>
                                                            <td>
                                                                <b>Descrição</b>

                                                            </td>
                                                            <td>
                                                                <b>Quantidade:</b></br>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <% x.Material %>
                                                            </td>
                                                            <td>
                                                                <% x.idPedido %>
                                                            </td>
                                                            <td>
                                                                <% x.nomeLoja %>
                                                            </td>
                                                            <td>
                                                                <span ng-if="x.altura == '' && x.largura==''">
                                                                    <% x.tipo %>
                                                                </span>
                                                                <span ng-if="x.altura != '' && x.largura!=''">
                                                                    Largura: <% x.largura %>cm | Altura: <% x.altura %> cm
                                                                </span>


                                                            </td>
                                                            <td>
                                                                <% x.quantidade %>
                                                            </td>



                                                        </tr>

                                                        <tr>
                                                            <td colspan="6">
                                                                <b>Observações</b><br>
                                                                <% x.observacao %>

                                                            </td>
                                                        </tr>

                                                    </table><p></p>
                                                </div>

                                                <div class="row">

                                                    <table class="table-responsive table" style="text-align: left">
                                                        <tr>
                                                            <td><b>Criação:</b><p><% x.criacao %></p></td>
                                                            <td>
                                                                <b>Previsão de entrega da Criação:</b><br>
                                                                <% x.tempoEstimado %>
                                                            </td>
                                                            <td><b>Status do Pedido</b><br> <h4 class="colorLaranja"><% x.status_pedido %> </h4></td>

                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b>Fornecedor</b><br>
                                                                <% x.razao %>
                                                            </td>
                                                            <td>
                                                                <b>Custo unitátio</b><br>
                                                                R$ <% x.precoUnitario | number: 2 %>
                                                            </td>
                                                            <td>
                                                                <b>Custo Total</b><br>
                                                                R$ <% x.valorProduto | number: 2  %>
                                                            </td>
                                                            <td>
                                                                <b>Data de entrega</b><br>
                                                                <% x.dataPrevista %>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>

                                                                <div class="switch switch-square"
                                                                     data-on-label="<i class=' fa fa-check'></i>"
                                                                     data-off-label="<i class='fa fa-times'></i>">
                                                                    <input type="checkbox" />
                                                                </div>

                                                            </td>
                                                            <td><b>Entregue</b><br><% x.dataSaida %></td>
                                                            <td>
                                                                <% x.entrega %>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>

                                            </div>
                                        </div>
                                    </li>


                                </ul>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-5">

                <div class="content-panel">

                    <div class="panel-body text-center" >

                        <div class="detailsPedido" >

                            <div class="container-fluid" >
                                <div class="page-header">
                                    <h1 id="timeline">Timeline do Pedido</h1>
                                </div>
                                <div class="row">
                                <form method="post" ng-submit="submit()">
                                    <div class="col-lg-7">
                                        <textarea name="obs" class="form-control" ng-model="obs" id="text" required placeholder="Digite alguma observação do produto"></textarea><br>
                                    </div>
                                    <div class="col-lg-3" style="color: #ffffff">
                                        <button class="btn btn-warning backgroundLaranja "  style="width: 100%">Enviar</button>
                                    </div>
                                </form><p></p>
                                </div>
                                <div class="row">
                                <ul class="timeline ">
                                    <li class="timeline-inverted" ng-repeat="x in timeline">
                                        <div class="timeline-badge"><img src="{{ url('img/sem-foto.png')  }}" class="img-responsive img-circle"> </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title"><% x.nome %></h4>
                                                <p><small class="text-muted"><% x.dataObs %></small></p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>Nº : <% x.idCompra %></p>
                                                <p><% x.observacao %></p>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                                    </div>
                            </div>

                        </div>

                    </div>
                </div><br><br>





            </div>
        </div>

</div>

