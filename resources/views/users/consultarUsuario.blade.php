{!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data', 'action'=>'UsuarioController@update')) !!}

{!! Form::hidden('token', '<% user.idUsuario %>') !!}
<div class="col-lg-12">

    <div class="col-lg-2">
        <img src="{{ url('img/sem-foto.png') }}" class="img-responsive"><br>
        {!! Form::file('foto') !!}
    </div>
    <div class="col-lg-6">
        <img src="{{ url('img/divider.png')  }}" class="img-responsive">
    </div>
    <div class="col-lg-4">
        <div class="form-group" style="font-size: 25px">

            <button class="btn btn-warning backgroundLaranja"><i class="fa fa-ban "></i></button>

        </div>
    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive">
<div class="col-lg-12">
    <div class="col-lg-4">
        <h3 class="colorAzul"><% user.nome %></h3>
        <p>Nivel: <% user.nivel %></p>
        <a href="#"><% user.email %></a>

    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive">
<div class="col-lg-12">
    <div class="col-lg-4">
        <div class="form-group">
        <label>Usuário
            <input type="text" class="form-control" value="<% user.usuario %>" name="user">
        </label>
        <label>Senha
            <input type="password" class="form-control" value="" name="senha">
        </label>
            <label>Email
                <input type="email" class="form-control" value="<% user.email %>" name="email">
            </label>
            <label>Nome
                <input type="text" class="form-control" value="<% user.nome %>" name="name">
            </label>
            <label>Sobrenome
                <input type="text" required class="form-control" value="<% user.sobrenome %>" name="sobrenome">
            </label><br>
            <label>Desativar?
                <input ng-checked="user.status == 0" type="checkbox" class="checkbox-inline" value="0" name="ativo">
            </label>
        </div>
    </div>
    <div class="col-lg-4">
        <label>Supervisor: <% user.consultor %>
            <select class="form-control" name="supervisor" ng-options="x as x.supervisor for x in supervisor track by x.idUsuario" ng-model="selectedSupervisor">
                <option>Não possui</option>
            </select>
        </label>


    </div>
    <div class="col-lg-4">
        <label>Nivel: <% user.nivel %>
            <select class="form-control" name="nivel">
                <option value="<% user.numNivel %>"><% user.nivel %></option>
                <option value="1">Administrador</option>
                <option value="2">Criação</option>
                <option value="3">Supervisor</option>
                <option value="4">Consultor</option>
                <option value="5">Interno</option>
            </select>
        </label>


    </div>

</div>

<img src="{{ url('img/divider.png') }}" class="img-responsive"><br>

<div class="form-group">
    <input type="submit" class="btn btn-warning backgroundLaranja" value="Editar" name="salvar">
</div>

{!! Form::close() !!}