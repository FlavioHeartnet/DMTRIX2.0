

@extends('master')


@section('content')

    <div ng-controller="producao-revisao">


        <div class="container-fluid" >
            <div class="page-header">
                <h1 id="timeline"><i class="fa fa-refresh"></i> Revisão interna de arte </h1>
            </div>
            <ul class="timeline">
                <li class="timeline-inverted" ng-repeat="x in revisao">
                    <div class="timeline-badge"><i class="fa fa-clock-o"></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-body">

                            <div class="col-lg-12">
                                <div class="col-lg-2">
                                    <div ng-if=" sub.fotoArte != 'Sem Arte' ">
                                        <a ng-if="sub.fotoArte != ''" data-toggle="modal" data-target="#myModal" ng-click="modal(x.fotoArte)" class="btn btn-primary"  >Foto</a><br><br>
                                        <a ng-if="sub.fotoArte != ''" data-toggle="modal" data-target="#myModal" ng-click="modal(x.fotoArte)" class="btn btn-primary"  >Foto - Opção 2</a>
                                    </div>
                                    <div ng-if=" sub.fotoArte == 'Sem Arte' ">
                                        <h3>Sem arte</h3>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <p>Pedido:
                                    <% x.idCompra %></p>
                                    <p>Item:
                                        <% x.idPedido %></p>
                                    <p><b>Criação:
                                            <% x.criacao %></b></p>
                                    <p><b>Loja:<br>
                                            <% x.loja %></b></p>
                                </div>
                                <div class="col-lg-2">
                                    <p style="font-size: 20px"><b><% x.material %></b></p>
                                    <p><b>Observação:<br> <% x.observacao %></b></p>
                                    <p><b>Custeio: <% x.custeio %></b></p>
                                    <p><b>Segmento: <% x.segmento %></b></p>
                                </div>
                                <div class="col-lg-2">
                                    <p><b>Data da Compra:<br> <% x.dataCompra %></b></p>
                                    <p><b>Atualização de orçamento:<br> <% x.dataOrcAtualizado %></b></p>
                                    <p><b>Data que a arte foi postada:<br> <% x.dataArtePostada %></b></p>
                                </div>
                                <div class="col-lg-4">
                                    <form method="post" action="/producao/fila/aprovacao/">
                                        <input type="hidden" name="token" value="<% x.idPedido %>">
                                        <input type="hidden" name="tipo" value="1">
                                        <input type="submit" name="enviar" value="Enviar para aprovação" class="btn btn-success" ><br>
                                    </form>
                                    <p></p>
                                    <form method="post" action="/producao/fila/aprovacao/">
                                        <input type="hidden" name="token" value="<% x.idPedido %>">
                                        <input type="hidden" name="tipo" value="2">
                                        <input type="submit" name="reprovar" value="Reprovar" class="btn btn-danger" ><br><br>
                                        <label>
                                            <textarea name="motivo" required class="form-control" placeholder="Motivo da reprovação"></textarea>
                                        </label>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </li>


            </ul>

        </div>

            <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Foto</h4>
                        </div>
                        <div class="modal-body">
                            <img src="{{ url('http://mkt.dmcardweb.com.br/img/fotos/<% foto %>') }}" class="img-responsive">
                        <!-- <img src="{{ url('http://dmcard.com.br/dmtrade/img/brindes/<% foto %>') }}" class="img-responsive"> -->
                        </div>
                    </div>
                </div>
            </div>




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
