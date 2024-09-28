@extends('Tamplate.Tamplate')

@section('Title','Subcategoria')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Subcategorias')

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
@section('Subcategoria', ' active')

@section('meuCss', '\css\meucss.css')

@section('btn')

@endsection


@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Registro subcategoria</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/subcategoria/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
        <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="CategoriaId" class="form-label">Selecione a categoria</label>
                        <p>{{$dados->subcategoriaCategoria->NomeCategoria}} - {{$dados->CategorizacaoSubCategoria}}</p>
                    </div>
                </div>
            </div>
            <div class="mb-3"> 
                <label for="NomeSuCategoria" class="form-label">Nome da subcategoria</label> 
                <p>{{$dados->NomeSubCategoria}}</p>
            </div>
            
            <div class="row col-2 mb-3">
                <input type="hidden" id="icone" name="IconeCategoria">
                <label for="btnPreviewEdit" class="form-label">Ícone</label>
                <button class="btn btn-lg @if($dados->subcategoriaCategoria->TipoCategoria == 'R') btn-receita @elseif($dados->subcategoriaCategoria->TipoCategoria == 'D') btn-despesa @else btn-secondary @endif" type="button" id='btnPreviewEdit'>
                    <span class="icon bi bi-{{$dados->IconeSubCategoria}}" id='preview'></span>
                                                        
                </button>
            </div>
            <div class="card-footer"> 
                <div class="row">
                    <div class="col-5 col-xl-3">
                        <button type="button" class="btn btn-primary" onclick="location.href = '/subcategoria/edit/{{$dados->id}}';">
                            <span class="icon bi bi-pen"></span>
                            <span class="title">Editar</span>
                        </button> 
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/subcategoria';">
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