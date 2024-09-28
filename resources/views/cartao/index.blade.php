@extends('Tamplate.Tamplate')

@section('Title','Cart')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cartões')

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
@section('Cartao', ' active')
@section('Categoria', ' ')
@section('Subcategoria', ' ')

@section('meuCss', '../css/meucss.css')

@section('btn')
<a href="cartao/cad">
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
        <h3 class="card-title">Relação de cartões de crédito</h3>
    </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
                        <th style="width: 10px">Código</th>
                        <th>Nome</th>
                        <th>Dia do vencimento</th>
                        <th>Dia do fechamento</th>
                        <th>Fatura atual</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($dados))
                    @foreach($dados as $cartao)
                        
                        <tr class="align-middle">
                            <td>{{$cartao->id}}.</td>
                            <td>{{$cartao->NomeCartao}}</td>
                            <td>{{$cartao->DataVencimentoCartao}}</td>
                            <td>{{$cartao->DataFechamentoCartao}}</td>
                            <td>R$ {{number_format($cartao->cartaoFatura->sum('ValorFatura'),2,',','.')}}</td>
                            <td>
                                <div class="row">
                                    <div class="d-flex gap-2">
                                        <a href="/cartao/{{$cartao->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                        <a href="/cartao/edit/{{$cartao->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                        <a href="/cartao/delete/{{$cartao->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
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