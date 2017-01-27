<?php


class Pedidos
{
    

    public function create($request)
    {

        $id = $request['idMaterial'];
        $largura = $request['largura'];
        $altura = $request['altura'];
        $quantidade = $request['quantidade'];
        $idUser = $request['idUsuario'];
        $status_pedido = $request['status_pedido'];
        $valorProduto = $request['valorProduto'];
        $valorTotal = $request['valorTotal'];
        $custeio = $request['custeio'];
        $formaPagamento = $request['formaPagamento'];
        $segmento = $request['segmento'];
        $observacao = $request['observacao'];
        $numeroLoja = $request['numeroLoja'];


       $con = new config();

        $con->query("insert into dmtrixII.PedidoDMTRIX (idMaterial,largura,altura,quantidade,observacao,Data_do_Pedido, idUsuario,status_pedido,valorProduto,valorTotal,custeio,formaPagamento,segmento,numeroLoja) values
        ('$id','$largura','$altura','$quantidade','$observacao',getdate(),'$idUser','$status_pedido','$valorProduto','$valorTotal','$custeio','$formaPagamento','$segmento','$numeroLoja')");

        if(odbc_error() == ''){

            return true;

        }else{

            return  odbc_errormsg();

        }

    }

    public function update()
    {


    }

    public function delete($id)
    {
        $con = new config();

        $con->query("delete from dmtrixII.PedidoDMTRIX where idPedido = '$id'");

    }


}

