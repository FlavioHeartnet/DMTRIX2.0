{!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data','name'=>'form', 'action'=>'ProdutoController@update')) !!}
<div class="row">
    <div class="col-lg-3">
        <img src="{{url('http://mkt.dmcardweb.com.br/img/fotos/<% itemPedido.foto %>')}}" class="img-responsive"><br>
        {!! Form::file('foto') !!} {!! Form::hidden('token','<% itemPedido.idMaterial %>') !!}

    </div>

    <div class="col-lg-4">
        <img src="{{url('img/divider.png')}}" class="img-responsive">
    </div>

</div>
<br><br>

<div class="row">



    <div class="col-lg-6">

            <div class="form-group" ng-class="{'has-error': !form.nome.$valid && form.nome.$touched}">
                <label class="control-label">Material</label>
                <input type="text"  required class="form-control" value="<% itemPedido.material %>" name="nome" ng-model="nome" placeholder="Nome do Produto">

                <div ng-messages="form.nome.$error" class="help-block" role="alert" ng-show="form.nome.$touched">
                    <div ng-message="required">Campo Obrigatorio.</div>
                </div>
            </div>

        <div class="form-group" ng-class="{'has-error': !form.nome.$valid && form.nome.$touched}">
            <label class="control-label">Valor</label>
            <input type="text"  required class="form-control" value="<% itemPedido.valor %>" maxlength="10" name="valor" ng-model="valor" placeholder="Valor em R$">

            <div ng-messages="form.valor.$error" class="help-block"  role="alert" ng-show="form.valor.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>
                <div ng-message="maxlength">Maximo de digitos é 10.</div>

            </div>
        </div>

            <label>Tipo do Pedido
                <select required name="forma" class="form-control dropdown" ng-options="x as x.nome for x in formaCalculo track by x.id" ng-model="forma">
                    <option value="1">Free</option>
                    <option value="2">Unidade</option>
                    <option value="3">Metro</option>
                </select>
            </label>

            <label>Categoria
                <select required name="categoria" class="form-control dropdown" ng-options="x as x.nome for x in categoria track by x.id" ng-model="selectedCategoria">
                    <option value="<% itemPedido.idCategoria %>"><% itemPedido.nomeCategoria %></option>

                </select>
            </label>
        <label>Desativar?
            <input ng-checked="itemPedido.status == 0" type="checkbox" class="checkbox-inline" value="0" name="status">
        </label>
            <!--<label>
                <select required class="form-control dropdown">
                    <option value="">Região Loja</option>

                </select>
            </label>-->




    </div>
    <div class="col-lg-6">

        <button class="btn btn-warning backgroundLaranja">Editar</button>
    </div>

</div>

{!! Form::close()  !!}