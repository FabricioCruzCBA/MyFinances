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


@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Atualizar categoria</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/categoria/update/{{$categoria->id}}" method="post"> <!--begin::Body-->
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeCategoria" class="form-label">Nome da categoria</label> 
                <input type="text" class="form-control" id="NomeCategoria" name="NomeCategoria" aria-describedby="Categoria" value="{{$categoria->NomeCategoria}}">
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="DiaVencimento" class="form-label">Tipo de categoria</label>
                        <select name="TipoCategoria" id="TipoCategoria" class="form-select" required>
                            <option selected disabled value="">Escolha tipo...</option>
                            <option value="R" @if($categoria->TipoCategoria == 'T') selected @endif >Transferência</option>
                            <option value="R" @if($categoria->TipoCategoria == 'R') selected @endif >Receita</option>
                            <option value="D" @if($categoria->TipoCategoria == 'D') selected @endif >Despesa</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="row col-2 mb-3">
                <input type="hidden" id="icone" name="IconeCategoria" value="{{$categoria->IconeCategoria}}">
                <button class="btn btn-lg btn-secondary" type="button" id='btnPreviewEdit'>
                    <span class="icon bi bi-{{$categoria->IconeCategoria}}" id='preview'></span>
                                                        
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="IconeCategoria" class="form-label">Ícone</label>
                        
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Selecionar o ícone
                                </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <ul id="icons-list" class="row row-cols-3 row-cols-sm-4 row-cols-lg-6 row-cols-xl-8 list-unstyled list">
                                            @foreach($icone as $icones)
                                                <li class="col mb-4" data-name="{{$icones->Nome}}" data-tags="number numeral" data-categories="shapes">
                                                    <button class="btn @if($categoria->IconeCategoria == $icones->Nome) btn-success @else btn-secondary @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" onclick=" var inp = document.getElementById('icone'); inp.value = '{{$icones->Nome}}'; var btn = document.getElementById('preview'); btn.className = 'icon bi bi-{{$icones->Nome}}'; ">
                                                        <span class="icon bi bi-{{$icones->Nome}}"></span>
                                                        
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                </div>
                            </div>
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