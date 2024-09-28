@extends('Tamplate.Tamplate')

@section('Title','Registro de cartão')

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
@section('Cartao', ' active')
@section('Categoria', ' ')
@section('Subcategoria', ' ')

@section('meuCss', '/css/meucss.css')

@section('btn')

@endsection


@section('Content')
<div class="card card-primary card-outline  col-xl-6 offset-xl-3"> <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">Atualizar cartão</div>
    </div> <!--end::Header--> <!--begin::Form-->
    <form action="/cartao/update/{{$cartao->id}}" method="post"> <!--begin::Body-->
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="mb-3"> 
                <label for="NomeCartao" class="form-label">Nome do cartão</label> 
                <input type="text" class="form-control" id="NomeCartao" value="{{$cartao->NomeCartao}}" name="NomeCartao" aria-describedby="Cartão">
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="DiaVencimento" class="form-label">Dia do vencimento</label>
                        <select name="DataVencimentoCartao" id="DataVencimentoCartao" class="form-select" required>
                            
                            @for($i=1;$i<=30;$i++)
                                <option @if($cartao->DataVencimentoCartao == $i) selected @else @endif value="{{$i}}">{{$i}}</option>
                            @endfor

                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="DiaFechamento" class="form-label">Dia do fechamento</label>
                        <select name="DataFechamentoCartao" id="DataFechamentoCartao" class="form-select" required>
                            
                            @for($i=1;$i<=30;$i++)
                                <option  @if($cartao->DataFechamentoCartao == $i) selected @else @endif value="{{$i}}">{{$i}}</option>
                            @endfor

                        </select>
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
                        <button class="btn btn-danger" type="button"  onclick="location.href = '/cartao';">
                            <span class="icon bi bi-ban"></span>
                            <span class="title">Cancelar</span>
                        </button>
                    </div>
                </div>
                
            </div> <!--end::Footer-->
    </form> <!--end::Form-->
</div> <!--end::Quick Example--> <!--begin::Input Group-->
@endsection