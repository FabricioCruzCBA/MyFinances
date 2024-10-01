@extends('Tamplate.Tamplate')

@section('Title','Cadastrar de dívidas')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cadastrar dívidas')

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
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')
@section('Divida', ' active')

@section('meuCss', '../css/meucss.css')

@section('btn')

@endsection



@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Cadastrar dívidas</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/divida/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-12 col-md-12 mb-3">
                    <label for="NomeDivida" class="form-label">Descrição:</label>
                    <input type="text" name="NomeDivida" id="NomeDivida" class="form-control" required>
                </div>
                
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorInicialDivida" class="form-label">Valor inicial:</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="ValorInicialDivida" id="ValorInicialDivida" class="form-control Numero" required>
                        <span class="input-group-text">,00</span>
                    </div>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="PrioridadeDivida" class="form-label">Prioridade:</label>
                    <select name="PrioridadeDivida" id="PrioridadeDivida" class="form-select" required>
                        <option value="" selected disabled>Selecione a prioridade...</option>
                        <option value="Baixa">Baixa</option>
                        <option value="Média">Média</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xl-3 col-md-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="VisivelDashDvida" name="VisivelDashDvida"> 
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
                        <span class="title">Cadastrar</span>
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

@endsection