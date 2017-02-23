<?php

namespace App\Http\Controllers;

use App\Services\FornecedorServices;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProdutoController extends Controller
{
    private $con;
    private $fornecedor;

    /**
     * ProdutoController constructor.
     * @param $con
     */
    public function __construct(FornecedorServices $fornecedor)
    {
        $this->con = new \config();
        $this->fornecedor = $fornecedor;
    }


    public function index()
    {
        return view('produtos.administrar-produtos');
    }

   
    public function create()
    {
        return view('produtos.cadProdutos');
    }

    public function consultar()
    {

        return view('produtos.consulta');
    }
    public function editarMostrar(){
        return view('produtos.editProduto');
    }

    public function gradeProdutos($id, $tipo)
    {

        if($tipo == 0) {

            $sql = $this->con->query("  select m.idMaterial,m.material,m.valor,m.formaCalculo,c.nomeCategoria,m.foto, g.nome as regiaoLoja
   from materiaisDMTRIX m join categoriaDMTRIX c on c.idCategoria = m.categoria join categoriaGeralDMTRIX g on g.idCategoriaGeral = c.idCategoriaGeral ");

        }elseif($tipo == 1 ){

            $sql = $this->con->query("  select m.idMaterial,m.material,m.valor,m.formaCalculo,c.nomeCategoria,m.foto, g.nome as regiaoLoja
   from materiaisDMTRIX m join categoriaDMTRIX c on c.idCategoria = m.categoria join categoriaGeralDMTRIX g on g.idCategoriaGeral = c.idCategoriaGeral where g.idCategoriaGeral  ='$id' ");

        }else{

            $sql = $this->con->query("  select m.idMaterial,m.material,m.valor,m.formaCalculo,c.nomeCategoria,m.foto, g.nome as regiaoLoja
   from materiaisDMTRIX m join categoriaDMTRIX c on c.idCategoria = m.categoria join categoriaGeralDMTRIX g on g.idCategoriaGeral = c.idCategoriaGeral where m.categoria  ='$id' ");


        }

        $response = array();
        $ponteiro = array();
        $x=0;
        while($rs = $this->con->fetch_array($sql))
        {

            array_push($ponteiro, [

                'idMaterial' => $rs['idMaterial'],
                'material' => utf8_encode($rs['material']),
                'valor' => $rs['valor'],
                'formaCalculo' => $rs['formaCalculo'],
                'nomeCategoria' => utf8_encode($rs['nomeCategoria']),
                'regiaoLoja' => utf8_encode($rs['regiaoLoja']),
                'foto' => utf8_encode($rs['foto'])

            ]);

            if($x == 4)
            {

                array_push($response,['item' => $ponteiro]);
                $x=0;

                $ponteiro = [];

            }else
            {

                $x++;
            }

        }
        array_push($response,['item' => $ponteiro]);

        return $response;

    }
    
    public function produtos()
    {

      return $this->gradeProdutos(0,0);
        
    }

    public function categoriaGeral(){


       $sql =  $this->con->query("select COUNT(*) as num, g.nome, g.idCategoriaGeral  from categoriaDMTRIX c join categoriaGeralDMTRIX g on g.idCategoriaGeral = c.idCategoriaGeral
join materiaisDMTRIX m on m.categoria = c.idCategoria 
  group by g.nome,g.idCategoriaGeral");

        $response = array();
        while($rs = $this->con->fetch_array($sql))
        {

            array_push($response,[

                'id' => $rs['idCategoriaGeral'],
                'nome' => utf8_encode($rs['nome']),
                'num' => $rs['num'],

            ]);
            

        }

        return $response;

    }

    public function produtosGeral($id){


        return $this->gradeProdutos($id,1);

    }

    public function produtosCategoria($id){


        return $this->gradeProdutos($id, 2);

    }

    public function categoria(){

        $sql =  $this->con->query(" select COUNT(*) as num , c.idCategoria, c.nomeCategoria  from categoriaDMTRIX c join materiaisDMTRIX m on m.categoria = c.idCategoria
  group by c.nomeCategoria , c.idCategoria");

        $response = array();
        while($rs = $this->con->fetch_array($sql))
        {

            array_push($response,[

                'id' => $rs['idCategoria'],
                'nome' => utf8_encode($rs['nomeCategoria']),
                'num' => $rs['num'],

            ]);

        }

        return $response;


    }

   
    public function store(Request $request)
    {

        $rs = $request->all();
        $nome = $rs['nome'];
        $valor = $rs['valor'];
        $forma = $rs['forma'];
        $categoria= $rs['categoria'];




        //foto

        if($request->hasFile('foto')) {

            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = $nome;
            $this->fornecedor->createFile($data);
            $nomeFoto = trim($nome) . '.' . $data['extension'];
        }else{
            $nomeFoto = '';
        }

        //validar

        $validador = odbc_num_rows($this->con->query("select material from materiaisDMTRIX where material = '$nome'"));

        if($validador == 0) {

            $this->con->query("insert into materiaisDMTRIX (material,valor,formaCalculo,foto,categoria, status, quantidade) values ('$nome','$valor','$forma','$nomeFoto','$categoria',0,0)");

        }else{


            $msg = 'Produto ja cadastrado!';
            $class = 'bg-warning text-center text-warning';

            $resp = ['msg' => $msg, 'class' => $class];
            return view('produtos.administrar-produtos', compact('resp'));
        }

        if(odbc_error() == ''){

            $msg = 'Cadastrado com sucesso!';
            $class = 'bg-success text-center text-success';



        }else{

            $msg = 'Falha, tente novamente!';
            $class = 'bg-danger text-center text-danger';


        }

        $resp = ['msg' => $msg, 'class' => $class];

        return view('produtos.administrar-produtos', compact('resp'));
    }

    
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        return $this->con->fetch_array($this->con->query("
   select idMaterial,material,valor,
   case when formaCalculo = 1 then 'Free'
   when formaCalculo = 2 then 'Unidade'
   when formaCalculo = 3 then 'Metro'
   else 'indefinido' end as forma, formaCalculo
   ,status,foto, c.nomeCategoria,c.idCategoria from materiaisDMTRIX m join categoriaDMTRIX c on c.idCategoria = m.categoria where idMaterial = '$id'"));
    }

 
    public function update(Request $request)
    {
           
        $rs = $request->all();
        $id= $rs['token'];
        $nome = $rs['nome'];
        $valor = $rs['valor'];
        $categoria = $rs['categoria'];
        $forma = $rs['forma'];

        if(isset($rs['status'])){

            $status = $rs['status'];
            $this->con->query("update materiaisDMTRIX set  status = '$status' where idMaterial = '$id'");

        }else{

            $this->con->query("update materiaisDMTRIX set  status = '1' where idMaterial = '$id'");

        }

        if($request->hasFile('foto')) {

            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = $nome;
            $this->fornecedor->createFile($data);
            $nomeFoto = trim($nome) . '.' . $data['extension'];
            $this->con->query("update materiaisDMTRIX set foto = '$nomeFoto' where idMaterial = '$id'");

        }

        $this->con->query(" update materiaisDMTRIX set material = '$nome', valor='$valor',formaCalculo = '$forma', categoria = '$categoria' where idMaterial = '$id' ");

        if(odbc_error() == '')
        {

            $msg = 'Atualizado com sucesso!';
            $class = 'bg-success text-center text-success';
            $resp = ['msg' => $msg, 'class' => $class];
            return view('produtos.administrar-produtos', compact('resp'));


        }else
        {

            $msg = 'Falha, tente novamente!';
            $class = 'bg-danger text-center text-danger';
            $resp = ['msg' => $msg, 'class' => $class];
            return view('produtos.administrar-produtos', compact('resp'));

        }

    }


    public function destroy($id)
    {
        //
    }
}
