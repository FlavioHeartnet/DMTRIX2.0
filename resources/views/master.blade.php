<?php
$value = session('user');

if($value['token'] == 1){


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

<body >

<section id="container" >
    <!-- **********************************************************************************************************************************************************
    TOP BAR CONTENT & NOTIFICATIONS
    *********************************************************************************************************************************************************** -->
    <!--header start-->
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
                        <span class="badge bg-theme04 ">4</span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <div class="notify-arrow notify-arrow-green"></div>
                        <li>
                            <p class="green">Voçê tem 4 mensagens</p>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
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
                        <span class="badge bg-theme04">5</span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <div class="notify-arrow notify-arrow-green"></div>
                        <li>
                            <p class="green">Voçê tem 5 itens para revisar</p>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
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
                        <span class="badge bg-theme04">5</span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <div class="notify-arrow notify-arrow-green"></div>
                        <li>
                            <p class="green">Voçê tem 5 aprovadas</p>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="{{url('img/sem-foto.png')}}"></span>
                                    <span class="subject">
                                    <span class="from">Flavio Barros</span>
                                    <span class="time">45 min</span>
                                    </span>

                                    <span class="message" >
                                        Pedido 785 - Implantação: Atualizado a arte
                                    </span>

                            </a>
                        </li>

                        <li>
                            <a href="{{url('/producao/aprovacao')}}">Ver todas as mensagens</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="form-group">
                    <label>
                        <input style="width: 600px; opacity: 0.5"  class="form-control pesquisa" name="search" value="" type="text" placeholder="Pesquise aqui o numero do Pedido">
                    </label>
                    </div>
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
    <!--header end-->

    <!-- **********************************************************************************************************************************************************
    MAIN SIDEBAR MENU
    *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="">

                <p class="centered" style="font-size: 30px"><a href="{{ url('/pedidos/todos')  }}" style="color: #7ad1e2"><i class="li_world"></i> Todos Pedidos</a></p>


                <li class="">
                    <a href="javascript:;" style="color: #fed77a">
                        <i class="fa fa-money"></i>
                        <span>Orçamentos</span>
                    </a>

                </li>

                <li><a  href="{{ url('/pedidos/atualizacao') }}"><span class="badge bg-theme04 nortifica">4</span><i class="fa fa-star-o"></i> Atualização de Custo</a></li>
               <!-- <li><a  href="{{ url('/pedidos/aprovado/trade')  }}"><i class="li_star"></i>Avaliação de orçamento do Trade</a></li>-->
                <li><a  href="{{ url('/pedidos/aprovado')  }}"><i class="li_vallet"></i>Triagem de pedidos com custo aprovado</a></li>
                <li><a  href="{{ url('/pedidos/cancelamento')  }}"><i class="fa fa-ban"></i>Pedidos proximo ao cancelemento</a></li>


                <li class="">
                    <a href="javascript:;" style="color: #fed77a">
                        <i class="fa fa-cogs"></i>
                        <span>Produção</span>
                    </a>
                </li>
                <li><a  href="{{  url('/producao/revisao') }}"><span class="badge bg-theme04 nortifica">4</span><i class="fa fa-refresh"></i>Revisão interna de arte</a></li>
                <li><a  href="{{ url('/producao/aprovacao') }}"><span class="badge bg-theme04 nortifica">3</span><i class="fa fa-warning"></i>Aprovação de arte</a></li>
                <li class="">
                    <a href="javascript:;" style="color: #fed77a" >
                        <i class="fa fa-truck"></i>
                        <span>Fornecedor</span>
                    </a>

                </li>
                <li><a  href="{{ url('/fornecedores/gestao')  }}"><i class="li_settings"></i>Gestão de fornecedores</a></li>
                <li><a  href="{{ url('/fornecedores/consulta')  }}"><i class="fa fa-industry"></i>Gestão de desenvolvimento</a></li>
                <li class="">
                    <a href="javascript:;" style="color: #fed77a"  >
                        <i class="fa fa-users"></i>
                        <span>Usuario do DMTRIX</span>
                    </a>



                </li>
                <li><a  href="{{url('/usuarios/gestao')}}"><i class="fa fa-user"></i>Gestão de usuários</a></li>
                <li class="">
                    <a href="javascript:;" style="color: #fed77a" >
                        <i class="fa fa-th"></i>
                        <span>Produtos</span>
                    </a>
                </li>
                <li><a  href="{{url('/produtos/administrar')}}"><i class="fa fa-shopping-cart"></i>Gestão de Produtos</a></li>


            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="container-fluid">

                <div style="margin-top: 50px">

                @yield('content')

                    </div>

            </div>

        </section>
    </section>

    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            DMCard | 2016
            <a href="{{ url('/home')  }}" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->

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
</html>
