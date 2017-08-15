<?php
namespace App\Services;

use App\Http\Controllers\HistoricoController;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\Mail;

class FornecedorServices
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Factory
     */
    private $storage;
    private $con;
    private $historico;
    private $services;
    


    /**
     * FornecedorServices constructor.
     */
    public function __construct(Filesystem $filesystem, Factory $storage, HistoricoController $historico, Services $services)
    {

        $this->con = new \config();
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->historico = $historico;
        $this->services = $services;
    }

    public function pedidosFornecedor(){

       $sql = $this->con->fetch_array($this->con->query("select (select count(*) as fila from PedidoDMTRIX where status_pedido = 6) as aprovados,
   (select count(*) as fila from PedidoDMTRIX where status_pedido = 8) as fila
   ,(select count(*) as fila from PedidoDMTRIX where  status_pedido = 81) as aguardandoRetirada
   ,(select count(*) as fila from PedidoDMTRIX where status_pedido = 11 and YEAR(dataArtePostada) = YEAR(GETDATE()) ) as finalizado,
   (select COUNT(*) as num from dmtrixII.[controle-fornecedor] c join PedidoDMTRIX p on p.idPedido = c.idPedido  
   where DATEDIFF(day, dataPrevista, GETDATE()) < 2 and p.status_pedido = 8) as entrega"));
        
        return $sql;



    }

    public function createFile(array $data)
    {

        $nomeArquivo = trim($data['nome']).'.'.$data['extension'];
        $this->storage->put($nomeArquivo, $this->filesystem->get($data['foto']));


    }
    public function getFile($file)
    {

        return $this->storage->get($file);

    }

    public function EnviarFornecedor($idPedido, $id, $data){

        $data = new \DateTime($data);
        $data = $data->format('d/m/y');

        $sql = $this->con->query(" select idPedido from dmtrixII.[controle-fornecedor] where idPedido = '$idPedido'");
        if(odbc_num_rows($sql) == 0)
        {

           $this->con->query("insert into dmtrixII.[controle-fornecedor] (idPedido,idFornecedor,dataPrevista) values('$idPedido', '$id', '$data')");

        }else{

           $this->con->query("update dmtrixII.[controle-fornecedor] set idPedido = '$idPedido', idFornecedor='$id',dataPrevista = '$data' where idPedido = '$idPedido'");

        }



        if(odbc_error() == ''){

            $dados = $this->services->infoPedido($idPedido);
            $idCompra = $dados['idCompra'];
            $respAtualizar = $this->services->atualizarStatusCompra(8,$idCompra,'Fornecedor');

            $this->con->query("update PedidoDMTRIX set status_pedido = 8 where idPedido = '$idPedido'");

            $razao = $this->con->fetch_array($this->con->query("select razao from dmtrixII.fornecedores where id = '$id'"));
            $razao = $razao['razao'];

            $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido Enviado ao fornecedor: '.$razao, 'tipo' => 4];
            $this->historico->create($info);

            $class = 'bg-success text-center text-success';
            $msg = 'Enviado para o Fornecedor com sucesso <br> <p>'.$respAtualizar.'</p>';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;
        }else{


            $class = 'bg-danger text-center text-danger';
            $msg = 'falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }



    }

    public function EntregaPedido($idPedido){


        $sql = $this->con->query("select cf.idPedido, f.razao, f.id from dmtrixII.[controle-fornecedor] cf join dmtrixII.fornecedores f on f.id= cf.idFornecedor where idPedido = '$idPedido'");

        $id = $this->con->fetch_array($sql);
        $razao = $id['razao'];
        $id = $id['id'];

        if(odbc_num_rows($sql) == 0)
        {

            $this->con->query("insert into dmtrixII.[controle-fornecedor] (idPedido,idFornecedor,dataEntrada) values('$idPedido', '$id', getdate())");

        }else{

            $this->con->query("update dmtrixII.[controle-fornecedor] set idPedido = '$idPedido', idFornecedor='$id',dataEntrada = getdate() where idPedido = '$idPedido'");

        }

        if(odbc_error() == ''){

            $this->con->query("update PedidoDMTRIX set status_pedido = 81 where idPedido = '$idPedido'");

            $msg = $this->con->fetch_array($this->con->query("select u.nome, u.email,p.idCompra, u.supervisor, m.material, p.idLoja from usuariosDMTRIX u join PedidoDMTRIX p on p.idUsuario = u.idUsuario
join materiaisDMTRIX m on m.idMaterial = p.idMaterial where p.idPedido = '$idPedido'"));
            $nome = $msg['nome'];
            $idSupervisor = $msg['supervisor'];
            $infoSupervisor = $this->services->infoSupervisor($idSupervisor);
            $Cc = $infoSupervisor['email'];
            $email = $msg['email'];
            $idCompra = $msg['idCompra'];
            $idLoja = $msg['idLoja'];

            $infoLoja = $this->services->infoLoja($idLoja);
            $nomeLoja = $infoLoja['numeroLoja'].' '.$infoLoja['nomeLoja'];

           $verifica =$this->con->fetch_array($this->con->query("  select COUNT(*) as num, (select COUNT(*) as num from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = 81) as numPedido 
  from PedidoDMTRIX where idCompra = '$idCompra'"));

            $num = $verifica['num'];
            $numPedido = $verifica['numPedido'];

            if($num == $numPedido) {

                $x = ['email' => $email, 'Cc' => $Cc, 'nome' => $nome];
                $material = $msg['material'];
                $mensagem = "Olá " . $nome . ", o materiail: " . $material . " da compra nº: ".$idCompra." para a loja: ".$nomeLoja."  que você solicitou já estão disponíveis para retirada, compareça na DMCard ou entre em contato com o trade para recebe-lo!";


                Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($x) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc($x['Cc'], 'Supervisor');
                    $m->to($x['email'], $x['nome'])->subject('Pedido já esta disponível para retirada!');
                });
            }


            $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido que estava com o fornecedor: '.$razao.' foi entregue!', 'tipo' => 4];
            $this->historico->create($info);

            $class = 'bg-success text-center text-success';
            $msg = 'Pedido esta na disponivel para retirada';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;
        }else{

            $class = 'bg-danger text-center text-danger';
            $msg = 'falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }



    }

    public function finalizarPedido($request){

        $idPedido = $request['token'];
        $retirou = $request['retirou'];
        $entregou = $request['entregou'];
        $data = $request['data'];

        $data = new \DateTime($data);
        $data = $data->format('d/m/y');

        $sql = $this->con->query("select cf.idPedido, f.razao, f.id from dmtrixII.[controle-fornecedor] cf join dmtrixII.fornecedores f on f.id= cf.idFornecedor where idPedido = '$idPedido'");

        $id = $this->con->fetch_array($sql);
        $id = $id['id'];

        if(odbc_num_rows($sql) == 0)
        {

            $this->con->query("insert into dmtrixII.[controle-fornecedor] (idPedido,idFornecedor,dataSaida) values('$idPedido', '$id', '$data')");

        }else{

            $this->con->query("update dmtrixII.[controle-fornecedor] set idPedido = '$idPedido', idFornecedor='$id',dataSaida = '$data' where idPedido = '$idPedido'");

        }

        if(odbc_error() == ''){

           $this->con->query("update PedidoDMTRIX set status_pedido = 11 where idPedido = '$idPedido'");

            $dados = $this->services->infoPedido($idPedido);
            $idCompra = $dados['idCompra'];

            $this->services->atualizarStatusCompra(11,$idCompra,'Finalizado');
            

            $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido foi entregue ao '.$retirou.' pelo(a) '.$entregou.'!', 'tipo' => 5];
            $this->historico->create($info);

            $class = 'bg-success text-center text-success';
            $msg = 'Pedido finalizado com sucesso';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;
        }else{

            $class = 'bg-danger text-center text-danger';
            $msg = 'falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;


        }


    }

    public function finalizarCompra($request){

        $idCompra = $request['token'];
        $sql = $this->con->query("select idPedido from PedidoDMTRIX where idCompra = '$idCompra'");

        $class = 'bg-danger text-center text-danger';
        $msg = 'Serviço não disponivel';
        $resp = ['class'=>$class, 'msg'=> $msg];
        
        while($rs = $this->con->fetch_array($sql)){

            $array = ['token' => $rs['idPedido'],
                    'retirou' =>$request['retirou'],
                    'entregou' =>$request['entregou'],
                    'data'=>$request['data']];

            $resp = $this->finalizarPedido($array);


        }

        return $resp;

    }


    public function pedidosEntreguesParados()
    {


        $sql = $this->con->query("select distinct c.idCompra, l.numeroLoja + ' - '+l.nomeLoja as loja, u.email,u.nome + ' '+u.sobrenome as nome,u.supervisor 
	 from PedidoDMTRIX p join ComprasDMTRIX c on c.idCompra = p.idCompra 
	 join usuariosDMTRIX u on u.idUsuario = p.idUsuario left join lojasDMTRIX l on l.numeroLoja = c.idLoja where p.status_pedido = 81");
        while($rs = $this->con->fetch_array($sql))
        {
            $materiais = '';
            $idCompra = $rs['idCompra'];

            //infos para o email
            $loja = $rs['loja'];
            $idSupervisor = $rs['supervisor'];
            $email = $rs['email'];
            $nome = $rs['nome'];

            $pedidos = $this->con->query("select idPedido from PedidoDMTRIX where idCompra = '$idCompra'");
            while($rt = $this->con->fetch_array($pedidos))
            {

                $idPedido = $rt['idPedido'];

                $situacao = $this->services->dataParaCancelar($idPedido);
                if($situacao['dias'] >= 7) {

                    $validador = $this->con->fetch_array($this->con->query("	 select top 1  m.material
	  from PedidoDMTRIX p join dmtrixII.historicoObs h on h.idPedido = p.idPedido join ComprasDMTRIX c on c.idCompra = p.idCompra 
	join materiaisDMTRIX m on m.idMaterial = p.idMaterial
	  where p.idPedido = '$idPedido' order by h.dataObs desc"));

                    $materiais .= $validador['material'].'<br>';


                }

            }

            if($materiais != '')
            {
                $infoSupervisor = $this->services->infoSupervisor($idSupervisor);
                $Cc = $infoSupervisor['email'];
                $mensagem = 'Caro(a) '.$nome.'<br>Este é um email para lembra-lo(a) de que seu pedido nº '.$idCompra.' para a loja '.$loja.' já está disponivel para retirada a '.$situacao['dias'].' dias, vá até a DMCard para retira-lo ou entre em contato conosco!<br> Segue materiais disponíveis: <br>'.$materiais;
                $x = ['email' =>$email, 'Cc' => $Cc, 'nome' => $nome ];
                
               echo 'Pedido: '.$idCompra.'<br> email: '.$email.'<br> Cc: '.$Cc.'<br> loja: '.$loja.'<br> '.$mensagem.' <br>';

             Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($x) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc($x['Cc'], 'Supervisor');
                    $m->to($x['email'], $x['nome'])->subject('Pedido já esta disponível para retirada a mais de 7 dias!');
                });
            }

        }

    }
    
    
    
    

}