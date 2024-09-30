@extends('Tamplate.Tamplate')

@section('Title','Cadastrar de categoria')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cadastrar categoria')

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
        <div class="card-title">Cadastrar categoria</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/categoria/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeCategoria" class="form-label">Nome da categoria</label> 
                <input type="text" class="form-control" id="NomeCategoria" name="NomeCategoria" aria-describedby="Categoria" required>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="DiaVencimento" class="form-label">Tipo de categoria</label>
                        <select name="TipoCategoria" id="TipoCategoria" class="form-select" required>
                            <option selected disabled value="">Escolha o tipo...</option>
                            <option value="T">Transferência</option>
                            <option value="R">Receita</option>
                            <option value="D">Despesa</option>
                        </select>
                    </div>
                </div>
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
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="icon bi bi-cloud-check"></span>
                            <span class="title">Cadastrar</span>
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
        // Esconde o botão de pré-visualização inicialmente
        $('#btnPreview').hide();

        // Adiciona um evento de clique ao botão de envio
        $('#submitBtn').on('click', function(event) {
            // Verifica se o ícone foi selecionado
            var iconeSelecionado = $('#icone').val();
            if (!iconeSelecionado) {
                // Previne o envio do formulário
                event.preventDefault();
                // Exibe a mensagem de alerta
                alert('Você precisa selecionar um ícone antes de cadastrar a categoria.');
            }
        });
    });
</script>
@endsection
