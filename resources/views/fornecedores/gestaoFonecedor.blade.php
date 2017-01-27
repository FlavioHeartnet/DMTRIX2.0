

@extends('master')


@section('content')

    <h1><i class="fa fa-gear"></i>Gestão de Fornecimento</h1>

    <div ng-controller="fornecedor" >

        @if($msg == '')

            @else
            <p class="{{ $class }}"><b>{{ $msg }}</b></p>
            @endif
        <div class="col-lg-5 detailsPedido">

            <div class="form-group">
                <label>Busca por fornecedor
                    <input type="text" class="form-control" name="busca" ng-model="busca"> <br>
                    <button class="btn btn-primary"><i class="fa fa-search "></i></button>
                </label>
            </div>

            <table class="table-responsive table">

                <tr ng-repeat="x in fornecedor | filter: busca">

                    <td><img class="img-responsive" src="{{ url('img/fotos/<% x.foto %>') }}"></td>
                    <td>

                        <h3 class="colorAzul"><a ng-href="#/consultar/<% x.id %>" ng-click="consulta(x.id)"><% x.fantasia %></a></h3>

                        <p><% x.endereco %></p>
                        <a href="#"><% x.site %></a>
                        <p>Tel: <% x.telefone %></p>
                    </td>
                    <td>
                        <button class="btn btn-warning backgroundLaranja"><i class="fa fa-ban"></i></button>
                        <a ng-href="#/edit/<% x.id %>" ng-click="consulta(x.id)" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <p><% x.email %></p>
                    </td>
                </tr>


            </table>

                <img src="{{ url('img/divider.png')  }}" class="img-responsive">
                <a href="{{ url('#/cadFornecedor') }}" class="btn btn-link colorAzul center-block" style="font-size: 25px"><i class="fa fa-plus-circle"></i> Cadastrar novo Forncedor</a>
        </div>

        <div class="col-lg-7"  id="result">
            <div ng-view></div>

        </div>


    </div>

    <script src="{{ asset('build/js/vendor/jquery.min.js')  }}"></script>
    <script src="{{ asset('js/TweenMax.min.js')  }}"></script>


@endsection
