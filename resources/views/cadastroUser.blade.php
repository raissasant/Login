@extends('paginas.base')

@extends('paginas.nav')

@section('content')

<body>
    <h1>Adicionar um novo usuário</h1>
    <br>
     
    <form action="{{ route('cadastrando/user')}}" method="POST">
      @csrf
    <div>
      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="Coloque seu nome completo" required>
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" name="cpf" required id="cpf" placeholder="Coloque seu CPF">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Coloque seu e-mail" required>
      </div>

      <div class="mb-3">
        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Defina sua senha" required>
      </div>

      <!-- Campo para definir se o usuário é Administrador ou Usuário Comum -->
      <div class="mb-3">
        <label for="is_admin" class="form-label">Tipo de Usuário</label>
        <select class="form-control" name="is_admin" id="is_admin" required>
          <option value="0">Usuário Comum</option>
          <option value="1">Administrador</option>
        </select>
      </div>

      <button type="submit" class="btn btn-dark">Salvar</button>
    </div>
    </form>
</body>

@endsection
