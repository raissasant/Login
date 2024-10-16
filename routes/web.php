<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ResertSenhaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;

############################## Rotas Públicas ##############################

Route::get('/', function () {
    return view('welcome');
});

######################### Rotas de Autenticação ############################

# -- Login e logout unificado para administrador e usuário comum -- #
Route::get('/login', [AdminController::class, 'login'])->name('login'); // Tela de login unificada
Route::post('/logando', [AdminController::class, 'loginUpdate'])->name('logando'); // Processo de login para ambos (admin e usuários)
Route::post('/logout', [AdminController::class, 'logout'])->name('logout'); // Logout unificado para ambos

########################### Rotas do Administrador ##########################

Route::middleware(['auth', 'admin'])->group(function () {
    // -- Painel do administrador -- //
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard'); // Home do admin (tela protegida)

    // -- CRUD de usuários (somente administradores) -- //
    Route::get('/cadastro/user', [UsuarioController::class, 'index'])->name('cadastro/user');
    Route::post('/cadastrando/user', [UsuarioController::class, 'store'])->name('cadastrando/user');
    Route::get('/listagem/user', [UsuarioController::class, 'listagemUser'])->name('listagem/user');
    Route::get('/editar/user/{id}', [UsuarioController::class, 'editUsuario'])->name('editar.usuario');
    Route::post('/atualizar/user/{id}', [UsuarioController::class, 'atualizarUsuario'])->name('atualizar.usuario');
    Route::get('/deletar/user/{id}', [UsuarioController::class, 'destroy'])->name('deletar.usuario');

    // -- Fornecedores (somente administradores) -- //
    Route::get('/cadastro/fornecedor', [FornecedorController::class, 'indexFornecedor'])->name('indexFornecedor');
    Route::post('/cadastrando/fornecedor', [FornecedorController::class, 'storeFornecedor'])->name('storeFornecedor');
    Route::get('/listagem/fornecedor', [FornecedorController::class, 'listagemFornecedor'])->name('listagemFornecedor');
    Route::get('/editar/fornecedor/{id}', [FornecedorController::class, 'editFornecedor'])->name('EditFornecedor');
    Route::post('/editando/fornecedor/{id}', [FornecedorController::class, 'atualizarFornecedor'])->name('atualizandoFornecedor');
    Route::delete('/deletar/fornecedor/{id}', [FornecedorController::class, 'deleteFornecedor'])->name('deleteFornecedor'); // Ajustada para usar DELETE corretamente
    Route::get('/fornecedores/search', [FornecedorController::class, 'searchFornecedores'])->name('searchFornecedores');
});

########################### Rotas de Produtos (Acessíveis para Todos os Autenticados) ##########################

Route::middleware(['auth'])->group(function () {
    // -- Rotas de produtos (acesso para todos os usuários autenticados) -- //
    Route::get('/cadastro/produto', [ProdutoController::class, 'TelaProduto'])->name('cadastroProduto');
    Route::post('/cadastrando/produto', [ProdutoController::class, 'storeProduto'])->name('storeProduto');
    Route::get('/listagem/produto', [ProdutoController::class, 'listagemProduto'])->name('ListagemProduto');
    Route::get('/atualizar/produto/{id}', [ProdutoController::class, 'editProduto'])->name('editProduto');
    Route::post('/editando/produto/{id}', [ProdutoController::class, 'atualizarProduto'])->name('atualizandoProduto');
    Route::delete('/produtos/{id}', [ProdutoController::class, 'deleteProduto'])->name('deleteProduto');
    Route::get('/produto/search', [ProdutoController::class, 'searchProduto'])->name('SearchProduto');
});

######################### Rotas do Usuário Comum ##########################

Route::middleware(['auth'])->group(function () {
    // -- Tela home do usuário comum -- //
    Route::get('/home', [UsuarioController::class, 'homeUsuario'])->name('user.dashboard'); // Painel do usuário comum (tela protegida)
});

######################### Rotas de Redefinição de Senha ##########################

# -- Redefinição de senha tanto para admin quanto para usuário comum -- #
Route::get('/nova/senha', [ResertSenhaController::class, 'NovaSenha'])->name('senha');
Route::post('/enviando', [ResertSenhaController::class, 'PedirSenha'])->name('enviandoSenha');

