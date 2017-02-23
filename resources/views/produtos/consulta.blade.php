<h3>Todos</h3>

<table class="table table-bordered table-responsive" style="text-align: center">
    <tr ng-repeat="x in produtos">
        <td ng-repeat="a in x.item | filter: busca">
            <a ng-href="#/produtos/edit/<% a.idMaterial %>" ng-click="editPedido(a.idMaterial)" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
            <!--<button class="btn btn-warning backgroundLaranja"><i class="fa fa-ban"></i></button><br><br>-->

            <img src="{{url('http://mkt.dmcardweb.com.br/img/fotos/<% a.foto %>')}}" width="150px" class="img-responsive center-block"><br>

            <p class="colorAzul" style="font-weight: bold"><% a.material %></p>
            <p>R$ <% a.valor %></p>

        </td>
    </tr>


</table>
