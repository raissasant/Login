<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\Cpf; #--- Importando o Rules CPF do Laravel --# php artisan make:rule CPF
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Password;
use Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;


class UsuarioController extends Controller
{
    //

    public function index(){
        return view('cadastroUser');
    }

    
    public function store(Request $request){
        //validação dos dados do form
        $request->validate([
        'name' => 'required|string|max:60',
        'email' => 'required|email',
        'password' => 'required|string|max:8',
        'cpf' => ['required', 'string', new Cpf], //Aqui valida o CPF
        'data_nascimento' => 'required|string',
        
       ]);

        $admin = Auth::guard('admins')->user();
    
        
        $user = new User;
        $user->admin_id = $admin->id;
        $user->name= $request->input('name');
        $user->email= $request->input('email');
        $user->password= bcrypt($request->input('password'));
        $user->cpf = $request->input('cpf');
        $user->data_nascimento= $request->input('data_nascimento');
        $user->save();

        return "Salvo com sucesso";
        
   }

    public function ListagemUser(){
        $admin = Auth::guard('admins')->user();

        // Buscar clientes associados ao admin logado
         $users = $admin->users; 
         

        return view('ListagemUsuario',['users' => $users]);

    }

         public function editUsuario($id)
    {
        $admin = Auth::guard('admins')->user();
        $user = $admin->users()->where('id', $id)->firstOrFail();
        return view('EditarUsuario', ['user' => $user]);
    }

    public function atualizarUsuario(Request $request, $id)
    {
        $admin = Auth::guard('admins')->user();
        $user = $admin->users()->where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
        ]);

        //$user->name = $request->input('name');
        //$user->email = $request->input('email');
        //$user->data_nascimento = $request->input('data_nascimento');
        //$user->password= bcrypt($request->input('password'));

      if ($request->filled('name')) {
        $user->name = $request->input('name');
        }
      if ($request->filled('email')) {
            $user->email = $request->input('email');
        }
      if ($request->filled('data_nascimento')) {
            $user->data_nascimento = $request->input('data_nascimento');
        }
      if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return 'Usuário atualizado com sucesso';
    }


    public function destroy($id){

        $admin = Auth::guard('admins')->user();
        $user = $admin->users()->where('id', $id)->first();

        $user->delete();

        return 'Usuário deletado(a) com sucesso';
    }


    
    public function loginUser(){
        return view('loginUsuario');
    }



    public function logandoUser(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|max:8',
        ]);



        $credentials = $request->only('email', 'password'); 

        // Determinar o limite de tentativas do usuário
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 4)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));

        //Exibir a mensagem que o usuário excedeu o login e tem que esperar. A menssagem de erro está aqui
        throw ValidationException::withMessages([
            'email'=> [trans('auth.throttle', ['seconds' => $seconds])]
          ]);
        
}

       
        if (Auth::attempt($credentials)) {
            // Autenticação bem-sucedida
            $request->session()->regenerate();

        // Obter o usuário autenticado
        $user = Auth::user();


            // Definindo as variáveis de sessão
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_email', $user->email);
          
           // Limpar as tentativas após o login com as credenciais certas
           RateLimiter::clear($this->throttleKey($request));

            return redirect()->route('homeUsuario');
        }
          
          //Fazer a contagem de tentativas de falhas
          RateLimiter::hit($this->throttleKey($request));


        return redirect()->route('login-user')->withErrors(['email' => 'Credenciais inválidas, verifique novamente']);
    }

    protected function throttleKey(Request $request){
        return strtolower($request->input('email')).'|' .$request->ip();
    
    }


    public function homeUsuario(){
        return view('Usuario');
    }


 public function logout(Request $request)
{
    $user = Auth::user();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login-user');
}



}
