@extends('Tamplate.Tamplate')

@section('Title','Dashboard')
@section('logo', '/imgsystem/logo.png')
@section('cssAdmin','/css/adminlte.css')
@section('jsAdmin', '/js/adminlte.js')
@section('meuCss', '/css/meucss.css')

@section('TitlePage')
Dashboard: {{date('d/m/Y', strtotime($start))}} - {{date('d/m/Y', strtotime($end))}}
@endsection

@section('Dash', ' ')
@section('imgUser')
    @if(!empty(session('imgUserPerfil')))
        /imguser/{{session('imgUserPerfil')}}
    @else 
        /imguser/user.png
    @endif
@endsection

@section('DashFim', ' active')
@section('Cad', ' ')
@section('Banco', ' ')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')
@section('Divida', ' ')
@section('Investimento', ' ')
@section('Meta', ' ')

@section('Content')
<div class="container-fluid">
    <div class="card card-primary card-outline col-12 mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">Filtros</div>
            <button class="btn btn-primary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros">
                <i class="bi bi-funnel-fill"></i>
            </button>
        </div>
        
        <div id="collapseFiltros" class="collapse">
            <div class="card-body">
                <form action="/home/filter" method="post" id="formPesquisa2">
                    @csrf
                    <div class="row">
                        <div class="form-group col-xl-4 col-md-4 mb-3">
                            <label for="dataStart" class="form-label">Data inicial:</label>
                            <input type="date" name="start" id="dataStart" class="form-control" required>
                        </div>
                        <div class="form-group col-xl-4 col-md-4 mb-3">
                            <label for="dataEnd" class="form-label">Data final:</label>
                            <input type="date" name="end" id="dataEnd" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <button type="button" id="btnPesquisar2" class="btn btn-primary">
                    <i class="bi bi-search"></i> Pesquisar
                </button>
                <button id="limparFiltros" class="btn btn-secondary" onclick="location.href='/home'">
                    <i class="bi bi-trash-fill"></i> Limpar
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box"> 
                <span class="info-box-icon text-bg-success shadow-sm"><i class="bi bi-currency-dollar"></i></span>
                <div class="info-box-content"> 
                    <span class="info-box-text">Receitas</span> 
                    <span class="info-box-number"><small>R$</small> {{number_format($mov->where('TipoMovimentacaoFinanc','R')->sum('ValorFimMovimentacaoFinanc'),2,',','.')}}</span> 
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box"> 
                <span class="info-box-icon text-bg-danger shadow-sm"><i class="bi bi-cash-stack"></i></span>
                <div class="info-box-content"> 
                    <span class="info-box-text">Despesas</span> 
                    <span class="info-box-number"><small>R$</small> {{number_format($mov->where('TipoMovimentacaoFinanc','D')->sum('ValorFimMovimentacaoFinanc') + $movCard->sum('ValorMovimentacaoCartao'),2,',','.')}}</span> 
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box"> 
                <span class="info-box-icon text-bg-primary shadow-sm"><i class="bi bi-wallet2"></i></span>
                <div class="info-box-content"> 
                    <span class="info-box-text">Saldo</span> 
                    @php $saldoVal = $mov->where('TipoMovimentacaoFinanc','R')->sum('ValorFimMovimentacaoFinanc') - $mov->where('TipoMovimentacaoFinanc','D')->sum('ValorFimMovimentacaoFinanc'); @endphp
                    <span class="info-box-number {{ $saldoVal < 0 ? 'text-danger' : '' }}">
                        <small>R$</small> {{number_format($saldoVal,2,',','.')}}
                    </span> 
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box"> 
                <span class="info-box-icon text-bg-warning shadow-sm"><i class="bi bi-exclamation-triangle"></i></span>
                <div class="info-box-content"> 
                    <span class="info-box-text">Dívidas</span> 
                    <span class="info-box-number">
                        <small>R$</small>
                        @php 
                            $vlDivida = 0;
                            foreach($div as $itens){
                                foreach($itens->dividaMovimentacaodivida->where('AtivoMovimentacaoDivida','1') as $fim){
                                    $vlDivida += ($fim->TipoMovimentacaoDivida == 'E') ? -$fim->ValorMovimentacaoDivida : $fim->ValorMovimentacaoDivida;
                                }
                            }
                        @endphp
                        {{number_format(($div->sum('ValorInicialDivida') - $vlDivida ),2,',','.')}}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Movimentações mensais</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center"><strong>Fluxo de monetário (R$)</strong></p>
                            <div id="sales-chart"></div>
                        </div>
                        <div class="col-md-4">
                            <p class="text-center"><strong>Tipo de despesas</strong></p>
                            <div id="pie-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Demostrativos</h3>
                </div>
                <div class="card-body">
                   @php
                        $meses = [1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun', 
                                7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'];
                        $labels = ['R' => 'Receitas', 'D' => 'Despesas'];
                    @endphp

                    <style>
                        .accordion-toggle { cursor: pointer; }
                        .hiddenRow { padding: 0 !important; border: 0 !important; }
                        /* Para garantir que o ícone mude quando abrir (opcional) */
                        .accordion-toggle[aria-expanded="true"] .bi-plus-square::before { content: "\f2ea"; } /* ícone de menos */
                    </style>

                    <div class="container-fluid py-4">
                        @foreach($labels as $sigla => $titulo)
                            @if(isset($dados[$sigla]))
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header {{ $sigla == 'R' ? 'bg-success' : 'bg-danger' }} text-white">
                                        <h5 class="mb-0">{{ $titulo }}</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th style="width: 250px;">Categoria / Subcategoria</th>
                                                    @foreach($meses as $m) <th class="text-center">{{ $m }}</th> @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dados[$sigla] as $nomeCat => $subcategorias)
                                                    @php $idUnico = Str::slug($sigla.$nomeCat); @endphp
                                                    
                                                    {{-- Gatilho do Collapse - Se usar Bootstrap 5 use data-bs-target --}}
                                                    <tr class="table-secondary font-weight-bold accordion-toggle" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse-{{ $idUnico }}" 
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $idUnico }}">
                                                        <td>
                                                            <i class="bi bi-plus-square me-2"></i>{{ $nomeCat }}
                                                        </td>
                                                        @for($m = 1; $m <= 12; $m++)
                                                            <td class="text-center">
                                                                @php
                                                                    $totalMes = 0;
                                                                    foreach($subcategorias as $sub) {
                                                                        $totalMes += $sub[$m] ?? 0;
                                                                    }
                                                                @endphp
                                                                {{ number_format($totalMes, 2, ',', '.') }}
                                                            </td>
                                                        @endfor
                                                    </tr>

                                                    {{-- Conteúdo do Collapse --}}
                                                    <tr>
                                                        <td colspan="13" class="hiddenRow">
                                                            <div class="collapse" id="collapse-{{ $idUnico }}">
                                                                <table class="table table-sm table-borderless mb-0">
                                                                    <tbody>
                                                                        @foreach($subcategorias as $nomeSub => $valoresMes)
                                                                            <tr>
                                                                                <td style="width: 250px;" class="ps-4 text-muted small">
                                                                                    <i class="bi bi-arrow-return-right me-1"></i> {{ $nomeSub }}
                                                                                </td>
                                                                                @for($m = 1; $m <= 12; $m++)
                                                                                    <td class="text-center small text-muted">
                                                                                        {{ number_format($valoresMes[$m] ?? 0, 2, ',', '.') }}
                                                                                    </td>
                                                                                @endfor
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    {{-- Tabela de Resultado Final (Igual ao anterior) --}}
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-dark text-white"><h5>Fluxo de Caixa Consolidado</h5></div>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Descrição</th>
                                    @foreach($meses as $m) <th class="text-center">{{ $m }}</th> @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-success font-weight-bold">
                                    <td>(+) Receitas</td>
                                    @for($m = 1; $m <= 12; $m++)
                                        <td class="text-center">{{ number_format($resumoMensal[$m]['R'] ?? 0, 2, ',', '.') }}</td>
                                    @endfor
                                </tr>
                                <tr class="text-danger font-weight-bold">
                                    <td>(-) Despesas</td>
                                    @for($m = 1; $m <= 12; $m++)
                                        <td class="text-center">{{ number_format($resumoMensal[$m]['D'] ?? 0, 2, ',', '.') }}</td>
                                    @endfor
                                </tr>
                                <tr class="table-info font-weight-bold">
                                    <td>(=) Saldo Liquido</td>
                                    @for($m = 1; $m <= 12; $m++)
                                        @php $res = ($resumoMensal[$m]['R'] ?? 0) - ($resumoMensal[$m]['D'] ?? 0); @endphp
                                        <td class="text-center {{ $res < 0 ? 'text-danger' : 'text-primary' }}">
                                            {{ number_format($res, 2, ',', '.') }}
                                        </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Orçamento Mensal</h3>
                </div>
                <div class="card-body">
                    @foreach($categoria as $itens)
                        @if($itens->TipoCategoria == 'D')
                            @php
                                $totalGasto = $itens->categoriaMovFin->sum('ValorFimMovimentacaoFinanc') + $itens->categoriaMovCard->sum('ValorMovimentacaoCartao');
                                $totalOrcado = $itens->categoriaItensOrcamento->sum('ValorItemOrc');
                                $porcentagem = ($totalOrcado > 0) ? ($totalGasto / $totalOrcado) : 0;
                                
                                $corBarra = 'text-bg-primary';
                                if($porcentagem >= 0.8 && $porcentagem < 1) $corBarra = 'text-bg-warning';
                                elseif($porcentagem >= 1) $corBarra = 'text-bg-danger';
                            @endphp
                            <div class="progress-group mb-3">
                                {{$itens->NomeCategoria}}
                                <span class="float-end"><b>{{number_format($totalGasto,2,',','.')}}</b> / {{number_format($totalOrcado,2,',','.')}}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar {{$corBarra}}" style="width: {{min($porcentagem * 100, 100)}}%">
                                        {{number_format($porcentagem * 100,1,',','.')}}%
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Metas</h3></div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Data Target</th>
                                <th>Valor Meta</th>
                                <th>Valor Atual</th>
                                <th>Atingimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meta as $itens)
                                @php
                                    $atual = $itens->metaMovimentacao->where('AtivoMovimentacaoMeta','1')->where('TipoMovimentacaoMeta','E')->sum('ValorMovimentacaoMeta') - 
                                             $itens->metaMovimentacao->where('AtivoMovimentacaoMeta','1')->where('TipoMovimentacaoMeta','S')->sum('ValorMovimentacaoMeta');
                                    $percMeta = ($itens->ValorMeta > 0) ? ($atual / $itens->ValorMeta) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{$itens->id}}</td>
                                    <td>{{$itens->NomeMeta}}</td>
                                    <td>{{date('d/m/y',strtotime($itens->DataFimMeta))}}</td>
                                    <td>R$ {{number_format($itens->ValorMeta,2,',','.')}}</td>
                                    <td>R$ {{number_format($atual,2,',','.')}}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{$percMeta}}%">{{number_format($percMeta,1)}}%</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">Nenhuma meta encontrada.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Faturas</h3></div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mês</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                                <th>Cartão</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fat as $itens)
                                <tr>
                                    <td>{{$itens->MesFatura}}</td>
                                    <td>R$ {{number_format($itens->ValorFatura,2,',','.')}}</td>
                                    <td>{{date('d/m/Y', strtotime($itens->DataVencimento))}}</td>
                                    <td>
                                        @php
                                            $statusCor = 'text-bg-secondary';
                                            if($itens->StatusFatura == 'Pago') $statusCor = 'text-bg-success';
                                            elseif($itens->StatusFatura == 'Fechada' && $itens->DataVencimento < now()) $statusCor = 'text-bg-danger';
                                            elseif($itens->StatusFatura == 'Fechada') $statusCor = 'text-bg-info';
                                        @endphp
                                        <span class="badge {{$statusCor}}">{{$itens->StatusFatura}}</span>
                                    </td>
                                    <td>{{$itens->faturaCartao->NomeCartao}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('Script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>        
<script>
    $(document).ready(function(){
        $('#btnPesquisar2').on('click', function(){ $('#formPesquisa2').submit(); });
        $('#dataStart').on('change', function(){ $('#dataEnd').val($('#dataStart').val()); });
    });

    // Gráfico de Vendas/Mensal
    var sales_chart_options = {
        series: [
            { name: "Receita", type: 'column', data: @json($receitas) },
            { name: "Despesas", type: 'column', data: @json($despesas) },
            { name: "Saldo", type: 'line', data: @json($saldos) }
        ],
        chart: { height: 250, toolbar: { show: false } },
        colors: ["#198754", "#dc3545", "#ffc107"],
        xaxis: { categories: @json($meses) },
        stroke: { curve: "smooth", width: [0, 0, 3] },
        tooltip: { y: { formatter: function (val) { return "R$ " + val.toLocaleString('pt-BR'); } } }
    };
    new ApexCharts(document.querySelector("#sales-chart"), sales_chart_options).render();

    // Gráfico de Pizza
    var pie_chart_options = {
        series: @json($Vl),
        chart: { type: "donut", height: 250 },
        labels: @json($TipoGasto),
        colors: ["#198754", "#dc3545", "#ffc107"],
        dataLabels: { enabled: true, formatter: (val) => val.toFixed(1) + "%" }
    };
    new ApexCharts(document.querySelector("#pie-chart"), pie_chart_options).render();
</script>
@endsection