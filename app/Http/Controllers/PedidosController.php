<?php

namespace App\Http\Controllers;

use App\Services\PedidosServices;
use App\Services\Services;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class PedidosController extends Controller
{

    private $con;
    private $pedidosService;
    private $services;
    private $historico;


    public function __construct(PedidosServices $pedidosService, Services $services, HistoricoController $historico)
    {
        $this->con = new \config();
        $this->pedidosService = $pedidosService;
        $this->services = $services;
        $this->historico = $historico;
        
    }

    public function Pedidos()
    {
        $con = $this->con;

        $sql = $this->con->query(" select top 100 p.dataCompra,p.valorTotal,
        case when p.titulo is null then 'Sem Titulo' else p.titulo end as Titulo ,p.idCompra,u.nome,l.numeroLoja +' - '+l.nomeLoja as loja, 
        case when p.Prioridade is null then 0 
        else p.Prioridade end as prioridade,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 5 and idCompra = p.idCompra) as numCriacao,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 101 and idCompra = p.idCompra) as numRevisao,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 10 and idCompra = p.idCompra) as numAprovacao,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 8 and idCompra = p.idCompra) as numFornecedor,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 81 and idCompra = p.idCompra) as numDisponivel,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 11 and idCompra = p.idCompra) as numFinalizado,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 6 and idCompra = p.idCompra) as numAprovados,
        (select COUNT(*) as num from PedidoDMTRIX where idCompra = p.idCompra) as total
        , p.status_compra
         from ComprasDMTRIX p 
        inner join usuariosDMTRIX u on u.idUsuario = p.idUsuario
        join lojasDMTRIX l on l.numeroLoja = p.idLoja order by p.dataCompra desc");

        $response = array();
        while($rs = $con->fetch_array($sql))
        {

            $situacao = $this->pedidosService->situacaoCompra($rs['idCompra']);
            $array = ['situacao'=>$situacao,'dataCompra' => $this->services->formatarData($rs['dataCompra']), 'valorTotal' => $rs['valorTotal'], 'loja' => utf8_encode($rs['loja']), 'idCompra' => $rs['idCompra'], 'nome' => ($rs['nome']), 'prioridade' => $rs['prioridade'],'status_compra' => $rs['status_compra']
            , 'criacao' => $rs['numCriacao'], 'numRevisao' => $rs['numRevisao'], 'numAprovacao' => $rs['numAprovacao'], 'numFornecedor' => $rs['numFornecedor'],'numDisponivel' => $rs['numDisponivel'],'numFinalizado' => $rs['numFinalizado'],'total'=>$rs['total'],'numAprovados'=>$rs['numAprovados']];
            array_push($response,$array);

        }


        return  json_encode($response);
        
        
    }

    public function detalhesPedidos($idCompra){

        $con = $this->con;

        $timeline = $this->con->query("select DISTINCT h.id,p.idCompra,h.tipo, h.observacao, u.nome, h.dataObs,h.lida from dmtrixII.historicoObs h join PedidoDMTRIX p on p.idPedido = h.idPedido 
  join usuariosDMTRIX u on u.idUsuario = h.idUsusario where p.idCompra = '$idCompra' order by h.dataObs desc");
        $arrayTimeline = array();
        while($rs = odbc_fetch_array($timeline))
        {


            $value = session('user');
            $idUsuario = $value['id'];

            $idHistorico = $rs['id'];
            $histVerifica = $this->con->query("select idHistorico from dmtrixII.controleMensagens where idHistorico = '$idHistorico' and idUsuario = '$idUsuario' ");

            if(odbc_num_rows($histVerifica) ==0) {
                
                $this->con->query("insert into dmtrixII.controleMensagens (idHistorico,idUsuario,lida,dataLida) values ('$idHistorico','$idUsuario',1,GETDATE())"); //marca quem foi o usuario que leu esta mensagem
            }

           if($rs['tipo'] == 1)
           {
               $nome = 'DMTRIX';
               
           }else{

               $nome = $rs['nome'];
           }
            
            array_push($arrayTimeline,
                [
                    'tipo' => $rs['tipo'],
                    'observacao' => $rs['observacao'],
                    'nome' => $nome,
                    'dataObs' => $this->services->formatarData($rs['dataObs']),
                    'idCompra' => $rs['idCompra']
                    
                ]);
            
        }

        $infoCompra = $con->fetch_array($con->query("select distinct p.dataCompra,p.valorTotal,
			case when p.titulo is null then 'Sem Titulo' else p.titulo end as Titulo ,p.idCompra,u.nome,case when p.idLoja = 0 then 'DMCard' else l.numeroLoja+' - '+l.nomeLoja end as Loja, 
			case when p.Prioridade is null then 0 
			else p.Prioridade end as prioridade, p1.segmento,p1.formaPagamento,p1.custeio, cf.dataEntrada,cf.dataSaida,p1.dataIdeal, p.status_compra from ComprasDMTRIX p 
			inner join usuariosDMTRIX u on u.idUsuario = p.idUsuario left join lojasDMTRIX l on l.numeroLoja = p.idLoja
			join PedidoDMTRIX p1 on p1.idCompra = p.idCompra
			left join dmtrixII.[controle-fornecedor] cf on cf.idPedido = p1.idPedido 
			 where p.idCompra = '$idCompra' order by p.dataCompra desc"));

        $criacao = $this->con->query("select u.idUsuario, u.nome+' '+u.sobrenome as criacao from dmtrixII.criacaoDMTRIX c join usuariosDMTRIX u on c.idUsuario = u.idUsuario");
        $array = array();
        while($rs = $this->con->fetch_array($criacao))
        {
            array_push($array,
                ['id' => $rs['idUsuario'], 'criacao' => $rs['criacao']] );

        }


        $infoCompra = [ 'dataCompra' =>$this->services->formatarData( $infoCompra['dataCompra']),
                        'valorTotal' => $infoCompra['valorTotal'],
                        'Titulo' => utf8_encode($infoCompra['Titulo']),
                        'Loja' => $infoCompra['Loja'],
                        'prioridade' => $infoCompra['prioridade'],
                        'segmento' => $infoCompra['segmento'],
                        'status_compra' => $infoCompra['status_compra'],
                        'formaPagamento' => $infoCompra['formaPagamento'],
                        'custeio' => $infoCompra['custeio'],
                        'idCompra' => $infoCompra['idCompra'],
                        'dataIdeal' => $this->services->formatarData($infoCompra['dataIdeal']),
                        'nome' => $infoCompra['nome'],
                        'dataSaida' => $this->services->formatarData($infoCompra['dataSaida']),
                        'dataEntrada' => $this->services->formatarData($infoCompra['dataEntrada']),
                        'criacao' => $array];

        $array = array();
        array_push($array, $infoCompra);


        $pedidos=$con->query(" select p.idPedido,p.idCompra,m.material as Material, l.nomeLoja,l.numeroLoja,p.altura,p.largura,p.observacao, p.quantidade, u.nome+ ' '+u.sobrenome as criacao,p.valorProduto, 
         p.valorTotal, case when m.formaCalculo = 1 then 'Produto sem custo' when m.formaCalculo = 3 then 'Item com medida padrão' else 'Medida Obrigatoria' end as tipo,
         p.data_entrega,p.dataIdeal,p.fotoArte,m.valor as precoUnitario, t.tempoEstimado,p.status_pedido, f.razao, cf.dataPrevista, cf.dataSaida,
         case when p.status_pedido = 11 then 'Finalizado'
			when p.status_pedido = 8 then 'Com  Fornecedor'
			when p.status_pedido = 9 then 'Aguardando Aprovação de Orçamento'
			when p.status_pedido = 2 then 'aguardando revisão de orçamento'
			when p.status_pedido = 3 then 'Orçamento aprovado'
			when p.status_pedido = 4 then 'Orçamento reprovado'
			when p.status_pedido = 5 then 'Em criação'
			when p.status_pedido = 6 then 'Arte aprovada'
			when p.status_pedido = 7 then 'Arte reprovada'
			when p.status_pedido = 81 then 'Pedido disponível para entrega'
			when p.status_pedido = 10 then 'Aguardando aprovação de arte'
			when p.status_pedido = 101 then 'Aguardando Revisão de arte'
			when p.status_pedido = 25 then 'Analise do trade'
			else 'Pedido não disponivel' end as status_pedido,
         (select observacao from dmtrixII.historicoObs where idPedido = p.idPedido and tipo = 5) as entrega
         from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial 
         join lojasDMTRIX l on l.idLoja = p.idLoja 
         left join tarefasDMTRIX t on t.idPedido = p.idPedido
         left join usuariosDMTRIX u on u.idUsuario = t.idUsuario
         left join dmtrixII.[controle-fornecedor] cf on cf.idPedido = p.idPedido
         left join dmtrixII.fornecedores f on f.id = cf.idFornecedor where p.idCompra ='$idCompra'");



        $pedido = array();
        while($rs = $con->fetch_array($pedidos))
        {
            
            if($rs['fotoArte'] == '' or $rs['fotoArte'] == 'Sem Arte' or $rs['fotoArte'] == 'Pedido cancelado')
            {
                $foto = '';
            }else{

                $foto = $rs['fotoArte'];

            }

            //status do

            $idPedido=$rs['idPedido'];
            $sql = $this->con->query("select * from dmtrixII.pedidosExpirados where status = 0 and idPedido = '$idPedido'");
            if(odbc_num_rows($sql) == 0){

                $status = $rs['status_pedido'];

            }else{

                $status = 13;
            }

            $array1 = [ 'idPedido' => $rs['idPedido'],
                'idCompra' => $rs['idCompra'],
                'Material' => $rs['Material'],
                'nomeLoja' => $rs['nomeLoja'],
                'numeroLoja' => $rs['numeroLoja'],
                'altura' => $rs['altura'],
                'largura' => $rs['largura'],
                'observacao' => $rs['observacao'],
                'quantidade' => $rs['quantidade'],
                'criacao' => $rs['criacao'],
                'valorProduto' => $rs['valorProduto'],
                'valorTotal' => $rs['valorTotal'],
                'tipo' => $rs['tipo'],
                'data_entrega' => $rs['data_entrega'],
                'dataIdeal' => $this->services->formatarData($rs['dataIdeal']),
                'fotoArte' => $foto,
                'precoUnitario' => $rs['precoUnitario'],
                'tempoEstimado' => $rs['tempoEstimado'],
                'status' => $status,
                'razao' => $rs['razao'],
                'status_pedido' => $rs['status_pedido'],
                'dataPrevista' => $this->services->formatarData($rs['dataPrevista']),
                'dataSaida' => $this->services->formatarData($rs['dataSaida']),
                'entrega' => $rs['entrega']
            ];

            
            array_push($pedido, $array1);

        }



        array_push($array,$pedido);
        array_push($array, $arrayTimeline);

        return compact('array');
        
    }
    
    public function showDetalhesPedidos()
    {
        
        return view('pedidos.detalhes-pedido');
        
    }

    public function showDelegarDetalhes(){

        return view('pedidos.custo-delegar');

    }

    public function index()
    {

        return view('pedidos.todos-pedidos');
        

    }
    
    public function consultaMostrar(){
        
        return view('pedidos.atualizacao-custo-detalhes');
        
    }

    public function showAtualizarValores($status){

        if($status == 2){

            $sts1 = 9;
            $sts2 = 4;

        }else{
            $sts1 = 0;
            $sts2 = 0;

        }

      $sql =  $this->con->query("select distinct c.idCompra,dataCompra, status_compra, c.idLoja as numeroLoja, u.nome +' '+ u.sobrenome as solicitante, c.titulo  
   from ComprasDMTRIX c join usuariosDMTRIX u on u.idUsuario = c.idUsuario join PedidoDMTRIX p on p.idCompra = c.idCompra 
   where p.status_pedido = '$status' or status_pedido = '$sts1' or status_pedido = '$sts2'");

        if(odbc_num_rows($sql) > 0) {

            $pedido = array();
            while($rs = $this->con->fetch_array($sql))
            {

                $idCompra = $rs['idCompra'];


                if($rs['status_compra'] == 'Em analise'){
                    
                    $color = 'red';
                    
                }else{
                    
                    $color ='';
                    
                }

                $reprovado = $this->con->query("select idCompra from PedidoDMTRIX where status_pedido = 4 and idCompra = '$idCompra'");

                if(odbc_num_rows($reprovado) >0 ){

                    $color = 'red';
                }


                $situacao = $this->pedidosService->situacaoCompra($rs['idCompra']);
                $array1 = [
                    'dataCompra' => $rs['dataCompra'],
                    'idCompra' => $rs['idCompra'],
                    'status_compra' => $rs['status_compra'],
                    'titulo' => utf8_encode($rs['titulo']),
                    'solicitante' => $rs['solicitante'],
                    'numeroLoja' => $rs['numeroLoja'],
                    'situacao' => $situacao,
                    'color' => $color

                ];

                array_push($pedido, $array1);
            }

            return  $pedido;
        }else{


            return 'Não há compras no momento';
        }

    }
    
    public function consultar($id, $status){
        
        $sql = $this->con->query("  select p.custeio,p.idCompra,p.idMaterial,c.titulo,p.formaPagamento,p.Data_do_Pedido,u.nome + ' '+ u.sobrenome as solicitante,
    m.material,p.idPedido,l.nomeLoja,l.numeroLoja,p.largura,p.altura,p.quantidade,m.valor as valorUnitario, p.valorProduto,p.observacao, p.status_pedido, rp.Motivo, m.formaCalculo
  from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial join ComprasDMTRIX c on c.idCompra = p.idCompra join lojasDMTRIX l on l.numeroLoja = c.idLoja 
   join usuariosDMTRIX u on u.idUsuario = p.idUsuario left join ControleReprovacoesDMTRIX rp on rp.idCompra = c.idCompra
   where p.idCompra = '$id'");

        if(odbc_num_rows($sql) > 0) {

            $pedido = array();
            while($rs = $this->con->fetch_array($sql))
            {

                if($rs['status_pedido'] == $status or $rs['status_pedido'] == 9 or $rs['status_pedido'] == 4) {

                    switch($rs['status_pedido']){
                        case 2: $situacao = 'Aguardando atualização de orçamento';
                            break;
                        case 9: $situacao = 'Aguardando aprovação de orçamento';
                            break;
                        case 4: $situacao = 'Reprovado o orçamento: '.$rs['Motivo'];
                            break;
                        case 25: $situacao = 'Aguardando avaliação trade';
                            break;
                        default: $situacao = 'Erro no pedido';


                    }
                    
                    

                    $array1 = [
                        'idCompra' => $rs['idCompra'],
                        'formaCalculo' => $rs['formaCalculo'],
                        'status_pedido'=>$rs['status_pedido'],
                        'custeio' => $rs['custeio'],
                        'idMaterial' => $rs['idMaterial'],
                        'titulo' => utf8_encode($rs['titulo']),
                        'formaPagamento' => $rs['formaPagamento'],
                        'Data_do_Pedido' => $rs['Data_do_Pedido'],
                        'solicitante' => $rs['solicitante'],
                        'material' => $rs['material'],
                        'idPedido' => $rs['idPedido'],
                        'nomeLoja' => $rs['nomeLoja'],
                        'numeroLoja' => $rs['numeroLoja'],
                        'largura' => $rs['largura'],
                        'altura' => $rs['altura'],
                        'quantidade' => $rs['quantidade'],
                        'valorUnitario' => $rs['valorUnitario'],
                        'valorProduto' => $rs['valorProduto'],
                        'observacao' => $rs['observacao'],
                        'situacao' => $situacao

                    ];

                    array_push($pedido, $array1);
                }
            }
         
            return  $pedido;
        }else{


            return 'Pedido não encontrado';
        }
        
    }
    
    public function updateStatus(Request $request)
    {
        
        $resp = $this->pedidosService->AtualizaValores($request->all());

        return view('pedidos.atualizacao-custo', compact('resp'));

    }
    
    public function showTriagemPedidos()
    {

        $sql = $this->con->query("select distinct c.idCompra,c.titulo,c.dataCompra,c.dataOrcAtualizado,u.nome +' '+u.sobrenome as solicitante, a.data_aprovado, c.status_compra, l.numeroLoja+' - '+l.nomeLoja as loja
  from PedidoDMTRIX p join ComprasDMTRIX c on c.idCompra = p.idCompra join usuariosDMTRIX u on p.idUsuario = u.idUsuario
  left join ControleAprovacoesDMTRIX a on a.idPedido = p.idPedido join lojasDMTRIX l on l.numeroLoja = c.idLoja where p.status_pedido = '3' order by c.idCompra desc");
        if(odbc_num_rows($sql) > 0) {
            $triagem = array();
            while ($rs = $this->con->fetch_array($sql)) {

                $idCompra = $rs['idCompra'];
                $itens = $this->con->query("select m.material from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial where p.idCompra = '$idCompra'");
                
                $compras = array();
                while($item = $this->con->fetch_array($itens))
                {
                    
                    array_push($compras,
                        [
                            'material' => $item['material']
                            
                        ]);
                    
                    
                }

                $array = [

                    'idCompra' => $rs['idCompra'],
                    'titulo' => utf8_encode($rs['titulo']),
                    'dataCompra' => $this->services->formatarData($rs['dataCompra']),
                    'solicitante' => $rs['solicitante'],
                    'loja' => $rs['loja'],
                    'compras' => $compras

                ];

                array_push($triagem, $array);


            }

            return $triagem;


        }else{

            return 'Sem compras no momento';

        }



    }
    
    public function delegarTarefas(Request $request)
    {

        $resp = $this->pedidosService->criarTarefa($request->all());

        return view('pedidos.custo-aprovado', compact('resp'));

    }
    public function redelegarTarefas(Request $request)
    {

            $resp = $this->pedidosService->redelegar($request->all());

        return view('pedidos.custo-aprovado', compact('resp'));

    }

    
    
    public function cancelados()
    {
        $sql = $this->con->query("select distinct p.idCompra, u.nome+' '+u.sobrenome as solicitante,c.titulo, c.dataCompra, a.data_aprovado, c.dataOrcAtualizado, 
	case when c.Prioridade  = 1 then 'alta' when c.Prioridade  = 2 then 'media' when c.Prioridade  = 3 then 'baixa' else 'Sem prioridade' end as prioridade, 
	(select nome +' '+sobrenome from usuariosDMTRIX where idUsuario = t.idUsuario)as criacao
   from dmtrixII.pedidosExpirados e join PedidoDMTRIX p on e.idPedido = p.idPedido join ComprasDMTRIX c on c.idCompra = p.idCompra
  join usuariosDMTRIX u on u.idUsuario = p.idUsuario left join ControleAprovacoesDMTRIX a on a.idPedido = p.idPedido
  left join tarefasDMTRIX t on t.idPedido = p.idPedido where e.status = 0");

        $response = array();

        while($rs = $this->con->fetch_array($sql)){

            $array = ['idCompra' => $rs['idCompra'],
                    'solicitante' => $rs['solicitante'],
                    'dataCompra' => $rs['dataCompra'],
                    'dataAprovado' => $rs['data_aprovado'],
                    'dataOrcAtualizado' => $rs['dataOrcAtualizado'],
                    'prioridade' => $rs['prioridade'],
                    'criacao' => $rs['criacao'],
                    'titulo' => utf8_encode($rs['titulo'])
            ];
            
            array_push($response, $array);

        }
        
        return $response;


    }
    
    public function cancelarPedido($id, $obs)
    {
        
        $request = ['token' => $id, 'motivo' => $obs];

        $resp = $this->pedidosService->cancelarPedido($request);

        return $resp;
        
    }
    
        public function nortificacao(){
    
        return $this->services->nortificacoesMenu();
    
    
         }

    public function msgTopo(){

        return $this->services->mensagensTopo();


    }

    public function finalizarCompra(Request $request)
    {
        $rs = $request->all();
        $idCompra = $rs['token'];
        $motivo = $rs['motivoFinalizar'];
        $cancelar = $rs['cancelar'];

       $sql =  $this->con->query("select p.valorTotal, u.budgetMerchandising, u.supervisor, p.idPedido, u.idUsuario as solicitante from ControleAprovacoesDMTRIX ca join PedidoDMTRIX p on p.idCompra = ca.idCompra 
join usuariosDMTRIX u on u.idUsuario = p.idUsuario where p.idCompra = '$idCompra'");

        if(odbc_num_rows($sql) > 0){

           $item = odbc_fetch_array($sql);

                $valorTotal = $item['valorTotal'];
                $budget = $item['budgetMerchandising'];
                $idUsuario = $item['supervisor'];
                $idPedido = $item['idPedido'];
                $solicitante = $item['solicitante'];

                $value = session('user');
                $controle = $value['id'];

                $total = ($budget) - ($valorTotal);
                $valorTotal = abs($valorTotal);
                $this->con->query("update usuariosDMTRIX set budgetMerchandising = '$total' where idUsuario = '$idUsuario'");
                $this->con->query("update usuariosDMTRIX set budgetMerchandising = '$total' where supervisor = '$idUsuario'");
                $this->con->query("insert into movimentacaoDMTRIX (dataMov, tipoMov,observacao,valor,budgetMerchan,idUsuario,controle,idPedido) values (GETDATE(),1,'Compra finalizada','$valorTotal','$total','$solicitante','$controle','$idPedido')");

        }

        $idPedido = $this->con->fetch_array($this->con->query("select idPedido from PedidoDMTRIX where idCompra = '$idCompra'"));
        $idPedido = $idPedido['idPedido'];

        if($cancelar == 1){

            $this->con->query("update ComprasDMTRIX set status_compra = 'Cancelado' where idCompra = '$idCompra'");
            $texto = 'Usuario cancelou a compra, motivo: '.$motivo;

        }else{

            $this->con->query("update ComprasDMTRIX set status_compra = 'Finalizado' where idCompra = '$idCompra'");
            $texto = 'Usuario finalizou a compra, observação: '.$motivo;

        }

        $info = ['idPedido' => $idPedido, 'texto'=> $texto, 'tipo' => 2];
        $this->historico->create($info);

        $this->con->query("update PedidoDMTRIX set status_pedido = 11 where idCompra ='$idCompra'");

        if(odbc_error() == '')
        {



                $info = ['idCompra' => $idCompra, 'texto' => 'Usuario finalizou a compra, observação: '.$motivo, 'tipo' => 2];
                $this->historico->historicoCompras($info);


            $class = 'bg-success text-center text-success';
            $msg = 'Finalizado com sucesso';

            $resp = ['class'=>$class, 'msg'=> $msg];


        }else{

            $class = 'bg-danger text-center';
            $msg = 'Falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];


        }

        return view('pedidos.todos-pedidos', compact('resp'));

    }
    
    public function teste(){
        
        return $this->pedidosService->cancelamento();
        
    }
}
