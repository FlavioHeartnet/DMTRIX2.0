<?php

namespace App\Http\Controllers;

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

        $infos = ['idCompra'=> $id, 'tipo' => 2, 'texto' => $obs];
        $this->historicoCompras($infos);
    }


    public function create($infos)
    {
        $value = session('user');
        $idUsuario = $value['id'];
        $idPedido = $infos['idPedido'];
        $texto = $infos['texto'];
        $tipo = $infos['tipo'];
        
        $this->con->query("insert into dmtrixII.historicoObs (tipo, observacao, idUsusario,dataObs,idPedido)
  values('$tipo','$texto', '$idUsuario',GETDATE(),'$idPedido')");
        
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
