<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Fornecedor;
use Auth;
use App\Rules\Cpf;
use App\Rules\CnpjValid;
use Illuminate\Validation\ValidationException;

class FornecedorController extends Controller
{
    // Exibir a tela de cadastro de fornecedor
    public function indexFornecedor()
    {
        return view('TelaFornecedor');
    }

    // Função para salvar um novo fornecedor
    public function storeFornecedor(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:60',
            'cnpj' => ['nullable', 'string', new CnpjValid(), 'unique:_fornecedores,cnpj'],
            'cpf' => ['nullable', 'string', new Cpf(), 'unique:_fornecedores,cpf'],
            'telefone' => 'string',
            'cep' => 'string',
            'rua' => 'string',
            'complemento' => 'nullable|string',
            'bairro' => 'string',
            'cidade' => 'string',
            'uf' => 'string',
            'email' => 'nullable|email',
            'status' => 'string|required'
        ], [
            'cnpj.unique' => 'O CNPJ digitado já está em uso.',
            'cpf.unique' => 'O CPF digitado já está em uso.',
        ]);

        // Obter o usuário autenticado
        $admin = Auth::user();

        // Verificar se o usuário é um administrador
        if (!$admin || !$admin->is_admin) {
            return redirect('/home')->withErrors(['msg' => 'Somente administradores podem acessar esta função']);
        }

        // Criar o fornecedor
        $fornecedor = new Fornecedor;
        $fornecedor->admin_id = $admin->id;
        $fornecedor->name = $request->input('name');
        $fornecedor->cnpj = $request->input('cnpj');
        $fornecedor->cpf = $request->input('cpf');
        $fornecedor->telefone = $request->input('telefone');
        $fornecedor->cep = $request->input('cep');
        $fornecedor->rua = $request->input('rua');
        $fornecedor->complemento = $request->input('complemento');
        $fornecedor->bairro = $request->input('bairro');
        $fornecedor->cidade = $request->input('cidade');
        $fornecedor->uf = $request->input('uf');
        $fornecedor->email = $request->input('email');
        $fornecedor->status = $request->input('status');
        $fornecedor->save();

        // Redirecionar após o cadastro
        return redirect()->route('listagemFornecedor');
    }

    // Listar fornecedores
    public function listagemFornecedor()
    {
        $admin = Auth::user();
        
        // Garantir que é um administrador
        if (!$admin || !$admin->is_admin) {
            return redirect('/home')->withErrors(['msg' => 'Somente administradores podem acessar esta função']);
        }

        $fornecedores = Fornecedor::where('admin_id', $admin->id)->get();

        return view('listagemFornecedor', ['fornecedores' => $fornecedores]);
    }

    // Exibir o formulário de edição de fornecedor
    public function editFornecedor($id)
    {
        $admin = Auth::user();

        if (!$admin || !$admin->is_admin) {
            return redirect('/home')->withErrors(['msg' => 'Somente administradores podem acessar esta função']);
        }

        $fornecedor = Fornecedor::where('admin_id', $admin->id)->where('id', $id)->firstOrFail();
        return view('EditFornecedor', ['fornecedor' => $fornecedor]);
    }

    // Atualizar fornecedor
    public function atualizarFornecedor(Request $request, $id)
    {
        $admin = Auth::user();

        if (!$admin || !$admin->is_admin) {
            return redirect('/home')->withErrors(['msg' => 'Somente administradores podem acessar esta função']);
        }

        $fornecedor = Fornecedor::where('admin_id', $admin->id)->where('id', $id)->firstOrFail();

        // Validação dos dados
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'cep' => 'nullable|string',
            'rua' => 'string',
            'complemento' => 'nullable|string',
            'bairro' => 'string',
            'cidade' => 'string',
            'uf' => 'string',
            'email' => 'nullable|email',
            'status' => 'nullable|in:ativo,inativo',
        ]);

        // Atualizar os dados preenchidos
        if ($request->filled('name')) $fornecedor->name = $request->input('name');
        if ($request->filled('email')) $fornecedor->email = $request->input('email');
        if ($request->filled('telefone')) $fornecedor->telefone = $request->input('telefone');
        if ($request->filled('cep')) $fornecedor->cep = $request->input('cep');
        if ($request->filled('rua')) $fornecedor->rua = $request->input('rua');
        if ($request->filled('complemento')) $fornecedor->complemento = $request->input('complemento');
        if ($request->filled('bairro')) $fornecedor->bairro = $request->input('bairro');
        if ($request->filled('cidade')) $fornecedor->cidade = $request->input('cidade');
        if ($request->filled('uf')) $fornecedor->uf = $request->input('uf');
        if ($request->filled('status')) $fornecedor->status = $request->input('status');

        $fornecedor->save();

        return redirect()->route('listagemFornecedor');
    }

    // Deletar fornecedor
    public function deleteFornecedor($id)
    {
        $admin = Auth::user();

        if (!$admin || !$admin->is_admin) {
            return redirect('/home')->withErrors(['msg' => 'Somente administradores podem acessar esta função']);
        }

        $fornecedor = Fornecedor::where('admin_id', $admin->id)->find($id);

        if ($fornecedor) {
            $fornecedor->delete();
            return redirect()->route('listagemFornecedor');
        } else {
            return redirect()->route('listagemFornecedor')->withErrors(['msg' => 'Fornecedor não encontrado.']);
        }
    }

    // Buscar fornecedores
    public function searchFornecedores(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');

        $query = Fornecedor::query();

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('cnpj', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $fornecedores = $query->get();

        return view('listagemFornecedor', ['fornecedores' => $fornecedores]);
    }
}
