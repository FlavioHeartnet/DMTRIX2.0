

@extends('master')


@section('content')

<div ng-controller="produtos">
    <h1><i class="fa fa-shopping-cart"></i> Administração de Produtos</h1>

    <div class="col-lg-4">

        <h3><a href="#/produtos/cad" class="colorAzul loader"><i class="fa fa-plus-circle"></i> Cadastrar Produtos</a></h3>

        <div class="form-group">

            <h3> <i class="fa fa-search"></i> Filtro</h3>

            <input ng-model="busca" type="text" class="form-control" name="filtro" placeholder="digite o nome do item"><br>


        </div>

        <a style="font-size: 30px" class="colorAzul loader"  href="#/produtos/consulta" ng-click="consulta()">Todos os produtos</a>
        <div class="form-group">

            <h4> <i class="fa fa-shopping-cart"></i> Regiões da Loja</h4>

            <table class="table colorAzul" style="font-weight:bold ">

                <tr ng-repeat="x in categoriaGeral">
                    <td><a class="loader"  href="#/produtos/consulta" ng-click="produtosGeral(x.id)"><% x.nome %></a></td>
                    <td><% x.num %></td>
                </tr>

            </table>

        </div>

        <div class="form-group">

            <h4><i class="fa fa-arrow-circle-right"></i> Categoria</h4>

            <table class="table colorAzul" style="font-weight:bold ">

                <tr ng-repeat="x in categoria">
                    <td><a class="loader"  href="#/produtos/consulta" ng-click="produtosCategoria(x.id)"><% x.nome %></a></td>
                    <td><% x.num %></td>
                </tr>


            </table>

        </div>




    </div>

    <div class="col-lg-8" ng-view id="result">




    </div>

</div>
@endsection