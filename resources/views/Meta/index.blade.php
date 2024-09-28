@extends('Tamplate.Tamplate')

@section('Title','Metas')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Metas')

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
@section('Investimento', ' ')
@section('Meta', ' active')


@section('meuCss', '\css\meucss.css')

@section('btn')
<a href="meta/cad">
    <button class="btn btn-primary">
        <span class="icon bi bi-plus-circle"></span>
        <span class="title">Cadastrar</span>
    </button>
</a>
@endsection


@section('Content')



<div class="card col-xl-10 offset-xl-1">
        <div class="card-header">
            <h3 class="card-title">Relação de Metas</h3>
        </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>    
                            <th class="w-5">Código</th>
                            <th class="w-15">Descrição</th>
                            <th>Data Target</th>
                            <th class="w-15">Valor Meta(R$)</th>
                            <th class="w-10">Valor Atual(R$)</th>
                            <th>Atingimento</th>
                            <th>Valor mensal</th>
                            <th class="w-30">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        
                            @if(count($meta)>0)
                                @foreach($meta as $itens)
                                    <tr>
                                        <td>{{$itens->id}}.</td>
                                        <td>{{$itens->NomeMeta}}</td>
                                        <td>{{date('d/m/y',strtotime($itens->DataFimMeta))}}</td>
                                        <td>R$ {{number_format($itens->ValorMeta,0,',','.')}}</td>
                                        <td> 
                                            R$ {{
                                                number_format(
                                                    $itens
                                                        ->metaMovimentacao
                                                        ->where('AtivoMovimentacaoMeta','1')
                                                        ->where('TipoMovimentacaoMeta','E')
                                                        ->sum('ValorMovimentacaoMeta')
                                                    -
                                                    $itens
                                                        ->metaMovimentacao
                                                        ->where('AtivoMovimentacaoMeta','1')
                                                        ->where('TipoMovimentacaoMeta','S')
                                                        ->sum('ValorMovimentacaoMeta'),
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </td>
                                        <td>
                                            <div class="progress" role="progressbar" aria-label="Default striped example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar" style="width: {{
                                                        number_format(
                                                            (
                                                                (
                                                                    $itens
                                                                        ->metaMovimentacao
                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                        ->where('TipoMovimentacaoMeta','E')
                                                                        ->sum('ValorMovimentacaoMeta')
                                                                    -
                                                                    $itens
                                                                        ->metaMovimentacao
                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                        ->where('TipoMovimentacaoMeta','S')
                                                                        ->sum('ValorMovimentacaoMeta')
                                                                )
                                                                /
                                                                $itens->ValorMeta
                                                            ) * 100,
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                    }}%">
                                                    {{
                                                        number_format(
                                                            (
                                                                (
                                                                    $itens
                                                                        ->metaMovimentacao
                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                        ->where('TipoMovimentacaoMeta','E')
                                                                        ->sum('ValorMovimentacaoMeta')
                                                                    -
                                                                    $itens
                                                                        ->metaMovimentacao
                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                        ->where('TipoMovimentacaoMeta','S')
                                                                        ->sum('ValorMovimentacaoMeta')
                                                                )
                                                                /
                                                                $itens->ValorMeta
                                                            ) * 100,
                                                            1,
                                                            ',',
                                                            '.'
                                                        )
                                                    }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $intervalo = Today()->diff($itens->DataFimMeta);
                                                $diferencaEmMeses = ($intervalo->y * 12) + $intervalo->m;
                                                $valorMensal = ($itens->ValorMeta - (
                                                                                    $itens
                                                                                        ->metaMovimentacao
                                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                                        ->where('TipoMovimentacaoMeta','E')
                                                                                        ->sum('ValorMovimentacaoMeta')
                                                                                    -
                                                                                    $itens
                                                                                        ->metaMovimentacao
                                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                                        ->where('TipoMovimentacaoMeta','S')
                                                                                        ->sum('ValorMovimentacaoMeta')
                                                                                    
                                                                                    )
                                                                ) / $diferencaEmMeses ;
                                            @endphp
                                            R$ {{
                                                number_format(
                                                    $valorMensal,
                                                    2,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="d-flex gap-2">
                                                    <a href="/meta/{{$itens->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                                    <a href="/meta/edit/{{$itens->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                                    <a href="/meta/delete/{{$itens->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                                
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="8">Nenhuma meta cadastrada!</td>
                            @endif
                        
                    </tbody>
                </table>
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->




@endsection