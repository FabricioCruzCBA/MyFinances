@extends('Tamplate.Tamplate')

@section('Title','Bancos')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Bancos')

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
@section('Banco', ' active')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')

@section('meuCss', '../css/meucss.css')

@section('btn')
<a href="bancos/cad">
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
        <h3 class="card-title">Relação de bancos cadastrados</h3>
    </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table table-striped table-hover table-responsive">
                <thead>
                    <tr>
                        <th style="width: 10px">Código</th>
                        <th>Descrição</th>
                        <th>Saldo</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banco as $dados)
                    
                        <tr class="align-middle">
                            <td>{{$dados->id}}.</td>
                            <td>{{$dados->NomeBanco}}</td>
                            <td @if(($dados->bancoMov
                                                ->where('AtivoMovimentacaoFinanc', '1')
                                                ->where('TipoMovimentacaoFinanc', 'R')
                                                ->where('PagoMovimentacaoFinanc','1')
                                                ->sum('ValorMovimentacaoFinanc')
                                        -
                                        $dados
                                                ->bancoMov
                                                ->where('AtivoMovimentacaoFinanc', '1')
                                                ->where('TipoMovimentacaoFinanc', 'D')
                                                ->where('PagoMovimentacaoFinanc','1')
                                                ->sum('ValorMovimentacaoFinanc'))<0) class="text-danger" @else class="text-success" @endif>
                                R$
                                {{
                                    number_format(
                                        $dados
                                                ->bancoMov
                                                ->where('AtivoMovimentacaoFinanc', '1')
                                                ->where('TipoMovimentacaoFinanc', 'R')
                                                ->where('PagoMovimentacaoFinanc','1')
                                                ->sum('ValorMovimentacaoFinanc')
                                        -
                                        $dados
                                                ->bancoMov
                                                ->where('AtivoMovimentacaoFinanc', '1')
                                                ->where('TipoMovimentacaoFinanc', 'D')
                                                ->where('PagoMovimentacaoFinanc','1')
                                                ->sum('ValorMovimentacaoFinanc'),
                                        2,
                                        '.',
                                        ','
                                    )
                                }}
                            </td>
                            <td>
                                <div class="row">
                                    <div class="d-flex gap-2">
                                        <a href="/bancos/{{$dados->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                        <a href="/bancos/edit/{{$dados->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                        <a href="/bancos/delete/{{$dados->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    
                    @endforeach
                </tbody>
            </table>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
@endsection