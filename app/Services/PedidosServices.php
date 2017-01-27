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

            $sql =  $this->con->query("select m.valor,p.idCompra from materiaisDMTRIX m join PedidoDMTRIX p on p.idMaterial = m.idMaterial  where p.idPedido = '$idPedidos[$i]'");

            $material = $this->con->fetch_array($sql);
            $custoUnitarioReal = $material['valor'];
            $idCompra =  $material['idCompra'];

            if($custoUnitarioReal != $custoUnitario[$i]){

                $observacao[$i] .= ' - Custo unitario neste pedido: '.$custoUnitario[$i];

            }

            if(isset($request['tipoAprovacao']))
            {

                $status = 25;

            }else{


                $status = 9;

            }
           $this->con->query("update PedidoDMTRIX set largura = '$largura[$i]', altura='$altura[$i]', quantidade='$quantidade[$i]',observacao='$observacao[$i]', status_pedido = '$status', valorProduto='$custoTotal[$i]'
            ,valorTotal='$total' where idPedido = '$idPedidos[$i]'");

                $info = ['idPedido' => $idPedidos[$i], 'texto'=> 'Valor do pedido foi atualizado', 'tipo' => 1];
                $this->historico->create($info);


        }


        if(odbc_error() == '')
        {

            $info = ['idCompra' => $idCompra, 'texto'=> 'Valor da compra foi atualizado', 'tipo' => 1];
            $this->historico->historicoCompras($info);

            $this->con->query("update ComprasDMTRIX set status_compra = 'aprovacoes', dataOrcAtualizado = getdate() where idCompra = '$idCompra'");


            $class = 'bg-success text-center text-success';
            $msg = 'Atualizado com sucesso';
            
            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;
            
        }else{

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

           $sql =  $this->con->query("select idPedido from PedidoDMTRIX where idCompra = '$idCompra[$i]'");

            if(odbc_num_rows($sql) > 0) //se estiver vazio a consulta sai da instrução
            {
                if(odbc_num_rows($sql) > 1)//se houver mais de uma linha gera um array dinamico
                {

                    while($rs = $this->con->fetch_array($sql))
                    {
                        $idPedido = $rs['idPedido'];
                        $tarefas = $this->con->query("select idPedido from tarefasDMTRIX where idPedido = '$idPedido'");
                        if(odbc_num_rows($tarefas) > 0)
                        {

                            $class = 'bg-warning text-center text-warning';
                            $msg = 'Já existe uma tarefa delegada para esta compra';

                            $resp = ['class'=>$class, 'msg'=> $msg];
                            return $resp;

                        }else{

                            $usuario = $this->con->fetch_array($this->con->query("select nome+' '+sobrenome as nome from usuariosDMTRIX where idUsuario = '$criacao[$i]'"));
                            $usuario = $usuario['nome'];

                            $this->con->query("update ComprasDMTRIX set prioridade = '$prioridade[$i]', status_compra = 'criacao' where idCompra = '$idCompra[$i]'");
                            $this->con->query("update PedidoDMTRIX set status_pedido = '5', dataIdeal = '$dataIdeal[$i]' where idPedido = '$idPedido'");
                            $this->con->query("insert into tarefasDMTRIX(idUsuario,idPedido,ativo,iniciado, dataDelegado) values('$criacao[$i]','$idPedido','nao',0, getdate()))");
                            $info = ['idPedido' => $idPedido, 'texto'=> 'Foi criado uma tarefa e o pedido foi delegado para usuario: '.$usuario.'.', 'tipo' => 3];
                            $this->historico->create($info);

                        }

                    }




                }else{ // se não apenas um array simples

                    $rs = $this->con->fetch_array($sql);
                    $idPedido = $rs['idPedido'];

                    $tarefas = $this->con->query("select idPedido from tarefasDMTRIX where idPedido = '$idPedido'");
                    if(odbc_num_rows($tarefas) > 0){



                        $class = 'bg-warning text-center text-warning';
                        $msg = 'Já existe uma tarefa delegada para esta compra';

                        $resp = ['class'=>$class, 'msg'=> $msg];
                        return $resp;

                    }else{

                        $this->con->query("update ComprasDMTRIX set prioridade = '$prioridade[$i]', status_compra = 'criacao' where idCompra = '$idCompra[$i]'");
                        $this->con->query("update PedidoDMTRIX set status_pedido = 5, dataIdeal = '$dataIdeal[$i]' where idPedido = '$idPedido'");
                        $this->con->query("insert into tarefasDMTRIX(idUsuario,idPedido,ativo,iniciado) values('$criacao[$i]','$idPedido','nao',0)");

                        $info = ['idPedido' => $idPedido, 'texto'=> 'Foi criado uma tarefa e o pedido foi delegado para usuario: '.$criacao[$i].'.', 'tipo' => 3];
                        $this->historico->create($info);

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
                        
                        $dataIdeal = new \DateTime($dataIdeal);
                        $dataIdeal = $dataIdeal->format('d/m/y');

                        if($status_pedido == 3 or $status_pedido == 5 or $status_pedido == 7 or $status_pedido == 6)
                        {

                            $tarefas = $this->con->query("select idPedido from tarefasDMTRIX where idPedido = '$idPedido'");
                            if (odbc_num_rows($tarefas) > 0) {

                                $usuario = $this->con->fetch_array($this->con->query("select nome+' '+sobrenome as nome from usuariosDMTRIX where idUsuario = '$criacao'"));
                                $usuario = $usuario['nome'];

                                $this->con->query("update ComprasDMTRIX set prioridade = '$prioridade', status_compra = 'criacao' where idCompra = '$idCompra'");
                                $this->con->query("update PedidoDMTRIX set status_pedido = '5', dataIdeal = '$dataIdeal' where idPedido = '$idPedido'");
                                $this->con->query("update tarefasDMTRIX set idUsuario = '$criacao', idPedido = '$idPedido',ativo = 'nao', iniciado = 0, dataDelegado = GETDATE() where idPedido = '$idPedido'");
                                $info = ['idPedido' => $idPedido, 'texto' => 'Foi Redelegado uma tarefa  para o usuario: ' . $usuario . ', material: ' . $material, 'tipo' => 3];
                                $this->historico->create($info);

                            } else{

                                $usuario = $this->con->fetch_array($this->con->query("select nome+' '+sobrenome as nome from usuariosDMTRIX where idUsuario = '$criacao'"));
                                $usuario = $usuario['nome'];


                                $this->con->query("update PedidoDMTRIX set status_pedido = 5, dataIdeal = '$dataIdeal' where idPedido = '$idPedido'");
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

        $sql = $this->con->query("  select idPedido,idCompra,status_pedido,u.email,u.nome+u.sobrenome as solicitante from 
  PedidoDMTRIX p join usuariosDMTRIX u on u.idUsuario = p.idUsuario where status_pedido != 12  order by p.idCompra");

        $array = array();
        while($rs = $this->con->fetch_array($sql))
        {
            $idPedido = $rs['idPedido'];
            $idCompra = $rs['idCompra'];
            $status =  $rs['status_pedido'];
            $email = $rs['email'];
            $nome = $rs['solicitante'];

            $pesquisa = $this->con->fetch_array($this->con->query("select top 1 dataObs from dmtrixII.historicoObs where idPedido = '$idPedido' order by dataObs desc"));
            $pesquisa = new \DateTime($pesquisa['dataObs']);
            $date = new \DateTime();
            $diff = $date->diff($pesquisa);
            if($diff->days >= 30)
            {
                $situacao = 'Pedido expirado';

                $verifica = $this->con->query("select * from dmtrixII.pedidosExpirados where idPedido = '$idPedido'");

                if(odbc_num_rows($verifica) == 0){


                    $this->con->query("insert into dmtrixII.pedidosExpirados (idPedido, dataExpirado,email, status) values ('$idPedido',GETDATE(),0, 0)");
                    $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido cancelado', 'tipo' => 1];
                    $this->historico->create($info);
                    $info = ['idCompra' => $idCompra, 'texto'=> 'o Pedido: '.$idPedido.' foi cancelado', 'tipo' => 1];
                    $this->historico->historicoCompras($info);

                }

            }else if($diff->days >= 25){

                $dias = 30 - $diff->days;
                $situacao = 'Expira em '.$dias.' dias';

            }else if($diff->days == 29)
            {

                $situacao = 'Expira em 1 dia';


            }else
            {
                $situacao = 'ok';


            }


            $x = ['dias'=> $diff->days, 'idPedido' => $idPedido, 'idCompra'=> $idCompra, 'situacao' => $situacao, 'status'=>$status,'email'=>$email, 'nome' => $nome];
            array_push($array, $x);

            if($x['situacao'] != 'ok' and $diff->days < 30 and $x['status'] == 3 or $x['status'] == 9 )
            {

                Mail::send('emails.cancelamento', compact('x'), function ($m) use ($x) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                    $m->to($x['email'], $x['nome'])->subject('Aviso de cancelamento de pedido');
                });

            }else if($x['situacao'] != 'ok' and $diff->days < 30 and  $x['status'] == 5)
            {

                $tarefa = $this->con->fetch_array($this->con->query("select distinct t.idUsuario, idCompra, u.email from tarefasDMTRIX t join dmtrixII.PedidoDMTRIX p on p.idPedido = t.idPedido join usuariosDMTRIX u on u.idUsuario = t.idUsuario
   where p.status_pedido = 5 and p.idPedido = '$idPedido'"));

                Mail::send('emails.cancelamento', compact('x'), function ($m) use ($tarefa) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                    $m->to($tarefa['email'], $tarefa['email'])->subject('Aviso de cancelamento de pedido');
                });

            }else if($x['situacao'] != 'ok'){

                Mail::send('emails.cancelamento', compact('x'), function ($m) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                    $m->to('agenciamarketing@dmcard.com.br', 'Loren')->subject('Aviso de cancelamento de pedido');
                });


            }
            
        }

        return $array;

    }

    public function cancelarPedido($idPedido)
    {


        $sql = $this->con->query("select p.idCompra, e.email as tipo, u.email, u.nome + ' '+u.sobrenome as nome from dmtrixII.pedidosExpirados e join PedidoDMTRIX p on p.idPedido = e.idPedido
        join usuariosDMTRIX u on u.idUsuario = p.idUsuario where e.idPedido = '$idPedido'");

        if(odbc_num_rows($sql) == 0){

            $sql = $this->con->fetch_array($sql);
            $idCompra = $sql['idCompra'];
            $buscaCompra = $this->con->query("select * from PedidoDMTRIX p join dmtrixII.pedidosExpirados e on e.idPedido = p.idPedido  where p.idCompra = '$idCompra'");

            $count = odbc_num_rows($buscaCompra);
            $x=0;
            while($RsbuscaCompra = odbc_fetch_array($buscaCompra))
            {
                $status = $RsbuscaCompra['status_pedido'];
                if($status == 11)
                {
                    $x++;
                }
            }

            if($count == $x)
            {

                $this->con->query("update ComprasDMTRIX set status_compra = 'Cancelado' where idCompra = '$idCompra'");

            }


            


            Mail::send('emails.avisoCancelado', compact('x'), function ($m) use ($x) {
                $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                $m->to($x['email'], $x['nome'])->subject('Aviso de cancelamento de pedido');
               
            });
            $this->con->query("insert into dmtrixII.pedidosExpirados (idPedido, dataExpirado,email, status) values ('$idPedido',GETDATE(),1, 0)");
            

        }else{

            $x = $this->con->fetch_array($sql);
            if($x['tipo'] == 0)
            {
                Mail::send('emails.avisoCancelado', compact('x'), function ($m) use ($x) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                    $m->to($x['email'], $x['nome'])->subject('Aviso de cancelamento de pedido');
                });
                

            }else{
                
                return 'Pedido já cancelado';
                
            }
            
        }
        
        $idCompra = $x['idCompra'];
        
        if(odbc_error() == ''){

            $info = ['idPedido' => $idPedido, 'texto'=> 'Pedido cancelado', 'tipo' => 2];
            $this->historico->create($info);
            $info = ['idCompra' => $idCompra, 'texto'=> 'o Pedido: '.$idPedido.' foi cancelado, pelo usuario', 'tipo' => 1];
            $this->historico->historicoCompras($info);
            
            
            return 'Cancelado com sucesso, um email foi enviado para o solcitante';
            
        }else{
            
            return 'Ocorreu um erro ao cancelar, tente novamente mais tarde';
            
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