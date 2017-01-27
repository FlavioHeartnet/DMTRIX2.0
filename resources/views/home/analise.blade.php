

@extends('master')


@section('content')

<div class="col-lg-7">
    <table class="table table-responsive " style="margin-top: 50px; text-align: center">
        <tr>
            <td>Em Produção</td>
            <td>Pedidos:<br> 125</td>
            <td>Compras:<br> 450</td>
        </tr>
        <tr>
            <td class="colorLaranja" style="font-size: 20px">2016</td>
            <td>280</td>
            <td>920</td>
        </tr>
        <tr>
            <td class="colorLaranja">1º trimestre</td>
            <td>50</td>
            <td>120</td>
        </tr>
        <tr>
            <td class="colorLaranja">2º trimestre</td>
            <td>32</td>
            <td>130</td>
        </tr>
        <tr>
            <td class="colorLaranja">3º trimestre</td>
            <td>26</td>
            <td>200</td>
        </tr>
        <tr>
            <td class="colorLaranja">4º trimestre</td>
            <td>25</td>
            <td>25</td>
        </tr>
        <tr>
            <td class="colorLaranja">Cancelados</td>
            <td>30</td>
            <td>60</td>
        </tr>
    </table><br>

    <table class="table table-responsive " style="margin-top: 50px; text-align: center">
        <tr>
            <td>Em Produção</td>
            <td>Pedidos:<br> 125</td>
            <td>Compras:<br> 450</td>
        </tr>
        <tr>
            <td class="colorLaranja" style="font-size: 20px">2015</td>
            <td>280</td>
            <td>920</td>
        </tr>
        <tr>
            <td class="colorLaranja">1º trimestre</td>
            <td>50</td>
            <td>120</td>
        </tr>
        <tr>
            <td class="colorLaranja">2º trimestre</td>
            <td>32</td>
            <td>130</td>
        </tr>
        <tr>
            <td class="colorLaranja">3º trimestre</td>
            <td>26</td>
            <td>200</td>
        </tr>
        <tr>
            <td class="colorLaranja">4º trimestre</td>
            <td>25</td>
            <td>25</td>
        </tr>
        <tr>
            <td class="colorLaranja">Cancelados</td>
            <td>30</td>
            <td>60</td>
        </tr>
    </table>
</div>

<div class="col-lg-5">



    <div class="panel-body">
        <div id="chartAnalise1" class="graph"></div>
    </div><br>

    <div class="panel-body">
        <div id="chartAnalise2" class="graph"></div>
    </div><br><br><br>


</div>
<script src="{{ asset('js/jquery.js')  }}"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
<script src="{{ asset('js/analiseCompleta-chart.js')  }}"></script>

@endsection