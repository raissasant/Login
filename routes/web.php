<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ResertSenhaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//http://127.0.0.1:8000 
//http://127.0.0.1:8000/login
//http://127.0.0.1:8000/admin #--Para salvar o admin que esta no AdminController --#
//http://127.0.0.1:8000/homeAdmin 
//http://127.0.0.1:8000/cadastro/user
//http://127.0.0.1:8000/login/usuario #-- Usuário fazer o login --#
//http://127.0.0.1:8000 
//http://127.0.0.1:8000 
//http://127.0.0.1:8000 
//http://127.0.0.1:8000 
//http://127.0.0.1:8000 
//http://127.0.0.1:8000 


Route::get('/', function () {
    return view('welcome');
});

####################### //----- Rotas do Administrador ----\\ ####################

Route::get('/login',[AdminController::class, 'login'])->name('login'); # -- Login do admin --#
Route::get('/admin',[AdminController::class, 'store'])->name('admin'); #--Para salvar o admin --#
Route::post('/logando',[AdminController::class, 'loginUpdate'])->name('logando');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');


//----Página Home do Administrador-----\\\
Route::get('/homeAdmin',[AdminController::class, 'index'])->name('homeAdmin');

##########################- Rota de Resert Password #################################

# --- Resert senha do Administrador e Usuário ------#
Route::get('/nova/senha', [ResertSenhaController::class, 'NovaSenha'])->name('senha');
Route::post('/enviando',[ResertSenhaController::class, 'PedirSenha'])->name('enviandoSenha');
//- Fim da rota Resert Senha do Administrador e Usuário --\\
############################################################################################



###################### //----- Rotas do usuario ----\\ ####################################
//--Login  e logout do usuário --\\
Route::get('/login/usuario', [UsuarioController::class, 'loginUser'])->name('login-user');
Route::post('/logando/usuario', [UsuarioController::class, 'logandoUser'])->name('logandoUser');
Route::post('logout/usuario',[UsuarioController::class, 'logout'])->name('logoutUser');
//-- Fim --//



// -- CRUD do Usuário --\\
Route::get('/cadastro/user',[UsuarioController::class, 'index'])->name('cadastro/user');
Route::post('/cadastrando/user',[UsuarioController::class, 'store'])->name('cadastrando/user');
Route::get('/listagem/user',[UsuarioController::class, 'listagemUser'])->name('listagem/user');
Route::get('/editar/user/{id}', [UsuarioController::class, 'editUsuario'])->name('editar.usuario');
Route::post('/atualizar/user/{id}', [UsuarioController::class, 'atualizarUsuario'])->name('atualizar.usuario');
Route::get('/deletar/user/{id}', [UsuarioController::class, 'destroy'])->name('deletar.usuario');
// --Fim do  CRUD do Usuário --\\


// ---- Tela home do usuário ---\\
Route::get('/home/usuario',[UsuarioController::class, 'homeUsuario'])->name('homeUsuario');


# ----- Fornecedores ------- #