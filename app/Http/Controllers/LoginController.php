<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use App\Rules\Cpf;
use App\Rules\CnpjValid;
use Illuminate\Support\Facades\Auth;

class FornecedorController extends Controller
{
    // Exibir a página de cadastro de fornecedor
    public function indexFornecedor()
    {
        return view('TelaFornecedor');
    }

    // Função para cadastrar um novo fornecedor
    public function storeFornecedor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'cnpj' => ['nullable', 'string', new CnpjValid(), 'unique:_fornecedores,cnpj'],
            'cpf' => ['nullable', 'string', new Cpf(), 'unique:_fornecedores,cpf'],
            'telefone' => 'required|string',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'complemento' => 'nullable|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'uf' => 'required|string',
            'email' => 'nullable|email',
            'status' => 'required|string|in:ativo,inativo'
        ], [
            'cnpj.unique' => 'O CNPJ digitado já está em uso.',
            'cpf.unique' => 'O CPF digitado já está em uso.',
        ]);

        // Usar o usuário autenticado como admin
        $admin = Auth::user();

        $fornecedor = new Fornecedor();
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

        return redirect()->route('listagemFornecedor')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    // Função para listar os fornecedores do admin autenticado
    public function listagemFornecedor()
    {
        $admin = Auth::user();
        $fornecedores = Fornecedor::where('admin_id', $admin->id)->get();

        return view('listagemFornecedor', ['fornecedores' => $fornecedores]);
    }

    // Exibir formulário de edição de fornecedor
    public function editFornecedor($id)
    {
        $admin = Auth::user();
        $fornecedor = Fornecedor::where('admin_id', $admin->id)->where('id', $id)->firstOrFail();

        return view('EditFornecedor', ['fornecedor' => $fornecedor]);
    }

    // Função para atualizar um fornecedor
    public function atualizarFornecedor(Request $request, $id)
    {
        $admin = Auth::user();
        $fornecedor = Fornecedor::where('admin_id', $admin->id)->where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'nullable|string|max:60',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'cep' => 'nullable|string',
            'rua' => 'nullable|string',
            'complemento' => 'nullable|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'uf' => 'nullable|string',
            'status' => 'nullable|in:ativo,inativo',
        ]);

        $fornecedor->update($request->only([
            'name', 'email', 'telefone', 'cep', 'rua', 'complemento', 'bairro', 'cidade', 'uf', 'status'
        ]));

        return redirect()->route('listagemFornecedor')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    // Função para deletar um fornecedor
    public function deleteFornecedor($id)
    {
        $admin = Auth::user();
        $fornecedor = Fornecedor::where('admin_id', $admin->id)->where('id', $id)->firstOrFail();

        $fornecedor->delete();

        return redirect()->route('listagemFornecedor')->with('success', 'Fornecedor deletado com sucesso!');
    }

    // Função para pesquisar fornecedores
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
