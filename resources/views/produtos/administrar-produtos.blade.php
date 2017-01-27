

@extends('master')


@section('content')


    <h1><i class="fa fa-shopping-cart"></i> Administração de Produtos</h1>

    <div class="col-lg-4">

        <h3><a onclick="exibir(1,0)" href="" class="colorAzul"><i class="fa fa-plus-circle"></i> Cadastrar Produtos</a></h3>

        <div class="form-group">

            <h3> <i class="fa fa-search"></i> Filtro</h3>

            <input type="text" class="form-control" name="filtro" placeholder="digite o nome do item"><br>
            <button onclick="exibir(2,0)" class="btn btn-primary"><i class="fa fa-search"></i></button>

        </div>


        <div class="form-group">

            <h4> <i class="fa fa-shopping-cart"></i> Regiões da Loja</h4>

            <table class="table colorAzul" style="font-weight:bold ">

                <tr>
                    <td><a onclick="exibir(2,0)" href="">Checkout</a></td>
                    <td>50</td>
                </tr>
                <tr>
                    <td><a onclick="exibir(2,0)" href="">Balcão</a></td>
                    <td>100</td>
                </tr>

            </table>

        </div>

        <div class="form-group">

            <h4><i class="fa fa-arrow-circle-right"></i> Categoria</h4>

            <table class="table colorAzul" style="font-weight:bold ">

                <tr>
                    <td><a onclick="exibir(2,0)" href="">Adesivo Balcão de Vendas</a></td>
                    <td>10</td>
                </tr>
                <tr>
                    <td><a onclick="exibir(2,0)" href="">Anuncio Impresso</a></td>
                    <td>12</td>
                </tr>

            </table>

        </div>




    </div>

    <div class="col-lg-8" id="result">


            @include('produtos.consulta')

    </div>


@endsection