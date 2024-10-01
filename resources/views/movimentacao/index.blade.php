@extends('Tamplate.Tamplate')

@section('Title','Movimentação')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Movimentação')

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
@section('Cad', ' ')
@section('Banco', ' ')
@section('Cartao', ' ')
@section('Categoria', ' ')
@section('Subcategoria', ' ')
@section('Divida', ' ')
@section('Investimento', ' ')
@section('Meta', ' ')
@section('Movimentacao', ' active')

@section('meuCss', '../css/meucss.css')

@section('btn')
<div class="d-flex gap-2">
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTransferencia">
            <span class="icon bi bi-arrow-repeat"></span>
            <span class="title">Transferencia</span>
        </button>
    </div>
    <div>
        <a href="movimentacao/cad">
            <button class="btn btn-primary">
                <span class="icon bi bi-plus-circle"></span>
                <span class="title">Cadastrar</span>
            </button>
        </a>
    </div>
</div>
@endsection

@section('meuCss', '../css/meucss.css')

@section('Content')

<div class="card card-primary card-outline col-xl-10 offset-xl-1 mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title">Filtros</div>
        <button class="btn btn-primary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros">
            <i class="bi bi-funnel-fill"></i> <!-- Ícone do botão para expandir/recolher -->
        </button>
    </div>
    
    <div id="collapseFiltros" class="collapse">
        <div class="card-body">
            <form action="/movimentacao/pesquisa" method="post" id="formPesquisa">
                @csrf
                <div class="row">
                    <div class="form-group col-xl-4 col-md-4 mb-3">
                        <label for="descricaoFilter" class="form-label">Obs.:</label>
                        <input type="text" name="descricaoFilter" id="descricaoFilter" class="form-control">
                    </div>
                    <div class="form-group col-xl-4 col-md-4 mb-3">
                        <label for="dataStart" class="form-label">Data inicial:</label>
                        <input type="date" name="dataStart" id="dataStart" class="form-control" required>
                    </div>
                    <div class="form-group col-xl-4 col-md-4 mb-3">
                        <label for="dataEnd" class="form-label">Data final:</label>
                        <input type="date" name="dataEnd" id="dataEnd" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xl-4 col-md-6 mb-3">
                        <label for="categoria_id" class="form-label">Categoria:</label>
                        <select name="categoria_id" id="categoria_id" class="form-select">
                            <option value="" selected disabled>Selecione a categoria...</option>
                            @foreach($categoria->sortBy('NomeCategoria') as $dados)
                                <option value="{{$dados->NomeCategoria}}">{{$dados->NomeCategoria}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-md-6 mb-3">
                        <label for="subcategoria_id" class="form-label">Subcategoria:</label>
                        <select name="subcategoria_id" id="subcategoria_id" class="form-select">
                            <option value="" selected disabled>Selecione a subcategoria...</option>
                            @foreach($subcategoria->sortBy('NomeSubCategoria') as $dados)
                                <option value="{{$dados->NomeSubCategoria}}">{{$dados->NomeSubCategoria}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-md-6 mb-3">
                        <label for="banco_id" class="form-label">Banco:</label>
                        <select name="banco_id" id="banco_id" class="form-select" >
                            <option value="" selected disabled>Selecione o banco...</option>
                            @foreach($banco->sortBy('NomeBanco') as $dados)
                                <option value="{{$dados->NomeBanco}}">{{$dados->NomeBanco}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-footer text-end">
            <button type="button" id="btnPesquisar" class="btn btn-primary" >
                <span class="icon bi bi-search"></span>
                <span class="title">Pesquisar</span>
            </button>
            <button id="limparFiltros" class="btn btn-primary">
                <span class="icon bi bi-trash-fill"></span>
                <span class="title">Limpar</span>
            </button>
        </div>
    </div>
</div>
<div class="col-xl-10 offset-xl-1 mb-3">
    <div class="row">
    <div class="col-xl-4 col-md-4">
            <div class="info-box"> <span class="info-box-icon text-bg-success shadow-sm"> <i class="bi bi-currency-dollar"></i> </span>
                <div class="info-box-content" id="receitasCard"> 
                    <span class="info-box-text">Receitas</span> <span class="info-box-number">
                     
                    </span> 
                </div>
            </div>  
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="info-box"> <span class="info-box-icon text-bg-danger shadow-sm"> <i class="bi bi-cash-stack"></i> </span>
                <div class="info-box-content" id="despesasCard"> 
                    <span class="info-box-text">Despesas</span> 
                    <span class="info-box-number">
                       
                    </span> 
                </div>
            </div>  
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="info-box"> <span class="info-box-icon text-bg-primary shadow-sm"> <i class="bi bi-wallet2"></i> </span>
                <div class="info-box-content" id="saldoCard"> 
                    <span class="info-box-text">Saldo</span> <span class="info-box-number">
                    
                </div>
            </div>  
        </div>
    </div>
</div>
<div class="card col-xl-10 offset-xl-1">
    <div class="card-header">
        <h3 class="card-title">Movimentações financeiras</h3>
    </div> <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tableContent">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Categoria</th>
                            <th>Subcategoria</th>
                            <th>Banco</th>
                            <th>Valor (R$)</th>
                            <th>Obs.</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($mov)>0)
                            @foreach($mov->sortBy('DataMovimentacaoFinanc') as $dados)
                                
                                <tr class="align-middle">
                                    <td>
                                        @if($dados->PagoMovimentacaoFinanc == '1')
                                            <i title="Pago" class="bi bi-check-circle-fill text-success"></i>
                                        @elseif($dados->DataVencimentoMovimentacaoFinanc < date('Y-m-d', strtotime(now())))
                                            <i title="Atrasado" class="bi bi-exclamation-circle-fill text-danger"></i>
                                        @else
                                            <i title="Previsto" class="bi bi-dash-circle-fill text-primary"></i>
                                        @endif
                                    </td>
                                    <td>{{date('d/m/y', strtotime($dados->DataMovimentacaoFinanc))}}</td>
                                    <td>
                                        @if($dados->movCategoria->TipoCategoria == 'T')
                                            @if($dados->TipoMovimentacaoFinanc == 'D')
                                                Despesa - Transferência
                                            @else
                                                Receita - Transferência
                                            @endif
                                        @else
                                            {{$dados->movSubcategoria->CategorizacaoSubCategoria}}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-2 col-lg-2">
                                                <button class="btn @if($dados->movCategoria->TipoCategoria == 'R')  btn-receita  @elseif($dados->movCategoria->TipoCategoria == 'D')  btn-despesa @else btn-secondary @endif" title="{{$dados->movCategoria->NomeCategoria}}"><i class="bi bi-{{$dados->movCategoria->IconeCategoria}}"></i> </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-2 col-lg-2">
                                                <button class="btn @if($dados->movCategoria->TipoCategoria == 'R')  btn-receita  @elseif($dados->movCategoria->TipoCategoria == 'D')  btn-despesa @else btn-secondary  @endif" title="{{$dados->movSubcategoria->NomeSubCategoria}}"><i class="bi bi-{{$dados->movSubcategoria->IconeSubCategoria}}"></i> </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$dados->movBanco->NomeBanco}}</td>
                                    <td>{{number_format($dados->ValorFimMovimentacaoFinanc,2,',','.')}}</td>
                                    <td>{{$dados->ObsMovimentacaoFinanc}}</td>
                                    <td>
                                        <div class="row">
                                            <div class="d-flex gap-2">
                                                <button type="button" id="openBaixa" class="btn btn-primary" title="Dar baixa" data-bs-toggle="modal" data-bs-target="#modalBaixa" data-bs-id="{{$dados->id}}" data-bs-card="{{$dados->FaturaCardMovimentacaoFinanc}}" data-bs-valor="{{number_format($dados->ValorMovimentacaoFinanc,2,',','.')}}" data-bs-data="{{date('Y-m-d', strtotime($dados->DataMovimentacaoFinanc))}}" @if($dados->PagoMovimentacaoFinanc == '1') disabled @endif> <i class="bi bi-check-circle"></i></button>
                                                <a href="/movimentacao/{{$dados->id}}"><button class="btn btn-secondary" title="Visualizar"><i class="bi bi-eye-fill"></i> </button></a>
                                                <a href="/movimentacao/edit/{{$dados->id}}"><button class="btn btn-success" title="Editar"><i class="bi bi-pen-fill"></i> </button></a>
                                                <a href="/movimentacao/delete/{{$dados->id}}"><button class="btn btn-danger" title="Excluir"><i class="bi bi-file-earmark-x"></i> </button></a>
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            
                            @endforeach
                        @else
                            <tr class="align-middle">
                                <td colspan="9">Nenhum dado cadastrado!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->



<!-- Modal  para transferência-->
<div class="modal fade" id="modalTransferencia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="movimentacao/transferencia" method="post">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Transferencia entre contas!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="categoria_id" class="form-label">Categoria:</label>
                        <select name="categoria_id" id="categoria_id2" class="form-select" required>
                            <option value="" selected disabled>Selecione a categoria...</option>
                            @foreach($categoria->where('TipoCategoria', 'T')->sortBy('NomeCategoria') as $dados)
                                <option value="{{$dados->id}}">{{$dados->NomeCategoria}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="subcategoria_id" class="form-label">Subcategoria:</label>
                        <select name="subcategoria_id" id="subcategoria_id2" class="form-select" required>
                            <option value="" selected disabled>Selecione a subcategoria...</option>
                            
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="banco_id" class="form-label">Banco origem:</label>
                        <select name="banco_id" id="banco_id" class="form-select" required>
                            <option value="" selected disabled>Selecione o banco...</option>
                            @foreach($banco->sortBy('NomeBanco') as $dados)
                                <option value="{{$dados->id}}">{{$dados->NomeBanco}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="banco_id2" class="form-label">Banco destino:</label>
                        <select name="banco_id2" id="banco_id2" class="form-select" required>
                            <option value="" selected disabled>Selecione o banco...</option>
                            @foreach($banco->sortBy('NomeBanco') as $dados)
                                <option value="{{$dados->id}}">{{$dados->NomeBanco}}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="data" class="form-label">Data da movimentação:</label>
                        <input type="date" name="data" id="data" class="form-control" required>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="ValorMovimentacaoFinanc" class="form-label">Valor:</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" name="ValorMovimentacaoFinanc" id="ValorMovimentacaoFinanc" class="form-control Numero2" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-5 col-xl-3">
                    <button type="submit" class="btn btn-primary">
                        <span class="icon bi bi-arrow-left-right"></span>
                        <span class="title">Transferir</span>
                    </button>  
                </div>
                <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                    <button class="btn btn-danger" type="button"   data-bs-dismiss="modal">
                        <span class="icon bi bi-ban"></span>
                        <span class="title">Cancelar</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>



<!-- Modal para baixa-->
<div class="modal fade" id="modalBaixa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Baixa financeira</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <form action="/movimentacao/baixa" method="post">
        <div class="modal-body">
            @csrf
            <input type="hidden" id="recipientId" name="id">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                    <label for="dataBaixa" class="form-label">Data:</label>
                    <input type="date" class="form-control" name="dataBaixa" id="dataBaixa" required>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <label for="Valor" class="form-label">Valor:</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="Valor" id="Valor" class="form-control Numero2" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3." id='waning'>
                <div class="card-title callout callout-warning">
                    Esse registro é um registro de cartão de crédito,
                    para baixas parcial é necessário escolher uma categoria e subcategoria para registrar o saldo na próxima fatura
                </div>
            </div>
            <div class="row" id='categoriaSaldo'>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="categoriaSaldo2" class="form-label">Categoria:</label>
                    <select name="categoriaSaldo2" id="categoriaSaldo2" class="form-select">
                        <option value="" selected disabled>Selecione a categoria...</option>
                            @foreach($categoria->sortBy('NomeCategoria')->where('TipoCategoria', 'D') as $dados)
                                <option value="{{$dados->id}}">{{$dados->NomeCategoria}}</option>
                            @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-6 col-md-6 mb-3">
                    <label for="subSaldo2" class="form-label">Subcategoria:</label>
                    <select name="subSaldo2" id="subSaldo2" class="form-select">
                        <option value="" selected disabled>Selecione a subcategoria...</option>
                    </select>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
                <div class="col-5 col-xl-3">
                    <button type="submit" class="btn btn-primary">
                        <span class="icon bi bi-arrow-down-circle"></span>
                        <span class="title">Baixar</span>
                    </button>  
                </div>
                <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                    <button class="btn btn-danger" type="button"   data-bs-dismiss="modal">
                        <span class="icon bi bi-ban"></span>
                        <span class="title">Cancelar</span>
                    </button>
                </div>
        </div>
    </form>
    </div>
  </div>
</div>
@endsection

@section('Script')
<script>

    

    $(document).ready(function() {

        $('#waning').hide();
        $('#categoriaSaldo').hide();

            const exampleModal = document.getElementById('modalBaixa')
            if (exampleModal) {
                
                exampleModal.addEventListener('show.bs.modal', event => {
                    // Button that triggered the modal
                    const button = event.relatedTarget
                    // Extract info from data-bs-* attributes
                    const recipientId = button.getAttribute('data-bs-id')
                    const recipientData = button.getAttribute('data-bs-data')
                    const recipientValor = button.getAttribute('data-bs-valor')
                    const fatCard = button.getAttribute('data-bs-card')
                    
                    // If necessary, you could initiate an Ajax request here
                    // and then do the updating in a callback.

                    // Update the modal's content.
                    //const modalTitle = exampleModal.querySelector('.modal-title')
                    //const modalBodyInput = exampleModal.querySelector('.modal-body input')

                    //modalTitle.textContent = `New message to ${recipient}`
                    //modalBodyInput.value = recipientId
                    $('#recipientId').val(recipientId)
                    $('#dataBaixa').val(recipientData)
                    $('#Valor').val(recipientValor)

                    if(fatCard === '0' ){
                            $('#waning').hide();
                            $('#categoriaSaldo').hide();
                            $('#categoriaSaldo2').attr('required', false);
                            $('#subSaldo2').attr('required', false);
                        }

                    $('#Valor').on('change', function(){
                        if(fatCard === '1' && $('#Valor').val() < recipientValor){
                            $('#waning').show();
                            $('#categoriaSaldo').show();
                            $('#categoriaSaldo2').attr('required', true);
                            $('#subSaldo2').attr('required', true);
                        }else{
                            $('#waning').hide();
                            $('#categoriaSaldo').hide();
                            $('#categoriaSaldo2').attr('required', false);
                            $('#subSaldo2').attr('required', false);
                        }
                    });
                })
            }
        


        

        
        function updateCards() {
            let receitaTotal = 0;
            let despesaTotal = 0;

            // Iterar pelas linhas visíveis da tabela
            $('#tableContent tbody tr:visible').each(function() {
                let valor = parseFloat($(this).find('td:eq(6)').text().replace(/\./g, '').replace(',', '.'));
                let categoria = $(this).find('td:eq(2)').text().trim(); // Categoria (Receita/Despesa)

                // Verificar se é receita ou despesa e somar os valores
                if (categoria === 'Receita - Fixa' || categoria === 'Receita - Variável' || categoria === 'Receita - Extra' || categoria === 'Receita - Transferência') {
                    receitaTotal += valor;
                } else if (categoria === 'Despesa - Fixa' || categoria === 'Despesa - Variável' || categoria === 'Despesa - Extra' || categoria === 'Despesa - Transferência') {
                    despesaTotal += valor;
                }
            });

            // Formatar os valores para reais (1.000,00)
            let saldoTotal = receitaTotal - despesaTotal;
            let options = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

            var str1 = '<span class="info-box-text">Receitas</span> <span class="info-box-number"><small>R$</small>';
            var str2 = '</span> ';
            $('#receitasCard .info-box-number').html('<small>R$</small> ' + receitaTotal.toLocaleString('pt-BR', options));
            $('#despesasCard .info-box-number').html('<small>R$</small> ' + despesaTotal.toLocaleString('pt-BR', options));
            //$('#saldoCard .info-box-number').html('<small>R$</small> ' + saldoTotal.toLocaleString('pt-BR', options));
            // Atualizar o saldo e aplicar a classe text-danger se for negativo
            let saldoElement = $('#saldoCard .info-box-number');
            saldoElement.html('<small>R$</small> ' + saldoTotal.toLocaleString('pt-BR', options));
            
            if (saldoTotal < 0) {
                saldoElement.addClass('text-danger');  // Aplicar classe vermelho
            } else {
                saldoElement.removeClass('text-danger');  // Remover classe vermelho se saldo positivo
            }
        }

        // Chamar a função sempre que os filtros forem aplicados ou a tabela for atualizada
        updateCards();
                
        

        $('#btnPesquisar').on('click', function() {
            if($('#dataStart').val() === '' || $('#dataEnd').val() === ''){
                alert('As datas precisam ser preenchidas!');
            }else{
                if($('#dataEnd').val()< $('#dataStart').val()){
                    alert('A data final necessáriamente precisa ser maior do que a data inicial!');
                }else{
                    $('#formPesquisa').submit();
                }
            }
        });

        // Função para filtrar a tabela
        function filterTable() {
            var descricaoFiltro = $('#descricaoFilter').val().toLowerCase();
            if($('#categoria_id').val() != null){
                var categoria = $('#categoria_id').val().toLowerCase();
            }else{
                var categoria = '';
            }
            
            if($('#subcategoria_id').val() != null){
                var sub = $('#subcategoria_id').val().toLowerCase();
            }else{
                var sub = '';
            }
            
            if($('#banco_id').val() != null){
                var banco = $('#banco_id').val().toLowerCase();
            }else{
                var banco = '';
            }
            
            var dataInicio = $('#dataStart').val();
            var dataFim = $('#dataEnd').val();

            $('#tableContent tbody tr').filter(function() {
                var descricao = $(this).find('td').eq(7).text().toLowerCase();
                var descricaoStatus = $(this).find('i').attr('title').toLowerCase();
                var descricaoTipo = $(this).find('td').eq(2).text().toLowerCase();

                var categoriaTable = $(this).find('button').eq(0).attr('title').toLowerCase();
                var subcategoriaTable = $(this).find('button').eq(1).attr('title').toLowerCase();
                var bancoTable = $(this).find('td').eq(5).text().toLowerCase();

                var dataTable = $(this).find('td').eq(1).text().trim();
                var mostrar = true;

                // Converter a data da tabela de DD/MM/YY para YYYY-MM-DD
                var partesData = dataTable.split('/');
                var dataConvertida = '20' + partesData[2] + '-' + partesData[1] + '-' + partesData[0];


                // Filtrar por descrição
                if (descricaoFiltro && (!descricao.includes(descricaoFiltro) && !descricaoStatus.includes(descricaoFiltro) && !descricaoTipo.includes(descricaoFiltro))) {
                    mostrar = false;
                }

                //filtrar categoria
                if(categoria && !categoriaTable.includes(categoria)){
                    mostrar = false;
                }

                if(sub && !subcategoriaTable.includes(sub)){
                    mostrar = false;
                }

                if(banco && !bancoTable.includes(banco)){
                    mostrar = false;
                }

                // Filtrar por intervalo de datas
                if (dataInicio && dataConvertida < dataInicio) {
                    mostrar = false;
                }

                if (dataFim && dataConvertida > dataFim) {
                    mostrar = false;
                }

                

                $(this).toggle(mostrar);
            });
        }

        // Aplicar filtros
        $('#descricaoFilter, #dataStart, #dataEnd').on('input change', function() {
            filterTable();
            updateCards()
        });

        $('#dataStart').on('change', function(){
            $('#dataEnd').val($('#dataStart').val());
        });

        $('#categoria_id, #subcategoria_id, #banco_id').on('change', function(){
            filterTable();
            updateCards()
        });

        $('#limparFiltros').click(function() {
            if(
                $('#descricaoFilter').val().trim() === '' && 
                $('#dataStart').val().trim() === '' && 
                $('#dataEnd').val().trim() === '' && 
                ($('#categoria_id').val() === '' || $('#categoria_id').val() === null) && 
                ($('#subcategoria_id').val() === '' || $('#subcategoria_id').val() === null) && 
                ($('#banco_id').val() === '' || $('#banco_id').val() === null)
            ){
                window.location.href = '/movimentacao';  
                  
            }else{
                // Limpar todos os inputs de texto e de data
                $('#descricaoFilter, #dataStart, #dataEnd').val('');
                // Resetar todos os selects
                $('#categoria_id, #subcategoria_id, #banco_id').val('');
                // Disparar o evento para refiltrar a tabela
                filterTable();
                updateCards();
            }
            
        });

        
            $('#categoria_id2').on('change', function() {
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
                            //console.log(data); // Verifica a estrutura dos dados

                            // Obtenha as chaves do objeto como um array
                            var subcategoriaIds = Object.keys(data);
                            //console.log(subcategoriaIds);
                            //console.log(subcategoriaIds.length); // Agora isso deve funcionar
                            $('#subcategoria_id2').empty();
                            $('#subcategoria_id2').append('<option value="" selected disabled>Selecione a Subcategoria...</option>');
                            subcategoriaIds.forEach(function(id) {
                                var subcategoria = data[id];
                                
                                $('#subcategoria_id2').append('<option value="'+ subcategoria.id +'">'+ subcategoria.NomeSubCategoria +'</option>');
                            });
                            
                            
                        }
                    });
                } else {
                    $('#subcategoria_id').empty();
                    $('#subcategoria_id').append('<option value="">Não deu certo...</option>');
                }
            });

            $('#categoriaSaldo2').on('change', function() {
                var categoriaId = $('#categoriaSaldo2').val();
                if (categoriaId) {
                    $.ajax({
                        url: '{{ route('subcategorias.get') }}',
                        type: 'POST',
                        data: {
                            categoria_id: categoriaId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            //console.log(data); // Verifica a estrutura dos dados

                            // Obtenha as chaves do objeto como um array
                            var subcategoriaIds = Object.keys(data);
                            //console.log(subcategoriaIds);
                            //console.log(subcategoriaIds.length); // Agora isso deve funcionar
                            $('#subSaldo2').empty();
                            $('#subSaldo2').append('<option value="" selected disabled>Selecione a Subcategoria...</option>');
                            subcategoriaIds.forEach(function(id) {
                                var subcategoria = data[id];
                                
                                $('#subSaldo2').append('<option value="'+ subcategoria.id +'">'+ subcategoria.NomeSubCategoria +'</option>');
                            });
                            
                            
                        }
                    });
                } else {
                    $('#subSaldo2').empty();
                    $('#subSaldo2').append('<option value="">Não deu certo...</option>');
                }
            });
    });

    
</script>
@endsection