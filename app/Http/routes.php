<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/teste', 'PedidosController@teste');

Route::post('/logar', 'Auth\AuthController@login');
Route::get('/logout', 'Auth\AuthController@logout');

Route::get('/home', function () {

        return view('home.home');
});

Route::get('/home/analise', function () {
    return view('home.analise');
});

Route::get('/pedidos/nortificacao','PedidosController@nortificacao');
Route::get('/pedidos/msgTopo','PedidosController@msgTopo');

Route::get('/pedidos/todos','PedidosController@index');
Route::get('/pedidos/todos/carregar','PedidosController@Pedidos');
Route::get('/pedidos/detalhes/finalizar','PedidosController@finalizarCompra');
Route::get('/pedidos/todos/detalhes/{id}',['as' => 'pedidos.detalhes','uses'=>'PedidosController@detalhesPedidos']);
Route::post('/pedidos/todos/detalhes/{id}/add/{obs}',['as' => 'pedidos.detalhes.addObs','uses'=>'HistoricoController@addObs']);
Route::get('/pedidos/detalhes/mostrar','PedidosController@showDetalhesPedidos');

Route::get('/pedidos/cancelados','PedidosController@cancelados');
Route::get('/pedidos/cancelar/{id}','PedidosController@cancelarPedido');

Route::get('/pedidos/aprovado', function () {
    return view('pedidos.custo-aprovado');
});
Route::get('/pedidos/aprovado/trade', function () {
    return view('pedidos.avaliacao-trade');
});
Route::get('/pedidos/aprovado/trade/show', 'PedidosController@tradeShow');

Route::get('/pedidos/aprovado/show/', 'PedidosController@showTriagemPedidos');
Route::post('/pedidos/aprovado/delegar', 'PedidosController@delegarTarefas');
Route::post('/pedidos/aprovado/redelegar', 'PedidosController@redelegarTarefas');
Route::get('/pedidos/aprovado/delegar/detalhes', 'PedidosController@showDelegarDetalhes');

Route::get('/pedidos/atualizacao', function () {
    return view('pedidos.atualizacao-custo');
});
Route::get('/pedidos/atualizacao/show/{status}', 'PedidosController@showAtualizarValores');
Route::post('/pedidos/atualizacao/update/', 'PedidosController@updateStatus');
Route::post('/pedidos/atualizacao/cancelar/', 'PedidosController@cancelarPedido');

Route::get('/pedidos/atualizacao/detalhes', function () {
    return view('pedidos.atualizacao-custo-detalhes');
});
Route::get('/pedidos/atualizacao/detalhes/{id}/{status}', 'PedidosController@consultar');

Route::get('/pedidos/cancelamento', function () {
    return view('pedidos.pedidos-cancelamento');
});
Route::get('/pedidos/mensagens', function () {
    return view('pedidos.mensagens');
});




Route::get('/fornecedores/consulta', function () {
    return view('fornecedores.consulta');
});
Route::get('/fornecedores/gestao/cad', ['as' => 'fornecedor.cad', 'uses' => 'FornecedorController@index']);

Route::post('/fornecedores/gestao/cad/store', ['as'=>'fornecedor.store', 'uses' => 'FornecedorController@store', 'before'=>'csrf']);

Route::get('/fornecedores/gestao/edit/', 'FornecedorController@edit');

Route::delete('/fornecedores/gestao/delete/{id}', 'FornecedorController@delete');

Route::post('/fornecedores/gestao/edit/{id}',  ['as'=>'fornecedor.update', 'uses' => 'FornecedorController@update']);

Route::get('/fornecedores/gestao/consulta',function(){ return view('fornecedores.consultaFornecedor'); } );

Route::get('/fornecedores/gestao/consultaFornecedor/{id}','FornecedorController@consultar' );

Route::get('/fornecedores/gestao', function () {
    $msg = '';
    return view('fornecedores.gestaoFonecedor', compact('msg'));
});
Route::get('/fornecedores/gestao/show', 'FornecedorController@show');
Route::get('/fornecedores/gestao/pedidos', 'FornecedorController@PedidosFornecedor');
Route::get('/fornecedores/gestao/pedidos/mostrar', function () {
    $msg = '';
    return view('fornecedores.detalhesCompra', compact('msg'));
});
Route::get('/fornecedores/gestao/pedidos/detalhes/{id}', 'FornecedorController@detalhesPedidos');
Route::post('/fornecedores/gestao/pedidos/detalhes/enviar/', 'FornecedorController@enviarFornecedor');
Route::post('/fornecedores/gestao/pedidos/detalhes/entregue/{id}', 'FornecedorController@EntregaPedido');
Route::post('/fornecedores/gestao/pedidos/detalhes/finalizar/{id}', 'FornecedorController@finalizar');


Route::get('/usuarios/gestao', function () {
    return view('users.gestaoUsuarios');
});
Route::get('/usuarios/gestao/consulta', function () {
    return view('users.consultarUsuario');
});
Route::get('/usuarios/gestao/edit/{id}', function ($id) {
    return view('users.editUsuario');
});
Route::get('/usuarios/gestao/cad', function () {
    return view('users.cadUsuario');
});


Route::get('/producao/revisao', function () {
    return view('producao.revisao-interna');
});
Route::get('/producao/aprovacao', function () {
    return view('producao.aprovacao-arte');
});

Route::get('/producao/fila', 'ProducaoController@fila' );
Route::get('/producao/fila/revisao', 'ProducaoController@ConsultaRevisao' );
Route::get('/producao/fila/aprovacao/', 'ProducaoController@consultaAprovacao' );
Route::get('/producao/fila/aprovar/{id}', 'ProducaoController@aprovar' );
Route::post('/producao/fila/salvarArte', 'ProducaoController@salvarArte' );
Route::post('/producao/fila/aprovacao/', 'ProducaoController@enviarAprovacao' );
Route::get('/producao/fila/{id}', 'ProducaoController@show' );
Route::get('/producao/fila/criacao/mostrar', 'ProducaoController@index' );



Route::get('/produtos/administrar', function () {
    return view('produtos.administrar-produtos');
});
Route::get('/produtos/cad', function () {
    return view('produtos.cadProdutos');
});
Route::get('/produtos/consulta', function () {
    return view('produtos.consulta');
});
Route::get('/produtos/edit/{id}', function ($id) {
    return view('produtos.editProduto');
});


//loader
Route::get('/loader', function () {
    return view('loaders.loader');
});



