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

        $erro = 0;



        for($i=0;$i<count($idPedido);$i++) {
            
            if (isset($_FILES['foto'])) {

                if ($_FILES['foto']['name'][$i] != '') {


                    $exteFoto = explode(".", $_FILES['foto']['name'][$i]);//pega a extensão da foto
                    $exteFoto_ex = strtolower($exteFoto[1]);

                    $data['foto'] = $_FILES['foto']['tmp_name'][$i];
                    $data['extension'] = $exteFoto_ex;
                    $data['nome'] = "arteDMTRIX" . $idPedido[$i];
                    $this->fornecedor->createFile($data);
                    $nomeFoto = trim($data['nome']) . '.' . $data['extension'];
                    $resp = $this->producaoService->revisao($idPedido[$i], $nomeFoto);
                    $class = 'bg-sucess text-center text-sucess';
                   $resp = ['class' => $class, 'msg' => $resp];

                } else {

                    $erro++;

                }


            } else {


                $class = 'bg-warning text-center text-warning';
                $msg = 'Erro ao enviar o arquivo, tente novamente mais tarde!';

                $resp = ['class' => $class, 'msg' => $msg];


            }


        }

        if($erro > 0){

            $class = 'bg-danger text-center text-danger';
            $resp = $erro.' itens não foram feitos o upload, o caminho esta vazio!';

            $resp = ['class' => $class, 'msg' => $resp];


        }


      return view('home.home', compact('resp'));

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
