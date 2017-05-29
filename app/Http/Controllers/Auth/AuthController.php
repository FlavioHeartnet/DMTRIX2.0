<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    private $con;



    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);

        $this->con = new \config();


    }
    
    public function logout(){

        session()->flush();
        return view('welcome');
        
    }

    public function login(Request $request)
    {

        $rs = $request->all();
        $user = $rs['user'];
        $senha = $rs['senha'];

       $sql = $this->con->query("select idUsuario,nivel,status from dbo.usuariosDMTRIX where usuario = '$user' and senha='$senha'");
        
        if(odbc_num_rows($sql) > 0){

            $dados = $this->con->fetch_array($sql);

            if($dados['nivel'] < 3 and $dados['status'] == 1) {

                $id = $dados['idUsuario'];

                $criacao = $this->con->query("select u.usuario from dmtrixII.criacaoDMTRIX c join usuariosDMTRIX u on u.idUsuario = c.idUsuario where u.idUsuario = '$id'"); //verifica se o usuario pertence a criacao


                
                if(odbc_num_rows($criacao) == 0 or $dados['nivel'] == 1) {
                    
                    $data = ['user' => $user, 'id' => $dados['idUsuario'], 'nivel' => $dados['nivel'], 'token' => '1', 'criacao' => 0];
                    $request->session()->put('user', $data);


                    return view('home.home');

                }else{


                    $data = ['user' => $user, 'id' => $dados['idUsuario'], 'nivel' => $dados['nivel'], 'token' => '1', 'criacao' => 1];
                    $request->session()->put('user', $data);

                    return view('producao.fila-home');

                }

            }else{

                $msg = 'Usuario não autorizado!';

                return view('welcome', compact('msg'));

            }
            
        }else{
            
            $msg = 'Usuario ou senha incorreta';
            
            return view('welcome', compact('msg'));
            
        }


    }

    public function criacao()
    {

       return view('producao.fila-home');

    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
