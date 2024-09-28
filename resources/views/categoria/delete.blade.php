@extends('Tamplate.Tamplate')

@section('Title','Registro de categoria')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro')

@section('jsAdmin', '/js/adminlte.js')

@section('Dash', ' ')

@section('imgUser')

    @if(!empty(session('imgUserPerfil')))
        /imguser/{{session('imgUserPerfil')}}
    @else 
        /imguser/user.png
    @endif

@endsection

@section('Dash', ' ')
@section('Cad', ' active')
@section('Banco', ' ')
@section('Cartao', ' ')
@section('Categoria', ' active')
@section('Subcategoria', ' ')

@section('meuCss', '/css/meucss.css')

@section('btn')

@endsection

@section('meuCss', '/css/meucss.css')

@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title callout callout-warning">Tem certeza que deseja deletar o registro abaixo?</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/categoria/delete" method="post"> <!--begin::Body-->
        @csrf
        <input type="hidden" name='id' value="{{$categoria->id}}">
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeCategoria" class="form-label">Nome da categoria</label> 
                <p>{{$categoria->NomeCategoria}}</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="DiaVencimento" class="form-label">Tipo de categoria</label>
                        <p>
                            @if($categoria->TipoCategoria == 'R')
                                Receita
                            @else 
                                Despesa
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="row col-2 mb-3">
                <button class="btn btn-lg @if($categoria->TipoCategoria == 'R') btn-receita @else btn-despesa @endif" type="button">
                    <span class="icon bi bi-{{$categoria->IconeCategoria}}" id='preview'></span>
                                                        
                </button>
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
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/categoria';">
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