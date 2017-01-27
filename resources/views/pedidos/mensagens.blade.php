

@extends('master')


@section('content')

    <div ng-controller="producao-revisao">


        <div class="container-fluid" >
            <div class="page-header">
                <h1 id="timeline"><i class="fa fa-envelope"></i> Mansagens (15)</h1>
            </div>
            <ul class="timeline">
                <li class="timeline-inverted">
                    <div class="timeline-badge"><i class="fa fa-user"></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-body">

                            <div class="col-lg-12">
                                <div class="col-lg-10">
                                    <p>Flavio Barros</p>
                                        <p class="colorLaranja">Pedido - Implantação</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-warning backgroundLaranja perfil">Analisar Pedido</button>
                                </div>

                            </div>


                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-badge"><i class="fa fa-user"></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-body">

                            <div class="col-lg-12">
                                <div class="col-lg-10">
                                    <p>Flavio Barros</p>
                                    <p class="colorLaranja">Pedido - Implantação</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-warning backgroundLaranja perfil">Analisar Pedido</button>
                                </div>

                            </div>


                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-badge"><i class="fa fa-user"></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-body">

                            <div class="col-lg-12">
                                <div class="col-lg-10">
                                    <p>Flavio Barros</p>
                                    <p class="colorLaranja">Pedido - Implantação</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-warning backgroundLaranja perfil">Analisar Pedido</button>
                                </div>

                            </div>


                        </div>
                    </div>
                </li>

            </ul>

        </div>


    </div>

    <div id="drop-area" class="drop-area detailsPedido">

        <div>

            @include('pedidos.detalhes-pedido')

        </div>


    </div>
    <div class="drop-overlay"></div>


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
