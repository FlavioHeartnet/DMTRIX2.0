

@extends('master')


@section('content')

    <h1><i class="fa fa-user"></i> Gestão de Usuários</h1>

    <div ng-controller="users" >

        <div class="col-lg-5 detailsPedido">

            <div class="form-group">
                <label>Busca:
                    <input type="text" class="form-control" name="busca" ng-model="busca"> <br>

                </label>
            </div>
            <img src="{{ url('img/divider.png')  }}" class="img-responsive">
            <a ng-href="{{ url('#/usuario/cad') }}" class="btn btn-link colorAzul center-block" style="font-size: 25px"><i class="fa fa-plus-circle"></i> Cadastrar novo Usuario</a>

            <table class="table-responsive table">

                <tr ng-repeat="x in usuario | filter: busca">

                    <td>

                        <img ng-if="x.foto != null" style="width: 50%" class="img-circle img-thumbnail img-responsive" src="{{ url('img/fotos/<% x.foto %>') }}">
                        <img ng-if="x.foto == null" class="img-circle img-thumbnail img-responsive" src="{{ url('/img/sem-foto.png') }}">

                    </td>
                    <td>

                        <h3 class="colorAzul"><a ng-href="#/usuario/consulta" ng-click="consulta(x.idUsuario)"><% x.nome %></a></h3>

                        <p>Usuario: <% x.usuario %></p>
                        <p>Nivel: <% x.nivel %></p>
                        <p>Supevisor: <% x.consultor %></p>
                        <p>Status: <% x.status %></p>
                    </td>
                    <td>
                        <button class="btn btn-warning backgroundLaranja"><i class="fa fa-ban"></i></button>
                        <a ng-href="#/usuario/consulta" ng-click="consulta(x.idUsuario)" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <p></p>
                        <p><% x.email %></p>
                    </td>
                </tr>


            </table>


        </div>

        <div class="col-lg-7" >
            <div ng-view></div>

        </div>


    </div>



@endsection
