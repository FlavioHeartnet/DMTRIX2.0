
<div class="col-lg-12">

    <div class="col-lg-2">
        <img src="{{ url('img/fotos/<% result.foto %>') }}" class="img-responsive"><br>
    </div>
    <div class="col-lg-6">
        <img src="{{ url('img/divider.png')  }}" class="img-responsive">
    </div>
    <div class="col-lg-4">
        <div class="form-group" style="font-size: 25px">


            <button class="btn btn-primary"><i class="fa fa-ban"></i></button>

        </div>
    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive">
<div class="col-lg-12">
    <div class="col-lg-4">
        <h3 class="colorAzul"><% result.fantasia %></h3>
        <p>CNPJ:  <% result.cnpj %></p>
        <p>IE:  <% result.inscricaoEstatudal %></p>
        <p>IM: <% result.inscricaoMunicipal %></p>
    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive">
<div class="col-lg-12">
    <div class="col-lg-4">
        <h3 class="">Contato</h3>
        <p><% result.site %></p>
        <p> <% result.telefone %></p>
        <p> <% result.email %></p>

    </div>
    <div class="col-lg-4">
        <h3 class="">Endereço</h3>
        <p> <% result.endereco %></p>
        <p>CEP: <% result.cep %> <% result.cidade %> <% result.estado %></p>


    </div>
    <div class="col-lg-4">
        <h3 class="">Dados Bancários</h3>
        <p><% result.banco %></p>
        <p>Agencia: <% result.agencia %> | Conta: <% result.conta %></p>


    </div>

</div>
<img src="{{ url('img/divider.png') }}" class="img-responsive"><br>
<div class="col-lg-12" style="font-size: 25px">

    <div class="col-lg-2">
        <a href="" class="colorCinza">
            <i class="fa fa-share-square-o"></i>
            <p>50</p>
            <p style="font-size: 10px">Em produção</p>
        </a>
    </div>
    <div class="col-lg-2">
        <a href="" class="colorCinza">
            <i class="fa fa-clock-o"></i>
            <p>36</p>
            <p style="font-size: 10px">Proximos a Entrega</p>
        </a>
    </div>

    <div class="col-lg-2">
        <a href="" class="colorCinza">
            <i class="fa fa-check-circle-o"></i>
            <p>516</p>
            <p style="font-size: 10px">Itens ja produzidos</p>
        </a>

    </div>

    <div class="col-lg-4">
        <a href="" class="colorCinza">
            <i class="fa fa-money"></i>
            <p>R$ 130.000,00</p>
            <p style="font-size: 10px">Volume em R$ investido do fornecedor</p>
        </a>

    </div>
    <div class="col-lg-5">

    </div>

</div>