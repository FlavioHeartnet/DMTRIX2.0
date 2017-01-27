<?php

namespace App\Http\Controllers;

use App\Services\FornecedorServices;
use App\Services\ProducaoServices;
use Illuminate\Http\Request;

use App\Http\Requests;


class ProducaoController extends Controller
{

    private $producaoService;
    private $fornecedor;

    /**
     * ProducaoController constructor.
     * @param $producaoService
     * @param $fornecedor
     */
    public function __construct(ProducaoServices $producaoService, FornecedorServices $fornecedor)
    {

        $this->fornecedor = $fornecedor;
        $this->producaoService = $producaoService;

    }


    public function index()
    {
        return view('home.criacao-fila');
    }

    
    public function fila()
    {
        return $this->producaoService->filaCriacao();
    }

    
    
    public function show($id)
    {
        return $this->producaoService->filaIndividual($id); 
    }
    
    public function enviarAprovacao( Request $request){

        $request = $request->all();
        
        if($request['tipo'] == 1){

            $resp = $this->producaoService->aprovarArte($request['token']);
            
        }else {

            $resp = $this->producaoService->reprovarArte($request['token'], $request['motivo']);
        }
        
        return view('producao.revisao-interna', compact('resp'));
        
    }


    public function salvarArte(Request $request)
    {

        $rs = $request->all();
        $idPedido = $rs['token'];
        if($request->hasFile('foto'))
        {

            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = "arte".$idPedido;
            $this->fornecedor->createFile($data);
            $nomeFoto = trim($data['nome']) . '.' . $data['extension'];

            return $this->producaoService->revisao($idPedido, $nomeFoto);
        }else{


            return 'Erro ao enviar o arquivo, tente novamente mais tarde!';

        }


        
    }
    
    public function ConsultaRevisao()
    {
        
       return $this->producaoService->ArtesEmRevisao();
        
    }

    public function consultaAprovacao(){


        return $this->producaoService->ArtesEmAprovacao();

    }

    public function aprovar($id){


         $resp = $this->producaoService->aprovar($id);

        return $resp;

    }


}
