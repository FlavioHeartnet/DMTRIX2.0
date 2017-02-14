

@extends('master')


@section('content')
<div ng-controller="home">
        <div class="col-lg-12">

        <div class="col-lg-3">
        <span class="colorLaranja">05 de julho de 2016</span>
        <h2>Processos da agencia</h2>
                <canvas class="center-block" id="chartGeral" height="300" width="300"></canvas><br>
            <a href="{{ url('/home/analise')  }}"><button class="btn btn-warning center-block  backgroundLaranja"  >Analise Completa</button><br></a>
            <div class="centered">
            <p><% criacao[0].total %> pedidos em<br>Criação</p>
            <h1><% criacao[0].porcentagem %>%</h1>
            <p>concluidos</p>
            </div>

        </div>

        <div CLASS="col-lg-6">

            <table class="table table-responsive">
                <tr>
                    <td><p>Equipe Criativa</p></td>
                    <td><p>Pedidos em produção</p></td>
                    <td><p></p></td>

                </tr>
                <tr ng-repeat="x in criacao" on-finish-render="ngRepeatFinished">
                    <td><p><b><% x.criacao %></b></p></td>
                    <td><canvas style="max-width: 300px" id="chart<% x.idUsuario %>" height="80" width="80"></canvas></td>
                    <td><a ng-href="#/criacao/mostrar" class="btn btn-warning center-block  backgroundLaranja perfil" ng-click="mostrarFila(x.idUsuario)">Perfil Completo</a></td>
                </tr>

            </table>


        </div>



        </div>


    <div class="col-lg-4">

        <div id="morris">
            <h2><i class="fa fa-bar-chart"></i> Pedidos em Fase de Orçamentação</h2>
            <div class="panel-body">
                <div id="hero-bar" class="graph"></div>
            </div>

        </div>


    </div>


       <!-- <div class="col-lg-6">


            <h2><i class="fa fa-area-chart"></i> Incidencia de Pedidos</h2>
            <div class="panel-body">
                <div id="hero-area" class="graph"></div>
            </div>

        </div> -->
        <div class="col-lg-5" style="font-size: 50px;">
            <h2><i class="fa fa-industry"></i> Incidencia de Fabricação</h2><br>

            <div class="col-lg-2">
                <a href="" class="colorCinza">
                    <i class="fa fa-search"></i>
                    <p>16</p>
                    <p style="font-size: 10px">Disponiveis para fabricação</p>
                </a>
            </div>
            <div class="col-lg-2">
                <a href="" class="colorCinza">
                    <i class="fa fa-share-square-o"></i>
                    <p>50</p>
                    <p style="font-size: 10px">Enviados para Fabricação</p>
                </a>
            </div>
            <div class="col-lg-2">
                <a href="" class="colorCinza">
                    <i class="fa fa-clock-o"></i>
                    <p>36</p>
                    <p style="font-size: 10px">Proximos a Entrega</p>
                </a>
            </div>
            <div class="col-lg-2">
                <a href="" class="colorCinza">
                    <i class="li_stack"></i>
                    <p>100</p>
                    <p style="font-size: 10px">Disponiveis para Retirada</p>

                </a>
            </div>
            <div class="col-lg-2">
                <a href="" class="colorCinza">
                    <i class="fa fa-check-circle-o"></i>
                    <p>516</p>
                    <p style="font-size: 10px">Pedidos finalizados</p>
                </a>

            </div>


        </div>
        <div id="drop-area" class="drop-area detailsPedido">
            <div >
                <button class="btn btn-primary voltar" ng-click="servico.voltar()">voltar</button>
                <p></p>

                <div ng-view>



                </div>

            </div>

        </div>
        <div class="drop-overlay"></div>
</div>

    <script src="{{ asset('js/jquery.js')  }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>

    <script src="{{ asset('js/morris-conf.js')  }}"></script>



@endsection