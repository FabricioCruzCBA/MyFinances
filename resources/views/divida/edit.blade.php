@extends('Tamplate.Tamplate')

@section('Title','Registro de dívida')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro de dívida')

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

@endsection



@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Regstro de dívidas</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/divida/edit" method="post"> <!--begin::Body-->
        @csrf
        <input type="hidden" name="id" value="{{$divida->id}}">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="NomeDivida" class="form-label">Descrição:</label>
                    <input type="text" name="NomeDivida" id="NomeDivida" class="form-control" value="{{$divida->NomeDivida}}" required>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="PrioridadeDivida" class="form-label">Prioridade:</label>
                    <select name="PrioridadeDivida" id="PrioridadeDivida" class="form-select" required>
                        <option value="" disabled>Selecione a prioridade...</option>
                        <option value="Baixa" @if($divida->PrioridadeDivida == 'Baixa') selected @endif >Baixa</option>
                        <option value="Média" @if($divida->PrioridadeDivida == 'Média') selected @endif>Média</option>
                        <option value="Alta" @if($divida->PrioridadeDivida == 'Alta') selected @endif>Alta</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorInicialDivida" class="form-label">Valor inicial:</label>
                    <p>R$ {{number_format($divida->ValorInicialDivida,0,',','.')}}</p>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorPagoDivida" class="form-label">Valor pago:</label>
                    <div class="input-group">
                        <p class="@if($divida->ValorPagoDivida < 0) text-danger @endif">
                        R$ {{
                                        number_format(
                                            -1*
                                            (
                                                $divida
                                                    ->dividaMovimentacaodivida
                                                    ->where('TipoMovimentacaoDivida', 'E')
                                                    ->sum('ValorMovimentacaoDivida')
                                                -
                                                $divida
                                                    ->dividaMovimentacaodivida
                                                    ->where('TipoMovimentacaoDivida', 'S')
                                                    ->sum('ValorMovimentacaoDivida')
                                            
                                            ),
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xl-3 col-md-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="VisivelDashDvida" name="VisivelDashDvida" @if($divida->VisivelDashDivida == '1') checked @endif> 
                        <label class="form-check-label" for="VisivelDashDvida">Visivel?</label> 
                    </div>
                </div>
            </div>
        </div>
            
            
        <div class="card-footer"> 
                <div class="row">
                    <div class="col-5 col-xl-3">
                        <button type="submit" class="btn btn-primary">
                            <span class="icon bi bi-cloud-check"></span>
                            <span class="title">Salvar</span>
                        </button>  
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/divida';">
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
    $(function() {
        //código a executar quando todos os elementos estão carregados
        $('#btnPreview').hide();
    });
</script>
<script>
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
                            console.log(data); // Verifica a estrutura dos dados

                            // Obtenha as chaves do objeto como um array
                            var subcategoriaIds = Object.keys(data);
                            console.log(subcategoriaIds);
                            console.log(subcategoriaIds.length); // Agora isso deve funcionar
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