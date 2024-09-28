@extends('Tamplate.Tamplate')

@section('Title','Dívidas')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Dívidas')

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
@section('Divida', ' active')

@section('meuCss', '\css\meucss.css')

@section('btn')
<a href="divida/cad">
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
            <h3 class="card-title">Relação de dívidas</h3>
        </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>    
                            <th class="w-5">Código</th>
                            <th class="w-15">Descrição</th>
                            <th class="w-15">Valor inicial(R$)</th>
                            <th class="w-10">Valor pago(R$)</th>
                            <th class="w-15">Montante restante(R$)</th>
                            <th class="w-5">Visivel no home?</th>
                            <th class="w-5">Prioridade</th>
                            <th class="w-30">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($divida)>0)
                            @foreach($divida as $itens)
                                <tr>
                                    <td>{{$itens->id}}.</td>
                                    <td>{{$itens->NomeDivida}}</td>
                                    <td>{{number_format($itens->ValorInicialDivida,0,',','.')}}</td>
                                    <td>{{ number_format(
                                                            -1*
                                                            ($itens
                                                                ->dividaMovimentacaodivida
                                                                ->where('AtivoMovimentacaoDivida','1')
                                                                ->where('TipoMovimentacaoDivida','E')
                                                                ->sum('ValorMovimentacaoDivida') 
                                                            - 
                                                            $itens
                                                                ->dividaMovimentacaodivida
                                                                ->where('AtivoMovimentacaoDivida','1')
                                                                ->where('TipoMovimentacaoDivida','S')
                                                                ->sum('ValorMovimentacaoDivida')
                                                            ), 0, ',', '.') }}</td>
                                    <td>{{number_format(
                                                        $itens
                                                            ->ValorInicialDivida 
                                                        - 
                                                        -1*
                                                        (
                                                            $itens
                                                                ->dividaMovimentacaodivida
                                                                ->where('AtivoMovimentacaoDivida','1')
                                                                ->where('TipoMovimentacaoDivida','E')
                                                                ->sum('ValorMovimentacaoDivida') 
                                                            - 
                                                            $itens
                                                                ->dividaMovimentacaodivida
                                                                ->where('AtivoMovimentacaoDivida','1')
                                                                ->where('TipoMovimentacaoDivida','S')
                                                                ->sum('ValorMovimentacaoDivida')
                                                        ),0,',','.')}}</td>
                                    <td>
                                        @if($itens->VisivelDashDivida == '1')
                                            <i title="Visível" class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                            <i title="Não visível" class="bi bi-x-circle-fill text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($itens->PrioridadeDivida == 'Baixa')
                                            <i title="Baixa" class="bi bi-exclamation-circle-fill text-success"></i>
                                        @elseif($itens->PrioridadeDivida == 'Média')
                                            <i title="Média" class="bi bi-exclamation-circle-fill text-warning"></i>
                                        @else
                                            <i  title="Alta" class="bi bi-exclamation-circle-fill text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="d-flex gap-2">
                                                <a href="/divida/{{$itens->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                                <a href="/divida/edit/{{$itens->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                                <a href="/divida/delete/{{$itens->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                            
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="8">Nenhuma dívida cadastrada!</td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->




@endsection