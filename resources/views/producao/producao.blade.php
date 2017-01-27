

@extends('master')


@section('content')

    <div ng-controller="filaCriacao">


        <div class="container-fluid" >
            <div class="page-header">
                <h1><i class="fa fa-refresh"></i>Fila de Criação</h1>
            </div>

            <table class="table table-responsive">

                <tr>
                    <td>Nº</td>
                    <td>Titulo</td>
                    <td>Criação</td>
                    <td>Prioridade</td>
                    <td>Produção</td>
                </tr>

                <tr>
                    <td>Nº</td>
                    <td>Titulo</td>
                    <td>Criação</td>
                    <td>Prioridade</td>
                </tr>


            </table>



        </div>


    </div>

    <div id="drop-area" class="drop-area detailsPedido">

        <div>

            <button class="btn btn-primary voltar" ng-click="botoes.voltar()">voltar</button>
            <p></p>
            <div id="result"></div>

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
