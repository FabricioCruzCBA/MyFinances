<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\agenda;

class AgendaController extends Controller
{
    //
    public function index()
    {
        if(!empty(session('user'))){
            $agenda = agenda::where('Ativo', '1')->where('usuario_id', session('user'))->where('familia_id', session('familia'))->get();
            //dd($agenda);
            return view('agenda.index')->with('agenda', $agenda);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
           $agenda = new agenda;

           $agenda->familia_id = session('familia');
           $agenda->usuario_id = session('user');
           $agenda->Descricao = $request->descricao;
           $agenda->DataStart = $request->dataStart;
           $agenda->DataEnd = $request->dataEnd;
           $agenda->Obs = $request->obs;
           $agenda->Tipo = $request->tipo;

           if($agenda->save()){
                return redirect('/agenda')->with('msg', 'Compromisso agendado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
           }else{
                return redirect('/agenda')->with('msg', 'Não conseguimos agendar o compromisso! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
           }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        } 
    }

    public function getEvent($id)
    {
        $agenda = agenda::find($id);

        return response()->json($agenda);
    }

    public function atualizar(Request $request)
    {
        if(!empty(session('user'))){
            $agenda = agenda::find($request->id2);
            if(!empty($agenda) && $agenda->Ativo == '1'){
                if($agenda->usuario_id == session('user')){
                    $agenda->Descricao = $request->descricao2;
                    $agenda->DataStart = $request->dataStart2;
                    $agenda->DataEnd = $request->dataEnd2;
                    $agenda->Obs = $request->obs2;
                    $agenda->Tipo = $request->tipo2;

                    if($agenda->save()){
                        return redirect('/agenda')->with('msg', 'Registro alterado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/agenda')->with('msg', 'Não conseguimos alterar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/agenda')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/agenda')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        } 
    }

    public function confirmar(Request $request)
    {
        if(!empty(session('user'))){
            $agenda = agenda::find($request->id2);
            if(!empty($agenda) && $agenda->Ativo == '1'){
                if($agenda->usuario_id == session('user')){
                    $agenda->Confirmacao = '1';

                    if($agenda->save()){
                        return redirect('/agenda')->with('msg', 'Evento confirmado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/agenda')->with('msg', 'Não conseguimos confirmar o evento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/agenda')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/agenda')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        } 
    }

    public function excluir(Request $request)
    {
        if(!empty(session('user'))){
            $agenda = agenda::find($request->id2);
            if(!empty($agenda) && $agenda->Ativo == '1'){
                if($agenda->usuario_id == session('user')){
                    $agenda->Ativo = '0';

                    if($agenda->save()){
                        return redirect('/agenda')->with('msg', 'Evento confirmado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/agenda')->with('msg', 'Não conseguimos confirmar o evento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/agenda')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/agenda')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        } 
    }
}
