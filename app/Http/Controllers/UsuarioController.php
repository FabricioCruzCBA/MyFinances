<?php

namespace App\Http\Controllers;


use App\Models\usuario;
use App\Models\historicosenhausuario;
use App\Models\tokenverificaemail;
use App\Models\familia;
use App\Models\banco;
use App\Models\categoria;
use App\Models\subcategoria;
use App\Models\acessosusuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\stdClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\sendmail;

class UsuarioController extends Controller
{
    //login
    public function store(Request $request)
    {
        $usuario = new usuario;

        $usuario->NomeUsuario = $request->NomeUsuario;
        $usuario->EmailUsuario = $request->EmailUsuario;
        $usuario->SenhaUsuario = Hash::make($request->PasswordUsuario);
        $usuario->ValidadeSenhaUsuario = date('Y-m-d',strtotime('+3 months', strtotime(today())));

        if(count(usuario::all()->where('EmailUsuario', $request->EmailUsuario))>0){
            return redirect('/CadastroUsuario')->with('msg', 'O e-mail informado já está cadastrado! Você pode usar a opção de recuperar senha.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }else{
            if($usuario->save()){
                $hist = new historicosenhausuario;

                $hist->usuario_id = $usuario->id;
                $hist->SenhaUsuario = $usuario->SenhaUsuario;

                if($hist->save()){
                    $token = new tokenverificaemail;

                    $token->usuario_id = $usuario->id;
                    $token->TokenVerificacao = '0000000000'.$usuario->id.csrf_token().random_int(100000,999999);

                    if($token->save()){
                       $familia = new familia;
                       $familia->usuario_id = $usuario->id;
                       if($familia->save()){
                        $banco = new banco;
                        $banco->familia_id = $familia->id;
                        $banco->NomeBanco = 'Minha carteira';
                        if($banco->save()){
                            $mail = new Arr;
                            $mail->url = config('app.url').':8000/verifica-mail/'.$token->TokenVerificacao;
                            $mail->mail = $usuario->EmailUsuario;
                            $mail->name = $usuario->NomeUsuario;
                            $mail->empresa = 'My Finances';
                            $mail->h1 =    "Cadastro de usuário";
                            $mail->p="Olá ".$usuario->NomeUsuario."!";
                            $mail->body="Você realizou o cadastro para acessar o aplicativo MyFinance. 
                                        Para isso é necessário validarmos seu e-mail, por gentileza click no botão abaixo para podermos 
                                        validar seu e-mail.";
                            $mail->button = 'Validar e-mail';

                            $assunto = "Confirmação de e-mail - Não responda!";
                        
                            if(Mail::send(new sendmail($mail, $assunto))){
                                $categoria = new categoria;
                                $categoria->familia_id = $familia->id;
                                $categoria->NomeCategoria = 'Cartão de crédito';
                                $categoria->TipoCategoria = 'D';
                                $categoria->IconeCategoria = 'credit-card-2-back-fill';
                                $categoria->ShowOrcamentoCategoria = '0';

                                $categoria->save();

                                $sub = new subcategoria;
                                $sub->familia_id = $familia->id;
                                $sub->categoria_id = $categoria->id;
                                $sub->NomeSubCategoria = 'Fatura do cartão de crédito';
                                $sub->IconeSubCategoria = 'list-check';
                                $sub->CategorizacaoSubCategoria = 'Despesa - Variável';

                                $sub->save();

                                return redirect('/login')->with('msg', 'Usuário cadastrado com sucesso. Enviamos um e-mail para '.$usuario->EmailUsuario.', para verificar seu endereço eletrônico siga as instruções do e-mail.')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');

                            }else{
                                return redirect('/login')->with('msg', 'Erro ao cadastrar usuário entre em contato com o adm.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                            }
                        }else{
                            return redirect('/login')->with('msg', 'Erro ao cadastrar usuário entre em contato com o adm.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');   
                        }
                       }else{
                        return redirect('/login')->with('msg', 'Erro ao cadastrar usuário entre em contato com o adm.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                       }
                    }else{
                        return redirect('/login')->with('msg', 'Erro ao cadastrar usuário entre em contato com o adm.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/login')->with('msg', 'Erro ao cadastrar usuário entre em contato com o adm.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');  
                }
            }else{
                return redirect('/login')->with('msg', 'Erro ao cadastrar usuário entre em contato com o adm.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }
    }

    public function login(Request $request)
    {
        $user = usuario::all()->where('EmailUsuario', $request->email)->where('AtivoUsuario','1')->first();

        if(!empty($user)){
            
            if(hash::check($request->password, $user->SenhaUsuario)){
                if($user->VerificacaoEmailUsuario == '1'){
                    $access = new acessosusuario;
                    $access->usuario_id = $user->id;
                    if($access->save()){
                        $familia = new familia;
                        $familia = familia::all()->where('usuario_id', $user->id)->first();
                        session(['user'=>$user->id, 'familia' => $familia->id, 'userName'=> $user->NomeUsuario, 'dateCad'=>date('M-Y', strtotime($user->created_at)),'imgUserPerfil'=> $user->ImgUsuario]);
                        return redirect('/home');
                    }else{
                        return redirect('/login')->with('msg', 'Tivemos um problema ao acessar seu cadastro, tente novamente mais tarde!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/login')->with('msg', 'Seu e-mail não foi verificado. Ao se cadastrar enviamos um e-mail para confirmar a autenticidade do seu endereço eletrônico, caso não esteja em sua caixa de entrada verifique no spam/Lixo eletrônico.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/login')->with('msg', 'Senha inválida!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Usuário não cadastrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function validaMail($token)
    {
        $verifica = new tokenverificaemail;
        $user = new usuario;

        if($verifica = tokenverificaemail::all()->where('TokenVerificacao', $token)->where('Verificado','0')->first()){
            $verifica->Verificado = '1';
            if($verifica->save()){
                $user = usuario::find($verifica->usuario_id);
                $user->VerificacaoEmailUsuario = '1';
                if($user->save()){
                    return redirect('/login')->with('msg', 'E-mail verificado com sucesso, agora é só fazer Login.')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                }else{
                    return redirect('/login')->with('msg', 'Não conseguimos verificar seu e-mail. Tente mais tarde!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/login')->with('msg', 'Tivemos um problema ao validar seu e-mail. Tente novamente mais tarde!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Token inválido!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showPerfil()
    {
        if(!empty(session('user'))){
            $user = usuario::with('usuarioAcesso')->where('id',session('user'))->get()->first();
            $lastAccess = $user->usuarioAcesso()->where('created_at', $user->usuarioAcesso()->max('created_at'))->get()->first();
            return view('perfil.index')->with(['user'=>$user, 'lastAccess' => $lastAccess]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $user = usuario::find($request->id);

            if($user->id == session('user')){
                $user->NomeUsuario = $request->NomeUsuario;
                
                if($user->EmailUsuario <> $request->EmailUsuario){
                    $userM = usuario::all()->where('EmailUsuario', $request->EmailUsuario)->first();
                    
                    if(empty($userM)){
                        $user->EmailUsuario = $request->EmailUsuario;
                        $user->VerificacaoEmailUsuario = '0';
                        if(!empty($request->SenhaUsuario)){
                            $user->SenhaUsuario = hash::make($request->SenhaUsuario);
                        }
                        if(!empty($request->ImagemUsuario) ){
                            $Nome = $user->id. '.png';//. $request->ImagemUsuario->getClientOriginalExtension();
                            //echo($Nome);
                            //$nomeImagem = $user->EmailUsuario . $img->extension();
                            $request->ImagemUsuario->move(public_path('imguser/'),$Nome);
                            $user->ImgUsuario = $Nome;
                            session('imgUserPerfil')->forget();
                            session(['imgUserPerfil'=> $Nome]);
                        }
                        if($user->save()){
                            $token = tokenverificaemail::all()->where('usuario_id', $user->id);
                            $token->TokenVerificacao = '0000000000'.$usuario->id.csrf_token().random_int(100000,999999);
                            $token->Verificado = '0';
                            if($token->save()){
                                $mail = new Arr;
                                $mail->url = 'http://localhost:8000/verifica-mail/'.$token->TokenVerificacao;
                                $mail->mail = $usuario->EmailUsuario;
                                $mail->name = $usuario->NomeUsuario;
                                $mail->empresa = 'Minhas Finanças';
                                $mail->h1 =    "Cadastro de usuário";
                                $mail->p="Olá ".$usuario->NomeUsuario."!";
                                $mail->body="Você alterou seu e-mail no cadastro do aplicativo MyFinance. 
                                            Para isso é necessário validarmos seu e-mail, por gentileza click no botão abaixo para podermos 
                                            validar seu e-mail.";
                                $mail->button = 'Validar e-mail';

                                $assunto = "Confirmação de e-mail - Não responda!";

                                if(Mail::send(new sendmail($mail, $assunto))){
                                    return redirect('/logout')->with('msg', 'Registro atualizado com sucesso! Como foi alterado seu e-mail é necessário validar para fazer login')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                                }else{
                                    return redirect('/home')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                                }
                            }else{
                                return redirect('/home')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                            }
                        }else{
                            return redirect('/home')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                        }
                    }else{
                        return redirect('/home')->with('msg', 'E-mail informado já está cadastrado para outro usuário.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                    }
                }else{
                    if(!empty($request->SenhaUsuario)){
                        $user->SenhaUsuario = hash::make($request->SenhaUsuario);
                    }

                    if(!empty($request->ImagemUsuario) ){
                        $Nome = $user->id. '.png';//. $request->ImagemUsuario->getClientOriginalExtension();
                        //echo($Nome);
                        //$nomeImagem = $user->EmailUsuario . $img->extension();
                        $request->ImagemUsuario->move(public_path('imguser/'),$Nome);
                        $user->ImgUsuario = $Nome;
                        session()->forget('imgUserPerfil');
                        session(['imgUserPerfil'=> $Nome]);
                    }

                    if($user->save()){
                        return redirect('/home')->with('msg', 'Registro atualizado com sucesso! Para que reflita a foto de perfil você precisa fazer logout.')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/home')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }
            }else{
                return redirect('/home')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
