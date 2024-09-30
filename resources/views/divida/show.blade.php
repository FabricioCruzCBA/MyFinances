@extends('Tamplate.Tamplate')

@section('Title','Cadastrar de dívidas')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Cadastrar dívidas')

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
@section('Subcategoria', ' ')
@section('Divida', ' active')

@section('meuCss', '../css/meucss.css')

@section('btn')

@endsection



@section('Content')




    <div class="card card-primary card-outline  col-xl-8 col-md-8 col-sm-12 offset-xl-2 offset-md-2 mb-3"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title @if($divida->ValorInicialDivida + (-1*$divida->ValorPagoDivida) > $divida->ValorInicialDivida) text-danger @endif">Valor da dívida - R$ {{number_format(
                                                                                                                                                                                            $divida->ValorInicialDivida 
                                                                                                                                                                                            + 
                                                                                                                                                                                            (
                                                                                                                                                                                                
                                                                                                                                                                                                (
                                                                                                                                                                                                    $divida
                                                                                                                                                                                                        ->dividaMovimentacaodivida
                                                                                                                                                                                                        ->where('TipoMovimentacaoDivida', 'E')
                                                                                                                                                                                                        ->where('AtivoMovimentacaoDivida','1')
                                                                                                                                                                                                        ->sum('ValorMovimentacaoDivida')
                                                                                                                                                                                                    -
                                                                                                                                                                                                    $divida
                                                                                                                                                                                                        ->dividaMovimentacaodivida
                                                                                                                                                                                                        ->where('TipoMovimentacaoDivida', 'S')
                                                                                                                                                                                                        ->where('AtivoMovimentacaoDivida','1')
                                                                                                                                                                                                        ->sum('ValorMovimentacaoDivida')
                                                                                                                                                                                                
                                                                                                                                                                                                )
                                                                                                                                                                                            ),
                                                                                                                                                                                            0,
                                                                                                                                                                                            ',',
                                                                                                                                                                                            '.'
                                                                                                                                                                                            )}}</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form action="/divida/cad" method="post"> <!--begin::Body-->
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="NomeDivida" class="form-label">Descrição:</label>
                        <p>{{$divida->NomeDivida}}</p>
                    </div>
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="PrioridadeDivida" class="form-label">Prioridade:</label>
                        @if($divida->PrioridadeDivida == 'Baixa')
                            <p><i title="Baixa" class="bi bi-exclamation-circle-fill text-success">Baixa</i></p>
                        @elseif($divida->PrioridadeDivida == 'Média')
                            <p><i title="Média" class="bi bi-exclamation-circle-fill text-warning">Média</i></p>
                        @else
                            <p><i  title="Alta" class="bi bi-exclamation-circle-fill text-danger">Alta</i></p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="ValorInicialDivida" class="form-label">Valor inicial:</label>
                        <div class="input-group">
                            <p>R$ {{number_format($divida->ValorInicialDivida,0,',','.')}}</p>
                        </div>
                    </div>
                    <div class="form-group col-xl-6 col-md-6 mb-3">
                        <label for="ValorPagoDivida" class="form-label">Valor pago:</label>
                        <div class="input-group">
                            <p class="@if($divida->ValorPagoDivida < 0) text-danger @endif">
                                R$ {{
                                        number_format(
                                            -1*
                                            (
                                                $divida
                                                    ->dividaMovimentacaodivida
                                                    ->where('TipoMovimentacaoDivida', 'E')
                                                    ->where('AtivoMovimentacaoDivida','1')
                                                    ->sum('ValorMovimentacaoDivida')
                                                -
                                                $divida
                                                    ->dividaMovimentacaodivida
                                                    ->where('TipoMovimentacaoDivida', 'S')
                                                    ->where('AtivoMovimentacaoDivida','1')
                                                    ->sum('ValorMovimentacaoDivida')
                                            
                                            ),
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-3 mb-3">
                        <div class="form-check">
                            @if($divida->VisivelDashDivida)
                                <p>Visível</p>
                            @else
                                <p>Invisivel</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                
                
            <div class="card-footer"> 
                <div class="row">
                        <div class="col-5 col-xl-3">
                            <button type="button" class="btn btn-primary" onclick="location.href = '/divida/edit/{{$divida->id}}';">
                                <span class="icon bi bi-pen"></span>
                                <span class="title">Editar</span>
                            </button> 
                        </div>
                        <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                            <button class="btn btn-danger" type="button"  onclick="location.href = '/divida';">
                                <span class="icon bi bi-ban"></span>
                                <span class="title">Cancelar</span>
                            </button>
                        </div>
                    </div>
            </div> <!--end::Footer-->
        </form> <!--end::Form-->
    </div> <!--end::Quick Example--> <!--begin::Input Group-->


        <div class="card card-primary card-outline col-xl-8 col-md-8 col-sm-12 offset-xl-2 offset-md-2">
            <div class="card-header">
                <div class="row">
                    <div class="card-title">Extrato da dívida (Saldo:R$ {{number_format(
                                                                                        (-1*
                                                                                        (
                                                                                            $divida
                                                                                                    ->dividaMovimentacaodivida
                                                                                                    ->where('AtivoMovimentacaoDivida','1')
                                                                                                    ->where('TipoMovimentacaoDivida','E')
                                                                                                    ->sum('ValorMovimentacaoDivida')
                                                                                            - 
                                                                                            $divida
                                                                                                    ->dividaMovimentacaodivida
                                                                                                    ->where('AtivoMovimentacaoDivida','1')
                                                                                                    ->where('TipoMovimentacaoDivida','S')
                                                                                                    ->sum('ValorMovimentacaoDivida')
                                                                                        )
                                                                                        ),
                                                                                        0,
                                                                                        ',',
                                                                                        '.'
                                                                                        )
                                                                        }})</div>
                </div>
                <div class="row">
                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formUpdate">
                            <span class="icon bi bi-arrow-repeat"></span>
                            <span class="title">Atualizar</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">Código</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Valor(R$)</th>
                                <th>Obs.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                @foreach($divida->dividaMovimentacaodivida->sortBy('DataMovimentacaoDivida')->where('AtivoMovimentacaoDivida','1') as $itens)
                                    <tr>
                                        <td>{{$itens->id}}.</td>
                                        <td>{{date('d/m/Y',strtotime($itens->DataMovimentacaoDivida))}}</td>
                                        <td>
                                            @if($itens->TipoMovimentacaoDivida == 'E') 
                                                <i class="bi bi-arrow-up-circle-fill text-danger"></i>
                                            @else 
                                                <i class="bi bi-arrow-down-circle-fill text-success"></i>
                                            @endif

                                        </td>
                                        <td class="@if($itens->TipoMovimentacaoDivida == 'E') text-danger @else text-success @endif">{{number_format(
                                                $itens->ValorMovimentacaoDivida,
                                                0,
                                                ',',
                                                '.'
                                            )}}
                                        </td>
                                        <td>{{$itens->ObservacaoMovimentacaoDivida}}</td>
                                    </tr>
                                @endforeach
                            
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    



<div class="modal fade" id="formUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Atualizar dívida: {{$divida->NomeDivida}}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/nova-rota" method="post">
            <input type="hidden" name="id" value="{{$divida->id}}">
            @csrf
            <p class="callout callout-warning">Os dados inseridos por essa janela não geram movimentações financeiras entre as contas.<br> Usar apenas para ajuste da dívida como aumento de juros ou diminuição do valor em negociação.</p>
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-6">
                    <div class="form-group">
                        <label for="DataMovimentacaoDivida" class="form-label">Data do movimento:</label>
                        <input type="date" name="DataMovimentacaoDivida" id="DataMovimentcaoDivida" class="form-control" required>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label for="TipoMovimentacaoDivida" class="form-label">Tipo de atualização:</label>
                        <select name="TipoMovimentacaoDivida" id="TipoMovimentacaoDivida" class="form-select" required>
                            <option value="" selected disabled>Selecione o tipo de atualização...</option>
                            <option value="E">Aumento da dívida</option>
                            <option value="S">Diminição da dívida</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ValorMovimentacaoDivida" class="form-label">Valor:</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="ValorMovimentacaoDivida" id="ValorMovimentacaoDivida" class="form-control Numero" required>
                        <span class="input-group-text">,00</span>
                    </div>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="ObservacaoMovimentacaoDivida" class="form-label">Observação:</label>
                    <input type="text" name="ObservacaoMovimentacaoDivida" id="ObservacaoMovimentacaoDivida" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="categoria_id" class="form-label">Categoria:</label>
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        <option value="" selected disabled>Selecione a categoria...</option>
                        @foreach($categoria as $itens)
                            <option value="{{$itens->id}}">{{$itens->NomeCategoria}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="subcategoria_id" class="form-label">Subcategoria:</label>
                    <select name="subcategoria_id" id="subcategoria_id" class="form-select">
                        <option value="" selected disabled>Selecione a Subcategoria...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <span class="icon bi bi-cloud-check"></span>
                    <span class="title">Salvar</span>
                </button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                    <span class="icon bi bi-ban"></span>
                    <span class="title">Cancelar</span>
                </button>
                
            </div>
        </form>
                                            
      </div>
      
    </div>
  </div>
</div>

@endsection


@section('Script')
<script>
    $(function() {
        //código a executar quando todos os elementos estão carregados
        $('#btnPreview').hide();
    });
</script>
<script>
        $(document).ready(function() {
            $('#categoria_id').on('change', function() {
                var categoriaId = $(this).val();
                if (categoriaId) {
                    $.ajax({
                        url: '{{ route('subcategorias.get') }}',
                        type: 'POST',
                        data: {
                            categoria_id: categoriaId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log(data); // Verifica a estrutura dos dados

                            // Obtenha as chaves do objeto como um array
                            var subcategoriaIds = Object.keys(data);
                            console.log(subcategoriaIds);
                            console.log(subcategoriaIds.length); // Agora isso deve funcionar
                            $('#subcategoria_id').empty();
                            $('#subcategoria_id').append('<option value="" selected disabled>Selecione a Subcategoria...</option>');
                            subcategoriaIds.forEach(function(id) {
                                var subcategoria = data[id];
                                
                                $('#subcategoria_id').append('<option value="'+ subcategoria.id +'">'+ subcategoria.NomeSubCategoria +'</option>');
                            });
                            
                            
                        }
                    });
                } else {
                    $('#subcategoria_id').empty();
                    $('#subcategoria_id').append('<option value="">Não deu certo...</option>');
                }
            });
        });
    </script>
@endsection