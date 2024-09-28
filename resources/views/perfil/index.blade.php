@extends('Tamplate.Tamplate')

@section('Title','Perfil do usuário')

@section('logo', '\imgsystem\logo.png')

@section('imgUser')

    @if(!empty(session('imgUserPerfil')))
        imguser/{{session('imgUserPerfil')}}
    @else 
        imguser/user.png
    @endif

@endsection

@section('meuCss', '../css/meucss.css')

@section('cssAdmin','\css\adminlte.css')

@section('TitlePage', 'Dados do usuário')

@section('jsAdmin', '../js/adminlte.js')

@section('Content')

<div class="row col-sm-10 col-md-12 col-lg-12 col-xl-12">
    <!-- Primeira Coluna -->
    <div class="col-xl-3 offset-xl-1  d-flex align-items-stretch mb-3">
        <div class="card card-primary card-outline w-100">
            <div class="card-header">
                <div class="card-title">Perfil</div>
            </div>
            <div class="card-body box-profile">
                <div class="text-center">
                    <div class="profile-img-container">
                        <img class="profile-user-img img-fluid" src="@if(!empty($user->ImgUsuario)) imguser/{{$user->ImgUsuario}} @else imguser/user.png @endif" alt="User profile picture">
                    </div>
                </div>
                <h3 class="profile-username text-center">{{$user->NomeUsuario}}</h3>
                <p class="text-muted text-center">Membro desde {{date('M-Y', strtotime($user->created_at))}}</p>
                <p class="text-muted text-center">Último acesso {{date('d/M/Y', strtotime($lastAccess->created_at))}}</p>
            </div>
        </div>
    </div>

    <!-- Segunda Coluna -->
    <div class="col-xl-7  d-flex align-items-stretch">
        <div class="card card-primary card-outline w-100">
            <div class="card-header">
                <div class="card-title">Dados do usuário</div>
            </div>
            <div class="card-body">
                <form action="perfil/update/{{session('user')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="NomeUsuario" class="form-label">Nome do usuário</label>
                        <input type="text" class="form-control" name="NomeUsuario" id="NomeUsuario" value="{{$user->NomeUsuario}}">
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 mb-3">
                                <label for="EmailUsuario" class="form-label">E-mail do usuário</label>
                                <input type="mail" class="form-control" id="EmailUsuario" name="EmailUsuario" value="{{$user->EmailUsuario}}">
                            </div>
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">
                                <label for="SenhaUsuario" class="form-label">Senha do usuário</label>
                                <input type="password" class="form-control" id="SenhaUsuario" name="SenhaUsuario">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ImgUser" class="form-label">Alterar imagem</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="ImagemUsuario" name="ImagemUsuario">
                            <label for="imgUser" class="input-group-text">Upload</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 col-xl-3">
                                <button type="submit" class="btn btn-primary">
                                    <span class="icon bi bi-cloud-check"></span>
                                    <span class="title">Salvar</span>
                                </button> 
                            </div>
                            <div class="col-5 offset-2 col-xl-3 offset-xl-6">
                                <button class="btn btn-danger" type="button" onclick="location.href = '/home';">
                                    <span class="icon bi bi-ban"></span>
                                    <span class="title">Cancelar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!--    <div class="row">
        <div class="col-sm-10 offset-1 col-md-3 col-lg-3 col-xl-3">
            <div class="card card-primary card-outline ">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="imguser/user.png"
                            alt="User profile picture" heigth='200px' width='200px'>
                    </div>

                    <h3 class="profile-username text-center">{{session('userName')}}</h3>

                    <p class="text-muted text-center">Membro desde {{session('dateCad')}}</p>

                    
                </div>
                     
            </div>
        </div>

        <div class="col-sm-10 offset-1 col-md-6 col-lg-6 col-xl-6">
            <div class="card card-primary card-outline  "> 
                <div class="card-header">
                    <div class="card-title">Dados cadastrais</div>
                </div> 
                <form action="/cartao/cad" method="post"> 
                    @csrf
                    <div class="card-body">
                        <div class="mb-3"> 
                            <label for="NomeCartao" class="form-label">Nome do usuário</label> 
                            <input type="text" class="form-control" id="NomeCartao" name="NomeCartao" aria-describedby="Cartão">
                        </div>
                        <div class="row">
                            <div class="mb-3 col-5">
                                <label for="EmailUsuario" class="form-label">E-mail do usuário:</label>
                                <input type="mail" class="form-control" id="EmailUsuario" name="EmailUsuario">
                            </div>
                            <div class="mb-3 col-5 offset-2">
                                <label for="EmailUsuario" class="form-label">Senha do usuário</label>
                                <input type="password" class="form-control" id="EmailUsuario" name="EmailUsuario">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="Img" class="form-label">Alterar imagem</label>
                                <div class="input-group mb-3"> <input type="file" class="form-control" id="inputGroupFile02"> <label class="input-group-text" for="inputGroupFile02">Upload</label> </div>
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
                                    <button class="btn btn-danger" type="button"  onclick="location.href = '/home';">
                                        <span class="icon bi bi-ban"></span>
                                        <span class="title">Cancelar</span>
                                    </button>
                                </div>
                            </div>
                            
                        </div> 
                </form> 
            </div> 
        </div>

    </div>

    <div class="row">.</div>

    <div class="row">
        <div class="col-10 offset-1">
        <div class="card card-primary card-outline  col-10 offset-1"> 
                <div class="card-header">
                    <div class="card-title">Pessoas com acesso ao seu espaço</div>
                </div>
                <div class="table-responsive">

                </div>
            </div> 
        </div>
    </div>

-->
@endsection


@section('Script')

<script>
    $('#fake2').hide();
</script>

@endsection