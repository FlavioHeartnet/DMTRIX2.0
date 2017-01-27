<?php
use TobyMaxham\Database\Connectors\DBLIBConnector;

class config {
    private $conexao;

    function __construct()
    {

        date_default_timezone_set("America/Sao_Paulo");
        header('Content-type: text/html; charset=utf-8');

        $conexao =  odbc_connect("DRIVER={SQL Server}; SERVER=10.50.1.227; DATABASE=MARKETING;", "MARKETING_AGENCIA","123123");
        

        $this->conexao = $conexao;

    }

    /**
     * @return resource
     */
    public function getConexao()
    {
       
        return $this->conexao;
    }
    
    public function fetch_array($sql)
    {
        
        return odbc_fetch_array($sql);
        
    }
    
    public function query($query){
        
        $con = $this->getConexao();

            $sql = odbc_exec($con, $query);

            if (odbc_error() == '') {

                return $sql;

            } else {


                return utf8_encode('falha: ' . odbc_errormsg());

            }


        
    }




}