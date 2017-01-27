<div class="row">
    <div class="col-lg-3">
        <img src="{{url('img/sem-arte.png')}}" class="img-responsive"><br>
        <input type="file" name="foto" >

    </div>

    <div class="col-lg-4">
        <img src="{{url('img/divider.png')}}" class="img-responsive">
    </div>

    <div class="col-lg-2">
        <button class="btn btn-warning backgroundLaranja"><i class="fa fa-ban"></i></button>
    </div>
</div>
<br><br>

<div class="row">

    <div class="col-lg-6">
        <div class="form-group">
            <input class="form-control" name="nome" type="text" value="Pinpad" placeholder="Nome do Produto"><br>
            <input class="form-control" name="valor" type="text" value="70.00" placeholder="Valor em R$"><br>

            <label>
                <select class="form-control ">
                    <option value="3">Metro</option>
                    <option value="">Forma calculo</option>
                    <option value="1">Free</option>
                    <option value="2">Unidade</option>
                    <option value="3">Metro</option>
                </select>
            </label>

            <label>
                <select class="form-control dropdown">
                    <option value="">Categoria</option>

                </select>
            </label>


        </div>

    </div>
    <div class="col-lg-6">

        <button class="btn btn-warning backgroundLaranja">Editar</button>
    </div>

</div>