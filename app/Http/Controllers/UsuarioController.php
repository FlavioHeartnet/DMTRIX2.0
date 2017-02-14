<?php

namespace App\Http\Controllers;

use App\Services\FornecedorServices;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsuarioController extends Controller
{

    private $con;
    private $service;



    /**
     * UsuarioController constructor.
     * @param $con
     */
    public function __construct(FornecedorServices $services)
    {
        $this->con = new \config();
        $this->service = $services;
    }


    public function create()
    {

        return view('users.cadUsuario');
    }
    
    public function mostrar(){
        
        return view('users.consultarUsuario');
        
    }
    public function index()
    {
        $user = $this->con->query(" select u.idUsuario,u.usuario,
           case when u.nivel = 1 then 'Administrador'
           when u.nivel = 2 then 'Criação'
           when u.nivel = 3 then 'Supervisor'
           when u.nivel = 4 then 'Consultor'
           when u.nivel = 5 then 'Interno'
           else 'Sem Atribuição' end as nivel
           ,u.nome + ' '+ u.sobrenome as nome, u.email,
           case when u.supervisor = 0 then 'Sem supervisor'
			else s.nome+' '+s.sobrenome end as consultor 
           ,u.foto, u.status, u.nivel as numNivel 
           from usuariosDMTRIX u left join usuariosDMTRIX s on s.idUsuario = u.supervisor order by u.status desc, u.nome asc");
        $response = array();
        while($rs = $this->con->fetch_array($user)){
            
            if($rs['status'] == 1)
            {
                
                $status = 'Ativo';
                
            }else{

                $status = 'Desativado';
                
            }
            
            array_push($response,
                [
                    'usuario' => $rs['usuario'],
                    'idUsuario' => $rs['idUsuario'],
                    'nivel' => $rs['nivel'],
                    'nome' => $rs['nome'],
                    'email' => $rs['email'],
                    'consultor' => $rs['consultor'],
                    'foto' => $rs['foto'],
                    'status' => $status,
                
                ]);
            
        }
        
        
        return $response;
        
    }


    public function store(Request $request)
    {
        $rs= $request->all();

        $nome = $rs['name'];
        $user = $rs['user'];
        $senha = $rs['senha'];
        $email = $rs['email'];
        $nivel = $rs['nivel'];
        $supervisor = $rs['supervisor'];
        $sobrenome = $rs['sobrenome'];

        if($request->hasFile('foto')) {

            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = 'User'.trim($nome);
            $this->service->createFile($data);
            $nomeFoto =  trim($data['nome']) . '.' . $data['extension'];

        }else{

            $nomeFoto = '';

        }


        $verifica = $this->con->query("select email from usuariosDMTRIX where email = '$email'");

        if(odbc_num_rows($verifica) > 0){

            $this->con->query(" insert into usuariosDMTRIX (usuario,nome,sobrenome,senha,nivel,supervisor,email,foto,status) values ('$user','$nome','$sobrenome','$senha','$nivel','$supervisor','$email','$nomeFoto',1)");

            if(odbc_error() == ''){

                $msg = 'Cadastrado com sucesso!';
                $class = 'bg-success text-center text-success';
                $resp = ['class' =>$class, 'msg'=>$msg];

                return view('users.gestaoUsuarios', compact('resp'));


            }else{

                $msg = 'Falha, tente novamente!';
                $class = 'bg-danger text-center text-danger';
                $resp = ['class' =>$class, 'msg'=>$msg];
                return view('users.gestaoUsuarios', compact('resp'));

            }


        }else{

            $msg = 'Usuario ja cadastrado!';
            $class = 'bg-warning text-center text-warning';
            $resp = ['class' =>$class, 'msg'=>$msg];

            return view('users.gestaoUsuarios', compact('resp'));

        }


    }

    public function supervisores(){

       $sql = $this->con->query("select idUsuario, nome+' '+sobrenome as supervisor from usuariosDMTRIX where nivel = 3 and status = 1");
        $response = array();
        while($rs = $this->con->fetch_array($sql)){
            

            array_push($response,
                [
                    'idUsuario' => $rs['idUsuario'],
                    'supervisor' => $rs['supervisor'],

                ]);

        }
        
        return $response;

    }

    public function show($id)
    {
                $user = $this->con->query(" select u.idUsuario,u.usuario,
           case when u.nivel = 1 then 'Administrador'
           when u.nivel = 2 then 'Criação'
           when u.nivel = 3 then 'Supervisor'
           when u.nivel = 4 then 'Consultor'
           when u.nivel = 5 then 'Interno'
           else 'Sem Atribuição' end as nivel
           ,u.nome, u.sobrenome, u.email,
           case when u.supervisor = 0 then 'Sem supervisor'
			else s.nome+' '+s.sobrenome end as consultor 
           ,u.foto, u.status, u.nivel as numNivel 
           from usuariosDMTRIX u left join usuariosDMTRIX s on s.idUsuario = u.supervisor where u.idUsuario = '$id'");

        if(odbc_num_rows($user) == 0){

            return 'Usuario não encontrado';
            

        }else {

            return $this->con->fetch_array($user);
            
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->con->query("  select u.idUsuario,u.usuario,
           case when u.nivel = 1 then 'Administrador'
           when u.nivel = 2 then 'Criação'
           when u.nivel = 3 then 'Supervisor'
           when u.nivel = 4 then 'Consultor'
           when u.nivel = 5 then 'Interno'
           else 'Sem Atribuição' end as nivel
           ,u.nome + ' '+u.sobrenome as nome, u.email, s.nome+' '+s.sobrenome as consultor ,u.foto, u.status 
           from usuariosDMTRIX u join usuariosDMTRIX s on s.idUsuario = u.supervisor where u.idUsuario = '$id'");

        if(odbc_num_rows($user) == 0){

            return 'Usuario não encontrado';
        }else {



            $resp =  $this->con->fetch_array($user);
            return view('users.editUsuario',compact('resp'));
        }


    }


    public function update(Request $request)
    {
        $rs= $request->all();

        $id= $rs['token'];
        $nome = $rs['name'];
        $user = $rs['user'];
        $senha = $rs['senha'];
        $email = $rs['email'];
        $nivel = $rs['nivel'];
        
        if(isset($rs['ativo'])){
            
            $status = $rs['ativo'];
            $this->con->query("update usuariosDMTRIX set  status = '$status' where idUsuario = '$id'");
        }else{

            $this->con->query("update usuariosDMTRIX set  status = '1' where idUsuario = '$id'");

        }

        if($request->hasFile('foto')) {

            $data['foto'] = $request->file('foto');
            $data['extension'] = $request->file('foto')->getClientOriginalExtension();
            $data['nome'] = 'User'.trim($nome);
            $this->service->createFile($data);
            $nomeFoto =  trim($data['nome']) . '.' . $data['extension'];

            $this->con->query("update usuariosDMTRIX set  foto = '$nomeFoto' where idUsuario = '$id'");

        }

        if($rs['supervisor'] != '?'){

            $supervisor = $rs['supervisor'];

            $this->con->query("update usuariosDMTRIX set  supervisor = '$supervisor' where idUsuario = '$id'");
        }
            if($senha == ''){

                $this->con->query("update usuariosDMTRIX set nome= '$nome', usuario = '$user',email='$email', nivel  ='$nivel' where idUsuario = '$id'");
            }else{

                $this->con->query("update usuariosDMTRIX set nome= '$nome',senha='$senha', usuario = '$user',email='$email', nivel  ='$nivel' where idUsuario = '$id'");
            }


        if(odbc_error() == ''){

            $msg = 'Atualizado com sucesso!';
            $class = 'bg-success text-center text-success';
            $resp = ['class' =>$class, 'msg'=>$msg];

            return view('users.gestaoUsuarios', compact('resp'));


        }else{

            $msg = 'Falha, tente novamente!';
            $class = 'bg-danger text-center text-danger';
            $resp = ['class' =>$class, 'msg'=>$msg];
            return view('users.gestaoUsuarios', compact('resp'));

        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
