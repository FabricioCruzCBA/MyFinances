@extends('Tamplate.Tamplate')

@section('Title','Cadastro de bancos')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cadastro')

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
@section('Banco', ' active')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')

@section('meuCss', '../css/meucss.css')


@section('btn')

@endsection



@section('Content')
<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Cadastrar banco</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/bancos/cad" method="post"> <!--begin::Body-->
        @csrf
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeBanco" class="form-label">Nome do banco</label> 
                <input type="text" class="form-control" id="NomeBanco" name="NomeBanco" aria-describedby="E-mail" required>
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
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/bancos';">
                            <span class="icon bi bi-ban"></span>
                            <span class="title">Cancelar</span>
                        </button>
                    </div>
                </div>
                
            </div> <!--end::Footer-->
    </form> <!--end::Form-->
</div> <!--end::Quick Example--> <!--begin::Input Group-->
@endsection