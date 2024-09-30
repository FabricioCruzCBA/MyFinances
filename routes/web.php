<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use App\Mail\sendmail;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\tokenverificaemail;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\CartaocreditoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\DividaController;
use App\Http\Controllers\InvestimentoController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\MovimentacaofinanceiraController;
use App\Http\Controllers\MovimentacaocartaoController;
use App\Http\Controllers\SistemaController;
use App\Http\Controllers\AgendaController;


//Rota do home dashboars
Route::get('/home', [SistemaController::class, 'home']);
route::get('/', [SistemaController::class, 'base']);
route::post('/home/filter', [SistemaController::class, 'homeFilter']);

//rotas do sistema
Route::get('/login', function(){
    return view('auth.login');
});
route::get('/sair', function(){
    session()->flush();
    return redirect('login');
});
route::get('/CadastroUsuario', function(){
    return view('auth.registro');
});
route::post('/CadastroUsuario', [UsuarioController::class, 'store']);
route::post('/logar',[UsuarioController::class, 'login']);
route::get('/verifica-mail/{token}', [UsuarioController::class, 'validaMail']);
route::get('/resetsenha', [UsuarioController::class, 'resetSenha']);
route::post('/resetsenha', [UsuarioController::class, 'enviarEmailSenha']);
route::get('/reset-senha/{token}', [UsuarioController::class, 'verificaToken']);
route::post('/new-password', [UsuarioController::class, 'salvarSenha']);


//Rotas dos bancos
route::get('/bancos', [BancoController::class, 'index']);
route::get('/bancos/cad', function(){
    return view('banco.create');
});
route::post('/bancos/cad', [BancoController::class, 'store']);
route::get('/bancos/{id}', [BancoController::class,'show']);
route::get('/bancos/edit/{id}', [BancoController::class, 'showEdit']);
route::put('/bancos/update/{id}', [BancoController::class, 'update']);
route::get('/bancos/delete/{id}', [BancoController::class, 'showDelet']);
route::post('/bancos/delete', [BancoController::class, 'delete']);



//Rotas dos cartões
route::get('/cartao', [CartaocreditoController::class, 'index']);
route::get('/cartao/cad', function(){
    return view('cartao.create');
});
route::post('/cartao/cad',[CartaocreditoController::class, 'store']);
route::get('/cartao/{id}', [CartaocreditoController::class, 'show']);
route::get('/cartao/edit/{id}', [CartaocreditoController::class, 'showEdit']);
route::put('/cartao/update/{id}', [CartaocreditoController::class, 'update']);
route::get('/cartao/delete/{id}', [CartaocreditoController::class, 'showDelet']);
route::post('/cartao/delete', [CartaocreditoController::class, 'delete']);
route::get('/cartao/item/{id}/{idCard}', [MovimentacaocartaoController::class, 'deleteItem']);
route::post('/cartao/fatura', [CartaocreditoController::class, 'closeFat']);
route::get('/cartao/fatura/delete/{id}', [CartaocreditoController::class, 'deleteFat']);

//Rotas das categorias
route::get('/categoria', [CategoriaController::class, 'index']);
route::get('/categoria/cad', [CategoriaController::class, 'Insert']);
route::post('/categoria/cad', [CategoriaController::class, 'store']);
route::get('/categoria/{id}', [CategoriaController::class, 'show']);
route::get('/categoria/edit/{id}', [CategoriaController::class, 'showEdit']);
route::put('/categoria/update/{id}', [CategoriaController::class, 'update']);
route::get('/categoria/delete/{id}', [CategoriaController::class, 'showDelete']);
route::post('/categoria/delete', [CategoriaController::class, 'delete']);


//Rotas das subcategorias
route::get('/subcategoria', [SubcategoriaController::class, 'index']);
route::get('/subcategoria/cad', [SubcategoriaController::class, 'insert']);
route::post('/subcategoria/cad', [SubcategoriaController::class, 'store']);
route::get('/subcategoria/{id}', [SubcategoriaController::class, 'show']);
route::get('/subcategoria/edit/{id}', [SubcategoriaController::class, 'showEdit']);
route::put('/subcategoria/update/{id}', [SubcategoriaController::class, 'update']);
route::get('/subcategoria/delete/{id}', [SubcategoriaController::class, 'showDelete']);
route::post('/subcategoria/delete', [SubcategoriaController::class, 'delete']);

//Rotas do perfil do usuário
route::get('/perfil',[UsuarioController::class, 'showPerfil']);
route::put('/perfil/update/{id}', [UsuarioController::class, 'update']);

//Rotas para orçamento
route::get('/orcamento', [OrcamentoController::class, 'index']);
route::get('/orcamento/cad', [OrcamentoController::class, 'store']);
route::get('/orcamento/edit', [OrcamentoController::class, 'showEdit']);
route::post('/orcamento/salvar', [OrcamentoController::class, 'salvar']);

//dívidas
route::get('/divida', [DividaController::class, 'index']); 
route::get('/divida/cad', [DividaController::class, 'cadastrarView']);
route::post('/divida/cad', [DividaController::class, 'store']);
route::post('/subcategorias/get', [DividaController::class, 'getSubcategoria'])->name('subcategorias.get');
route::get('/divida/{id}', [DividaController::class, 'show']);
route::post('/divida/up-mov/', [DividaController::class, 'addMov']);
route::get('/divida/edit/{id}', [DividaController::class, 'showEdit']);
route::post('/divida/edit', [DividaController::class, 'atualizarRegistro']);
route::get('/divida/delete/{id}', [DividaController::class, 'showDelete']);
route::post('divida/delete', [DividaController::class, 'delete']);


//Rotas para os investimentos...
route::get('/investimento', [InvestimentoController::class, 'index']);
route::get('/investimento/cad', [InvestimentoController::class, 'cadastrarView']);
route::post('/investimento/cad', [InvestimentoController::class, 'store']);
route::get('/investimento/{id}', [InvestimentoController::class, 'show']);
route::post('/investimento/atualizar', [InvestimentoController::class, 'atualizar']);
route::get('/investimento/edit/{id}', [InvestimentoController::class, 'showEdit']);
route::post('/investimento/edit', [InvestimentoController::class, 'update']);
route::get('/investimento/delete/{id}', [InvestimentoController::class, 'showDel']);
route::post('/investimento/delete', [InvestimentoController::class, 'delete']);

//Rotas para as metas
route::get('/meta',[MetaController::class, 'index']);
route::get('/meta/cad', [MetaController::class, 'create']);
route::post('/meta/cad', [MetaController::class, 'store']);
route::get('/meta/{id}',[MetaController::class, 'show']);
route::post('/meta/atualizar', [MetaController::class,  'atualizar']);
route::get('/meta/edit/{id}', [MetaController::class, 'showEdit']);
route::put('/meta/update/{id}', [MetaController::class ,'update']);
route::get('/meta/delete/{id}', [MetaController::class,'showDelete']);
route::post('/meta/delete', [MetaController::class, 'delete']);


//Rotas para movimentação financeira
route::get('/movimentacao', [MovimentacaofinanceiraController::class, 'index'])  ;
route::get('/movimentacao/cad', [MovimentacaofinanceiraController::class, 'create']);
route::post('/categorias/get', [MovimentacaofinanceiraController::class, 'getCategoria'])->name('categorias.get');
route::post('/movimentacao/cad', [MovimentacaofinanceiraController::class,'store']);
route::get('/movimentacao/{id}', [MovimentacaofinanceiraController::class, 'show']);
route::post('/movimentacao/pesquisa', [MovimentacaofinanceiraController::class, 'pesquisar']);
route::post('/movimentacao/transferencia', [MovimentacaofinanceiraController::class, 'transferencia']);
route::get('/movimentacao/edit/{id}', [MovimentacaofinanceiraController::class, 'showEdit']);
route::put('/movimentacao/edit/{id}', [MovimentacaofinanceiraController::class, 'update']);
route::get('/movimentacao/delete/{id}', [MovimentacaofinanceiraController::class, 'showDelete']);
route::post('/movimentacao/delete', [MovimentacaofinanceiraController::class, 'delete']);
route::post('/movimentacao/baixa', [MovimentacaofinanceiraController::class, 'baixa']);


///rotas de agenda
route::get('/agenda', [AgendaController::class, 'index']);
route::post('/agenda/cad', [AgendaController::class, 'store']);
route::get('/agenda/event/{id}', [AgendaController::class, 'getEvent']);
route::post('/agenda/salvar', [AgendaController::class, 'atualizar']);
route::post('/agenda/confirmar', [AgendaController::class, 'confirmar']);
route::post('/agenda/excluir', [AgendaController::class, 'excluir']);

route::get('mail-teste', function(){
    $mail = new Arr;
    $mail->url = config('app.url').':8000/verifica-mail/44444';
    $mail->mail = 'fabriciogm.cruzcba@gmail.com';
    $mail->name = 'Fabricio';
    $mail->empresa = 'Minhas Finanças';
    $mail->h1 =    "Cadastro de usuário";
    $mail->p="Olá ".$mail->name."!";
    $mail->body="Você foi cadastrado para acessar o sistema da empresa ".$mail->empresa.". 
                Para isso é necessário validarmos seu e-mail, por gentileza click no botão abaixo para podermos 
                validar seu e-mail.";
    $mail->button = 'Validar e-mail';

    if(Mail::send(new sendmail($mail, 'Assunto teste'))){
        echo('Deu certo!');
    }else{
        echo('Deu ruim!');
    }
});
