<?php

/**
 * Created by PhpStorm.
 * User: flavio.barros
 * Date: 28/11/2016
 * Time: 11:55
 */
class Produtos
{

    private $material;
    private $valor;
    private $formaCalculo ;
    private $quantidade ;
    private $foto ;
    private $categoria ;
    private $status;
    private $con;


    public function __construct($request)
    {
        $this->material = $request['material'];
        $this->valor = $request['valor'];
        $this->formaCalculo = $request['formaCalculo'];
        $this->quantidade = $request['quantidade'];
        $this->categoria = $request['categoria'];
        $this->status = $request['status'];
        $this->foto = $request['foto'];

        $this->con = new config();
    }


    public function create()
    {

        $this->con->query("insert into materiaisDMTRIX (material,valor,formaCalculo,quantidade,foto,categoria,status) values
  ('$this->material','$this->valor','$this->formaCalculo','$this->quantidade','$this->foto','$this->categoria','$this->status'))");

        if(odbc_error() == ''){

            return true;

        }else {

            return odbc_errormsg();

        }

    }

    public function edit($id)
    {
            $this->con->query("update materiaisDMTRIX set material='$this->material',valor='$this->valor',formaCalculo='$this->formaCalculo',quantidade='$this->quantidade',foto='$this->foto',categoria='$this->categoria',status='$this->status' where idMaterial = '$id'");

        if(odbc_error() == ''){

            return true;

        }else {

            return odbc_errormsg();

        }


    }

    public function delete($id)
    {

        $this->con->query("delete from materiaisDMTRIX where idMaterial = '$id'");

        if(odbc_error() == ''){

            return true;

        }else {

            return odbc_errormsg();

        }

    }

}