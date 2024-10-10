@extends('paginas.base')

@extends('paginas.navUser')

@section('content')

<h1>Produtos cadastrados</h1>


    <table class="table mt-3">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>SKU</th>
            <th>Valor de compra(R$)</th>
            <th>Valor de venda(R$)</th>
            <th>Quantidade em estoque</th>
            <th>Qr Code</th>
            <th>Editar ou Excluir</th>
        </tr>
        @foreach($produtos as $produto)
            <tr>
                <td>{{ $produto->id }}</td>
                <td>{{ $produto->name }}</td>
                <td>{{ $produto->descricao }}</td>
                <td>{{ $produto->categoria }}</td>
                <td>{{ $produto->sku }}</td>
                <td>{{ $produto->valor_compra}}</td>
                <td>{{ $produto->valor_venda }}</td>
                <td>{{ $produto->quantidade }}</td>
               
                <td>


                    <a href="{{ route('editProduto', ['id' => $produto->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
                     <form action="{{ route('deleteProduto', ['id' => $produto->id]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </form>
                </td>
            </tr>
        @endforeach
    </table>

<a href="{{ route('cadastroProduto') }}" class="btn btn-dark"><i class="fas fa-plus"></i> Cadastrar novo produto</a>





@endsection