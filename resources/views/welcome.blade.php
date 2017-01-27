<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>DMTRIX - GESTÃO DE PEDIDOS</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('build/css/vendor/bootstrap.min.css')  }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('css/font-awesome/css/font-awesome.css')  }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css')  }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css')  }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->

<div id="login-page">
    <div class="container">



        {!! Form::open(array( 'method' => 'post', 'class'=>'form-login', 'action'=>'Auth\AuthController@login')) !!}

            <h2 class="form-login-heading">Logar no DMTRIX</h2>
            <div class="login-wrap">
                @if(isset($msg))

                <p class="bg-danger" style="padding: 15px;"><b>{{ $msg }}</b></p>

                @endif


                <input type="text" required name="user" class="form-control" placeholder="Usuario" autofocus>
                <br>
                <input type="password" required name="senha" class="form-control" placeholder="Senha">

                <label class="checkbox">
		                <span class="pull-right">
		                    <a data-toggle="modal" href="login.html#myModal"> Esqueceu a senha?</a>

		                </span>
                </label>
                <button class="btn btn-theme btn-block" href="index.html" type="submit"><i class="fa fa-lock"></i> ENTRAR</button>
                <hr>

                <div class="registration">
                    Ainda não tem uma conta?<br/>
                    <a class="" href="#">
                        Cadastre-se
                    </a>
                </div>

            </div>

            <!-- Modal -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Esqueceu a senha?</h4>
                        </div>
                        <div class="modal-body">
                            <p>Digite seu endereço de email e enviaremos uma nova senha para você.</p>
                            <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                            <button class="btn btn-theme" type="button">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal -->

        {!! Form::close() !!}

    </div>
</div>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ asset('build/js/vendor/jquery.min.js')  }}"></script>
<script src="{{ asset('build/js/vendor/bootstrap.min.js')  }}"></script>


<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<script type="text/javascript" src="{{ asset('build/js/vendor/jquery.backstretch.min.js')  }}"></script>
<script>
    $.backstretch("{{ asset('img/login-bg.jpg')  }}", {speed: 500});
</script>


</body>
</html>
