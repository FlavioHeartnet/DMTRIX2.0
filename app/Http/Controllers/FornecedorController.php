<?php

namespace App\Http\Controllers;

use App\Services\FornecedorServices;
use App\Services\Services;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class FornecedorController extends Controller
{

    private $con;
    private $fornecedor;
    private $service;

    public function __construct(FornecedorServices $fornecedor, Services $services)
    {
        $this->con = new \config();
        $this->fornecedor = $fornecedor;
        $this->service = $services;

    }

    public function index()
    {
        return view('fornecedores.cadFornecedor');
    }

    public function store(Request $request)
    {

        $rs = $request->all();
        $nome = $rs['nome'];
        $razao = $rs['razao'];
        $cnpj = $rs['cnpj'];
        $conta = $rs['conta'];
        $agencia = $rs['agencia'];
        $cep = $rs['cep'];
        $telefone = $rs['tel'];
        $endereco = $rs['endereco']." ".$rs['complemento'];
        $email = $rs['email'];
        $ie = $rs['ie'];
        $im = $rs['im'];
        $estado = $rs['estado'];
        $cidade = $rs['cidade'];
        $site = $rs['site'];
        $complemento = $rs['complemento'];
        $banco = $rs['banco'];


        //foto

        if($request->hasFile('foto')) {

            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = $nome;
            $this->fornecedor->createFile($data);
            $nomeFoto = trim($nome) . '.' . $data['extension'];
        }

        //validar

       $validador = odbc_num_rows($this->con->query("select cnpj from dmtrixII.fornecedores where cnpj = '$cnpj'"));

        if($validador == 0) {

            $this->con->query("insert into dmtrixII.fornecedores (razao,cnpj,conta,agencia,cep,telefone,endereco,estado,email,inscricaoEstatudal,cidade,fantasia,dataCadastramento,site,inscricaoMunicipal,banco, complemento,foto) values
('$razao','$cnpj','$conta','$agencia','$cep','$telefone','$endereco','$estado','$email','$ie','$cidade','$nome',GETDATE(),'$site','$im', '$banco','$complemento', '$nomeFoto')");

        }else{


            $msg = 'Fornecedor ja cadastrado!';
            $class = 'bg-warning text-center text-warning';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));

        }
        
        if(odbc_error() == ''){
            
            $msg = 'Cadastrado com sucesso!';
            $class = 'bg-success text-center text-success';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));
            
            
        }else{

            $msg = 'Falha, tente novamente!';
            $class = 'bg-danger text-center text-danger';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));
            
        }

        


    }
    
    public function consultar($id)
    {

            $sql = $this->con->query("select id,[fantasia],[cnpj],[conta],[agencia],[cep],[telefone],[endereco],[email],[inscricaoEstatudal],[inscricaoMunicipal],[razao],[estado],[foto],[cidade],[site],[dataCadastramento], [banco],[complemento] from dmtrixII.fornecedores where id = '$id'");

            if(odbc_num_rows($sql) == 0){

                return 'Fornecedor não encontrado';
            }else {



                return $this->con->fetch_array($sql);
            }

        
    }

    public function show()
    {
       $sql= $this->con->query("select id,[fantasia],[telefone],[endereco],[email],[site], foto from dmtrixII.fornecedores");
        $array = array();
        while($rs= $this->con->fetch_array($sql)){

            $resp = ['id' => $rs['id'],
                    'fantasia'=> $rs['fantasia'],
                    'telefone' => $rs['telefone'],
                    'endereco' => $rs['endereco'],
                    'email' => $rs['email'],
                    'site' => $rs['site'],
                    'foto' => $rs['foto']];

            array_push($array,$resp);


        }

        return $array;


    }


    public function edit()
    {
        return view('fornecedores.editFornecedor');
    }


    public function update(Request $request)
    {
       
         $rs = $request->all();
        
         $id= $rs['token'];
        $nome = $rs['nome'];
        $razao = $rs['razao'];
        $cnpj = $rs['cnpj'];
        $conta = $rs['conta'];
        $agencia = $rs['agencia'];
        $cep = $rs['cep'];
        $telefone = $rs['tel'];
        $endereco = $rs['endereco']." ".$rs['complemento'];
        $email = $rs['email'];
        $ie = $rs['ie'];
        $im = $rs['im'];
        $estado = $rs['estado'];
        $cidade = $rs['cidade'];
        $site = $rs['site'];
        $complemento = $rs['complemento'];
        $banco = $rs['banco'];

        if($request->hasFile('foto')) {
            
            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = $nome;
            $this->fornecedor->createFile($data);
            $nomeFoto = trim($nome) . '.' . $data['extension'];
            $this->con->query("update dmtrixII.fornecedores set foto = '$nomeFoto' where id = '$id'");

        }
        


     $this->con->query("update dmtrixII.fornecedores set fantasia = '$nome', cnpj='$cnpj',conta='$conta',agencia='$agencia',cep='$cep',telefone='$telefone',endereco='$endereco',email='$email',inscricaoEstatudal = '$ie', inscricaoMunicipal ='$im',
        razao='$razao', estado='$estado',cidade='$cidade',site='$site',banco='$banco',complemento='$complemento'
        where id= '$id' ");

        if(odbc_error() == ''){

            $msg = 'Atualizado com sucesso!';
            $class = 'bg-success text-center text-success';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));


        }else{

            $msg = 'Falha, tente novamente!';
            $class = 'bg-danger text-center text-danger';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));

        }
            
        

    }
    
    public function indicadores(){
        
        return $this->fornecedor->pedidosFornecedor();
        
    }


    public function destroy($id)
    {
        
        $this->con->query("delete from dmtrixII.fornecedores where id = '$id'");

        if(odbc_error() == ''){

            $msg = 'Deletado com sucesso!';
            $class = 'bg-success text-center text-success';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));


        }else{

            $msg = 'Falha, tente novamente!';
            $class = 'bg-danger text-center text-danger';
            return view('fornecedores.gestaoFonecedor', compact('msg', 'class'));

        }
    }

    public function PedidosFornecedor(){

        $sql =  $this->con->query("select distinct p.idCompra, u.nome+' '+u.sobrenome as solicitante, c.Prioridade,c.dataOrcAtualizado, c.valorTotal,
  (select nome+' '+sobrenome as solicitante from usuariosDMTRIX where idUsuario = t.idUsuario) as criacao, l.numeroLoja+' - '+l.nomeLoja as loja,
   p.status_pedido  from 
  PedidoDMTRIX p  join ComprasDMTRIX c on c.idCompra = p.idCompra join lojasDMTRIX l on l.numeroLoja = c.idLoja
  left join ControleAprovacoesDMTRIX ca on ca.idPedido = p.idPedido
  join usuariosDMTRIX u on u.idUsuario = p.idUsuario
  left join tarefasDMTRIX t on t.idPedido = p.idPedido where p.status_pedido = 6 or p.status_pedido = 8 or p.status_pedido = 81");

        $response = array();
        while($rs = $this->con->fetch_array($sql))
        {
            $status_pedido = $rs['status_pedido'];

            switch ($status_pedido){
                case 8: $status = 'fornecedor';
                    break;
                case 6: $status = 'aprovado';
                    break;
                case 81: $status = 'aguadando';
                    break;
                case 11: $status = 'finalizado';
                    break;
                default: $status = '';

            }

            array_push($response, [
                
                'idCompra' => $rs['idCompra'],
                'solicitante' => $rs['solicitante'],
                'Prioridade' => $rs['Prioridade'],
                'status' => $status,
                'loja' => $rs['loja'],
                'criacao' =>$rs['criacao'],
                'dataOrcAtualizado' =>$this->service->formatarData($rs['dataOrcAtualizado']),
                'valorTotal' =>$rs['valorTotal']

            ]);


        }

        return $response;




        }

    public function detalhesPedidos($id){

        $sql = $this->con->query("select c.dataOrcAtualizado, (select top 1 dataObs from dmtrixII.historicoObs where observacao like '%Pedido enviado para %' and idPedido = p.idPedido order by dataObs desc) as dataRevisao, 
   m.material,p.idPedido, l.numeroLoja+' - '+l.nomeLoja as loja, p.largura,p.altura,p.quantidade,m.formaCalculo, ca.data_aprovado,
   ca.data_aprovado_arte, p.dataArtePostada, p.observacao,p.fotoArte,p.valorProduto,p.valorTotal, p.status_pedido,f.razao,cf.dataPrevista,
   cf.dataEntrada,cf.dataSaida, p.idCompra, p.custeio
  from PedidoDMTRIX p join materiaisDMTRIX m on m.idMaterial = p.idMaterial left join ControleAprovacoesDMTRIX ca on ca.idPedido = p.idPedido
  join ComprasDMTRIX c on c.idCompra = p.idCompra join lojasDMTRIX l on l.numeroLoja = c.idLoja
  left join dmtrixII.[controle-fornecedor] cf on cf.idPedido = p.idPedido
  left join dmtrixII.fornecedores f on f.id = cf.idFornecedor where c.idCompra = '$id'");

        $fornecedor = $this->con->query("select razao,id from dmtrixII.fornecedores");
        $arrayFor = array();
        while($rs = $this->con->fetch_array($fornecedor)){
            array_push($arrayFor, ['nome' => utf8_encode($rs['razao']), 'id' => $rs['id']]);
        }

        $response = array();
        while($rs = $this->con->fetch_array($sql))
        {
            $status_pedido = $rs['status_pedido'];
            $dataPrevista = $rs['dataPrevista'];
            if($dataPrevista != '') {

                $date = new \DateTime();
                $dataPrevista = new \DateTime($dataPrevista);
                $diff = $date->diff($dataPrevista);
                $dias = $diff->days;


                if($dias <= 2 && $status_pedido != 81 && $status_pedido != 11)
                {

                    $status_pedido = 82; //pedido proximo a data de entrega

                }
            }



            switch ($status_pedido){
                case 8: $status = 'Aguardando Entrega...';
                    break;
                case 6: $status = 'Defina um fornecedor...';
                    break;
                case 81: $status = 'Disponivel para entrega';
                    break;
                case 11: $status = 'finalizado';
                    break;
                case 82: $status = 'Proximo a data de entrega...';
                    break;
                default: $status = '';

            }

            if($status != '') {


                array_push($response, [
                    'idPedido' => $rs['idPedido'],
                    'idCompra' => $rs['idCompra'],
                    'custeio' => $rs['custeio'],
                    'material' => $rs['material'],
                    'status' => $status,
                    'dataRevisao' => $this->service->formatarData($rs['dataRevisao']),
                    'loja' => $rs['loja'],
                    'largura' => $rs['largura'],
                    'altura' => $rs['altura'],
                    'quantidade' => $rs['quantidade'],
                    'formaCalculo' => $rs['formaCalculo'],
                    'data_aprovado' => $this->service->formatarData($rs['data_aprovado']),
                    'dataOrcAtualizado' => $this->service->formatarData($rs['dataOrcAtualizado']),
                    'data_aprovado_arte' => $this->service->formatarData($rs['data_aprovado_arte']),
                    'razao' => utf8_encode($rs['razao']),
                    'dataPrevista' => $this->service->formatarData($rs['dataPrevista']),
                    'dataEntrada' => $this->service->formatarData($rs['dataEntrada']),
                    'dataSaida' => $this->service->formatarData($rs['dataSaida']),
                    'dataArtePostada' => $this->service->formatarData($rs['dataArtePostada']),
                    'observacao' => $rs['observacao'],
                    'fotoArte' => $rs['fotoArte'],
                    'valorProduto' => $rs['valorProduto'],
                    'valorTotal' => $rs['valorTotal'],
                    'fornecedor' => $arrayFor,
                    'status_pedido' => $status_pedido


                ]);

            }
        }

        return $response;


    }

    public function enviarFornecedor( Request $request){
        
        $rs = $request->all();

        $resp =  $this->fornecedor->EnviarFornecedor($rs['token'],$rs['fornecedor'], $rs['data'] );
        return view('fornecedores.consulta', compact('resp'));

    }
    
    public function EntregaPedido($id){

        $resp =   $this->fornecedor->EntregaPedido($id);
        return $resp;

    }
    public function finalizar(Request $request){
        $rs = $request->all();
        
       $resp =  $this->fornecedor->finalizarPedido($rs);
       return view('fornecedores.consulta', compact('resp'));

    }

    public function finalizarCompra(Request $request){
        $rs = $request->all();
        $resp =  $this->fornecedor->finalizarCompra($rs);

        return view('fornecedores.consulta', compact('resp'));

    }
    
    public function verificaPedidosParados(){
        
         $this->fornecedor->pedidosEntreguesParados();
        
    }
    
    
}
