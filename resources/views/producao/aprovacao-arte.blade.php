

@extends('master')


@section('content')

<div ng-controller="aprovacao-arte">

    @if(isset($resp))

        <p class="{{ $resp['class'] }}"><b>{{ $resp['msg'] }}</b></p>

    @endif
    <div class="container-fluid" >
        <div class="page-header">
            <h1 id="timeline"><i class="fa fa-paint-brush"></i> Aprovação de Arte </h1>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Pesquise aqui" ng-model="busca">
        </div>
        <ul class="timeline">
            <li class="timeline-inverted" ng-repeat="rev in aprovacao | filter: busca">
                <div class="timeline-badge"><i class="fa fa-clock-o"></i></div>
                <div class="timeline-panel">
                    <div class="timeline-body">

                        <div class="col-lg-12">
                            <div class="col-lg-2">
                                <p style="font-size: 25px">Pedido: <% rev.idCompra %></p>
                                <p>Loja: <% rev.loja %></p>
                                <p>Total de itens: <% rev.item %></p>
                                <a class="btn btn-warning backgroundLaranja" ng-href="#/pedidos/detalhes/mostrar" ng-click="pesquisar(rev.idCompra)">Analisar Pedido</a>
                            </div>
                            <div class="col-lg-10">
                                <table class="table table-responsive">
                                    <tr>
                                        <td>Item</td>
                                        <td>Material</td>
                                        <td>Observação</td>
                                        <td>Quantidade</td>
                                        <td>Arte</td>
                                        <td>Criação</td>
                                        <td>Status</td>
                                    </tr>
                                    <tr ng-repeat="sub in rev.pedidos">
                                        <td><% sub.idPedido %></td>
                                        <td><% sub.material %></td>
                                        <td><% sub.observacao %></td>
                                        <td><% sub.quantidade %></td>
                                        <td>
                                            <a ng-if="sub.fotoArte != ''" class="btn btn-primary"  ng-href="{{ url('http://dmcard.com.br/dmtrade/img/brindes/<% sub.fotoArte %>') }}">Foto</a><br><br>
                                            <a ng-if="sub.fotoArte != ''" class="btn btn-primary"  ng-href="{{ url('img/fotos/<% sub.fotoArte %>') }}">Foto - Opção 2</a>
                                        </td>
                                        <td style="font-size: 20px"><b><% sub.criacao %></b></td>
                                        <td>
                                            <div class="form-group" ng-if="sub.status == 'Aguardando'">
                                                <p class="colorLaranja" style="font-size: 25px"><i class="fa fa-clock-o"></i> Aguardadndo</p>
                                                <input ng-click="aprovar(sub.idPedido)" type="button" value="aprovar" class="btn btn-warning backgroundLaranja">
                                            </div>
                                            <div class="form-group" ng-if="sub.status == 'Reprovado'">
                                                <p class="colorVermelho" style="font-size: 25px"><i class="fa fa-remove"></i> Reprovado</p>
                                            </div>
                                            <div class="form-group" ng-if="sub.status == 'Aprovado'">
                                                <p class="colorVerde" style="font-size: 25px"><i class="fa fa-check"></i> Aprovado</p>
                                            </div>

                                        </td>
                                    </tr>

                                </table>
                            </div>


                        </div>


                    </div>
                </div>
            </li>



        </ul>

    </div>


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
