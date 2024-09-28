<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\movimentacaocartao;

class MovimentacaocartaoController extends Controller
{
    //
    public function deleteItem($id, $idCard)
    {
        if(!empty(session('user'))){
            $mov = movimentacaocartao::find($id);
            if($mov->AtivoMovimentacaoCartao == '1'){
                if($mov->familia_id == session('familia')){
                    $mov->AtivoMovimentacaoCartao = '0';
                    if($mov->save()){
                        return redirect('/cartao/'.$idCard)->with('msg', 'Registro excluído com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/cartao/'.$idCard)->with('msg', 'Não conseguimos excluir o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/cartao/'.$idCard)->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/cartao/'.$idCard)->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
