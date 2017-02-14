

<div class="container left">

    <div class="col-lg-12">

        <div class="col-lg-2">
            <img ng-if="criacaoUser[0].foto != null"  class="img-circle img-thumbnail img-responsive" src="{{ url('img/fotos/<% criacaoUser[0].foto %>') }}">
            <img ng-if="criacaoUser[0].foto == null" class="img-circle img-thumbnail img-responsive" src="{{ url('/img/sem-foto.png') }}"><br>
        </div>
        <div class="col-lg-2">
            <br>
            <!--<a href="#"><img src="{{ url('img/carregar-foto.png') }}" class=""></a>-->
            <h2 class="colorAzul"><% criacaoUser[0].criacao %></h2>
            <p>Criação/Design</p>
            <a href=""><% criacaoUser[0].email %></a><p></p>
        </div>
        <div class="col-lg-4"><img src="{{ url('img/divider.png') }}" class="img-responsive"><br></div>

        <div class="col-lg-2">

            <a href="#"> <img src="{{ url('img/edit-btn.png') }}" class=""></a>
            <a href="#"><img src="{{ url('img/delete-btn.png') }}" class=""><br></a>
        </div>
    </div>


    <div class="col-lg-11">
        <div class="progress progress-striped ">
            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<% porcentagem | number: 2 %>" aria-valuemin="0" aria-valuemax="100" style="width: <% porcentagem | number: 2 %>%">
                <span class="sr-only"><% porcentagem | number: 2 %>% completo</span>
            </div>
        </div>
        <div class="task-info">
            <div class="desc">Conclusão</div>
            <div class="percent"><b><% porcentagem | number: 2 %>%</b></div>
        </div>
    </div>


    <input type="text" class="form-control" placeholder="Pesquise aqui" ng-model="busca">
    <table class="table table-responsive" style="margin-top: 50px; text-align: center; font-size: 25px" align="center">
        <tr class="colorLaranja">
            <td>Tarefa/Compra</td>

        </tr>
        <tr ng-repeat="x in criacaoUser | filter: busca" on-finish-render="ngRepeatFinishedDetails">
            <td>
                <div class="col-lg-12">
                <% x.idCompra - x.titulo %><br>
                <p>Loja: <% x.loja %></p>
                <p>Solicitante: <% x.solicitante %></p>
                    {!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data', 'action' => 'ProducaoController@salvarArte')) !!}
                    <table class="table subTable table-responsive left">
                        <tr>
                            <td>Material</td>
                            <td>Observação</td>
                            <td>Situação</td>
                            <td>Data da Ultima atualização</td>
                            <td>Arte</td>
                            <td>Quantidade</td>
                            <td>Custeio</td>
                            <td>Descrição</td>
                            <td></td>
                        </tr>
                        <tr ng-repeat="sub in x.detalhes">
                            <td><% sub.material %></td>
                            <td><% sub.observacao %></td>
                            <td>
                                <b><% sub.situacao %></b>
                            </td>
                            <td><% sub.dataObs %></td>
                            <td>

                                <p ng-if="sub.status == 'criacao'" >Arte ainda não disponivel</p>
                                <a ng-if="sub.status != 'criacao'"  ng-href="{{ url('http://dmcard.com.br/dmtrade/img/brindes/<% sub.foto %>') }}">Foto</a><br>
                                <a ng-if="sub.status != 'criacao'"  ng-href="{{ url('img/fotos/<% sub.foto %>') }}">Foto - Opção 2</a>

                            </td>
                            <td><% sub.quantidade %></td>
                            <td><% sub.custeio %></td>
                            <td><% sub.descricao %></td>
                            <td>

                                <div class="form-group" ng-if="sub.status == 'criacao'">

                                    <input type="file" class="" required name="foto[]" style="    font-size: 10px;"><br><br>
                                    <input type="hidden" name="token[]" value="<% sub.idPedido %>"><br><br>


                                </div>

                                <div class="form-group" ng-if=" sub.status == 'revisao'">

                                    <input type="button" ng-click="servico.aprovacaoPedido(sub.idPedido, 1)" name="enviar" value="Enviar para aprovação" class="btn btn-success" ><br>
                                    <p></p>
                                    <input type="button" ng-click="servico.aprovacaoPedido(sub.idPedido, 2)" name="reprovar" value="Reprovar" class="btn btn-danger" ><br>

                                </div>
                                <div class="form-group" ng-if="sub.status == 'aprovacao'">
                                    <p class="colorLaranja" style="font-size: 20px"><i class="fa fa-clock-o"></i> Aguardadndo</p>
                                </div>
                                <div class="form-group" ng-if="sub.status == 'aprovado'">
                                    <p class="colorVerde" style="font-size: 20px"><i class="fa fa-check"></i> Aprovado</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td ng-if="x.filaEnviar == 1" colspan="9" align="right"><input type="submit" name="enviar" value="Enviar" class="btn btn-success" ></td>
                        </tr>
                    </table><br>
                    {!! Form::close()!!}
                </div>
                <div class="centered center-block">
                     <p class="colorLaranja">Indicador de Produção</p>
                    <canvas style="max-width: 250px; margin-left: 436px;" id="detalhes<% x.idCompra %>" height="80px" width="80px"></canvas>
                </div>
            </td>

        </tr>


    </table>
</div>