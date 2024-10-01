@extends('Tamplate.Tamplate')

@section('Title','Registro de cartão')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro')

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

@endsection


@section('Content')



<div class="card card-primary card-outline col-xl-10 offset-xl-1 mb-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Registro de cartão de crédito</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/cartao/cad" method="post"> <!--begin::Body-->
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeCartao" class="form-label">Nome do cartão</label> 
                <p>{{$cartao->NomeCartao}}</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="DiaVencimento" class="form-label">Dia do vencimento</label>
                        <p>{{$cartao->DataVencimentoCartao}}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="DiaFechamento" class="form-label">Dia do fechamento</label>
                        <p>{{$cartao->DataFechamentoCartao}}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer"> 
                <div class="row">
                    <div class="col-5 col-xl-3">
                        <button type="button" class="btn btn-primary" onclick="location.href = '/cartao/edit/{{$cartao->id}}';">
                            <span class="icon bi bi-pen"></span>
                            <span class="title">Editar</span>
                        </button> 
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/cartao';">
                            <span class="icon bi bi-ban"></span>
                            <span class="title">Cancelar</span>
                        </button>
                    </div>
                </div>
            </div> <!--end::Footer-->
        </div>
    </form> <!--end::Form-->
</div> <!--end::Quick Example--> <!--begin::Input Group-->
<div class="card  card-primary card-outline col-xl-10 offset-xl-1 mb-3">
    <div class="card-header">
        <h3 class="card-title">Faturas</h3>
        <div class="card-tools"> 
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#closeFat">
                <span class="icon bi bi-check-square"></span>
                <span class="title">Fechar fatura</span>
            </button>
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
                        <th>Ação</th>
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
                                <div class="row">
                                    <div class="d-flex gap-2">
                                        <a href="/cartao/fatura/delete/{{$itens->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> <!-- /.card-body -->
        <div class="card-footer clearfix">                
        </div> <!-- /.card-footer -->
    </div> <!-- /.card -->
</div> <!-- /.col -->

<div class="card  card-primary card-outline col-xl-10 offset-xl-1">
    <div class="card-header">
        <div class="row">
            <h3 class="card-title">Lançamentos sem fatura</h3>
        </div>
        
        <div class="card-tools"> 
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadNewCardMov">
                <span class="icon bi bi-plus"></span>
                <span class="title">Novo</span>
            </button>
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
                        <th class="">Data</th>
                        <th class="">Categoria</th>
                        <th>Subcategoria</th>
                        <th class="">Valor(R$)</th>
                        <th class="">OBS.:</th>
                        <th>Fatura</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movCard->sortBy('DataMovimentacaoCartao') as $dados)
                        <tr>
                            <td>{{date('d/m/Y', strtotime($dados->DataMovimentacaoCartao))}}</td>
                            <td>
                                <div class="row">
                                    <div class="col-2 col-lg-2">
                                        <button class="btn @if($dados->movcardCategoria->TipoCategoria == 'R')  btn-receita  @elseif($dados->movcardCategoria->TipoCategoria == 'D')  btn-despesa @else btn-secondary @endif" title="{{$dados->movcardCategoria->NomeCategoria}}"><i class="bi bi-{{$dados->movcardCategoria->IconeCategoria}}"></i> </button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-2 col-lg-2">
                                        <button class="btn @if($dados->movcardCategoria->TipoCategoria == 'R')  btn-receita  @elseif($dados->movcardCategoria->TipoCategoria == 'D')  btn-despesa @else btn-secondary  @endif" title="{{$dados->movcardSub->NomeSubCategoria}}"><i class="bi bi-{{$dados->movcardSub->IconeSubCategoria}}"></i> </button>
                                    </div>
                                </div>
                            </td>
                            <td>{{number_format($dados->ValorMovimentacaoCartao,2,',','.')}}</td>
                            <td>{{$dados->ObsMovimentacaoCartao}}</td>
                            <td>
                                @if($dados->DataMovimentacaoCartao
                                    <
                                    date(
                                        'Y-m-d', 
                                        strtotime(
                                            date('Y', strtotime($dados->DataMovimentacaoCartao)) 
                                            . '-' . date('m', strtotime($dados->DataMovimentacaoCartao)) 
                                            . '-' .  $cartao->DataFechamentoCartao
                                        )
                                    )
                                ) {{ date('m', strtotime($dados->DataMovimentacaoCartao)) . '/' .date('Y', strtotime($dados->DataMovimentacaoCartao))}}

                                @else {{date('m', strtotime('+1 months',strtotime($dados->DataMovimentacaoCartao))) . '/' . date('Y', strtotime('+1 months',strtotime($dados->DataMovimentacaoCartao)))}}

                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <div class="d-flex gap-2">
                                        <a href="/cartao/item/{{$dados->id}}/{{$cartao->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div> <!-- /.card-body -->
            <div class="card-footer clearfix"> 
                    
            </div> <!-- /.card-footer -->
        </div> 
    </div> 
</div>
<!-- Modal cad movimentacao-->
<div class="modal fade" id="cadNewCardMov" tabindex="-1" aria-labelledby="cadNewCardMov" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/movimentacao/cad" method="post" id="newMovFin">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Novo lançamento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="return" value="/cartao/{{$cartao->id}}">
                    <input type="hidden" name="cartaocredito_id" value="{{$cartao->id}}">
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="cartao" id="cartao" class="form-check-input" disabled checked>
                                <label for="cartao" class="form-check-label">Transação no cartão de crédito?</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="recorrente" name="recorrente"> 
                                <label class="form-check-label" for="recorrente">Lançamento recorrente?</label> 
                            </div>
                        </div>
                        <div class="form-group col-xl-6 col-md-6 mb-3" id='parcelas'>
                            <label for="meta_id" class="form-label">Quantos meses?</label>
                            <input type="number" name="parcelasNum" id="parcelasNum" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoria:</label>
                            <select name="categoria_id" id="categoria_id" class="form-select" required>
                                <option value="" selected disabled>Selecione a categoria...</option>
                                @foreach($categoria as $cat)
                                    <option value="{{$cat->id}}">{{$cat->NomeCategoria}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <label for="subcategoria_id" class="form-label">Subcategoria:</label>
                            <select name="subcategoria_id" id="subcategoria_id" class="form-select" required>
                                <option value="" selected disabled>Selecione a subcategoria...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <label for="DataVencimentoMovimentacaoFinanc" class="form-label">Data da movimentacao:</label>
                            <input type="date" name="DataVencimentoMovimentacaoFinanc" id="DataVencimentoMovimentacaoFinanc" class="form-control" required>
                        </div>
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <label for="ValorMovimentacaoFinanc" class="form-label">Valor:</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" name="ValorMovimentacaoFinanc" id="ValorMovimentacaoFinanc" class="form-control Numero2" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xl-12 col-md-12 mb-3">
                            <label for="ObsMovimentacaoFinanc" class="form-label">Obs.:</label>
                            <input type="text" name="ObsMovimentacaoFinanc" id="ObsMovimentacaoFinanc" class="form-control" required>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5 col-xl-3">
                        <button type="submit" class="btn btn-primary" id="btnSub">
                            <span class="icon bi bi-cloud-check"></span>
                            <span class="title">Cadastrar</span>
                        </button> 
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                            <span class="icon bi bi-ban"></span>
                            <span class="title">Cancelar</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal close Fat-->
<div class="modal fade" id="closeFat" tabindex="-1" aria-labelledby="closeFat" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/cartao/fatura" method="post" id="newCloseFat">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Fechar fatura</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="return" value="/cartao/{{$cartao->id}}">
                    <input type="hidden" name="cartaocredito_id" value="{{$cartao->id}}">
                    <div class="row">
                        <div class="form-group col-12 mb-3">
                            <label for="banco_id" class="form-label">Banco:</label>
                            <select name="banco_id" id="banco_id" class="form-select" required>
                                <option value="" selected disabled>Selecione o banco...</option>
                                @foreach($banco as $itens)
                                    <option value="{{$itens->id}}">{{$itens->NomeBanco}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <label for="categoria_id2" class="form-label">Categoria:</label>
                            <select name="categoria_id2" id="categoria_id2" class="form-select" required>
                                <option value="" selected disabled>Selecione a categoria...</option>
                                @foreach($categoria as $cat)
                                    <option value="{{$cat->id}}">{{$cat->NomeCategoria}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-6 col-md-6 mb-3">
                            <label for="subcategoria_id2" class="form-label">Subcategoria:</label>
                            <select name="subcategoria_id2" id="subcategoria_id2" class="form-select" required>
                                <option value="" selected disabled>Selecione a subcategoria...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6 mb-3">
                            <label for="mes" class="form-label">Mês da fatura</label>
                            <select name="mes" id="mes" class="form-select" required>
                                <option value="">Selecione o mês...</option>
                                @for($i = 0; $i < 12; $i++)
                                    <option value="{{$i+1}}">{{$i+1}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="ano" class="form-label">Ano da fatura</label>
                            <select name="ano" id="ano" class="form-select" required>
                                <option value="">Selecione o mês...</option>
                                @for($i = date('Y', strtotime(now())) - 3; $i < date('Y', strtotime(now())) + 2; $i++)
                                    <option value="{{$i+1}}">{{$i+1}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <div class="col-5 col-xl-3">
                        <button type="submit" class="btn btn-primary" id="btnSub">
                            <span class="icon bi bi-cloud-check"></span>
                            <span class="title">Salvar</span>
                        </button> 
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                            <span class="icon bi bi-ban"></span>
                            <span class="title">Cancelar</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection



@section('Script')
<script>
    $(document).ready(function(){
        $('#divida').hide();
        $('#meta').hide();
        $('#investimento').hide();
        $('#parcelas').hide();
        $('#cartaoId').hide();
        $('#ehDivida').hide();
        $('#ehInvestimento').hide();
        $('#ehMeta').hide();
        
    });
    $(document).ready(function(){
        $('#recorrente').on('change', function(){
            var checkDivida = $(this).prop('checked');
            if(checkDivida){
                $('#parcelas').show();
            }else{
                $('#parcelas').hide();
                $('#parcelasNum').val('');
            }
        });
    });

    $('#btnSub').on('click', function(){
        $('#cartao').prop('disabled', false);
    });

    $(document).on("submit", "#newMovFin", function (event) {
        $('#cartao').prop('disabled', false);
        $('#btnSub').prop('disabled', true);
       
        Swal.fire({
            title: "Aguarde!",
            html: "Estamos cadastrando a movimentação.",
            timer: 2000000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
            }
                
        });
    });
    $(document).ready(function() {
        $('#categoria_id').on('change', function() {
            var categoriaId = $(this).val();
            if (categoriaId) {
                $.ajax({
                    url: '{{ route('subcategorias.get') }}',
                    type: 'POST',
                    data: {
                        categoria_id: categoriaId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                    //console.log(data); // Verifica a estrutura dos dados

                    // Obtenha as chaves do objeto como um array
                    var subcategoriaIds = Object.keys(data);
                    //console.log(subcategoriaIds);
                    //console.log(subcategoriaIds.length); // Agora isso deve funcionar
                    $('#subcategoria_id').empty();
                    $('#subcategoria_id').append('<option value="" selected disabled>Selecione a Subcategoria...</option>');
                    subcategoriaIds.forEach(function(id) {
                        var subcategoria = data[id];
                                
                            $('#subcategoria_id').append('<option value="'+ subcategoria.id +'">'+ subcategoria.NomeSubCategoria +'</option>');
                        });
                            
                        
                    }
                });
            } else {
                $('#subcategoria_id').empty();
                $('#subcategoria_id').append('<option value="">Não deu certo...</option>');
            }
        });
    });

    $(document).ready(function() {
        $('#categoria_id2').on('change', function() {
            var categoriaId = $(this).val();
            if (categoriaId) {
                $.ajax({
                    url: '{{ route('subcategorias.get') }}',
                    type: 'POST',
                    data: {
                        categoria_id: categoriaId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                    //console.log(data); // Verifica a estrutura dos dados

                    // Obtenha as chaves do objeto como um array
                    var subcategoriaIds = Object.keys(data);
                    //console.log(subcategoriaIds);
                    //console.log(subcategoriaIds.length); // Agora isso deve funcionar
                    $('#subcategoria_id2').empty();
                    $('#subcategoria_id2').append('<option value="" selected disabled>Selecione a Subcategoria...</option>');
                    subcategoriaIds.forEach(function(id) {
                        var subcategoria = data[id];
                                
                            $('#subcategoria_id2').append('<option value="'+ subcategoria.id +'">'+ subcategoria.NomeSubCategoria +'</option>');
                        });
                            
                        
                    }
                });
            } else {
                $('#subcategoria_id2').empty();
                $('#subcategoria_id2').append('<option value="">Não deu certo...</option>');
            }
        });
    });
</script>
@endsection