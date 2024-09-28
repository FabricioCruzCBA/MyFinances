@extends('Tamplate.Tamplate')

@section('Title','Cadastrar meta')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cadastrar meta')

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
        <div class="card-title">Cadastrar Meta</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/meta/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group mb-3">
                    <label for="NomeMeta" class="form-label">Descrição:</label>
                    <input type="text" name="NomeMeta" id="NomeMeta" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="DataFimMeta" class="form-label">Data alvo:</label>
                    <input type="date" name="DataFimMeta" id="DataFimMeta" class="form-control" required>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorMeta" class="form-label">Valor:</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="ValorMeta" id="ValorMeta" class="form-control Numero" required>
                        <span class="input-group-text">,00</span>
                    </div>
                </div>
                
            </div>
        </div> 
        <div class="card-footer"> 
            <div class="row">
                <div class="col-5 col-xl-3">
                    <button type="submit" class="btn btn-primary">
                        <span class="icon bi bi-cloud-check"></span>
                        <span class="title">Cadastrar</span>
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