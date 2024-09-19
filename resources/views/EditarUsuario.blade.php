@extends('paginas.base')

@extends('paginas.nav')

@section('content')



<br>



    <div class="container">
    <h1>Editar e atualizar usuario</h1>
    <form action="{{ route('atualizar.usuario',['id' => $user->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
        </div>
        <div class="form-group">
            <label for="telefone">Data de nascimento</label>
            <input type="text" class="form-control" name="data_nascimento" id="data_nascimento" value="{{ \Carbon\Carbon::createFromFormat('d/m/Y', $user->data_nascimento)->format('d/m/Y') }}">
             
        </div>

        <button type="submit" class="btn btn-primary">Atualizar dados do usuário </button>
    </form>
</div>

@endsection







