
<div class="container-fluid">

    {!! Form::open(array( 'method' => 'post', 'action'=>'PedidosController@delegarTarefas')) !!}
<table class="table center-block" border="0" cellspacing="0" cellpadding="0">
    <tr ng-repeat="feed in userFeeds.feeds">
        <td><b><% feed.idCompra  %></b>
        {!! Form::hidden('token[]', '<% feed.idCompra  %>') !!}</td>
        <td><b><% feed.titulo   %></b> </td>
        <td>

            <label>Prioridade
                <select required name="prioridade[]" class="form-control dropdown">
                    <option value="3">Baixa</option>
                    <option value="2">Media</option>
                    <option value="1">Alta</option>
                    <option value="0">Sem categoria</option>
                </select>
            </label>

        </td>
        <td>

            <label>Criação
                <select required class="form-control dropdown" name="criacao[]">
                    <option value="14">Ana Plesky</option>
                    <option value="12">Diego Simeão</option>
                    <option value="10">Flavio Nogueira</option>
                    <option value="156">Carlos Maia</option>
                </select>
            </label>

        </td>

        <td>

            <label>Estimativa de entrega
                <input class="form-control" name="dataEstimada[]" type="date">
            </label>

        </td>
    </tr>
</table>


    <div class="form-group">
        <button class="btn btn-warning backgroundLaranja">Delegar</button>
    </div>
    {!! Form::close()!!}
</div>