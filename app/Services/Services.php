<?php
namespace App\Services;



use App\Http\Controllers\HistoricoController;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Mail;


class Services
{

    private $con;
    private $historico;
    private $fornecedor;

    public function __construct()
    {
        $this->con = new \config();
        $this->historico = new HistoricoController();
      

    }

    public function formatarData($data){


        if($data != '') {
            $data = new \DateTime($data);
            return $data->format('d/m/Y');
        }else{

            return 'Sem data no momento!';

        }
        
    }

    public function atualizarStatusCompra($status, $idCompra, $tipoStatus)
    {


       $numStatus = $this->con->fetch_array($this->con->query("select COUNT(*) as num from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = '$status'"));
       $numPedidos = $this->con->fetch_array($this->con->query("select COUNT(*) as num from PedidoDMTRIX where idCompra = '$idCompra'"));

        $numStatus = $numStatus['num'];
        $numPedidos = $numPedidos['num'];


        if($numPedidos == $numStatus){

            $this->con->query("update ComprasDMTRIX set status_compra = '$tipoStatus' where idCompra = '$idCompra' ");
            
            return 'Status da compra alterado';
            
        }else{
            
            return 'ainda tem itens pendentes nesta compra!';
            
        }



    }

    public function infoPedido($idPedido){

        $service = new Services();

        $rs = $this->con->fetch_array($this->con->query(" select p.idPedido,p.idCompra,m.material as Material, l.nomeLoja,l.numeroLoja,p.altura,p.largura,p.observacao, p.quantidade, u.nome+ ' '+u.sobrenome as criacao,p.valorProduto, 
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
			else 'Pedido não disponivel' end as status_pedido,
         (select observacao from dmtrixII.historicoObs where idPedido = p.idPedido and tipo = 5) as entrega
         from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial 
         join lojasDMTRIX l on l.idLoja = p.idLoja 
         left join tarefasDMTRIX t on t.idPedido = p.idPedido
         left join usuariosDMTRIX u on u.idUsuario = t.idUsuario
         left join dmtrixII.[controle-fornecedor] cf on cf.idPedido = p.idPedido
         left join dmtrixII.fornecedores f on f.id = cf.idFornecedor where p.idPedido ='$idPedido'"));

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
            'dataIdeal' =>$service->formatarData($rs['dataIdeal']),
            'precoUnitario' => $rs['precoUnitario'],
            'tempoEstimado' => $rs['tempoEstimado'],
            'razao' => $rs['razao'],
            'status_pedido' => $rs['status_pedido'],
            'dataPrevista' => $service->formatarData($rs['dataPrevista']),
            'dataSaida' => $service->formatarData($rs['dataSaida'])

        ];

        return $array1;

    }


}