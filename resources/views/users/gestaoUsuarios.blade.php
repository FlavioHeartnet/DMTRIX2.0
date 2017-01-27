

@extends('master')


@section('content')

    <h1><i class="fa fa-user"></i> Gestão de Usuários</h1>

    <div >


        <div class="col-lg-5 detailsPedido">

            <div class="form-group">
                <label>Busca por Usuários
                    <input type="text" class="form-control" name="busca"> <br>
                    <button class="btn btn-primary"><i class="fa fa-search "></i></button>
                </label>
            </div>

            <table class="table-responsive table">

                <tr>
                    <td><img class="img-responsive" src="{{ url('img/sem-foto.png') }}"></td>
                    <td>
                        <h3 class="colorAzul"><a onclick="exibir(2,0)" href="#">Lorem</a></h3>
                        <p>Nivel: admistradora</p>
                        <a href="#">agencia@dmcard.com.br</a>
                    </td>
                    <td>
                        <button class="btn btn-warning backgroundLaranja"><i class="fa fa-ban"></i></button>
                </tr>


            </table>
            <img src="{{ url('img/divider.png')  }}" class="img-responsive">
            <button  class="btn btn-link colorAzul center-block" style="font-size: 25px"><i class="fa fa-plus-circle"></i> Cadastrar novo Usuario</button>
        </div>

        <div class="col-lg-7" id="result">





        </div>


    </div>



@endsection
