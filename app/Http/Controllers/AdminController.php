<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Modelo User será usado para admins e usuários comuns
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class AdminController extends Controller
{
    // Mostrar a página de login
    public function login()
    {
        return view('login'); // Tela de login unificada para ambos
    }

    // Função para cadastrar um novo admin (apenas exemplo de como criar admin)
    public function store()
    {
        $admin = new User; // Criar admin usando o modelo User
        $admin->name = 'Seara';
        $admin->email = 'seara@gmail.com';
        $admin->password = bcrypt('123456');
        $admin->is_admin = true; // Define o campo 'is_admin' como true para identificar admin
        $admin->save();

        return "Admin salvo com sucesso!";
    }

    // Função para autenticar o usuário (admin ou comum)
    public function loginUpdate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Limitar tentativas de login
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 4)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => [trans('auth.throttle', ['seconds' => $seconds])]
            ]);
        }

        // Tentar autenticar o usuário (admin ou comum)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user(); // Usuário autenticado

            // Definir variáveis de sessão
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_email', $user->email);

            // Limpar as tentativas após login com sucesso
            RateLimiter::clear($this->throttleKey($request));

            // Redirecionar com base no tipo de usuário
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard'); // Redireciona para o painel de admin
            } else {
                return redirect()->route('user.dashboard'); // Redireciona para o painel de usuário comum
            }
        }

        // Se falhar, incrementar a contagem de tentativas de login falhas
        RateLimiter::hit($this->throttleKey($request));

        return redirect()->route('login')->withErrors(['email' => 'Credenciais inválidas, verifique novamente']);
    }

    // Definir a chave de limitação de tentativas
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')).'|' .$request->ip();
    }

    // Página do administrador
    public function index()
    {
        return view('homeAdmin'); // Exibe o painel de admin
    }

    // Função para logout
    public function logout(Request $request)
    {
        Auth::logout(); // Desloga o usuário atual (admin ou comum)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // Redireciona para a página de login
    }
}
