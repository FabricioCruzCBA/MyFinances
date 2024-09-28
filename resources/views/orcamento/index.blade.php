@extends('Tamplate.Tamplate')

@section('Title','Orçamento')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Orçamento')

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
@section('Subcategoria', ' ')
@section('Orcamento', ' active')

@section('meuCss', '\css\meucss.css')


@section('btn')
    
    
        <a href="orcamento/cad">
            <button class="btn btn-primary">
                <span class="icon bi bi-plus-circle"></span>
                <span class="title">Criar</span>
            </button>
        </a>
        
        <a href="/orcamento/edit" id='editButton'>
            <button class="btn btn-success">
                <span class="icon bi bi-pen-fill"></span>
                <span class="title">Editar</span>
            </button>
        </a>
    
    
@endsection



@section('meuCss', '../css/meucss.css')

@section('Content')



<div class="card col-xl-10 offset-xl-1">
    <div class="card-header">
        <h3 class="card-title">Orçamento</h3>
    </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th>Ícone</th>
                                <th>Valor(R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($orcamento)>0)        
                                @foreach($orcamento as $dados)
                                    <tr>
                                        <td>{{$dados->categoriaorcamentoCategoria->NomeCategoria}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-2 col-lg-2">
                                                    <button class="btn @if($dados->categoriaorcamentoCategoria->TipoCategoria == 'D') btn-despesa @else btn-receita @endif " title="Visualizar"><i class="bi bi-{{$dados->categoriaorcamentoCategoria->IconeCategoria}}"></i> </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            R$ {{number_format($dados->ValorItemOrc,2,',','.')}}
                                            <!--<input type="hidden" id="id{{$dados->id}}" name="id{{$dados->id}}">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span> 
                                                <input type="number" class="form-control" aria-label="Valor" id="valor{{$dados->id}}" name="valor{{$dados->id}}" value="{{$dados->ValorItemOrc}}"> 
                                                <span class="input-group-text">,00</span>
                                            </div>
-->
                                        </td>
                                    </tr>
                                @endforeach
                            @else    
                                <tr class="align-middle">
                                    <td colspan="3">Não tem orçamento criado! Clique em criar orçamento</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    
              
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
@endsection