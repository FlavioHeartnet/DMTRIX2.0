<?php

namespace App\Http\Controllers;

use App\Services\Services;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HistoricoController extends Controller
{

    private $con;


    public function __construct()
    {
        $this->con = new \config();
        
    }

    

    public function addObs($id, $obs)
    {

       $Compra = $this->con->fetch_array($this->con->query("select idPedido from PedidoDMTRIX where idCompra= '$id'"));
       $idPedido = $Compra['idPedido'];

       $infos = ['idPedido'=> $idPedido, 'tipo' => 2, 'texto' => $obs];

       return $this->create($infos);
        
    }


    public function create($infos)
    {
        $value = session('user');
        $idUsuario = $value['id'];
        $idPedido = $infos['idPedido'];
        $texto = $infos['texto'];
        $tipo = $infos['tipo'];

        $this->con->query("insert into dmtrixII.historicoObs (tipo, observacao, idUsusario,dataObs,idPedido,lida)
  values('$tipo','$texto', '$idUsuario',GETDATE(),'$idPedido',0)");

        if(odbc_error() == ''){
            

            return 'sucesso';

        }else{

            return 'Erro';

        }
        
    }


    public function historicoCompras($infos)
    {
        $value = session('user');
        $idUsuario = $value['id'];
        $idCompra = $infos['idCompra'];
        $texto = $infos['texto'];
        $tipo = $infos['tipo'];

          $this->con->query("insert into dmtrixII.historicoObsCompras (tipo, observacao, idUsuario,dataObs,idCompra)
  values('$tipo','$texto', '$idUsuario',GETDATE(),'$idCompra')");

        if(odbc_error() == ''){

            return 'sucesso';

        }else{

            return 'Erro';

        }



    }


    public function lastRecord($tipo, $idPedido)
    {

        

    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
