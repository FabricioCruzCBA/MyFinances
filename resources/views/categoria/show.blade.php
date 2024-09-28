@extends('Tamplate.Tamplate')

@section('Title','Registro de categoria')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Registro')

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
@section('Categoria', ' active')
@section('Subcategoria', ' ')

@section('meuCss', '../css/meucss.css')

@section('btn')

@endsection


@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Registro de categoria</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/categoria/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeCategoria" class="form-label">Nome da categoria:</label> 
                <p>{{$categoria->NomeCategoria}}</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="DiaVencimento" class="form-label">Tipo:</label>
                        <p>
                            @if($categoria->TipoCategoria == 'R')
                                Receita
                            @elseif($categoria->TipoCategoria == 'D')
                                Despesa
                            @else 
                                Transferência
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="row col-2 mb-3">
                <button class="btn btn-lg @if($categoria->TipoCategoria == 'R') btn-receita @elseif($categoria->TipoCategoria == 'D') btn-despesa @else btn-secondary @endif" type="button">
                    <span class="icon bi bi-{{$categoria->IconeCategoria}}" id='preview'></span>
                                                        
                </button>
            </div>
            
            
            <div class="card-footer"> 
                <div class="row">
                    <div class="col-5 col-xl-3">
                        <button type="button" class="btn btn-primary" onclick="location.href = '/categoria/edit/{{$categoria->id}}';">
                            <span class="icon bi bi-pen"></span>
                            <span class="title">Editar</span>
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