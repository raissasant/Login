@extends('paginas.base')

@extends('paginas.navUser')

@section('content')



<body>
    <h1>Cadastro de produto</h1>
    <br>
     
    <form action="{{ route('storeProduto')}}" method="POST">
      @csrf
    <div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Nome</label>
        <input type="text" name ="name" class="form-control" required id="exampleFormControlInput1" placeholder="Coloque o nome do produto">
      </div>
       <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label">Descrição</label>
      <input type="text" class="form-control" name="descricao" required id="exampleFormControlInput1" placeholder=" Informe a descrição">
    </div>
      <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label">Valor de compra</label>
      <input type="text" class="form-control" name="valor_compra"  required  id="exampleFormControlInput1" placeholder="Informe o valor de compra, coloque somente números inteiros">
    </div>
    <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label">Valor de venda</label>
      <input type="text" class="form-control" name="valor_venda" required id="exampleFormControlInput1" placeholder="Informe o valor de venda, coloque somente números inteiros">
    </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Altura</label>
        <input type="text" class="form-control" name="altura" required   id="exampleFormControlInput1" placeholder="Informe a altura do produto, coloque somente números inteiros">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Largura</label>
        <input type="text" class="form-control" name="largura" required id="exampleFormControlInput1" placeholder="Informe a largura do produto, coloque somente números inteiros">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Peso(Kg)</label>
        <input type="text" class="form-control" name="peso" required id="exampleFormControlInput1" placeholder="Informe o peso do produto, coloque números sem a virgula">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Quantidade</label>
        <input type="text" class="form-control" name="quantidade" required id="exampleFormControlInput1" placeholder="Coloque números sem a virgula">
      </div>
     <div class="mb-3">
      <label>Categoria</label>
      <select class="custom-select" required  name = "categoria" id="inputGroupSelect01">
        <option selected>Selecionar...</option>
        <option value="Matérias primas(Rações, Vacinas)">Matérias primas(Rações, Vacinas)</option>
        <option value="Higiene/Limpeza(Produtos quimicos)">Higiene/Limpeza(Produtos quimicos)</option>
        <option value="Equipamentos(Máquinas de corte)">Equipamentos(Máquinas de corte)</option>
        <option value="Alimentos(Produtos no geral)">Alimentos(Produtos no geral)</option>
      </select>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">SKU</label>
        <input type="text" class="form-control" required name="sku" id="exampleFormControlInput1" placeholder="Informe o SKU do produto">
      </div>

      
<button  type="submit" class="btn btn-success">Salvar os dados produto</button>
<button  type="reset" class="btn  btn-dark">Cancelar cadastro de produto</button>

  </div>
  </form>
  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
       

      </div>

  </body>



@endsection