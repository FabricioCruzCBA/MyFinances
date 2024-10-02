<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\investimento;
use App\Models\movimentacaoinvestimento;
use Illuminate\Support\Str;
use App\Models\categoria;


class InvestimentoController extends Controller
{
    //
    public function index()
    {
        if(!empty(session('user'))){
            $investimento = investimento::with('investimentoMovimentacao')->where('AtivoInvestimento', '1')->where('familia_id',session('familia'))->get();
            //echo($investimento);
            //dd($investimento);
            return view('investimento.index')->with('investimento', $investimento);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function cadastrarView()
    {
        if(!empty(session('user'))){
            return view('investimento.create');
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            $inv = new investimento;
            $inv->NomeInvestimento = $request->NomeInvestimento;
            $inv->familia_id = session('familia');
            $inv->ValorInicialInvestimento = Str::replace(['.'],'', $request->ValorInicialInvestimento) ;
            
            if($inv->save()){
                return redirect('/investimento')->with('msg', 'Investimento cadastrado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/login')->with('msg', 'Não conseguimos cadastrar o inestimento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }

        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $inv = investimento::with('investimentoMovimentacao')->where('id', $id)->get()->first();
            $cat = categoria::all()->where('familia_id', session('familia'))->where('AtivoCategoria', '1');
            //echo($inv);
            //dd($inv);

            if(!empty($inv) && $inv->AtivoInvestimento == '1'){

                if($inv->familia_id == session('familia')){
                    return view('investimento.show')->with(['investimento'=> $inv, 'categoria' => $cat]);
                }else{
                    return redirect('/investimento')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/investimento')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    

    public function novaUpMov(Request $request) 
    {
        if(!empty(session('user'))){
            $inv = investimento::find($request->id);

            if(!empty($inv) && $inv->AtivoInvestimento == '1'){

                if($inv->familia_id == session('familia')){
                    $mov = new movimentacaoinvestimento;
                    $mov->investimento_id = $request->id;
                    $mov->familia_id = session('familia');
                    $mov->categoria_id = $request->categoria_id;
                    $mov->subcategoria_id = $request->subcategoria_id;
                    $mov->TipoMovimentacaoInvestimento = $request->TipoMovimentacaoInvestimento;
                    $mov->DataMovimentacaoInvestimento = $request->DataMovimentacaoInvestimento;
                    $mov->ValorMovimentacaoInvestimento = Str::replace(['.'],'',$request->ValorMovimentacaoInvestimento);
                    $mov->ObsMovimentacaoInvestimento = $request->ObsMovimentacaoInvestimento;

                    if($mov->save()){
                        return redirect('/investimento/'.$inv->id)->with('msg', 'Investimento atualizado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/investimento/'.$inv->id)->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/investimento')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/investimento')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $inv = investimento::with('investimentoMovimentacao')->where('id', $id)->get()->first();

            if(!empty($inv) && $inv->AtivoInvestimento == '1'){

                if($inv->familia_id == session('familia')){
                   return view('investimento.edit')->with('investimento', $inv); 
                }else{
                    return redirect('/divida')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $inv = investimento::with('investimentoMovimentacao')->where('id', $request->id)->get()->first();

            if(!empty($inv) && $inv->AtivoInvestimento == '1'){

                if($inv->familia_id == session('familia')){
                    $inv->NomeInvestimento = $request->NomeInvestimento;
                    if($inv->save()){
                        return redirect('/investimento')->with('msg', 'Investimento atualizado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/investimento')->with('msg', 'Não conseguimos atualizar o investimento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    } 
                }else{
                    return redirect('/investimento')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/investimento')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDel($id)
    {
        if(!empty(session('user'))){
            $inv = investimento::with('investimentoMovimentacao')->where('id', $id)->get()->first();

            if(!empty($inv) && $inv->AtivoInvestimento == '1'){

                if($inv->familia_id == session('familia')){
                  return view('investimento.delete')->with('investimento', $inv);
                }else{
                    return redirect('/investimento')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/investimento')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $inv = investimento::with('investimentoMovimentacao')->where('id', $request->id)->get()->first();

            if(!empty($inv) && $inv->AtivoInvestimento == '1'){

                if($inv->familia_id == session('familia')){
                  $inv->AtivoInvestimento = '0';
                  if($inv->save()){
                    $mov = movimentacaoinvestimento::all()->where('investimento_id',$inv->id);

                    if(count($mov)>0){
                        foreach($mov as $itens){
                            $itens->AtivoMovimentacaoInvestimento = '0';
                            $itens->save();
                        }
                        return redirect('/investimento')->with('msg', 'Investimento excluído com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/investimento')->with('msg', 'Investimento excluído com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }
                  }else{
                    return redirect('/investimento')->with('msg', 'Não foi possivel excluir o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                  }
                }else{
                    return redirect('/investimento')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/investimento')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
