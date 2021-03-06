<?php
namespace App\Services;



use App\Http\Controllers\HistoricoController;
use Illuminate\Support\Facades\Mail;

class PedidosServices
{

    private $con;
    private $historico;
    private $services;

    public function __construct(Services $services)
    {
        $this->con = new \config();
        $this->historico = new HistoricoController();
        $this->services = $services;

    }


    

    public function AtualizaValores($request)
    {
        $idPedidos = $request['token'];
        $altura = $request['altura'];
        $largura = $request['largura'];
        $quantidade = $request['quantidade'];
        $custoUnitario = $request['custoUnitario'];
        $custoTotal =  $request['custoTotal'];
        $observacao = $request['observacao'];
        
        $total = 0;
        foreach ($custoTotal as $x){
            
            $total+= $x;
            
        }

        $idCompra = '';
        for($i=0;$i<count($idPedidos); $i++)
        {
            $sql =  $this->con->query("select m.formaCalculo,m.valor,p.idCompra,m.material, m.idMaterial,p.idUsuario from materiaisDMTRIX m join PedidoDMTRIX p on p.idMaterial = m.idMaterial  where p.idPedido = '$idPedidos[$i]'");

            $material = $this->con->fetch_array($sql);
            $custoUnitarioReal = $material['valor'];
            $idCompra =  $material['idCompra'];
            $idMaterial = $material['idMaterial'];
            $nomeMaterial = $material['material'];
            $idUsuario = $material['idUsuario'];
            $calculo = $material['formaCalculo'];


            //Verifica se a compra ja foi aprovada
            $verificaPedido = $this->con->query("select * from ControleAprovacoesDMTRIX where idCompra = '$idCompra'");
            if(odbc_num_rows($verificaPedido) > 0)
            {

                $class = 'bg-warning text-center text-warning';
                $msg = 'Compra já atualizada!';

                $resp = ['class'=>$class, 'msg'=> $msg];
                return $resp;

            }


            //altera o valor da tabela de materiais quando o campo de valor unitario é alterado
            if($custoUnitarioReal != $custoUnitario[$i]){

                $this->con->query("update materiaisDMTRIX set valor = '$custoUnitario[$i]' where idMaterial = '$idMaterial'");

                $observacao[$i] .= ' - Custo unitario neste pedido: '.$custoUnitario[$i];

            }

            if(isset($request['tipoAprovacao']))
            {

                    $status = 25;

            }else{

                if(isset($request['reprovar']))
                {

                    $status = 4;
                    $motivoOrc = $request['orcReprovado'];
                    $this->con->query("insert into [MARKETING].[dbo].[ControleReprovacoesDMTRIX] (idCompra, idPedido,data_reprovado,Motivo) values('$idCompra','$idPedidos[$i]',GETDATE(),'$motivoOrc')");

                }else {

                    if($calculo == 1) {
                        $this->con->query("update [marketing].[dbo].[PedidoDMTRIX] set status_pedido = 3  where idPedido = '$idPedidos[$i]'");
                        $this->con->query("update [marketing].[dbo].[ComprasDMTRIX] set status_compra = 'aprovacoes', dataOrcAtualizado=getdate() where idCompra = '$idCompra'");
                        $this->con->query(" insert into ControleAprovacoesDMTRIX (idCompra, data_aprovado,idPedido) values('$idCompra',GETDATE(),'$idPedidos[$i]')");
                        $this->con->query("insert into dmtrixII.historicoObs (tipo,observacao,idUsusario,dataObs,idPedido) 
								values (3,'Pedido de um produto sem custo aprovado!', '$idUsuario',getdate(),'$idPedidos[$i]' )");
                        $status = 3;
                    }else{

                        $status = 9;

                    }

                }


            }
            $this->con->query("update PedidoDMTRIX set largura = '$largura[$i]', altura='$altura[$i]', quantidade='$quantidade[$i]',observacao='$observacao[$i]', status_pedido = '$status', valorProduto='$custoTotal[$i]'
            ,valorTotal='$total' where idPedido = '$idPedidos[$i]'");


                $info = ['idPedido' => $idPedidos[$i], 'texto'=> 'Valor do pedido foi atualizado, item: '.$nomeMaterial, 'tipo' => 1];
                $this->historico->create($info);

        }


        if(odbc_error() == '')
        {
            //Salva no historico e envia email!
            $infos = $this->services->infoPedido($idPedidos[0]);
            $mensagem = 'Olá caro(a) '.$infos['solicitante'].' sua compra: '.$idCompra.' foi atualizado o orçamento, entre no DMTRIX para aprovar/reprovar, caso de duvidas entre em contato com a agencia!';

            Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($infos) {
                $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                //$m->cc('agenciamarketing@dmcard.com.br', 'Flavio');
                $m->to($infos['email'], $infos['solicitante'])->subject('Aviso de atualização de compra');

            });


            $info = ['idCompra' => $idCompra, 'texto' => 'Valor da compra foi atualizado', 'tipo' => 1];
            $this->historico->historicoCompras($info);

            $this->con->query("update ComprasDMTRIX set status_compra = 'aprovacoes', dataOrcAtualizado = getdate(), valorTotal = '$total' where idCompra = '$idCompra'");


            $class = 'bg-success text-center text-success';
            $msg = 'Atualizado com sucesso';
            
            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;
            
        }else
        {

            $class = 'bg-danger text-center';
            $msg = 'Falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];
            
            return $resp;
            
        }



    }
    
    public function criarTarefa($request){
        
        $idCompra = $request['token'];
        $prioridade = $request['prioridade'];
        $criacao = $request['criacao'];
        $dataIdeal = $request['dataEstimada'];



        for($i=0;$i<count($idCompra);$i++)
        {



           $sql =  $this->con->query("select idPedido, status_pedido from PedidoDMTRIX where idCompra = '$idCompra[$i]'");

            if(odbc_num_rows($sql) > 0) //se estiver vazio a consulta sai da instrução
            {

                
                    $dataIdealFormatada = $this->services->formatarData($dataIdeal[$i]);
                   while($rs = $this->con->fetch_array($sql))
                    {
                        $idPedido = $rs['idPedido'];
                        $status_pedido = $rs['status_pedido'];
                        $tarefas = $this->con->query("select idPedido from tarefasDMTRIX where idPedido = '$idPedido'");
                        if(odbc_num_rows($tarefas) > 0)
                        {

                            $class = 'bg-warning text-center text-warning';
                            $msg = 'Já existe uma tarefa delegada para esta compra';

                            $resp = ['class'=>$class, 'msg'=> $msg];


                        }else{
                            
                            $verificaCancelado = $this->services->VerificaCancelado($idPedido);

                            if($status_pedido == 3 and $verificaCancelado == false) {
                                
                                

                                $usuario = $this->con->fetch_array($this->con->query("select nome+' '+sobrenome as nome from usuariosDMTRIX where idUsuario = '$criacao[$i]'"));
                                $usuario = $usuario['nome'];

                                $this->con->query("update ComprasDMTRIX set prioridade = '$prioridade[$i]', status_compra = 'criacao' where idCompra = '$idCompra[$i]'");
                                $this->con->query("update PedidoDMTRIX set status_pedido = '5', dataIdeal = '$dataIdealFormatada' where idPedido = '$idPedido'");
                                $this->con->query("insert into tarefasDMTRIX(idUsuario,idPedido,ativo,iniciado, dataDelegado) values('$criacao[$i]','$idPedido','nao',0, getdate())");
                                $info = ['idPedido' => $idPedido, 'texto' => 'Foi criado uma tarefa e o pedido foi delegado para usuario: ' . $usuario . '.', 'tipo' => 3];
                                $this->historico->create($info);
                            }

                        }

                    }

            }


        }

        if(odbc_error() == '')
        {

            for($i=0;$i<count($idCompra);$i++) {
                $info = ['idCompra' => $idCompra[$i], 'texto' => 'Foi criado uma tarefa para esta compra', 'tipo' => 1];
                $this->historico->historicoCompras($info);
            }



            $class = 'bg-success text-center text-success';
            $msg = 'Delegado com sucesso';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }else{

            $class = 'bg-danger text-center';
            $msg = 'Falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];

            return $resp;

        }
        
        
    }
    public function redelegar($request){

        $idCompra = $request['token'];
        $prioridade = $request['prioridade'];
        $criacao = $request['criacao'];
        $dataIdeal = $request['dataEstimada'];
        
            $sql =  $this->con->query("select p.idPedido, m.material, p.status_pedido from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial where idCompra = '$idCompra'");

            if(odbc_num_rows($sql) > 0) //se estiver vazio a consulta sai da instrução
            {

                    while($rs = $this->con->fetch_array($sql))
                    {
                        $idPedido = $rs['idPedido'];
                        $material = $rs['material'];
                        $status_pedido = $rs['status_pedido'];

                       $date = new \DateTime($dataIdeal);
                        $dataIdealFormatada = $date->format('d/m/Y H:i');

                        if($status_pedido == 3 or $status_pedido == 5 or $status_pedido == 7 or $status_pedido == 6)
                        {

                            $tarefas = $this->con->query("select idPedido from tarefasDMTRIX where idPedido = '$idPedido'");
                            if (odbc_num_rows($tarefas) > 0)
                            {

                                $usuario = $this->con->fetch_array($this->con->query("select nome+' '+sobrenome as nome from usuariosDMTRIX where idUsuario = '$criacao'"));
                                $usuario = $usuario['nome'];

                                $this->con->query("update ComprasDMTRIX set prioridade = '$prioridade', status_compra = 'criacao' where idCompra = '$idCompra'");
                                $this->con->query("update PedidoDMTRIX set status_pedido = '5', dataIdeal = '$dataIdealFormatada' where idPedido = '$idPedido'");
                                $this->con->query("update tarefasDMTRIX set idUsuario = '$criacao', idPedido = '$idPedido',ativo = 'nao', iniciado = 0, dataDelegado = GETDATE() where idPedido = '$idPedido'");
                                $info = ['idPedido' => $idPedido, 'texto' => 'Foi Redelegado uma tarefa  para o usuario: ' . $usuario . ', material: ' . $material, 'tipo' => 3];
                                $this->historico->create($info);

                            } else{

                                $usuario = $this->con->fetch_array($this->con->query("select nome+' '+sobrenome as nome from usuariosDMTRIX where idUsuario = '$criacao'"));
                                $usuario = $usuario['nome'];


                                $this->con->query("update PedidoDMTRIX set status_pedido = 5, dataIdeal = '$dataIdealFormatada' where idPedido = '$idPedido'");
                                $this->con->query("insert into tarefasDMTRIX(idUsuario,idPedido,ativo,iniciado, dataDelegado) values('$criacao','$idPedido','nao','0', getdate())");
                                $this->con->query("update ComprasDMTRIX set prioridade = '$prioridade', status_compra = 'criacao' where idCompra = '$idCompra'");
                                $info = ['idPedido' => $idPedido, 'texto' => 'Foi criado uma tarefa e o pedido foi delegado para usuario: ' . $usuario . ', material: ' . $material, 'tipo' => 3];
                                $this->historico->create($info);

                            }
                        }else{

                            $class = 'bg-warning text-center text-warning';
                            $msg = 'Este pedido não pode ser delegado, pois ja foi concluido ou esta em fase de orçamentação';

                            $resp = ['class'=>$class, 'msg'=> $msg];
                            return $resp;

                        }

                    }

                if(odbc_error() == '')
                {

                    for($i=0;$i<count($idCompra);$i++) {
                        $info = ['idCompra' => $idCompra[$i], 'texto' => 'Foi criado uma tarefa para esta compra', 'tipo' => 1];
                        $this->historico->historicoCompras($info);
                    }
                    $class = 'bg-success text-center text-success';
                    $msg = 'Delegado com sucesso';

                    $resp = ['class'=>$class, 'msg'=> $msg];
                    return $resp;

                }else{

                    $class = 'bg-danger text-center';
                    $msg = 'Falha: '.odbc_errormsg();

                    $resp = ['class'=>$class, 'msg'=> $msg];

                    return $resp;

                }


            }


        


    }

    public function cancelamento()
    {
        ini_set('max_execution_time', 300);
        $sql = $this->con->query("  select m.material,p.idPedido,p.idCompra,status_pedido,u.email,u.nome+u.sobrenome as solicitante from 
  PedidoDMTRIX p join usuariosDMTRIX u on u.idUsuario = p.idUsuario join materiaisDMTRIX m on m.idMaterial = p.idMaterial 
  where status_pedido != 11 and p.status_pedido !=1  order by p.idCompra");

        $array = array();
        while($rs = $this->con->fetch_array($sql))
        {
            $idPedido = $rs['idPedido'];
            $idCompra = $rs['idCompra'];
            $status =  $rs['status_pedido'];
            $email = $rs['email'];
            $nome = $rs['solicitante'];
            $material = $rs['material'];

            $situacao = $this->services->dataParaCancelar($idPedido);

            if($situacao['situacao'] == 'Pedido expirado'){

                $verifica = $this->con->query("select * from dmtrixII.pedidosExpirados where idPedido = '$idPedido'");

                if(odbc_num_rows($verifica) == 0){


                    $this->con->query("insert into dmtrixII.pedidosExpirados (idPedido, dataExpirado,email, status) values ('$idPedido',GETDATE(),0, 0)");
                    $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido cancelado', 'tipo' => 1];
                    $this->historico->create($info);
                    $info = ['idCompra' => $idCompra, 'texto'=> 'o Pedido: '.$idPedido.' foi cancelado', 'tipo' => 1];
                    $this->historico->historicoCompras($info);

                }

            }

            if($situacao['situacao'] != 'ok') {

                $x = ['dias' => $situacao['dias'], 'idPedido' => $idPedido, 'idCompra' => $idCompra, 'situacao' => $situacao['situacao'], 'status' => $this->services->status_pedido($status), 'email' => $email, 'nome' => $nome, 'material' => $material];
                array_push($array, $x);


                if ($status == 10 or $status == 9 or $status == 81) {

                    Mail::send('emails.cancelamento', compact('x'), function ($m) use ($x) {
                        $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                        $m->cc('agenciamarketing@dmcard.com.br', 'Loren');
                        $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                        $m->to($x['email'], $x['nome'])->subject('Pedidos proximos ao cancelamento');
                    });

                }else if ($situacao['dias'] < 30 or $situacao['dias'] > 7  and $status == 5) {

                    $tarefa = $this->con->fetch_array($this->con->query("select distinct t.idUsuario, idCompra, u.email from tarefasDMTRIX t join dmtrixII.PedidoDMTRIX p on p.idPedido = t.idPedido join usuariosDMTRIX u on u.idUsuario = t.idUsuario
   where p.status_pedido = 5 and p.idPedido = '$idPedido'"));

                    Mail::send('emails.cancelamento', compact('x'), function ($m) use ($tarefa) {
                        $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                        $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                        $m->to($tarefa['email'], $tarefa['email'])->subject('Aviso de cancelamento de pedido');
                    });

                }else if($situacao['dias'] < 30 or $situacao['dias'] > 25) {

                    Mail::send('emails.cancelamento', compact('x'), function ($m) {
                        $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                        $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                        $m->to('agenciamarketing@dmcard.com.br', 'Loren')->subject('Aviso de cancelamento de pedido');
                    });

                }


            }



        }

        return $array;

    }

    public function cancelarPedido($request)
    {

        $idPedido = $request['token'];
        $motivo = $request['motivo'];


        $sql = $this->con->query("select p.idCompra, e.email as tipo, u.email, u.nome + ' '+u.sobrenome as nome from dmtrixII.pedidosExpirados e join PedidoDMTRIX p on p.idPedido = e.idPedido
        join usuariosDMTRIX u on u.idUsuario = p.idUsuario where e.idPedido = '$idPedido'");

        if(odbc_num_rows($sql) == 0){


            $idCompra = $this->con->fetch_array($this->con->query("select idCompra from PedidoDMTRIX  where idPedido = '$idPedido'"));
            $idCompra = $idCompra['idCompra'];

            $buscaCompra = $this->con->fetch_array($this->con->query("select COUNT(*) as num from PedidoDMTRIX  where idCompra = '$idCompra'"));
            $buscaCompraFinalizada = $this->con->fetch_array($this->con->query("select COUNT(*) as num from PedidoDMTRIX  where idCompra = '$idCompra' and status_pedido = 11"));
            $numItens = $buscaCompra['num'];
            $numItensFinalizado = $buscaCompraFinalizada['num'];
            $this->con->query("update PedidoDMTRIX set status_pedido = 11 where idPedido = '$idPedido'");


            if($numItens == 1)
            {

               $this->con->query("update ComprasDMTRIX set status_compra = 'Cancelado' where idCompra = '$idCompra'");

            }else {


                if ($numItens == $numItensFinalizado) {

                   $this->con->query("update ComprasDMTRIX set status_compra = 'Cancelado' where idCompra = '$idCompra'");

                }
            }
            
            $infos = $this->services->infoPedido($idPedido);
            $this->con->query("insert into dmtrixII.pedidosExpirados (idPedido, dataExpirado,email, status) values ('$idPedido',GETDATE(),1, 0)");
            $mensagem = 'Caro(a) '.$infos['solicitante'].' o material: '.$infos['Material'].' foi cancelado pelo seguinte motivo: "]'.$motivo.'". <br> Para mais informações entre em contato com a agência!';


            Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($infos) {
                $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                $m->to($infos['email'], $infos['solicitante'])->subject('Aviso de cancelamento de pedido');
               
            });


            if(odbc_error() == ''){
                
               $pedidoMaterial = $this->services->materialCompra($idPedido);
                $material = $pedidoMaterial['material'];

                $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido cancelado, item: '.$material, 'tipo' => 2];
                $this->historico->create($info);
                $info = ['idCompra' => $idCompra, 'texto'=> 'o Pedido: '.$idPedido.' foi cancelado, pelo usuario', 'tipo' => 2];
                $this->historico->historicoCompras($info);
                
                $class = 'bg-success text-center text-success';
                $msg = 'Cancelado com sucesso, um email foi enviado para o solicitante';

                $resp = ['class'=>$class, 'msg'=> $msg];
                return $msg = ['resp'=>'Cancelado com sucesso, um email foi enviado para o solicitante'];

            }else{

                $class = 'bg-danger text-center text-danger';
                $msg = 'Ocorreu um erro ao cancelar, tente novamente mais tarde';

                $resp = ['class'=>$class, 'msg'=> $msg];
                return $msg = ['resp'=>'Ocorreu um erro ao cancelar, tente novamente mais tarde'];

             

            }
            

        }else{

            $x = $this->con->fetch_array($sql);
            if($x['tipo'] == 0)
            {
                Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($x) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                    $m->to($x['email'], $x['nome'])->subject('Aviso de cancelamento de pedido');
                });
                

            }else{
                
                $class = 'bg-warning text-center text-warning';
                $msg = 'Pedido já cancelado';

                $resp = ['class'=>$class, 'msg'=> $msg];
                return  $msg = ['resp'=>'Pedido já cancelado'];
                
            }
            
        }
        

        


    }

    public function devolverPedido($request){
        $idPedido = $request['token'];
        $motivo = $request['motivo'];

        $this->con->query("update PedidoDMTRIX set status_pedido = 13 where idPedido = '$idPedido'");
        
        if(odbc_error() == ''){


            
            $pedidoInfo = $this->services->infoPedido($idPedido);
            $idCompra = $pedidoInfo['idCompra'];
            $sql1=  $this->con->fetch_array($this->con->query("select COUNT(*) as num from PedidoDMTRIX  where idCompra = '$idCompra'"));
            $sql2 = $this->con->fetch_array($this->con->query("select COUNT(*) as num from PedidoDMTRIX  where idCompra = '$idCompra' and status_pedido = 13"));

            if($sql1['num'] == $sql2['num']){

                $this->con->query("update ComprasDMTRIX set status_compra= 'Devolvido' where idCompra = '$idCompra' ");
            }

            $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido: '.$pedidoInfo['idCompra'].' '.$pedidoInfo['Material'].' foi devolvido para correção pelo seguinte motivo: '.$motivo, 'tipo'=> 6];
            $this->historico->create($info);

            $mensagem = 'Caro(a) '.$pedidoInfo['solicitante'].' seu pedido: '.$idCompra.' foi Recusado pela nossa equipe, pelo seguinte motivo: <b>'.$motivo.'</b> entre no <a href="http://dmcard.com.br/dmtrix">DMTRIX</a> acesse a opção "Gerenciar Compras" para
            atualizar a compra para que possamos melhor atende-lo!<p>Caso de duvídas entre em contato com a agência.</p>';

            Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($pedidoInfo) {
                $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                $m->to($pedidoInfo['email'], $pedidoInfo['solicitante'])->subject('Aviso de devolução de pedido');

            });

            $class = 'bg-success text-center text-success';
            $msg = 'Cancelado com sucesso, um email foi enviado para o solicitante';

            $resp = ['class'=>$class, 'msg'=> $msg];

            return $msg = ['resp'=>'Devolvido com sucesso, um email foi enviado para o solicitante'];
            
            
        }else{

            $class = 'bg-warning text-center text-warning';
            $msg = 'Pedido já cancelado';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return  $msg = ['resp'=>'Ocorreu um Erro'];


        }



    }
    
    public function situacaoCompra($idcompra)
    {
        
        $sql = $this->con->query("select (select status from dmtrixII.pedidosExpirados where idPedido = p.idPedido) as status, c.idCompra
from PedidoDMTRIX p join ComprasDMTRIX c on c.idCompra = p.idCompra where c.idCompra = '$idcompra' order by c.idCompra");
        $x=0;
        $cancel = 0;
        $rows = odbc_num_rows($sql);
        while($rs = $this->con->fetch_array($sql))
        {
            $status = $rs['status'];

            if($status == '' || $status == 1){

                $x++;
            }else{

                $cancel++;
            }


        }

        if($x == $rows)
        {
            return 'Ativo';

        }else if($cancel == $rows){

            return 'Compra cancelada';

        }
        else{

            return 'Ativo, mas com pedidos cancelados';

        }
        
    }

    public function emailPedidosCancelados()
    {



    }


}