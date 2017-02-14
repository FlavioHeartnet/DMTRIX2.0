{!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data','name'=>'form', 'action'=>'UsuarioController@store')) !!}
<div class="col-lg-12">

    <div class="col-lg-4">
       {!! Form::file('foto') !!}
    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive">
<div class="col-lg-12">
    <div class="col-lg-4">
        <div class="form-group" ng-class="{'has-error': !form.user.$valid && form.user.$touched}">
            <label class="control-label">Usuário</label>
                <input type="text"  required class="form-control" value="" name="user" ng-model="user">

            <div ng-messages="form.user.$error" class="help-block" role="alert" ng-show="form.user.$touched">
                   <div ng-message="required">Campo Obrigatorio.</div>
            </div>
        </div>

        <div class="form-group" ng-class="{'has-error': !form.senha.$valid && form.senha.$touched}">
            <label class="control-label">Senha</label>
            <input type="password" maxlength="15"  required class="form-control" value="" name="senha" ng-model="senha">

            <div ng-messages="form.senha.$error" class="help-block" role="alert" ng-show="form.senha.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>
                <div ng-message="maxlength">Campo deve ter no maximo 15 caractéres.</div>
            </div>
        </div>

        <div class="form-group" ng-class="{'has-error': !form.email.$valid && form.email.$touched}">
            <label class="control-label">Email</label>
            <input type="email"  required class="form-control" value="" name="email" ng-model="email">

            <div ng-messages="form.email.$error" class="help-block" role="alert" ng-show="form.email.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>
                <div ng-message="email">Digite uma email válido.</div>
            </div>
        </div>

        <div class="form-group" ng-class="{'has-error': !form.name.$valid && form.name.$touched}">
            <label class="control-label">Nome</label>
            <input type="text"  required class="form-control" value="" name="name" ng-model="name">

            <div ng-messages="form.name.$error" class="help-block" role="alert" ng-show="form.name.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>
            </div>
        </div>

        <div class="form-group" ng-class="{'has-error': !form.sobrenome.$valid && form.sobrenome.$touched}">
            <label class="control-label">Sobrenome</label>
            <input type="text"  required class="form-control" value="" name="sobrenome" ng-model="sobrenome">

            <div ng-messages="form.sobrenome.$error" class="help-block" role="alert" ng-show="form.sobrenome.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>
            </div>
        </div>


    </div>
    <div class="col-lg-4">
        <label>Supervisor
            <select class="form-control" required name="supervisor" ng-options="x as x.supervisor for x in supervisor track by x.idUsuario" ng-model="selectedSupervisor">
                <option>Não possui</option>
            </select>
        </label>


    </div>
    <div class="col-lg-4">
        <label>Nivel
            <select class="form-control" required name="nivel">
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
    <input type="submit" class="btn btn-warning backgroundLaranja" value='Criar' name="salvar">
</div>

{!! Form::close() !!}