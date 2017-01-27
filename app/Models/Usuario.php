<?php


class Usuario
{

    private $usuario;
    private $senha;
    private $nivel ;
    private $nome ;
    private $sobrenome ;
    private $email ;
    private $supervisor;
    private $budgetBrindes;
    private $budgetMerchandising;
    private $status;
    private $con;


    public function __construct($request)
    {
        $this->usuario = $request['usuario'];
        $this->senha = $request['senha'];
        $this->nivel = $request['nivel'];
        $this->nome = $request['nome'];
        $this->sobrenome = $request['sobrenome'];
        $this->email = $request['email'];
        $this->supervisor = $request['supervisor'];


        $this->con = new config();
    }


    public function create()
    {

        $this->con->query("insert into usuariosDMTRIX (usuario,senha,nivel,nome,sobrenome,email,supervisor,budgetBrindes,budgetMerchandising,status,dataCadastro) values
  ('$this->usuario','$this->senha','$this->nivel','$this->nome','$this->sobrenome','$this->email','$this->supervisor',0,0,0,GETDATE())");

        if(odbc_error() == ''){

            return true;

        }else {

            return odbc_errormsg();

        }

    }

    public function edit($tipo, $id)
    {
        if($tipo == 1) {

            $this->con->query("update usuariosDMTRIX set usuario = '$this->usuario', senha = '$this->senha', nivel='$this->nivel',nome='$this->nome',sobrenome='$this->sobrenome',email='$this->email',supervisor='$this->supervisor',status='$this->status' where idUsuario = '$id'");
        }else
        {
            $this->con->query("update usuariosDMTRIX set usuario = '$this->usuario', senha = '$this->senha',nome='$this->nome',sobrenome='$this->sobrenome',email='$this->email' where idUsuario = '$id'");


        }

        if(odbc_error() == ''){

            return true;

        }else {

            return odbc_errormsg();

        }


    }

    public function delete($id)
    {

        $this->con->query("delete from usuariosDMTRIX where idUsuario = '$id'");

        if(odbc_error() == ''){

            return true;

        }else {

            return odbc_errormsg();

        }

    }

}