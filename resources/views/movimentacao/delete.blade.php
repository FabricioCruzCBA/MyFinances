@extends('Tamplate.Tamplate')

@section('Title','Registro de movimentação')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro de movimentação')

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
@section('Cad', ' ')
@section('Banco', ' ')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')
@section('Divida', ' ')
@section('Investimento', ' ')
@section('Movimentacao', ' active')

@section('meuCss', '\css\meucss.css')

@section('btn')

@endsection



@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title callout callout-warning">
                Tem certeza que deseja deletar o registro abaixo?<br>
                Todos os movimentos relacionados serão apagados.
        </div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/movimentacao/delete" method="post"> <!--begin::Body-->
        @csrf
        <input type="hidden" name="ids" id="id" value="{{$mov->id}}">
        <div class="card-body">
            @if($mov->RecorrenteMovimentacaoFinanc == '1')
                <div class="row">
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="recorrente" id="recorrente" class="form-check-input">
                            <label for="cartao" class="form-check-label">Para essa transação existem recorrências, você quer apagar as futuras?</label>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="form-group mb-3">
                    <label for="Situacao" class="form-label">Situação:</label>
                    <p id="Situacao">
                        @if($mov->PagoMovimentacaoFinanc == '1')
                            <i title="Pago" class="bi bi-check-circle-fill text-success">Pago</i>
                        @elseif($mov->DataVencimentoMovimentacaoFinanc < now())
                            <i title="Atrasado" class="bi bi-exclamation-circle-fill text-danger">Atrasado</i>
                        @else
                            <i title="Previsto" class="bi bi-dash-circle-fill text-primary">Previsto</i>
                        @endif
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label for="recorrente" class="form-label">Recorrente:</label>
                        <p id="recorrente">
                            @if($mov->RecorrenteMovimentacaoFinanc == '1')
                                Sim: {{$mov->QntParcelasMovimentacaoFinanc}}
                            @else 
                                Não
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="TipoMovimentacaoFinanc" class="form-label">Tipo de transação:</label>
                    <p>
                        {{$mov->movSubcategoria->CategorizacaoSubCategoria}}
                    </p>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3" id="banco">
                    <label for="banco_id" class="form-label">Banco:</label>
                    <p>{{$mov->movBanco->NomeBanco}}</p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="categoria_id" class="form-label">Categoria:</label>
                    <p>
                        {{$mov->movCategoria->NomeCategoria}}
                    </p>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="subcategoria_id" class="form-label">Subcategoria:</label>
                    <p>
                        {{$mov->movSubcategoria->NomeSubCategoria}}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="DataVencimentoMovimentacaoFinanc" class="form-label">Data da movimentacao:</label>
                    <p>
                        @if(empty($mov->DataPagamentoMovimentacaoFinanc))
                            {{date('d/m/y',strtotime($mov->DataVencimentoMovimentacaoFinanc))}}
                        @else
                            {{date('d/m/y', strtotime($mov->DataPagamentoMovimentacaoFinanc))}}
                        @endif
                    </p>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorMovimentacaoFinanc" class="form-label">Valor:</label>
                    <p>
                        @if(empty($mov->ValorPagoMovimentacaoFinanc))
                            {{
                                number_format(
                                    $mov->ValorMovimentacaoFinanc,
                                    2,
                                    ',',
                                    '.'
                                )
                            }}
                        @else
                            {{
                                number_format(
                                    $mov->ValorPagoMovimentacaoFinanc,
                                    2,
                                    ',',
                                    '.'
                                )
                            }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-12 col-md-12 mb-3">
                    <label for="ObsMovimentacaoFinanc" class="form-label">Obs.:</label>
                    <p>
                        {{$mov->ObsMovimentacaoFinanc}}
                    </p>
                </div> 
            </div>
            
        </div> 
        <div class="card-footer"> 
            <div class="row">
                <div class="col-5 col-xl-3">
                    <button type="submit" class="btn btn-danger" >
                        <span class="icon bi bi-trash"></span>
                        <span class="title">Deletar</span>
                    </button> 
                </div>
                <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                    <button class="btn btn-danger" type="button"  onclick="location.href = '/movimentacao';">
                        <span class="icon bi bi-ban"></span>
                        <span class="title">Cancelar</span>
                    </button>
                </div>
            </div>
        </div> <!--end::Footer-->
    </form> <!--end::Form-->
</div> <!--end::Quick Example--> <!--begin::Input Group-->


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
        $('#DividaMovimentacaoFinanc').on('change', function(){
            var checkDivida = $(this).prop('checked');
            if(checkDivida){
                $('#divida').show();
            }else{
                $('#divida').hide()
                $('#divida_id').val('');
            }
        });
    });
    $(document).ready(function(){
        $('#MetaMovimentacaoFinanc').on('change', function(){
            var checkDivida = $(this).prop('checked');
            if(checkDivida){
                $('#meta').show();
            }else{
                $('#meta').hide();
                $('#meta_id').val('');
            }
        });
    });
    $(document).ready(function(){
        $('#InvestimentoMovimentacaoFinanc').on('change', function(){
            var checkDivida = $(this).prop('checked');
            if(checkDivida){
                $('#investimento').show();
            }else{
                $('#investimento').hide();
                $('#investimento_id').val('');
                
                
            }
        });
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
    $(document).ready(function(){
        $('#cartao').on('change', function(){
            var checkDivida = $(this).prop('checked');
            if(checkDivida){
                $('#cartaoId').show();
                $('#banco').hide();
            }else{
                $('#cartaoId').hide();
                $('#banco').show();
                $('#cartaocredito_id').val('');
            }
        });
    });

    $(document).ready(function(){
        $('#TipoMovimentacaoFinanc').on('change', function(){
            var TipoDespesa = $(this).val();
            if(TipoDespesa == 'D'){
                $('#ehDivida').show();
                $('#ehInvestimento').show();
                $('#ehMeta').show();
            }else{
                $('#ehDivida').hide();
                $('#ehInvestimento').hide();
                $('#ehMeta').hide();
            }
            if(TipoDespesa){
                $.ajax({
                    url: '{{route('categorias.get')}}',
                    type: 'POST',
                    data:{
                        TipoMovimentacaoFinanc: TipoDespesa,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        //console.log(data);

                        var categorias = Object.keys(data);
                        //console.log(categorias);
                        $('#categoria_id').empty();
                        $('#categoria_id').append('<option value="" selected disabled>Selecione a Categoria...</option>');
                        $('#subcategoria_id').empty();
                        $('#subcategoria_id').append('<option value="" selected disabled>Selecione a Subcategoria...</option>');
                        categorias.forEach(function(id) {
                            var categoria = data[id];
                            //console.log(categoria) ;           
                            $('#categoria_id').append('<option value="'+ categoria.id +'">'+ categoria.NomeCategoria +'</option>');
                        });

                    }
                });
            }else{
                $('#categoria_id').empty();
                $('#categoria_id').append('<option value="">Não há categorias cadastradas...</option>');
                $('#subcategoria_id').empty();
                $('#subcategoria_id').append('<option value="">Não há subcategorias cadastradas...</option>');
                
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
    </script>
@endsection