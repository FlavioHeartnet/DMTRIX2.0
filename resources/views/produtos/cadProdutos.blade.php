{!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data','name'=>'form', 'action'=>'ProdutoController@store')) !!}
<div class="row">
    <div class="col-lg-3">

        {!! Form::file('foto') !!}
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
            <input type="text"  required class="form-control" value="" name="nome" ng-model="nome" placeholder="Nome do Produto">

            <div ng-messages="form.nome.$error" class="help-block" role="alert" ng-show="form.nome.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>
            </div>
        </div>

        <div class="form-group" ng-class="{'has-error': !form.nome.$valid && form.nome.$touched}">
            <label class="control-label">Valor</label>
            <input type="text" id="valor"  required class="form-control" value="" maxlength="10" name="valor" ng-model="valor" placeholder="Valor em R$">

            <div ng-messages="form.valor.$error" class="help-block"  role="alert" ng-show="form.valor.$touched">
                <div ng-message="required">Campo Obrigatorio.</div>

                <div ng-message="maxlength">Maximo de digitos é 10.</div>

            </div>
        </div>


        <label>
            <select class="form-control" name="forma">
                <option value="">Forma calculo</option>
                <option value="1">Free</option>
                <option value="2">Unidade</option>
                <option value="3">Metro</option>
            </select>
        </label>

        <label>
            <select required name="categoria" class="form-control dropdown" ng-options="x as x.nome for x in categoria track by x.id" ng-model="selectedCategoria">
                <option value="">Categoria</option>

            </select>
        </label>
        <!--<label>
            <select required name="categoriaGeral" class="form-control dropdown" ng-options="x as x.nome for x in categoriaGeral track by x.id" ng-model="selectedcategoriaGeral">
                <option value="">Região Loja</option>

            </select>
        </label>-->




    </div>
    <div class="col-lg-6">

        <button class="btn btn-warning backgroundLaranja">Salvar</button>
    </div>

</div>

{!! Form::close() !!}

<script>
    //$("#valor").mask("9999.99");
</script>