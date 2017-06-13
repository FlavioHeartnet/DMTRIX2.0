<?php
$value = session('user');

if($value['token'] == 1){

    $id = $value['id'];

}else{

    echo '<script>location.href="/logout"</script>';

}

?>
        <!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>DMTRIX - GESTÃO DE PEDIDOS</title>

@if(\Illuminate\Support\Facades\Config::get('app.debug'))

    <!-- Bootstrap core CSS -->
        <link href="{{ asset('build/css/vendor/bootstrap.min.css')  }}" rel="stylesheet">

        <!--external css-->
        <link href="{{ asset('css/font-awesome/css/font-awesome.css')  }}" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="{{ asset('build/css/vendor/jquery.gritter.css')  }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('build/css/vendor/bootstrap-switch.min.css')  }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('lineicons/style.css')  }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/page_scale.css')  }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css')  }}">
        <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
        <!-- Custom styles for this template -->
        <link href="{{ asset('css/style.css')  }}" rel="stylesheet">
        <link href="{{ asset('css/estilo.css')  }}" rel="stylesheet">
        <link href="{{ asset('css/style-responsive.css')  }}" rel="stylesheet">
    @else

    @endif


    <script src="{{ asset('build/js/vendor/Chart.min.js')  }}"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="background-color: #f0f0f0">
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <!--logo start-->
    <a href="{{ url('/home')  }}" class="logo"><b>DMTRIX - GESTÃO DE PROJETOS</b></a>
    <!--logo end-->
    <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <ul class="nav top-menu">
            <!-- settings start -->
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                    <i class="fa fa-envelope"></i>
                    <span ng-if="nortificacoes.msg != 0" class="badge bg-theme04 nortifica"><% nortificacoes.msg %></span>
                </a>
                <ul class="dropdown-menu extended inbox">
                    <div class="notify-arrow notify-arrow-green"></div>
                    <li>
                        <p class="green">Voçê tem <% nortificacoes.msg %> mensagens</p>
                    </li>
                    <li ng-repeat="a in msg">
                        <a href="{{url('/pedidos/mensagens')}}">
                                <span class="photo"><img ng-if="a.foto != null"  class="img-circle img-thumbnail img-responsive" src="{{ url('img/fotos/<% a.foto %>') }}">
                                    <img ng-if="a.foto == null" class="img-circle img-thumbnail img-responsive" src="{{ url('/img/sem-foto.png') }}"></span>
                                    <span class="subject">
                                    <span class="from"><% a.solicitante %></span>
                                    <span class="time"><% a.data %></span>
                                    </span>

                                    <span class="message" >
                                        Pedido <% a.idCompra %> - <% a.loja %>
                                    </span>

                        </a>
                    </li>

                    <li>
                        <a href="{{url('/pedidos/mensagens')}}">Ver todas as mensagens</a>
                    </li>
                </ul>
            </li>
            <!-- settings end -->
            <!-- inbox dropdown start-->
            <li id="header_inbox_bar" class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                    <i class="fa fa-refresh"></i>
                    <span ng-if="nortificacoes.numRevisao != 0" class="badge bg-theme04 nortifica"><% nortificacoes.numRevisao %></span>
                </a>
                <ul class="dropdown-menu extended inbox">
                    <div class="notify-arrow notify-arrow-green"></div>
                    <li>
                        <p class="green">Voçê tem <% nortificacoes.numRevisao %> itens para revisar</p>
                    </li>
                    <li ng-repeat="a in revisao">
                        <a href="{{url('/producao/revisao')}}">
                                <span class="photo"><img ng-if="a.foto != null"  class="img-circle img-thumbnail img-responsive" src="{{ url('img/fotos/<% a.foto %>') }}">
                                    <img ng-if="a.foto == null" class="img-circle img-thumbnail img-responsive" src="{{ url('/img/sem-foto.png') }}"></span>
                                    <span class="subject">
                                    <span class="from"><% a.solicitante %></span>
                                    <span class="time"><% a.data %></span>
                                    </span>

                                    <span class="message" >
                                        Pedido <% a.idCompra %> - <% a.loja %>
                                    </span>

                        </a>
                    </li>
                    <li>
                        <a href="{{url('/producao/revisao')}}">Ver todas as mensagens</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                    <i class="fa fa-paint-brush"></i>
                    <span ng-if="nortificacoes.numAprovacao != 0" class="badge bg-theme04 nortifica"><% nortificacoes.numAprovados %></span>
                </a>
                <ul class="dropdown-menu extended inbox">
                    <div class="notify-arrow notify-arrow-green"></div>
                    <li>
                        <p class="green">Voçê tem <% nortificacoes.numAprovados %> aprovadas</p>
                    </li>
                    <li ng-repeat="a in aprovados">
                        <a href="{{url('/fornecedores/consulta')}}">
                                <span class="photo"><img ng-if="a.foto != null"  class="img-circle  img-responsive" src="{{ url('img/fotos/<% a.foto %>') }}">
                                    <img ng-if="a.foto == null" class="img-circle img-responsive" src="{{ url('/img/sem-foto.png') }}"></span>
                                    <span class="subject">
                                    <span class="from"><% a.solicitante %></span>
                                    <span class="time"><% a.data %></span>
                                    </span>

                                    <span class="message">
                                        Pedido <% a.idCompra %> - <% a.loja %>
                                    </span>

                        </a>
                    </li>
                    <li>
                        <a href="{{url('/fornecedores/consulta')}}">Ver todas as mensagens</a>
                    </li>
                </ul>
            </li>

            <!-- inbox dropdown end -->
        </ul>
        <!--  notification end -->
    </div>


    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <li><a class="logout" href="{{ url('/logout') }}">Logout</a></li>
        </ul>
    </div>
</header>
<div class="container-fluid" ng-controller="criacao-fila">




    <input type="hidden" value="{{ $id }}" id="criacao">
    <div class="col-lg-12" style="margin-top: 90px;">

        @if(isset($resp))

            <p class="{{ $resp['class'] }}"><b>{{ $resp['msg'] }}</b></p>

        @endif

        <div class="col-lg-2">
            <img ng-if="criacaoUser[0].foto != null" width="150px"  class="img-circle img-thumbnail img-responsive" src="{{ url('img/fotos/<% criacaoUser[0].foto %>') }}">
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
                    <a ng-href="#/pedidos/detalhes/mostrar" ng-click="pesquisar(x.idCompra)"><% x.idCompra %></a><br>
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
                                <a ng-if="sub.status != 'criacao'" data-toggle="modal" data-target="#myModal" ng-click="modal(sub.foto)" class="btn btn-primary"  >Foto</a><br><br>

                            </td>
                            <td><% sub.quantidade %></td>
                            <td><% sub.custeio %></td>
                            <td><% sub.descricao %></td>
                            <td>


                                <div class="form-group" ng-if="sub.status == 'criacao'">

                                    {!! Form::file('foto[]',['style'=> 'font-size: 10px']) !!}
                                    {!! Form::hidden('token[]', '<% sub.idPedido %>')  !!}

                                </div>

                                <div class="form-group" ng-if=" sub.status == 'revisao'">

                                    <p class="colorAzul" style="font-size: 20px"><i class="fa fa-clock-o"></i> Revisão</p>


                                </div>
                                <div class="form-group" ng-if="sub.status == 'aprovacao'">
                                    <p class="colorLaranja" style="font-size: 20px"><i class="fa fa-clock-o"></i> Aguardando</p>
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

            </td>
            <td>
                <div class="">
                    <p class="colorLaranja">Indicador de Produção</p>
                    <canvas style="max-width: 250px; margin-left: 32px;" id="detalhes<% x.idCompra %>" height="80px" width="80px"></canvas>
                </div>
            </td>

        </tr>


    </table>


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Foto</h4>
            </div>
            <div class="modal-body">
                <img src="{{ url('http://mkt.dmcardweb.com.br:8000/img/fotos/<% foto %>') }}" class="img-responsive">
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
<script src="{{ asset('build/js/vendor/jquery.min.js')  }}"></script>
<script src="{{ asset('js/TweenMax.min.js')  }}"></script>
<script src="{{ asset('build/js/vendor/angular.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('build/js/vendor/angular-route.min.js')  }}"></script>
<script type="text/javascript" src="http://vitalets.github.io/angular-xeditable/dist/js/xeditable.js"></script>
<script type="text/javascript" src="{{ asset('build/js/vendor/angular-resource.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('build/js/vendor/angular-messages.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('build/js/vendor/angular-animate.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('build/js/vendor/ui-bootstrap.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('js/checklist-model.js')  }}"></script>
<script src="{{ asset('build/js/vendor/bootstrap.min.js')  }}"></script>
<script src="{{ asset('build/js/vendor/jquery.scrollTo.min.js')  }}"></script>
<script src="{{ asset('build/js/vendor/jquery.nicescroll.min.js')  }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.sparkline.js')  }}"></script>
<script src="{{ asset('js/mask.js')  }}"></script>




<!--common script for all pages-->
<script src="{{ asset('js/common-scripts.js')  }}"></script>

<script type="text/javascript" src="{{ asset('build/js/vendor/jquery.gritter.min.js')  }}"></script>


<!--script for this page-->
<script src="{{ asset('js/sparkline-chart.js')  }}"></script>
<script src="{{ asset('js/module.js')}}"></script>

</body>