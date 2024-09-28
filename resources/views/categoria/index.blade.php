@extends('Tamplate.Tamplate')

@section('Title','Categorias')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Categorias')

@section('jsAdmin', '../js/adminlte.js')

@section('Dash', ' ')

@section('imgUser')

    @if(!empty(session('imgUserPerfil')))
        ../imguser/{{session('imgUserPerfil')}}
    @else 
        ../imguser/user.png
    @endif

@endsection

@section('Dash', ' ')
@section('Cad', ' active')
@section('Banco', ' ')
@section('Cartao', ' ')
@section('Categoria', ' active')
@section('Subcategoria', ' ')

@section('meuCss', '../css/meucss.css')

@section('btn')
<a href="categoria/cad">
    <button class="btn btn-primary">
        <span class="icon bi bi-plus-circle"></span>
        <span class="title">Cadastrar</span>
    </button>
</a>
@endsection

@section('meuCss', '../css/meucss.css')

@section('Content')



<div class="card col-xl-10 offset-xl-1">
    <div class="card-header">
        <h3 class="card-title">Relação de categorias</h3>
    </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">Código</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Ícone</th>
                            
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($categoria))
                        @foreach($categoria as $Dados)
                            
                            <tr class="align-middle">
                                <td>{{$Dados->id}}.</td>
                                <td>{{$Dados->NomeCategoria}}</td>
                                <td> @if($Dados->TipoCategoria == 'R')  Receita  @elseif($Dados->TipoCategoria == 'D') Despesa @else Transferência  @endif </td>
                                <td>
                                    <div class="row">
                                        <div class="col-2 col-lg-2">
                                            <button class="btn @if($Dados->TipoCategoria == 'R')  btn-receita  @elseif($Dados->TipoCategoria == 'D')  btn-despesa @else btn-secondary  @endif" title="Visualizar"><i class="bi bi-{{$Dados->IconeCategoria}}"></i> </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <a href="/categoria/{{$Dados->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                            <a href="/categoria/edit/{{$Dados->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                            <a href="/categoria/delete/{{$Dados->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        
                        @endforeach
                        @else
                            <tr class="align-middle">
                                <td>Nenhum dado cadastrado!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
@endsection