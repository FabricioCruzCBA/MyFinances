@extends('Tamplate.Tamplate')

@section('Title','Registro de bancos')

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
@section('Banco', ' active')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')

@section('meuCss', '../css/meucss.css')

@section('btn')

@endsection

@section('meuCss', '../css/meucss.css')

@section('Content')

<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Registro de banco</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/bancos/cad" method="post"> <!--begin::Body-->
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeBanco" class="form-label">Nome do banco:</label> 
                <p>{{$banco->NomeBanco}}</p>
            </div>
            
            
            <div class="card-footer"> 
                <div class="row">
                    <div class="col-5 col-xl-3">
                        <button type="button" class="btn btn-primary" onclick="location.href = '/bancos/edit/{{$banco->id}}';">
                            <span class="icon bi bi-pen"></span>
                            <span class="title">Editar</span>
                        </button> 
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-success" type="button"  onclick="location.href = '/bancos';">
                            <span class="icon bi bi-arrow-return-left"></span>
                            <span class="title">Voltar</span>
                        </button>
                    </div>
                </div>
                
            </div> <!--end::Footer-->
    </form> <!--end::Form-->
</div> <!--end::Quick Example--> <!--begin::Input Group-->
@endsection