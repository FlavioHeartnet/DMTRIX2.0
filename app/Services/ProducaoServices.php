<?php
namespace App\Services;



use App\Http\Controllers\HistoricoController;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class ProducaoServices
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

    public function filaIndividual($idUsuario){

       $sql = $this->con->query("  select distinct count(*)as pedidos, ut.criacao, p.idCompra, c.titulo, ut.email, l.numeroLoja+' '+ l.nomeLoja as loja, u.nome+' '+u.sobrenome as solicitante,ut.foto,c.status_compra  from tarefasDMTRIX t  
  inner join PedidoDMTRIX p on p.idPedido = t.idPedido join ComprasDMTRIX c on c.idCompra = p.idCompra join lojasDMTRIX l on l.idLoja = p.idLoja
  join usuariosDMTRIX u on u.idUsuario = c.idUsuario,
  (select nome+' '+sobrenome as criacao, email,foto from usuariosDMTRIX  where idUsuario = '$idUsuario' ) as ut 
  where t.idUsuario = '$idUsuario' and c.status_compra = 'criacao'
  group by ut.criacao, p.idCompra, c.titulo,ut.email, l.nomeLoja,l.numeroLoja,u.nome,u.sobrenome,ut.foto,c.status_compra order by p.idCompra desc");

        if(odbc_num_rows($sql) > 0)
        {
            $total = 0;
            $aprovados = 0;
            $response = array();
            while($rs = $this->con->fetch_array($sql)) {

                $idCompra = $rs['idCompra'];

                    //detalhes de cada Compra
                    $itensCompra = $this->con->query("select  m.material, p.observacao, u.nome+' '+u.sobrenome as Solicitante, p.dataArtePostada,t.dataDelegado, p.status_pedido, re.MotivoArte, p.idPedido, p.fotoArte, p.quantidade,p.custeio
,p.altura,p.largura,m.formaCalculo
   from tarefasDMTRIX t  
  inner join PedidoDMTRIX p on p.idPedido = t.idPedido 
  inner join materiaisDMTRIX m on p.idMaterial = m.idMaterial
  join usuariosDMTRIX u on u.idUsuario = p.idUsuario
  left join ControleReprovacoesDMTRIX re on re.idPedido = p.idPedido
  where  p.idCompra = '$idCompra' 
  order by p.status_pedido");
                    $texto = array();
                    while($is = $this->con->fetch_array($itensCompra)){

                        $material = $is['material'];
                        $observacao = $is['observacao'];
                        switch ($is['status_pedido'])
                        {
                            case 6: $situacao = 'Aprovado';
                                break;
                            case 7: $situacao = 'Reprovado, motivo: '.$is['MotivoArte'];
                                break;
                            case 10: $situacao = "Pendente aprovação de arte";
                                break;
                            case 5: $situacao = "Fila de criação";
                                break;
                            default: $situacao = "Item não esta em fase de produção";
                        }
                        
                        $idPedido = $is['idPedido'];
                        $dataHist = $this->con->fetch_array($this->con->query("select top 1 dataObs from dmtrixII.historicoObs where tipo = 3 and idPedido = '$idPedido' order by dataObs desc"));
                        
                        if($is['status_pedido'] == 101){
                            
                            $status = 'revisao';
                            
                            
                        }elseif ($is['status_pedido'] == 10)
                        {
                            
                            $status = 'aprovacao';
                            
                        }elseif ($is['status_pedido'] == 6)
                        {
                            $status = 'aprovado';
                            
                        }elseif($is['status_pedido'] == 5 or $is['status_pedido'] == 7){
                            
                            $status = 'criacao';
                            
                        }else{

                            $status = '';

                        }

                        $altura = $is['altura'];
                        $largura = $is['largura'];
                        $calculo = $is['formaCalculo'];

                        if($calculo == 2)
                        {
                            $descricao = 'Largura: '.$largura.'cm - Altura: '.$altura.'cm';

                        }else if($calculo == 2){

                            $descricao = 'Produto Free';

                        }else{

                            $descricao = 'Produto com medida padão';
                        }

                        if($status != '') { // caso o pedido esteja em um status diferente da criação ele não é exibido
                            array_push($texto, ["material" => $material, 'observacao' => $observacao, 'situacao' => $situacao, 'dataObs' => $this->services->formatarData($dataHist['dataObs']), 'idPedido' => $idPedido, 'status' => $status, 'foto' => $is['fotoArte'],
                                'quantidade' => $is['quantidade'], 'custeio' => $is['custeio'], 'descricao' => $descricao]);
                        }
                        

                    }
                    //gerar o grafico dos pedidos baseado em seus status
                $graph = $this->con->fetch_array($this->con->query("select (select count(*) as fila from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = 6) as aprovados,
   (select count(*) as fila from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = 5) as fila
   ,(select count(*) as fila from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = 7) as reprovados
   ,(select count(*) as fila from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = 10) as pendente
    ,(select count(*) as fila from PedidoDMTRIX where idCompra = '$idCompra' and status_pedido = 101) as revisao
    ,(select count(*) as fila from PedidoDMTRIX where idCompra = '$idCompra' ) as total"));

                $total += $graph['aprovados'] + $graph['fila'] + $graph['reprovados'] + $graph['pendente']+ $graph['revisao'];
                $aprovados += $graph['aprovados'];
                
                if( $graph['fila'] > 0 or $graph['reprovados'] > 0){
                    
                    $fila = 1;
                    
                }else{
                    
                    
                    $fila = 0;
                }
                array_push($response, [

                    'pedidos' => $rs['pedidos'],
                    'criacao' => $rs['criacao'],
                    'idCompra' => $rs['idCompra'],
                    'titulo' => $rs['titulo'],
                    'foto' => $rs['foto'],
                    'email' => $rs['email'],
                    'loja' => $rs['loja'],
                    'aprovados' => $graph['aprovados'],
                    'fila' => $graph['fila'],
                    'reprovados' => $graph['reprovados'],
                    'pendente' => $graph['pendente'],
                    'detalhes' => $texto,
                    'revisao' =>$graph['revisao'],
                    'solicitante' =>$rs['solicitante'],
                    'filaEnviar' => $fila //esta variavel define se o botão para enviar artes irá aparecer, se for 1, o botão de enviar arte aparecerá.

                ]);


            }

            return $response;

        }


    }

    public function filaCriacao()
    {


            $sql = $this->con->query("select u.nome +' '+u.sobrenome as criacao, u.idUsuario, u.email, 
  (select count(*)as pedidos from tarefasDMTRIX t  
  inner join PedidoDMTRIX p on p.idPedido = t.idPedido join ComprasDMTRIX c on c.idCompra = p.idCompra,
  (select nome+' '+sobrenome as criacao, email from usuariosDMTRIX  where idUsuario = u.idUsuario ) as ut 
  where t.idUsuario = u.idUsuario and p.status_pedido = 5 ) as pedidos,
  (  select COUNT(*)as aprovados from PedidoDMTRIX p 
  join tarefasDMTRIX t on t.idPedido = p.idPedido where p.status_pedido = 6 and t.idUsuario = u.idUsuario) as aprovados,
  (  select COUNT(*)as aprovados from PedidoDMTRIX p 
  join tarefasDMTRIX t on t.idPedido = p.idPedido where p.status_pedido = 7 and t.idUsuario = u.idUsuario) as reprovados,
  (  select COUNT(*)as aprovados from PedidoDMTRIX p 
  join tarefasDMTRIX t on t.idPedido = p.idPedido where p.status_pedido = 10 and t.idUsuario = u.idUsuario) as pendente,
  (  select COUNT(*)as aprovados from PedidoDMTRIX p 
  join tarefasDMTRIX t on t.idPedido = p.idPedido where p.status_pedido = 101 and t.idUsuario = u.idUsuario) as revisao
   from usuariosDMTRIX u join dmtrixII.criacaoDMTRIX c on c.idUsuario = u.idUsuario order by u.nome");

        $geral = $this->con->fetch_array($this->con->query("select (select COUNT(*) as criacao from PedidoDMTRIX where status_pedido = 5) as criacao
  ,(select COUNT(*) as aprovados from PedidoDMTRIX where status_pedido = 6 ) as aprovados,
  (select COUNT(*) as reprovados from PedidoDMTRIX where status_pedido = 7) as reprovados,
  (select COUNT(*) as pendente from PedidoDMTRIX where status_pedido = 10) as pendente
  ,(select count(*) as fila from PedidoDMTRIX where status_pedido = 101) as revisao
  ,(select count(*) as fila from PedidoDMTRIX where status_pedido = 9) as aprovacaoOrc
  ,(select count(*) as fila from PedidoDMTRIX where status_pedido = 2) as atualizacao"));
        
        $total = $geral['reprovados']+$geral['criacao']+$geral['aprovados']+$geral['pendente']+$geral['revisao'];
        $aprovados = $geral['aprovados'];
        
        $porcentagem = ($aprovados/$total) * 100;



        if(odbc_num_rows($sql) > 0)
        {
            $response = array();
            while($rs = $this->con->fetch_array($sql))
            {

                array_push($response, [

                    'pedidos'=>$rs['pedidos'],
                    'criacao'=>$rs['criacao'],
                    'idUsuario'=>$rs['idUsuario'],
                    'email'=>$rs['email'],
                    'aprovados'=>$rs['aprovados'],
                    'reprovados'=>$rs['reprovados'],
                    'pendente'=>$rs['pendente'],
                    'revisao' => $rs['revisao'],
                    'aprovadosGeral' => $geral['aprovados'],
                    'criacaoGeral'=> $geral['criacao'],
                    'reprovadosGeral' => $geral['reprovados'],
                    'total' => $total,
                    'revisaoGeral'=>$geral['revisao'],
                    'porcentagem' => round($porcentagem,2),
                    'pendenteGeral' => $geral['pendente'],
                    'atualizacaoGeral' => $geral['atualizacao'],
                    'aprovacaoOrc' => $geral['aprovacaoOrc'],

                ]);


            }

            return $response;

        }else{

            return json_encode('Não há tarefas');

        }


    }

    public function revisao($idPedido, $nomeArquivo){

        $this->con->query("update PedidoDMTRIX set status_pedido = 101, fotoArte='$nomeArquivo', dataArtePostada= getdate() where idPedido = '$idPedido'");

        $infos = ['tipo' => 3, 'idPedido' => $idPedido, 'texto'=>'Pedido enviado para revisão'];
        $this->historico->create($infos);

        
       if(odbc_error() == '')
       {

           return 'sucesso';

       }else{

           return odbc_errormsg();
       }

    }

    public function aprovar($idPedido){

        $idCompra = $this->con->fetch_array($this->con->query("select idCompra from PedidoDMTRIX where idPedido = '$idPedido'"));
        $idCompra = $idCompra['idCompra'];

        $this->con->query("update [marketing].[dbo].[PedidoDMTRIX] set status_pedido = '6'  where idPedido = '$idPedido'");
        $this->con->query("update [marketing].[dbo].[ControleAprovacoesDMTRIX] set idCompra='$idCompra', idPedido='$idPedido', data_aprovado_arte=getdate() where idPedido = '$idPedido'");
        $this->con->query("update [marketing].[dbo].[tarefasDMTRIX] set tempoFinal = getdate() , ativo = 'nao'  where idPedido = '$idPedido'");
        $infos = ['tipo' => 3, 'idPedido' => $idPedido, 'texto'=>'A arte do pedido foi aprovada.'];
        $this->historico->create($infos);

        if(odbc_error() == ''){
            
            $class = 'bg-sucess text-center text-sucess';
            $msg = 'Aprovado com sucesso';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }else{

             ['resp'=>'falha: '.odbc_errormsg()];
            $class = 'bg-danger text-center text-danger';
            $msg = 'Falha: '.odbc_errormsg();

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }


    }

    public function aprovarArte($idPedido){
        $verifica = $this->con->fetch_array($this->con->query("select status_pedido, idCompra from PedidoDMTRIX where idPedido = '$idPedido'"));

        $idCompra = $verifica['idCompra'];
        if ($verifica['status_pedido'] != 10)
        {

       $this->con->query("update [marketing].[dbo].[PedidoDMTRIX] set status_pedido = 10  where idPedido = '$idPedido'");
        $itens = $this->con->fetch_array($this->con->query("select u.nome, u.email,m.material from usuariosDMTRIX u join PedidoDMTRIX p on p.idUsuario = u.idUsuario join materiaisDMTRIX m on m.idMaterial = p.idMaterial where p.idPedido = '$idPedido'"));

        $material = $itens['material'];
        $nome = $itens['nome'];
        $email = $itens['email'];
        $mensagem = utf8_decode("Caro(a) " . $nome . " nossa equipe postou uma arte para a compra que você solicitou. Para visualiza-la basta entrar no E-commerce DMTRIX e ir em Aprovar/Reprovar Artes, o produto é: $material, Compra: $idCompra ");


        if(odbc_error() == '') {

            $itens = ['msg' => $mensagem, 'email' => $email, 'nome' => $nome];

            $this->con->query("update PedidoDMTRIX set dataArtePostada = getdate() where idPedido = '$idPedido'");
            $infos = ['tipo' => 3, 'idPedido' => $idPedido, 'texto'=>'Pedido enviado para aprovação do solicitante'];
            $this->historico->create($infos);


            Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($itens) {
                $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                $m->to($itens['email'], $itens['nome'])->subject('Arte foi disponibilizada para aprovação');
            });

            $class = 'bg-success text-center text-success';
            $msg = 'Enviado para aprovação do solicitante';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }else{

            $mensagem = utf8_decode("Ocorreu um erro ao aprovar a arte do pedido: ".$idPedido.", ".odbc_errormsg());

            Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($itens) {
                $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                $m->to('flavio.barros@dmcard.com.br', 'Flavio')->subject('Erro na aprovação de arte');
            });

            
            $class = 'bg-warning text-center text-warning';
            $msg = 'Ocorreu um erro ao salvar, é possivel que o Token digitado não exista!';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }

    }else
        {

            $class = 'bg-warning text-center text-warning';
            $msg = 'Pedido ja foi atualizado';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;
        }

        
    }

    public function reprovarArte($idPedido, $motivo)
    {

        $verifica = $this->con->fetch_array($this->con->query("select status_pedido, idCompra from PedidoDMTRIX where idPedido = '$idPedido'"));
        $idCompra = $verifica['idCompra'];
        if ($verifica['status_pedido'] != 7 )
        {
            $controle = $this->con->query("select * from ControleReprovacoesDMTRIX where idPedido = '$idPedido'");
            if(odbc_num_rows($controle) == 0){

            $this->con->query("insert into ControleReprovacoesDMTRIX (idPedido,MotivoArte,data_reprovado_arte, idCompra) values ('$idPedido','$motivo',GETDATE(), '$idCompra')");

            }else {
                $this->con->query(" update ControleReprovacoesDMTRIX set MotivoArte = '$motivo', data_reprovado_arte=getdate() where idPedido = '$idPedido'");
            }


            $this->con->query("update [marketing].[dbo].[PedidoDMTRIX] set status_pedido = 7  where idPedido = '$idPedido'");

            $itens = $this->con->fetch_array($this->con->query(" select u.nome, u.email, m.material from usuariosDMTRIX u join tarefasDMTRIX t on t.idUsuario = u.idUsuario 
      join PedidoDMTRIX p on p.idPedido = t.idPedido join materiaisDMTRIX m on m.idMaterial = p.idMaterial
      where p.idPedido = '$idPedido'"));

            $material = $itens['material'];
            $nome = $itens['nome'];
            $email = $itens['email'];
            $mensagem = ("Caro(a) " . $nome . " , a arte que você enviou foi reprovada, pelo seguinte motivo: <br> " . $motivo . ", o produto é: $material, Compra: $idCompra ");

            if (odbc_error() == '') {

                $itens = ['msg' => $mensagem, 'email' => $email, 'nome' => $nome];

                $this->con->query("update PedidoDMTRIX set dataArtePostada = getdate() where idPedido = '$idPedido'");
                $infos = ['tipo' => 3, 'idPedido' => $idPedido, 'texto' => 'Pedido foi reprovado e enviado para criação'];
                $this->historico->create($infos);


                Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($itens) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->cc('flavio.barros@dmcard.com.br', 'Flavio');
                    $m->to($itens['email'], $itens['nome'])->subject('Arte foi disponibilizada para aprovação');
                });

                $class = 'bg-success text-center text-success';
                $msg = 'Reprovado com sucesso';

                $resp = ['class'=>$class, 'msg'=> $msg];
                return $resp;

            } else {

                $mensagem = utf8_decode("Ocorreu um erro ao aprovar a arte do pedido: " . $idPedido . ", " . odbc_errormsg());

                Mail::send('emails.aprovacaoArte', compact('mensagem'), function ($m) use ($itens) {
                    $m->from('faqdmtrade@dmcard.com.br', 'DMTRIX');
                    $m->to('flavio.barros@dmcard.com.br', 'Flavio')->subject('Erro na aprovação de arte');
                });


                $class = 'bg-danger text-center text-danger';
                $msg = 'Ocorreu um erro ao salvar, é possivel que o Token digitado não exista!';

                $resp = ['class'=>$class, 'msg'=> $msg];
                return $resp;

            }
        }else {

            $class = 'bg-warning text-center text-warning';
            $msg = 'Pedido ja atualizado';

            $resp = ['class'=>$class, 'msg'=> $msg];
            return $resp;

        }

    }

    public function ArtesEmRevisao()
    {

        $sql = $this->con->query("select u.nome+' '+ u.sobrenome as criacao, l.numeroLoja+' - '+l.nomeLoja as loja,m.material, p.observacao, p.idPedido,p.idCompra,p.quantidade,p.custeio,p.segmento, c.dataCompra, c.dataOrcAtualizado, p.dataArtePostada, p.fotoArte from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial
  join ComprasDMTRIX c on c.idCompra = p.idCompra join lojasDMTRIX l on l.numeroLoja = c.idLoja
  join tarefasDMTRIX t on t.idPedido = p.idPedido join usuariosDMTRIX u on u.idUsuario = t.idUsuario
  where status_pedido = 101");

        $response = array();
        while($rs = $this->con->fetch_array($sql))
        {


            array_push($response, [
                'material' => $rs['material'],
                'observacao' => $rs['observacao'],
                'idPedido' => $rs['idPedido'],
                'idCompra' => $rs['idCompra'],
                'quantidade' => $rs['quantidade'],
                'custeio' => $rs['custeio'],
                'segmento' => $rs['segmento'],
                'dataCompra' => $this->services->formatarData($rs['dataCompra']),
                'dataOrcAtualizado' => $this->services->formatarData($rs['dataOrcAtualizado']),
                'dataArtePostada' => $this->services->formatarData($rs['dataArtePostada']),
                'fotoArte' => $rs['fotoArte'],
                'loja' => $rs['loja'],
                'criacao' =>$rs['criacao'] 

            ]);


        }

        return $response;


    }

    public function ArtesEmAprovacao()
    {

        $sql = $this->con->query(" select COUNT(*) as item, c.idCompra, c.valorTotal, l.numeroLoja+' - '+l.nomeLoja as loja,u.nome+' '+ u.sobrenome as criacao  from ComprasDMTRIX c join PedidoDMTRIX p on p.idCompra = c.idCompra
                                 join lojasDMTRIX l on l.numeroLoja = c.idLoja 
                                 join tarefasDMTRIX t on t.idPedido = p.idPedido
                                 join usuariosDMTRIX u on u.idUsuario = t.idUsuario where p.status_pedido =10 or p.status_pedido = 6 or p.status_pedido = 7
                                 group by c.idCompra, c.valorTotal, l.numeroLoja, l.nomeLoja,u.nome, u.sobrenome order by c.idCompra desc");

        $response = array();
        while($rs = $this->con->fetch_array($sql))
        {

            $idCompra = $rs['idCompra'];

            $pedidos = $this->con->query("select u.nome+' '+ u.sobrenome as criacao,m.material, p.observacao, p.idPedido,p.idCompra,p.quantidade, p.fotoArte,p.status_pedido from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial
  join ComprasDMTRIX c on c.idCompra = p.idCompra join lojasDMTRIX l on l.numeroLoja = c.idLoja
  join tarefasDMTRIX t on t.idPedido = p.idPedido join usuariosDMTRIX u on u.idUsuario = t.idUsuario
  where p.idCompra = '$idCompra'");

            $subResponse = array();
            while($is = $this->con->fetch_array($pedidos))
            {

                $status = $is['status_pedido'];

                switch ($status)
                {
                    case 10: $situacao = 'Aguardando';
                        break;
                    case 7: $situacao = 'Reprovado';
                        break;
                    case 6: $situacao = 'Aprovado';
                        break;
                    case 8: $situacao = 'Fornecedor';
                        break;
                    default: $situacao = '';

                }
                
                if($situacao != '') {

                    array_push($subResponse, [
                        'criacao' => $is['criacao'],
                        'material' => $is['material'],
                        'observacao' => $is['observacao'],
                        'idPedido' => $is['idPedido'],
                        'quantidade' => $is['quantidade'],
                        'fotoArte' => $is['fotoArte'],
                        'status' => $situacao,

                    ]);
                }

            }

            array_push($response, [
                'item' => $rs['item'],
                'idCompra' => $rs['idCompra'],
                'valorTotal' => $rs['valorTotal'],
                'loja' => $rs['loja'],
                'criacao' =>$rs['criacao'],
                'pedidos' => $subResponse //array com os pedidos individuais

            ]);


        }

        return $response;

    }



}