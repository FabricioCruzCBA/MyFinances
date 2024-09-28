@extends('Tamplate.Tamplate')

@section('Title','Cadastrar de subcategoria')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cadastrar subcategoria')

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
@section('Subcategoria', ' active')

@section('meuCss', '../css/meucss.css')

@section('btn')

@endsection



@section('Content')


<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Cadastrar subcategoria</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/subcategoria/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
        <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="mb-3">
                        <label for="Tipo" class="form-label">Tipo de Subcategoria</label>
                        <select name="Tipo" id="Tipo" class="form-select" required>
                            <option selected disabled value="">Escolha o tipo...</option>
                            <option value="Fixa">Fixa</option>
                            <option value="Extra">Extra</option>
                            <option value="Variável">Variável</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="mb-3">
                        <label for="CategoriaId" class="form-label">Selecione a categoria</label>
                        <select name="CategoriaId" id="CategoriaId" class="form-select" required>
                            <option selected disabled value="">Escolha a Subcategoria...</option>
                            @foreach($categoria->sortBy('NomeCategoria') as $dados)
                                <option value="{{$dados->id}}">{{$dados->NomeCategoria}} - @if($dados->TipoCategoria == 'R') Receita @else Despesa @endif</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3"> 
                <label for="NomeSuCategoria" class="form-label">Nome da subcategoria</label> 
                <input type="text" class="form-control" id="NomeSubCategoria" name="NomeSubCategoria" aria-describedby="Subcategoria">
            </div>
            
            <div class="row col-2 mb-3">
                <input type="hidden" id="icone" name="IconeCategoria">
                <button class="btn btn-lg btn-secondary" type="button" id='btnPreview'>
                    <span class="icon bi bi-" id='preview'></span>
                                                        
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
                                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" onclick=" var inp = document.getElementById('icone'); inp.value = '{{$icones->Nome}}'; var btn = document.getElementById('preview'); btn.className = 'icon bi bi-{{$icones->Nome}}'; $('#btnPreview').show(); ">
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
                            <span class="title">Cadastrar</span>
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