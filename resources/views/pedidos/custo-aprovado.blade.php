

@extends('master')


@section('content')
<div ng-controller="mainCusto">

    <div class="form-group">
        <input type="text" ng-model="busca" placeholder="pesquise aqui" class="form-control">
    </div>
    <table class="table table-responsive table-bordered">
        <tr>
            <td>Nº</td>
            <td>Loja</td>
            <td>Solicitante</td>
            <td>Data de Entrada</td>
            <td>Materiais</td>
            <td></td>

        </tr>
        <tr ng-repeat="role in feedSource | filter: busca">

            <td><a class="perfil" ng-href="#/pedidos/detalhes/mostrar" ng-click="pesquisar(role.idCompra)"><% role.idCompra %></a></td>
            <td ><% role.loja %></td>
            <td><% role.solicitante %></td>
            <td><% role.dataCompra %></td>
            <td>
                <p ng-repeat="sub in role.compras">- <% sub.material %></p>

            </td>


            <td>
                <label>
                    <input checklist-model="userFeeds.feeds" checklist-value="role" type="checkbox" data-toggle="switch"/>
                </label>
            </td>

        </tr>


    </table>




<a ng-href="#/delegar/detalhes" class="perfil btn btn-primary">Delegar</a>

    <div id="drop-area" class="drop-area detailsPedido">

        <div>
            <button class="btn btn-primary voltar" >voltar</button><br><br>
            <div ng-view>



            </div>
        </div>


    </div>
    <div class="drop-overlay"></div>

</div>
    <script src="{{ asset('js/jquery.js')  }}"></script>
    <script src="{{ asset('build/js/vendor/bootstrap-switch.min.js')  }}"></script>
    <script src="{{ asset('js/form-component.js')  }}"></script>


    <script type="text/javascript">

        $('.perfil').click(function () {
            $('#drop-area').addClass('show');
        });

        $('.voltar').click(function () {
            $('#drop-area').removeClass('show');
        });

    </script>
@endsection