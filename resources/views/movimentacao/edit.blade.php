@extends('Tamplate.Tamplate')

@section('Title','Registrar movimentação')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registrar movimentação')

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
        <div class="card-title">Registro de movimentação</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/movimentacao/edit/{{$mov->id}}" method="post" id="newMovFin"> <!--begin::Body-->
        @csrf
        @method('PUT')
        <div class="card-body">
            
            
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="TipoMovimentacaoFinanc" class="form-label">Tipo de transação:</label>
                    <select name="TipoMovimentacaoFinanc" id="TipoMovimentacaoFinanc" class="form-select" required>
                        <option value="" disabled>Selecione o tipo de transação...</option>
                        <option value="D" @if($mov->TipoMovimentacaoFinanc == 'D') selected @endif >Despesa</option>
                        <option value="R" @if($mov->TipoMovimentacaoFinanc == 'R') selected @endif>Receita</option>
                    </select>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3" id="banco">
                    <label for="banco_id" class="form-label">Banco:</label>
                    <select name="banco_id" id="banco_id" class="form-select" required>
                        <option value="" selected disabled>Selecione o banco...</option>
                        @foreach($banco as $dados)
                            <option value="{{$dados->id}}" @if($mov->movBanco->id == $dados->id) selected @endif>{{$dados->NomeBanco}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="categoria_id" class="form-label">Categoria:</label>
                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                        <option value="" disabled>Selecione a categoria...</option>
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
                    <input type="date" name="DataVencimentoMovimentacaoFinanc" id="DataVencimentoMovimentacaoFinanc" class="form-control" value="{{$mov->DataVencimentoMovimentacaoFinanc}}" required>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorMovimentacaoFinanc" class="form-label">Valor:</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="ValorMovimentacaoFinanc" id="ValorMovimentacaoFinanc" class="form-control Numero2" value="@if(empty($mov->ValorPagoMovimentacao)) {{number_format($mov->ValorMovimentacaoFinanc,2,'.',',')}} @else {{number_format($mov->ValorPagoMovimentacao,2,'.',',')}} @endif" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-12 col-md-12 mb-3">
                    <label for="ObsMovimentacaoFinanc" class="form-label">Obs.:</label>
                    <input type="text" name="ObsMovimentacaoFinanc" id="ObsMovimentacaoFinanc" class="form-control" value="{{$mov->ObsMovimentacaoFinanc}}" required>
                </div> 
            </div>
            
        </div> 
        <div class="card-footer"> 
            <div class="row">
                <div class="col-5 col-xl-3">
                    <button type="submit" class="btn btn-primary" id="btnSub">
                        <span class="icon bi bi-cloud-check"></span>
                        <span class="title">Salvar</span>
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

        $(document).on("submit", "#newMovFin", function (event) {
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


    $(function() {
        //código a executar quando todos os elementos estão carregados
        $('#btnPreview').hide();
    });
</script>
<script>
    $(document).ready(function(){


        function sub(){
                var categoriaId = $('#categoria_id').val();
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

                                if(subcategoria.id === {{$mov->subcategoria_id}}){
                                    var sel2 = 'selected';
                                }else{
                                    var sel2 = '';
                                }
                                
                                $('#subcategoria_id').append('<option value="'+ subcategoria.id +'" '+sel2+' >'+ subcategoria.NomeSubCategoria +'</option>');
                            });
                            
                            
                        }
                    });
                } else {
                    $('#subcategoria_id').empty();
                    $('#subcategoria_id').append('<option value="">Não deu certo...</option>');
                }
        }

        function categorias(){
            var TipoDespesa = $('#TipoMovimentacaoFinanc').val();
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
                            if(categoria.id === {{$mov->categoria_id}}){
                                var sel = 'selected';
                            }else{
                                var sel = '';
                            }       
                            $('#categoria_id').append('<option value="'+ categoria.id +'" '+sel+' >'+ categoria.NomeCategoria +'</option>');
                        });
                        sub();

                    }
                });
            }else{
                $('#categoria_id').empty();
                $('#categoria_id').append('<option value="">Não há categorias cadastradas...</option>');
                $('#subcategoria_id').empty();
                $('#subcategoria_id').append('<option value="">Não há subcategorias cadastradas...</option>');
                
            }
        }

        categorias();

        

        

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