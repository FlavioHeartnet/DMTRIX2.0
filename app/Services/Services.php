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

    public function DiffDatasPedidos($data){

        $data = new \DateTime($data);
        $hoje = new \DateTime();

        $diff = $data->diff($hoje);

         if($diff->days > 0){

             return $diff->days.' dias';

         }else {

             if($diff->m < 60){

                 return $diff->m.' min';

             }else{

                 return $diff->h.' horas';

             }



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
    
    public function nortificacoesMenu(){

        $value = session('user');
        $idUsuario = $value['id'];

       $rs = $this->con->fetch_array($this->con->query("select (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 3 ) as triagem,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 101 ) as numRevisao,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 10 ) as numAprovacao,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 6 ) as numAprovados,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 2 or status_pedido = 4  ) as numOrcamento,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 25  ) as numTrade,
        (select COUNT(*) as num from PedidoDMTRIX where status_pedido = 6 or status_pedido = 81 or status_pedido = 8 ) as numFornecedor"));

        $sql = $this->con->query("select top 10  h.id
        from dmtrixII.historicoObs h join PedidoDMTRIX p on p.idPedido = h.idPedido
        join usuariosDMTRIX u on u.idUsuario = h.idUsusario
        join ComprasDMTRIX c on c.idCompra = p.idCompra
        join lojasDMTRIX l on l.numeroLoja = c.idLoja
         where h.tipo = 2 ");

        $msg = 0;
       while($x = $this->con->fetch_array($sql))
       {
           $idHistorico = $x['id'];
           $verifica = $this->con->query("select idHistorico FROM [MARKETING].[dmtrixII].[controleMensagens] where idHistorico = '$idHistorico' and idUsuario = '$idUsuario'");
           if(odbc_num_rows($verifica) == 0 )
           {

               $msg++;

           }


       }
        
        
        return ['msg'=>$msg, 'triagem' => $rs['triagem'],'numTrade' => $rs['numTrade'],'numRevisao' => $rs['numRevisao'],'numAprovacao' => $rs['numAprovacao'],'numOrcamento' => $rs['numOrcamento'],'numFornecedor' => $rs['numFornecedor'],'numAprovados' => $rs['numAprovados'] ];
        
        
    }

    public function mensagensTopo(){

        $value = session('user');
        $idUsuario = $value['id'];

    $response = array();
        $sql = $this->con->query("select distinct top 10 u.nome+' '+u.sobrenome as solicitante, c.idCompra, l.numeroLoja+' - '+l.nomeLoja as loja,u.foto, ca.data_aprovado_arte
        from ComprasDMTRIX c join usuariosDMTRIX u on u.idUsuario = c.idUsuario join lojasDMTRIX l on l.numeroLoja = c.idLoja
        join PedidoDMTRIX p on p.idCompra = c.idCompra
        join ControleAprovacoesDMTRIX ca on ca.idPedido = p.idPedido where p.status_pedido = 6 order by ca.data_aprovado_arte desc");
        $aprovacao = array();
        if(odbc_num_rows($sql) != 0) {

            while ($rs = odbc_fetch_array($sql)) {

                $dataAprvado = $rs['data_aprovado_arte'];

                $dataAprvado = $this->DiffDatasPedidos($dataAprvado);

                array_push($aprovacao,

                    [
                        'solicitante' => $rs['solicitante'],
                        'loja' => $rs['loja'],
                        'idCompra' => $rs['idCompra'],
                        'foto' => $rs['foto'],
                        'data' => $dataAprvado,
                    ]

                );

            }
        }

        $sql = $this->con->query("select top 10 u.nome+' '+u.sobrenome as solicitante, c.idCompra, l.numeroLoja+' - '+l.nomeLoja as loja,u.foto, p.dataArtePostada
        from ComprasDMTRIX c join usuariosDMTRIX u on u.idUsuario = c.idUsuario join lojasDMTRIX l on l.numeroLoja = c.idLoja
        join PedidoDMTRIX p on p.idCompra = c.idCompra where p.status_pedido = 101");
        $revisao = array();
        if(odbc_num_rows($sql) != 0) {
            while ($rs = odbc_fetch_array($sql)) {

                $data = $rs['dataArtePostada'];

                $data = $this->DiffDatasPedidos($data);


                array_push($revisao,

                    [
                        'solicitante' => $rs['solicitante'],
                        'loja' => $rs['loja'],
                        'idCompra' => $rs['idCompra'],
                        'foto' => $rs['foto'],
                        'data' => $data
                    ]

                );

            }
        }

        $sql = $this->con->query(" select top 10 u.nome+' '+u.sobrenome as solicitante, c.idCompra, l.numeroLoja+' - '+l.nomeLoja as loja,u.foto, h.observacao,h.dataObs,h.id
        from dmtrixII.historicoObs h join PedidoDMTRIX p on p.idPedido = h.idPedido
        join usuariosDMTRIX u on u.idUsuario = h.idUsusario
        join ComprasDMTRIX c on c.idCompra = p.idCompra
        join lojasDMTRIX l on l.numeroLoja = c.idLoja
        where h.tipo = 2  ");
        $mensagem = array();
        if(odbc_num_rows($sql) != 0) {
            while ($rs = odbc_fetch_array($sql)) {


                $data = $rs['dataObs'];
                $idHistorico = $rs['id'];
                $verifica = $this->con->query("select idHistorico FROM [MARKETING].[dmtrixII].[controleMensagens] where idHistorico = '$idHistorico' and idUsuario = '$idUsuario'");

                $data = $this->DiffDatasPedidos($data);

                if(odbc_num_rows($verifica) == 0) {

                    array_push($mensagem,

                        [
                            'solicitante' => $rs['solicitante'],
                            'loja' => $rs['loja'],
                            'idCompra' => $rs['idCompra'],
                            'foto' => $rs['foto'],
                            'data' => $data,
                            'obs' => $rs['observacao']
                        ]

                    );
                }

            }
        }
        
        array_push($response, $aprovacao);
        array_push($response, $revisao);
        array_push($response, $mensagem);
        
        return $response;


    }

    public function infoPedido($idPedido){

        $service = new Services();

        $rs = $this->con->fetch_array($this->con->query(" select p.idPedido,p.idCompra,m.material as Material, l.nomeLoja,l.numeroLoja,p.altura,p.largura,p.observacao, p.quantidade, u.nome+ ' '+u.sobrenome as criacao,p.valorProduto, 
         p.valorTotal, case when m.formaCalculo = 1 then 'Produto sem custo' when m.formaCalculo = 3 then 'Item com medida padrão' else 'Medida Obrigatoria' end as tipo,
         p.data_entrega,p.dataIdeal,p.fotoArte,m.valor as precoUnitario, t.tempoEstimado,p.status_pedido, f.razao, cf.dataPrevista, cf.dataSaida,us.nome as solicitante, us.email,
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
         join usuariosDMTRIX us on us.idUsuario = p.idUsusario
         left join tarefasDMTRIX t on t.idPedido = p.idPedido
         left join usuariosDMTRIX u on u.idUsuario = t.idUsuario
         left join dmtrixII.[controle-fornecedor] cf on cf.idPedido = p.idPedido
         left join dmtrixII.fornecedores f on f.id = cf.idFornecedor where p.idPedido ='$idPedido'"));

        $array1 = [ 'idPedido' => $rs['idPedido'],
            'idCompra' => $rs['idCompra'],
            'Material' => $rs['Material'],
            'solicitante' => $rs['solicitante'],
            'email' => $rs['email'],
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