@extends('Tamplate.Tamplate')

@section('Title','Registro de investimento')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro de investimento')

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
@section('Investimento', ' active')

@section('meuCss', '\css\meucss.css')

@section('btn')

@endsection



@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Registro investimento</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/investimento/edit" method="post"> <!--begin::Body-->
        <input type="hidden" name="id" id="id" value="{{$investimento->id}}">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group mb-3">
                    <label for="NomeInvestimento" class="form-label">Descrição:</label>
                    <input type="text" name="NomeInvestimento" id="NomeInvestimento" class="form-control" required value="{{$investimento->NomeInvestimento}}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-12 col-md-12 mb-3">
                    <label for="ValorInicialInvestimento" class="form-label">Valor inicial:</label>
                    <p>
                        R$ {{
                                number_format(
                                    $investimento->ValorInicialInvestimento,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}
                    </p>
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
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/investimento';">
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