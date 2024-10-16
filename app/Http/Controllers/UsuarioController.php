<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\Cpf;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class UsuarioController extends Controller
{
    // Exibir o formulário de cadastro de usuário
    public function index()
    {
        return view('cadastroUser');
    }

    // Função para cadastrar um novo usuário comum
    public function store(Request $request)
    {
        // Valida os campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|string|unique:users,cpf',
            'data_nascimento' => 'required|date',
            'password' => 'required|string|min:8',
            'is_admin' => 'required|boolean',
        ]);
    
        // Cria o novo usuário
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->cpf = $request->input('cpf');
        $user->data_nascimento = $request->input('data_nascimento');
        $user->is_admin = $request->input('is_admin'); // Define se é administrador ou usuário comum
        $user->password = Hash::make($request->input('password')); // Criptografando a senha
        $user->save();
    
        // Redireciona para a listagem de usuários
        return redirect()->route('listagem/user')->with('success', 'Usuário cadastrado com sucesso!');
    }
    

    public function listagemUser()
    {
        $users = User::all(); // Buscar todos os usuários
    
        return view('ListagemUsuario', ['users' => $users]);
    }
    // Exibir formulário de edição de um usuário específico
    public function editUsuario($id)
    {
        $user = User::findOrFail($id);
        return view('EditarUsuario', ['user' => $user]);
    }

    // Atualizar um usuário específico
    public function atualizarUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validação dos dados
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
        ]);

        // Atualizar os dados conforme o formulário
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

    // Deletar um usuário específico
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return 'Usuário deletado(a) com sucesso';
    }

    // Exibir o formulário de login do usuário
    public function loginUser()
    {
        return view('loginUsuario');
    }

    // Processar o login do usuário
    public function logandoUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|max:8',
        ]);

        $credentials = $request->only('email', 'password');

        // Limitar tentativas de login
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 4)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => [trans('auth.throttle', ['seconds' => $seconds])]
            ]);
        }

        // Tentar autenticar o usuário
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Definir as variáveis de sessão
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_email', $user->email);

            // Limpar tentativas falhas
            RateLimiter::clear($this->throttleKey($request));

            return redirect()->route('homeUsuario');
        }

        // Incrementar as tentativas de falhas
        RateLimiter::hit($this->throttleKey($request));

        return redirect()->route('login-user')->withErrors(['email' => 'Credenciais inválidas, verifique novamente']);
    }

    // Definir a chave para limitação de tentativas de login
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    // Página inicial do usuário comum
    public function homeUsuario()
    {
        return view('Usuario'); // Página inicial do usuário
    }

    // Logout do usuário
    public function logout(Request $request)
    {
        Auth::logout(); // Deslogar o usuário
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-user');
    }
}
