<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\divida;
use App\Models\categoria;
use App\Models\subcategoria;
use App\Models\movimentacaodivida;
use Illuminate\Support\Str;

class DividaController extends Controller
{
    //
    public function index()
    {
        if(!empty(session('user'))){
            $divida = divida::with('dividaMovimentacaodivida')->where('AtivoDivida', '1')->where('familia_id', session('familia'))->get();
            return view('divida.index')->with('divida',$divida);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }

    }

    public function cadastrarView()
    {
        if(!empty(session('user'))){
            $categoria = categoria::all()->where('AtivoCategoria','1')->where('familia_id', session('familia'))->where('TipoCategoria','D');
            return view('divida.create')->with('categoria', $categoria);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }

    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            if($request->boolean('VisivelDashDvida')){
                $visivel = '1';
            }else{
                $visivel = '0';
            }
            $divida = new divida;
            $divida->familia_id = session('familia');
            //$divida->categoria_id = $request->categoria_id;
            //$divida->subcategoria_id = $request->subcategoria_id;
            $divida->NomeDivida = $request->NomeDivida;
            $divida->ValorInicialDivida = Str::replace(['.'],'', $request->ValorInicialDivida);
            $divida->PrioridadeDivida = $request->PrioridadeDivida;
            $divida->VisivelDashDivida = $visivel;

            if($divida->save()){
                return redirect('/divida')->with('msg', 'Dívida cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/divida')->with('msg', 'Não conseguimos cadastrar a dívida! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function getSubcategoria(Request $request)
    {
        $sub = subcategoria::all()->where('categoria_id', $request->categoria_id)->where('AtivoSubCategoria', '1');
        if(count($sub)>0){
            
            return response()->json($sub);
        }
        
    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $divida = divida::with('dividaMovimentacaodivida')->where('id',$id)->get()->first();
            $categoria = categoria::all()->where('familia_id', session('familia'))->where('AtivoCategoria','1');
            
            //echo($movIn->sum('ValorMovimentacaoDivida').'<br>');
            //echo($movOut->sum('ValorMovimentacaoDivida').'<br>');
            //echo($valor.$check);
            //dd($movOut->sum('ValorMovimentacaoDivida'));
            
            if(!empty($divida) && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    return view('divida.show')->with(['divida'=> $divida, 'categoria' =>$categoria]);
                }else{
                    return redirect('/divida')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI SHOW - Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function atualizar(Request $request)
    {
        if(!empty(session('user'))){
            $divida = divida::find($request->id);
            //echo($request->id);
            //dd($divida);
            if($divida && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    $mov = new movimentacaodivida;
                    $mov->divida_id = $divida->id;
                    $mov->familia_id = $divida->familia_id;
                    $mov->categoria_id = $request->categoria_id;
                    $mov->subcategoria_id = $request->subcategoria_id;
                    $mov->TipoMovimentacaoDivida = $request->TipoMovimentacaoDivida;
                    $mov->DataMovimentacaoDivida = $request->DataMovimentacaoDivida;
                    $mov->ValorMovimentacaoDivida = Str::replace(['.'],'',$request->ValorMovimentacaoDivida);
                    $mov->ObservacaoMovimentacaoDivida = $request->ObservacaoMovimentacaoDivida;

                    if($mov->save()){
                        return redirect('/divida/'.$divida->id)->with('msg', 'Dívida atualizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/divida/'.$divida->id)->with('msg', 'Não conseguimos atualizar a dívida! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/divida/'.$divida->id)->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI - ATUALIZAR Mentira!'.$divida.$request->all())->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function addMov(Request $request)
    {
        if(!empty(session('user'))){
            $divida = divida::find($request->id);
            //echo($request->id);
            //dd($divida);
            if($divida && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    $mov = new movimentacaodivida;
                    $mov->divida_id = $divida->id;
                    $mov->familia_id = $divida->familia_id;
                    $mov->categoria_id = $request->categoria_id;
                    $mov->subcategoria_id = $request->subcategoria_id;
                    $mov->TipoMovimentacaoDivida = $request->TipoMovimentacaoDivida;
                    $mov->DataMovimentacaoDivida = $request->DataMovimentacaoDivida;
                    $mov->ValorMovimentacaoDivida = Str::replace(['.'],'',$request->ValorMovimentacaoDivida);
                    $mov->ObservacaoMovimentacaoDivida = $request->ObservacaoMovimentacaoDivida;

                    if($mov->save()){
                        return redirect('/divida/'.$divida->id)->with('msg', 'Dívida atualizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/divida/'.$divida->id)->with('msg', 'Não conseguimos atualizar a dívida! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/divida/'.$divida->id)->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI ADDMOV - Mentira!'.$divida.$request->all())->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $divida = divida::with('dividaMovimentacaodivida')->with('dividaCategoria')->with('dividaSubcategoria')->where('id', $id)->get()->first();

            if(!empty($divida) && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    return view('divida.edit')->with('divida', $divida);
                }else{
                    return redirect('/divida')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI SHOWEDIT -Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function atualizarRegistro(Request $request)
    {
        if(!empty(session('user'))){
            $divida = divida::find($request->id);

            if(!empty($divida) && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    if($request->boolean('VisivelDashDvida')){
                        $visivel = '1';
                    }else{
                        $visivel = '0';
                    }
                    //echo($$request->boolean('VisivelDashDvida'));
                    //dd($request->boolean('VisivelDashDvida'));
                    $divida->NomeDivida = $request->NomeDivida;
                    $divida->PrioridadeDivida = $request->PrioridadeDivida;
                    $divida->VisivelDashDivida = $visivel;

                    if($divida->save()){
                        return redirect('/divida')->with('msg', 'Dívida atualizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/divida')->with('msg', 'Não conseguimos editar a dívida! Tente novamente mais tarde!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/divida')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI ATUALIZARREGISTRO - Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelete($id)
    {
        if(!empty(session('user'))){
            
            
            //echo($movIn->sum('ValorMovimentacaoDivida').'<br>');
            //echo($movOut->sum('ValorMovimentacaoDivida').'<br>');
            //echo($valor.$check);
            //dd($movOut->sum('ValorMovimentacaoDivida'));
            $divida = divida::with('dividaMovimentacaodivida')->with('dividaCategoria')->with('dividaSubcategoria')->where('id', $id)->get()->first();

            if(!empty($divida) && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    return view('divida.delete')->with('divida', $divida);
                }else{
                    return redirect('/divida')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI SHOWDELETE - Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $divida = divida::find($request->id);

            if(!empty($divida) && $divida->AtivoDivida == '1'){

                if($divida->familia_id == session('familia')){
                    $divida->AtivoDivida = '0';

                    if($divida->save()){
                        $mov = movimentacaodivida::all()->where('divida_id', $request->id);

                        if(count($mov)>0){
                            foreach($mov as $dados){
                                $dados->AtivoMovimentacaoDivida = '0';
                                $dados->save();
                            }
                            return redirect('/divida')->with('msg', 'Dívida excluída com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }else{
                            return redirect('/divida')->with('msg', 'Dívida excluída com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }
                    }else{
                        return redirect('/divida')->with('msg', 'Não conseguimos excluír a dívida! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/divida')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/divida')->with('msg', 'AQUI DELETE - Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
