@extends('paginas.base')

@extends('paginas.nav')

@section('content')

<br>

<div class="container">
    <h1>Editar e atualizar fornecedor</h1>
    <form action="{{ route('atualizandoFornecedor', ['id' => $fornecedor->id]) }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="name">Nome</label>
          <input type="text" class="form-control" name="name" id="name" value="{{ $fornecedor->name }}">
       </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="{{ $fornecedor->email }}">
        </div>

        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" class="form-control" name="telefone" id="telefone" value="{{ $fornecedor->telefone }}">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status">
                <option value="ativo" {{ $fornecedor->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ $fornecedor->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

@endsection
