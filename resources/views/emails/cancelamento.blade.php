
<h3>Situação do seu pedido</h3>
<p>Verifique seus pedidos </p>
<p>Situação do seu pedido</p>
<table cellspacing="2" cellpadding="1">
    <tr>
        <td>Dias</td>
        <td>Compra</td>
        <td>Material</td>

    </tr>
    <tr>
        <td>{{ $x['dias'] }}</td>
        <td>{{ $x['idCompra'] }}</td>
        <td>{{ $x['material'] }}</td>
    </tr>


    <tr>
        <td>Situação</td>
        <td>Status</td>
    </tr>
    <tr>
        <td>{{ $x['situacao'] }}</td>
        <td>{{ $x['status'] }}</td>
    </tr>
</table>