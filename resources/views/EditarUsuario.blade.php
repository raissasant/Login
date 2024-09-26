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
            <label for="date">Data de nascimento</label>
            <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" 
            value="{{ $user->data_nascimento }}">

        </div>
        <div class="form-group">
            <label for="password">Senha</label>
            <input type="text" class="form-control" name="password" id="password" value="{{ $user->password}}">
             
        </div>

        <button type="submit" class="btn btn-primary">Atualizar dados do usuário </button>
    </form>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection







