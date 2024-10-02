@extends('Tamplate.Tamplate')

@section('Title','Registro de meta')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro de meta')

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

@endsection



@section('Content')



    <div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title callout callout-warning">
                Tem certeza que deseja deletar o registro abaixo?<br>
                Todos os movimentos da dívida serão apagados, porém os movimentos financeiros persistirão.
            </div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form action="/meta/delete" method="post"> <!--begin::Body-->
            <input type="hidden" name="id" id="id" value="{{$meta->id}}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group mb-3">
                        <label for="NomeMeta" class="form-label">Descrição:</label>
                        <p>{{$meta->NomeMeta}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="DataFimMeta" class="form-label">Data alvo:</label>
                        <p>{{date('d/m/y', strtotime($meta->DataFimMeta))}}</p>
                    </div>
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="ValorMeta" class="form-label">Valor:</label>
                        <p>R$ {{number_format($meta->ValorMeta,0,',','.')}}</p>
                    </div>  
                </div>
                
                
                    
                    <label for="progress" class="form-label text-center">Progresso:</label>
                    <div id="progress" class="progress" role="progressbar" aria-label="Default striped example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar" style="width: {{
                                                                                        number_format(
                                                                                            (
                                                                                                (
                                                                                                    $meta
                                                                                                        ->metaMovimentacao
                                                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                                                        ->where('TipoMovimentacaoMeta','E')
                                                                                                        ->sum('ValorMovimentacaoMeta')
                                                                                                    -
                                                                                                    $meta
                                                                                                        ->metaMovimentacao
                                                                                                        ->where('AtivoMovimentacaoMeta','1')
                                                                                                        ->where('TipoMovimentacaoMeta','S')
                                                                                                        ->sum('ValorMovimentacaoMeta')
                                                                                                )
                                                                                                /
                                                                                                $meta->ValorMeta
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
                                                                        $meta
                                                                            ->metaMovimentacao
                                                                            ->where('AtivoMovimentacaoMeta','1')
                                                                            ->where('TipoMovimentacaoMeta','E')
                                                                            ->sum('ValorMovimentacaoMeta')
                                                                        -
                                                                        $meta
                                                                            ->metaMovimentacao
                                                                            ->where('AtivoMovimentacaoMeta','1')
                                                                            ->where('TipoMovimentacaoMeta','S')
                                                                            ->sum('ValorMovimentacaoMeta')
                                                                    )
                                                                    /
                                                                    $meta->ValorMeta
                                                                ) * 100,
                                                                1,
                                                                ',',
                                                                '.'
                                                            )
                                                        }}%
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
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/meta';">
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