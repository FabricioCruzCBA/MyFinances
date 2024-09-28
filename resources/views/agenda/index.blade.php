@extends('Tamplate.Tamplate')

@section('Title','Agenda')

@section('logo', '\imgsystem\logo.png')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Agenda')

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
@section('Movimentacao', ' ')
@section('Agenda', ' active')

@section('meuCss', '../css/meucss.css')

@section('btn')

<div class="d-flex gap-2">
    
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadNewEvensts">
            <span class="icon bi bi-plus-circle"></span>
            <span class="title">Cadastrar</span>
        </button>
    </div>
</div>


@endsection

@section('meuCss', '../css/meucss.css')

@section('Content')

<div class="container">
    <div class="calendar-container">
        <div class="calendar-wrapper">
            <div id="calendar"></div>
        </div>
    </div>
</div>
<!-- Modal cad evento-->
<div class="modal fade" id="cadNewEvensts" tabindex="-1" aria-labelledby="cadNewEvensts" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/agenda/cad" method="post" id="newEvents">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Compromisso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" name="descricao" id="descricao" class="form-control" required>
                        </div>
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="tipo" class="form-label">Tipo de evento:</label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="" selected disabled>Selecione o tipo...</option>
                                <option class="text-primary" value="P">Pessoal</option>
                                <option class="text-warning" value="F">Profissional</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="dataStart" class="form-label">inicio:</label>
                            <input type="datetime-local" name="dataStart" id="dataStart" class="form-control">
                        </div>
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="dataEnd" class="form-label">Fim:</label>
                            <input class="form-control" type="datetime-local" name="dataEnd" id="dataEnd" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 mb-3">
                            <label for="obs" class="form-label">Obsevações:</label>
                            <input type="text" name="obs" id="obs" class="form-control">
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <div class="col-5 col-xl-3">
                        <button type="submit" class="btn btn-primary" id="btnSub">
                            <span class="icon bi bi-cloud-check"></span>
                            <span class="title">Salvar</span>
                        </button> 
                    </div>
                    <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
                            <span class="icon bi bi-ban"></span>
                            <span class="title">Cancelar</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal cad evento editar/confirmar/excluir-->
<div class="modal fade" id="Event" tabindex="-1" aria-labelledby="Event" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post" id="formEvent">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Compromisso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id2" id="id2">
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="descricao2" class="form-label">Descrição:</label>
                            <input type="text" name="descricao2" id="descricao2" class="form-control" required>
                        </div>
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="tipo2" class="form-label">Tipo de evento:</label>
                            <select name="tipo2" id="tipo2" class="form-select" required>
                                <option value="" selected disabled>Selecione o tipo...</option>
                                <option class="text-primary" value="P">Pessoal</option>
                                <option class="text-warning" value="F">Profissional</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="dataStart2" class="form-label">inicio:</label>
                            <input type="datetime-local" name="dataStart2" id="dataStart2" class="form-control">
                        </div>
                        <div class="form-group col-xl-6 col-md-6 col-sm-12 mb-3">
                            <label for="dataEnd2" class="form-label">Fim:</label>
                            <input class="form-control" type="datetime-local" name="dataEnd2" id="dataEnd2" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 mb-3">
                            <label for="obs2" class="form-label">Obsevações:</label>
                            <input type="text" name="obs2" id="obs2" class="form-control">
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" id="btnSalvar">
                        <span class="icon bi bi-cloud-check"></span>
                        <span class="title">Salvar</span>
                    </button>

                    <button class="btn btn-primary" type="button" id="btnConfirmar">
                        <span class="icon bi bi-check-circle"></span>
                        <span class="title">Confirmar</span>
                    </button>

                    <button class="btn btn-warning" type="button" id="btnExcluir">
                        <span class="icon bi bi-trash"></span>
                        <span class="title">Excluir</span>
                    </button>

                    <button class="btn btn-danger" type="button"  data-bs-dismiss="modal">
                        <span class="icon bi bi-ban"></span>
                        <span class="title">Cancelar</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('Script')

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>

    $(document).ready(function(){
        $('#btnSalvar').on('click', function(){
            $('#formEvent').attr('action','/agenda/salvar');
            $('#formEvent').submit();
        });

        $('#btnConfirmar').on('click', function(){
            $('#formEvent').attr('action','/agenda/confirmar');
            $('#formEvent').submit();
        });

        $('#btnExcluir').on('click', function(){
            $('#formEvent').attr('action','/agenda/excluir');
            $('#formEvent').submit();
        });

        $('#dataStart').on('change', function(){
            $('#dataEnd').val($('#dataStart').val());
        });

        $('#dataStart2').on('change', function(){
            $('#dataEnd2').val($('#dataStart2').val());
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Visão inicial (mensal)
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay' // Opções de visualização
            },
            locale: 'pt-br',
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia',
            },
            height: 500,
            events: [
                @foreach($agenda as $itens)
                    @php
                        if($itens->Confirmacao == '1'){
                            $cor = '#75b798';
                        }else{
                            if($itens->Tipo == 'P'){
                                $cor = '#0d6efd';
                            }else{
                                $cor = '#0dcaf0';
                            }
                        }
                    @endphp
                    {
                        title: '{{$itens->Descricao}}',
                        start: '{{$itens->DataStart}}',
                        end:   '{{$itens->DataEnd}}',
                        color: '{{$cor}}',
                        id: '{{$itens->id}}'
                    },
                @endforeach
                
            ],
            editable: false, // Permite arrastar eventos
            selectable: true, // Permite selecionar dias/horários
            dateClick: function(info) {
                // Define a data clicada no campo dataStart
                document.getElementById('dataStart').value = info.dateStr + 'T00:00';

                // Abre a modal
                var myModal = new bootstrap.Modal(document.getElementById('cadNewEvensts'));
                myModal.show();
            },
            eventClick: function(info) {
                // Realiza uma requisição AJAX para obter os dados do evento pelo ID
                fetch(`/agenda/event/${info.event.id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Necessário para requests no Laravel
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Preenche os campos da modal com os dados recebidos
                    document.getElementById('id2').value = data.id;
                    document.getElementById('descricao2').value = data.Descricao;
                    document.getElementById('tipo2').value = data.Tipo;
                    document.getElementById('dataStart2').value = data.DataStart;
                    document.getElementById('dataEnd2').value = data.DataEnd;
                    document.getElementById('obs2').value = data.Obs;

                    // Altera a action do form para a edição com o ID do evento
                    document.getElementById('formEvent').action = `/agenda/edit/${info.event.id}`;

                    

                    // Abre a modal
                    var myModal = new bootstrap.Modal(document.getElementById('Event'));
                    myModal.show();
                })
                .catch(error => {
                    console.error('Erro ao buscar dados do evento:', error);
                });
            }
        });

        calendar.render();
    });
</script>

@endsection