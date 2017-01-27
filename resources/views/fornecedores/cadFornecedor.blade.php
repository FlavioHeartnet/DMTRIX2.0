

{!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data', 'action' => 'FornecedorController@store')) !!}


    <div class="form-group">
        <img class="img-responsive " src="{{ url('img/sem-foto.png') }}">
{!! Form::file('foto', $attributes = array('required')) !!}
</div>

<div class="form-group">
{!! Form::label('nome','Nome Fantasia:') !!}
    {!! Form::text('nome','', $attributes  = array('class' => 'form-control','required'))!!}

    {!! Form::label('razao','Razão Social:') !!}
    {!! Form::text('razao','', $attributes  = array('class' => 'form-control','required'))!!}

<label>CNPJ:

    {!! Form::text('cnpj','', $attributes  = array('class' => 'form-control', 'id'=>'cnpj','required'))!!}
</label>
<label>IE (Instcrição Estadual):
    {!! Form::text('ie','', $attributes  = array('class' => 'form-control','required'))!!}

</label>
<label>IM (Inscrição Municipal):
    {!! Form::text('im','', $attributes  = array('class' => 'form-control','required'))!!}
</label>

</div>
<div class="form-group">

<label>Site:
    {!! Form::text('site','', $attributes  = array('class' => 'form-control'))!!}
</label>
<label>Telefone:

    {!! Form::text('tel','', $attributes  = array('class' => 'form-control','required', 'id'=> 'tel'))!!}
</label>
<label>E-mail:
    {!! Form::email('email','', $attributes  = array('class' => 'form-control','required'))!!}
</label>

</div>

<div class="form-group">
<label>CEP:
    {!! Form::text('cep','', $attributes  = array('class' => 'form-control', 'id'=>'cep','required', 'ng-blur' => 'cep()')) !!}

</label>
<label>Endereço:
    {!! Form::text('endereco','', $attributes  = array('class' => 'form-control', 'id'=>'endereco','required'))!!}
</label>
<label>Complemento:
    {!! Form::text('complemento','', $attributes  = array('class' => 'form-control', 'id'=>'complemento','required'))!!}
</label>
<label>Cidade:
    {!! Form::text('cidade','', $attributes  = array('class' => 'form-control', 'id'=>'cidade','required'))!!}
</label>
<label>Estado:
    {!! Form::text('estado','', $attributes  = array('class' => 'form-control' , 'id'=>'estado', 'required'))!!}
</label>

</div>

<div class="form-group">
<label>Banco:
    {!! Form::text('banco','', $attributes  = array('class' => 'form-control', 'required'))!!}
</label>
<label>Agência:
    {!! Form::text('agencia','', $attributes  = array('class' => 'form-control', 'id'=>'agencia', 'required'))!!}
</label>
<label>Conta corrente:
    {!! Form::text('conta','', $attributes  = array('class' => 'form-control', 'id'=>'conta', 'required'))!!}
</label>

</div>

<div class="form-group">
<input type="submit" name="salvar" value="Salvar" class="btn btn-warning backgroundLaranja ">

</div>

{!! Form::close()!!}

<script type="text/javascript">

    $("#cnpj").mask("99.999.999/9999-99");
    $("#cep").mask("99999-999");
    $("#conta").mask("99999-9");
    $("#agencia").mask("9999");
    $("#tel").mask("(99)9999-9999");

</script>