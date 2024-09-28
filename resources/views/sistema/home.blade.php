@extends('Tamplate.Tamplate')

@section('Title','Dashboard')

@section('logo', '/imgsystem/logo.png')

@section('cssAdmin','/css/adminlte.css')

@section('TitlePage')
    Dashboard: {{date('d/m/Y', strtotime($start))}} - {{date('d/m/Y', strtotime($end))}}
@endsection

@section('jsAdmin', '/js/adminlte.js')

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


@section('meuCss', '/css/meucss.css')

@section('btn')


@endsection


@section('Content')

<div class="card card-primary card-outline col-12 mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title">Filtros</div>
        <button class="btn btn-primary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros">
            <i class="bi bi-funnel-fill"></i> <!-- Ícone do botão para expandir/recolher -->
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
            <button type="button" id="btnPesquisar2" class="btn btn-primary" >
                <span class="icon bi bi-search"></span>
                <span class="title">Pesquisar</span>
            </button>
            <button id="limparFiltros" class="btn btn-primary" onclick="location.href='/home'">
                <span class="icon bi bi-trash-fill"></span>
                <span class="title">Limpar</span>
            </button>
        </div>
    </div>
</div>

<div class="app-content"> <!--begin::Container-->
    <div class="container-fluid"> <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box"> 
                    <span class="info-box-icon text-bg-success shadow-sm"> 
                        <i class="bi bi-currency-dollar"></i> 
                    </span>
                    <div class="info-box-content"> 
                        <span class="info-box-text">Receitas</span> 
                        <span class="info-box-number">
                            <small>R$</small> 
                            {{number_format($mov->where('TipoMovimentacaoFinanc','R')->sum('ValorFimMovimentacaoFinanc'),2,',','.')}}
                        </span> 
                    </div> <!-- /.info-box-content -->
                </div> <!-- /.info-box -->
            </div> <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box"> 
                    <span class="info-box-icon text-bg-danger shadow-sm"> 
                        <i class="bi bi-cash-stack"></i>
                    </span>
                    <div class="info-box-content"> 
                        <span class="info-box-text">Despesas</span> 
                        <span class="info-box-number">
                            <small>R$</small> 
                            {{number_format($mov->where('TipoMovimentacaoFinanc','D')->sum('ValorFimMovimentacaoFinanc') + $movCard->sum('ValorMovimentacaoCartao'),2,',','.')}}
                        </span> 
                    </div> <!-- /.info-box-content -->
                </div> <!-- /.info-box -->
            </div> <!-- /.col --> <!-- fix for small devices only --> <!-- <div class="clearfix hidden-md-up"></div> -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box"> 
                    <span class="info-box-icon text-bg-primary shadow-sm"> 
                        <i class="bi bi-wallet2"></i> 
                    </span>
                    <div class="info-box-content"> 
                        <span class="info-box-text">Saldo</span> 
                        <span class="info-box-number @if(($mov->where('TipoMovimentacaoFinanc','R')->sum('ValorFimMovimentacaoFinanc')-$mov->where('TipoMovimentacaoFinanc','D')->sum('ValorFimMovimentacaoFinanc'))<0) text-danger @endif">
                            <small>R$</small> 
                            {{number_format(($mov->where('TipoMovimentacaoFinanc','R')->sum('ValorFimMovimentacaoFinanc')-$mov->where('TipoMovimentacaoFinanc','D')->sum('ValorFimMovimentacaoFinanc')),2,',','.')}}
                        </span> 
                    </div> <!-- /.info-box-content -->
                </div> <!-- /.info-box -->
            </div> <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box"> 
                    <span class="info-box-icon text-bg-warning shadow-sm"> 
                        <i class="bi bi-exclamation-triangle"></i> 
                    </span>
                    <div class="info-box-content"> 
                        <span class="info-box-text">Dívidas</span> 
                        <span class="info-box-number">
                            <small>R$</small>
                                @php 
                                    $vl = 0; // Certifique-se de inicializar a variável $vl
                                @endphp

                                @foreach($div as $itens)
                                    @foreach($itens->dividaMovimentacaodivida->where('AtivoMovimentacaoDivida','1') as $fim)
                                        @php
                                            if($fim->TipoMovimentacaoDivida == 'E'){
                                                $vl += -$fim->ValorMovimentacaoDivida; // Adiciona o valor
                                            } else {
                                                $vl += +$fim->ValorMovimentacaoDivida; // Subtrai o valor
                                            }
                                        @endphp
                                    @endforeach
                                @endforeach
                                {{number_format(($div->sum('ValorInicialDivida') - $vl ),2,',','.')}}
                        </span>
                    </div> <!-- /.info-box-content -->
                </div> <!-- /.info-box -->
            </div> <!-- /.col -->
        </div> <!-- /.row --> <!--begin::Row-->
        <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Movimentações mensais</h5>
                    <div class="card-tools"> 
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"> 
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i> 
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i> 
                        </button>
                    </div>
                </div> <!-- /.card-header -->
                <div class="card-body"> <!--begin::Row-->
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center"> <strong>Fluxo de monetário (R$)</strong> </p>
                            <div id="sales-chart"></div>
                        </div> <!-- /.col -->
                        <div class="col-md-4">
                            <p class="text-center"> <strong>Tipo de despesas</strong> </p>
                            <div id="pie-chart"></div>
                        </div> <!-- /.col -->
                    </div> <!--end::Row-->
                </div> <!-- ./card-body -->
                <div class="card-footer"> <!--begin::Row-->
                </div> <!-- /.card-footer -->
            </div> <!-- /.card -->
        </div> <!-- /.col -->
    </div> <!--end::Row--> <!--begin::Row-->
    <div class="row"> <!-- Start col -->
        <div class="col-12"> <!-- Info Boxes Style 2 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Orçamento</h3>
                    <div class="card-tools"> 
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"> 
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i> 
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i> 
                        </button> 
                    </div>
                </div> <!-- /.card-header -->
                <div class="card-body"> <!--begin::Row-->
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center"> <strong>Orçamento mensal</strong> </p>
                                @foreach($categoria as $itens)
                                    @if($itens->TipoCategoria == 'D')
                                        @php
                                            if($itens->categoriaItensOrcamento->sum('ValorItemOrc')> 0){
                                                $por = ($itens->categoriaMovFin->sum('ValorFimMovimentacaoFinanc')+$itens->categoriaMovCard->sum('ValorMovimentacaoCartao')) / $itens->categoriaItensOrcamento->sum('ValorItemOrc');
                                            }else{
                                                $por = 0;
                                            }
                                        @endphp
                                            <div class="progress-group">
                                                {{$itens->NomeCategoria}}
                                                <span class="float-end"><b>{{number_format($itens->categoriaMovFin->sum('ValorFimMovimentacaoFinanc')+$itens->categoriaMovCard->sum('ValorMovimentacaoCartao'),2,',','.')}}</b>/{{number_format($itens->categoriaItensOrcamento->sum('ValorItemOrc')+$itens->categoriaMovCard->sum('ValorMovimentacaoCartao'),2,',','.')}}</span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar @if($por < 0.8 ) text-bg-primary @elseif($por < 1) text-bg-warning @else text-bg-danger @endif" style="width: {{$por * 100}}%">{{number_format($por * 100,1,',','.')}}%</div>
                                                </div>
                                            </div>
                                    @endif
                                @endforeach
                            </div> <!-- /.col -->
                        </div> <!--end::Row-->
                    </div> <!-- /.card-body -->
                    <div class="card-footer p-0">
                    </div> <!-- /.footer -->
                </div> <!-- /.card --> <!-- PRODUCT LIST -->
            </div> <!-- /.col -->
            <div class="col-md-12 mb-3"> <!--begin::Row-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Metas</h3>
                        <div class="card-tools"> 
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"> 
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i> 
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i> 
                            </button> 
                        </div>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($meta)>0)
                                            @foreach($meta as $itens)
                                                <tr>
                                                    <td><a href="/meta/{{$itens->id}}" class='link-offset-2  link-underline link-underline-opacity-0'>{{$itens->id}}.</a></td>
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
                                                                ) 
                                                                / 
                                                                $diferencaEmMeses ;
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
                                                            
                                                    </tr>
                                            @endforeach
                                        @else
                                            <td colspan="8">Nenhuma meta cadastrada!</td>
                                        @endif
                                            
                                    </tbody>
                                </table>
                            </div> <!-- /.table-responsive -->
                        </div> <!-- /.card-body -->
                        <div class="card-footer clearfix"> 
                            <a href="/meta" class="btn btn-sm btn-secondary float-end">
                                Ver metas
                                </a> 
                            </div> <!-- /.card-footer -->
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!--end::Row-->
                <div class="row">
                    <div class="col-12 mb-3"> <!--begin::Row-->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Investimentos</h3>
                                <div class="card-tools"> 
                                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"> 
                                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i> 
                                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i> 
                                    </button>     
                                </div>
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
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            
                                                @if(count($investimento)>0)
                                                    @foreach($investimento as $itens)
                                                        <tr>
                                                            <td><a href="/investimento/{{$itens->id}}" class='link-offset-2  link-underline link-underline-opacity-0'>{{$itens->id}}.</a></td>
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
                                                            
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <td colspan="8">Nenhum investimento cadastrada!</td>
                                                @endif
                                            
                                        </tbody>
                                    </table>
                                </div> <!-- /.table-responsive -->
                            </div> <!-- /.card-body -->
                            <div class="card-footer clearfix"> 
                                <a href="/investimento" class="btn btn-sm btn-secondary float-end">
                                    Ver investimentos
                                </a> 
                            </div> <!-- /.card-footer -->
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12 mb-3"> <!--begin::Row-->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Faturas</h3>
                                <div class="card-tools"> 
                                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"> 
                                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i> 
                                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i> 
                                    </button> 
                                </div>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>    
                                                <th class="">Mês</th>
                                                <th class="">Valor(R$)</th>
                                                <th>Data vencimento</th>
                                                <th class="">Status</th>
                                                <th>Cartão</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fat as $itens)
                                                <tr>
                                                    <td>{{$itens->MesFatura}}</td>
                                                    <td>{{number_format($itens->ValorFatura,2,',','.')}}</td>
                                                    <td>{{date('d/m/Y', strtotime($itens->DataVencimento))}}</td>
                                                    <td>
                                                        @php
                                                            if($itens->StatusFatura == 'Fechada'){
                                                                if($itens->DataVencimento < now()){
                                                                    $cor = 'text-bg-danger';
                                                                    $title = 'Atrasado';
                                                                }else{
                                                                    $cor = 'text-bg-info';
                                                                    $title = 'Previsto';
                                                                }
                                                                
                                                            }elseif($itens->StatusFatura == 'Pago'){
                                                                $cor = 'text-bg-success';
                                                                $title = 'Pago';
                                                            }else{
                                                                $cor = 'text-bg-warning';
                                                                $title = 'Pagamento parcial';
                                                            }
                                                        @endphp
                                                        <span class="badge {{$cor}}" title="{{$title}}">
                                                            {{$itens->StatusFatura}}
                                                        </span> 
                                                    </td>
                                                    <td>
                                                        {{$itens->faturaCartao->NomeCartao}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div> <!-- /.table-responsive -->
                            </div> <!-- /.card-body -->
                            <div class="card-footer clearfix"> 
                                <a href="/cartao" class="btn btn-sm btn-secondary float-end">
                                    Ver cartões
                                </a> 
                            </div> <!-- /.card-footer -->
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->
                </div>
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </div>

    

@endsection

@section('Script')
<SCRIpt>
    $(document).ready(function(){
        $('#btnPesquisar2').on('click', function(){
            $('#formPesquisa2').submit();
        });

        $('#dataStart').on('change', function(){
            $('#dataEnd').val($('#dataStart').val());
        });
    });
</SCRIpt>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>        
        
    <script>
        
        
        

        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script> <!--end::OverlayScrollbars Configure--> <!-- OPTIONAL SCRIPTS --> <!-- apexcharts -->
    
    <script>
        
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        /* apexcharts
         * -------
         * Here we will create a few charts using apexcharts
         */

        //-----------------------
        // - MONTHLY SALES CHART -
        //-----------------------
        // Dados recebidos do Laravel
        var meses = @json($meses);
        var receitas = @json($receitas);
        var despesas = @json($despesas);
        var saldos = @json($saldos);
        const sales_chart_options = {
            series: [{
                    type: 'column',    
                    name: "Receita",
                    data: receitas,
                },
                {
                    type: 'column',
                    name: "Despesas",
                    data: despesas,
                },
                {
                    type: 'line',
                    name: 'Saldo',
                    data:saldos
                }
            ],
            chart: {
                height: 180,
                //type: "area",
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: true,
            },
            colors: ["#198754", "#dc3545", "#ffc107"],
            backgroundBarOpacity: 1,
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            xaxis: {
                type: "text",
                categories: meses,
            },
            fill: {
                opacity: 1, // Define a opacidade para 100%, ou seja, sem transparência
            },
            tooltip: {
                x: {
                    format: "MMMM yyyy",
                },
                y: {
                    formatter: function (value) {
                        return value.toLocaleString('pt-BR'); // Formata com separador de milhar e decimal
                    }
                }
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector("#sales-chart"),
            sales_chart_options,
        );
        sales_chart.render();

        //---------------------------
        // - END MONTHLY SALES CHART -
        //---------------------------

        function createSparklineChart(selector, data) {
            const options = {
                series: [{
                    data
                }],
                chart: {
                    type: "line",
                    width: 150,
                    height: 30,
                    sparkline: {
                        enabled: true,
                    },
                },
                colors: ["var(--bs-primary)"],
                stroke: {
                    width: 2,
                },
                tooltip: {
                    fixed: {
                        enabled: false,
                    },
                    x: {
                        show: false,
                    },
                    y: {
                        title: {
                            formatter: function(seriesName) {
                                return "";
                            },
                        },
                    },
                    marker: {
                        show: false,
                    },
                },
            };

            const chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }

        const table_sparkline_1_data = [
            25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54,
        ];
        const table_sparkline_2_data = [
            12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44,
        ];
        const table_sparkline_3_data = [
            15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64,
        ];
        const table_sparkline_4_data = [
            30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64,
        ];
        const table_sparkline_5_data = [
            20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64,
        ];
        const table_sparkline_6_data = [
            5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44,
        ];
        const table_sparkline_7_data = [
            12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74,
        ];

        createSparklineChart("#table-sparkline-1", table_sparkline_1_data);
        createSparklineChart("#table-sparkline-2", table_sparkline_2_data);
        createSparklineChart("#table-sparkline-3", table_sparkline_3_data);
        createSparklineChart("#table-sparkline-4", table_sparkline_4_data);
        createSparklineChart("#table-sparkline-5", table_sparkline_5_data);
        createSparklineChart("#table-sparkline-6", table_sparkline_6_data);
        createSparklineChart("#table-sparkline-7", table_sparkline_7_data);

        //-------------
        // - PIE CHART -
        //-------------

        

        var Tipo = @json($TipoGasto);
        var Valor = @json($Vl);
        const pie_chart_options = {
            series: Valor,
            chart: {
                type: "donut",
            },
            labels: Tipo,
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(2) + '%';  // Exibe as porcentagens com duas casas decimais
                }
            },
            colors: [
                "#198754", "#dc3545", "#ffc107"
            ],
        };

        const pie_chart = new ApexCharts(
            document.querySelector("#pie-chart"),
            pie_chart_options,
        );
        pie_chart.render();

        //-----------------
        // - END PIE CHART -
        //-----------------
        
    </script> <!--end::Script-->
@endsection