<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\banco;

class BancoController extends Controller
{
    //

    public function index()
    {
        if(!empty(session('user'))){
            $banco = banco::with('bancoMov')->where('familia_id',session('familia'))->where('AtivoBanco','1')->get();
            //echo($banco);
            return view('banco.index')->with('banco',$banco);
        }else{
            return redirect('/login')->with('msg', 'É necessário estar logado para acessar essa página!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            $banco = new banco;

            $banco->NomeBanco = $request->NomeBanco;
            $banco->familia_id = session('familia');

            if($banco->save()){
                return redirect('/bancos')->with('msg', 'Banco cadastrado com sucesso')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/bancos')->with('msg', 'Erro ao cadastrar banco, tente novamente mais tarde')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }

    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $banco = banco::find($id);

            if(!empty($banco) && $banco->AtivoBanco == '1'){
                if($banco->familia_id == session('familia')){
                    return view('banco.show')->with('banco', $banco);
                }else{
                    return redirect('/bancos')->with('msg', 'Você não tem acesso ao registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/bancos')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
        
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $banco = banco::find($id);

            if(!empty($banco) && $banco->AtivoBanco == '1'){
                if($banco->familia_id == session('familia')){
                    return view('banco.edit')->with('banco', $banco);
                }else{
                    return redirect('/bancos')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');        
                }
            }else{
                return redirect('/bancos')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $banco = banco::find($request->id);

            if(!empty($banco) && $banco->AtivoBanco == '1'){
                if($banco->familia_id == session('familia')){
                    if($banco->update($request->all())){
                        return redirect('/bancos')->with('msg', 'Cadastro alterado com sucesso')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/bancos')->with('msg', 'Não conseguimos salvar as alterações, tente novamente mais tarde!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/bancos')->with('msg', 'Você não tem acesso ao registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/banco')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelet($id)
    {
        if(!empty(session('user'))){
            $banco = banco::find($id);

            if(!empty($banco) && $banco->AtivoBanco == '1'){
                if($banco->familia_id == session('familia')){
                    return view('banco.delete')->with('banco', $banco);
                }else{
                    return redirect('/bancos')->with('msg', 'Você não tem acesso ao registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/bancos')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $banco = banco::find($request->id);

            if(!empty($banco) && $banco->AtivoBanco == '1'){
                if($banco->familia_id == session('familia')){
                    $banco->AtivoBanco = '0';

                    if($banco->save()){
                        return redirect('/bancos')->with('msg', 'Registro deletado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/bancos')->with('msg', 'Não conseguimos deletar o registro, tente novamente mais tarde!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                    }
                }else{
                    return redirect('/bancos')->with('msg', 'Você não tem acesso ao registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/banco')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
