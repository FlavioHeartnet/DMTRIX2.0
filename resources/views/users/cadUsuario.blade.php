<div class="col-lg-12">

    <div class="col-lg-4">
        <img src="{{ url('img/sem-foto.png') }}" class="img-responsive"><br>
        <input type="file" name="foto">
    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive">
<div class="col-lg-12">
    <div class="col-lg-4">
        <div class="form-group">
            <label>Usuário
                <input type="text" class="form-control" value="MKT1" name="user">
            </label>
            <label>Senha
                <input type="password" class="form-control" value="MKT1" name="senha">
            </label>
            <label>Email
                <input type="email" class="form-control" value="agencia@dmcard.com.br" name="email">
            </label>
            <label>Nome
                <input type="text" class="form-control" value="Loren" name="name">
            </label>
        </div>
    </div>
    <div class="col-lg-4">
        <label>Supervisor
            <select class="form-control">
                <option>Não possui</option>
            </select>
        </label>


    </div>
    <div class="col-lg-4">
        <label>Nivel
            <select class="form-control">
                <option>Administrador</option>
            </select>
        </label>


    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive"><br>

<div class="form-group">
    <input type="button" class="btn btn-warning backgroundLaranja" value="Editar" name="salvar">
</div>