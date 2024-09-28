@extends('Tamplate.Tamplate')

@section('Title','investimentos')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Investimentos')

@section('jsAdmin', '\js\adminlte.js')

@section('Dash', ' ')

@section('imgUser')

    @if(!empty(session('imgUserPerfil')))
        \imguser\{{session('imgUserPerfil')}}
    @else 
        \imguser\user.png
    @endif

@endsection

@section('Dash', ' ')
@section('Cad', ' active')
@section('Banco', ' ')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')
@section('Divida', ' ')
@section('Investimento', ' active')

@section('meuCss', '\css\meucss.css')

@section('btn')
<a href="investimento/cad">
    <button class="btn btn-primary">
        <span class="icon bi bi-plus-circle"></span>
        <span class="title">Cadastrar</span>
    </button>
</a>
@endsection


@section('Content')



<div class="card col-xl-10 offset-xl-1">
        <div class="card-header">
            <h3 class="card-title">Relação de investimento</h3>
        </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>    
                            <th class="w-5">Código</th>
                            <th class="w-15">Descrição</th>
                            <th class="w-15">Valor inicial(R$)</th>
                            <th class="w-10">Valor Atual(R$)</th>
                            <th class="w-30">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        
                            @if(count($investimento)>0)
                                @foreach($investimento as $itens)
                                    <tr>
                                        <td>{{$itens->id}}.</td>
                                        <td>{{$itens->NomeInvestimento}}</td>
                                        <td>{{number_format($itens->ValorInicialInvestimento,0,',','.')}}</td>
                                        <td> {{number_format(
                                                $itens->ValorInicialInvestimento 
                                                + 
                                                (
                                                    $itens
                                                                ->investimentoMovimentacao
                                                                ->where('TipoMovimentacaoInvestimento', 'E')
                                                                ->where('AtivoMovimentacaoInvestimento','1')
                                                                ->sum('ValorMovimentacaoInvestimento')
                                                    -
                                                    $itens
                                                                ->investimentoMovimentacao
                                                                ->where('TipoMovimentacaoInvestimento', 'S')
                                                                ->where('AtivoMovimentacaoInvestimento','1')
                                                                ->sum('ValorMovimentacaoInvestimento')
                                                ), 
                                                0,
                                                ',',
                                                '.') }}
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="d-flex gap-2">
                                                    <a href="/investimento/{{$itens->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                                    <a href="/investimento/edit/{{$itens->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                                    <a href="/investimento/delete/{{$itens->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                                
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="8">Nenhum investimento cadastrada!</td>
                            @endif
                        
                    </tbody>
                </table>
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->




@endsection