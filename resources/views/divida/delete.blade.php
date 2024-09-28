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




    <div class="card card-primary card-outline  col-xl-8 col-md-8 offset-xl-2 offset-md-2"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title callout callout-warning">
                Tem certeza que deseja deletar o registro abaixo?<br>
                Todos os movimentos da dívida serão apagados, porém os movimentos financeiros persistirão.
            </div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form action="/divida/delete" method="post"> <!--begin::Body-->
            @csrf
            <input type="hidden" name="id" value="{{$divida->id}}">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="NomeDivida" class="form-label">Descrição:</label>
                        <p>{{$divida->NomeDivida}}</p>
                    </div>
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="PrioridadeDivida" class="form-label">Prioridade:</label>
                        @if($divida->PrioridadeDivida == 'Baixa')
                            <p><i title="Baixa" class="bi bi-exclamation-circle-fill text-success">Baixa</i></p>
                        @elseif($divida->PrioridadeDivida == 'Média')
                            <p><i title="Média" class="bi bi-exclamation-circle-fill text-warning">Média</i></p>
                        @else
                            <p><i  title="Alta" class="bi bi-exclamation-circle-fill text-danger">Alta</i></p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="ValorInicialDivida" class="form-label">Valor inicial:</label>
                        <div class="input-group">
                            <p>R$ {{number_format($divida->ValorInicialDivida,0,',','.')}}</p>
                        </div>
                    </div>
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="ValorPagoDivida" class="form-label">Valor pago:</label>
                        <div class="input-group">
                            <p class="">
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
                            @if($divida->VisivelDashDivida)
                                <p>Visível</p>
                            @else
                                <p>Invisivel</p>
                            @endif
                        </div>
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