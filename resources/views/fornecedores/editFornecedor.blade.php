

{!! Form::open(array( 'method' => 'post', 'enctype' => 'multipart/form-data', 'action'=>'FornecedorController@update')) !!}

<div class="form-group">
    <img class="img-responsive " src="{{ url('img/sem-foto.png') }}">
    {!! Form::file('foto') !!}
    {!! Form::hidden('token','<% result.id %>') !!}

</div>

<div class="form-group">
    {!! Form::label('nome','Nome Fantasia:') !!}
    {!! Form::text('nome','<% result.fantasia %>', $attributes  = array('class' => 'form-control','required'))!!}

    {!! Form::label('razao','Razão Social:') !!}
    {!! Form::text('razao','<% result.razao %>', $attributes  = array('class' => 'form-control','required'))!!}

    <label>CNPJ:

        {!! Form::text('cnpj','<% result.cnpj %>', $attributes  = array('class' => 'form-control', 'id'=>'cnpj','required'))!!}
    </label>
    <label>IE (Instcrição Estadual):
        {!! Form::text('ie','<% result.inscricaoEstatudal %>', $attributes  = array('class' => 'form-control','required'))!!}

    </label>
    <label>IM (Inscrição Municipal):
        {!! Form::text('im','<% result.inscricaoMunicipal %>', $attributes  = array('class' => 'form-control','required'))!!}
    </label>

</div>
<div class="form-group">

    <label>Site:
        {!! Form::text('site','<% result.site %>', $attributes  = array('class' => 'form-control'))!!}
    </label>
    <label>Telefone:

        {!! Form::text('tel','<% result.telefone %>', $attributes  = array('class' => 'form-control','required', 'id'=> 'tel'))!!}
    </label>
    <label>E-mail:
        {!! Form::email('email','<% result.email %>', $attributes  = array('class' => 'form-control','required'))!!}
    </label>

</div>

<div class="form-group">
    <label>CEP:
        {!! Form::text('cep','<% result.cep %>', $attributes  = array('class' => 'form-control', 'id'=>'cep','required', 'ng-blur' => 'cep()')) !!}

    </label>
    <label>Endereço:
        {!! Form::text('endereco','<% result.endereco %>', $attributes  = array('class' => 'form-control', 'id'=>'endereco','required'))!!}
    </label>
    <label>Complemento:
        {!! Form::text('complemento','<% result.complemento %>', $attributes  = array('class' => 'form-control', 'id'=>'complemento','required'))!!}
    </label>
    <label>Cidade:
        {!! Form::text('cidade','<% result.cidade %>', $attributes  = array('class' => 'form-control', 'id'=>'cidade','required'))!!}
    </label>
    <label>Estado:
        {!! Form::text('estado','<% result.estado %>', $attributes  = array('class' => 'form-control' , 'id'=>'estado', 'required'))!!}
    </label>

</div>

<div class="form-group">
    <label>Banco:
        {!! Form::text('banco','<% result.banco %>', $attributes  = array('class' => 'form-control', 'required'))!!}
    </label>
    <label>Agência:
        {!! Form::text('agencia','<% result.agencia %>', $attributes  = array('class' => 'form-control', 'id'=>'agencia', 'required'))!!}
    </label>
    <label>Conta corrente:
        {!! Form::text('conta','<% result.conta %>', $attributes  = array('class' => 'form-control', 'id'=>'conta', 'required'))!!}
    </label>

</div>

<div class="form-group">
    <input type="submit" name="salvar"  value="Salvar" class="btn btn-warning backgroundLaranja ">

</div>

{!! Form::close()!!}

<script type="text/javascript">



</script>