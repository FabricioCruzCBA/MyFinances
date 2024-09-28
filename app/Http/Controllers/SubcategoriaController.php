<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subcategoria;
use App\Models\categoria;
use App\Models\icone;

class SubcategoriaController extends Controller
{
    //

    public function index()
    {
        if(!empty(session('user'))){
            $sub = subcategoria::with('subcategoriaCategoria')->where('familia_id',session('familia'))->where('AtivoSubCategoria', '1')->get();
            //echo($sub);
            //dd($sub);
            return view('subcategoria.index')->with(['sub' => $sub]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function insert()
    {
        if(!empty(session('user'))){
            $icones = icone::all();
            $categoria = categoria::all()->where('familia_id', session('familia'))->where('AtivoCategoria','1');
            return view('subcategoria.create')->with(['icone' => $icones, 'categoria' => $categoria]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            $cat = categoria::find($request->CategoriaId);
           

            $sub = new subcategoria;
            $sub->familia_id = session('familia');
            $sub->categoria_id = $request->CategoriaId;
            $sub->NomeSubCategoria=$request->NomeSubCategoria;
            $sub->IconeSubCategoria = $request->IconeCategoria;

            if($cat->TipoCategoria == 'R'){
                $sub->CategorizacaoSubCategoria = 'Receita - '.$request->Tipo;
            }elseif($cat->TipoCategoria == 'D'){
                $sub->CategorizacaoSubCategoria = 'Despesa - '.$request->Tipo;
            }else{
                $sub->CategorizacaoSubCategoria = 'Transferência - '.$request->Tipo;
            }

            if($sub->save()){
                return redirect('/subcategoria')->with('msg', 'Subcategoria cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/subcategoria')->with('msg', 'Não foi possivel cadastrar a subcategoria! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $sub = subcategoria::with('subcategoriaCategoria')->where('id', $id)->get()->first();
            if(!empty($sub) && $sub->AtivoSubCategoria == '1'){
                if($sub->familia_id == session('familia')){
                    return view('subcategoria.show')->with('dados', $sub);
                }else{
                    return redirect('/subcategoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                }
            }else{
                return redirect('/subcategoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $sub = subcategoria::find($id);
            $categoria = categoria::all()->where('AtivoCategoria', '1')->where('familia_id', session('familia'));
            $icones = icone::all();
            if(!empty($sub) && $sub->AtivoSubCategoria == '1'){
                if($sub->familia_id == session('familia')){
                    return view('subcategoria.edit')->with(['dado'=> $sub, 'categoria' => $categoria, 'icone' => $icones]);
                }else{
                    return redirect('/subcategoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                }
            }else{
                return redirect('/subcategoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $sub = subcategoria::find($request->id);

            if(!empty($sub) && $sub->AtivoSubCategoria == '1'){
                if($sub->familia_id == session('familia')){
                    $cat = categoria::find($request->categoria_id);

                    $sub->categoria_id = $request->categoria_id;
                    $sub->NomeSubCategoria=$request->NomeSubCategoria;
                    $sub->IconeSubCategoria = $request->IconeSubCategoria;

                    if($cat->TipoCategoria == 'R'){
                        $sub->CategorizacaoSubCategoria = 'Receita - '.$request->Tipo;
                    }elseif($cat->TipoCategoria == 'D'){
                        $sub->CategorizacaoSubCategoria = 'Despesa - '.$request->Tipo;
                    }else{
                        $sub->CategorizacaoSubCategoria = 'Transferência - '.$request->Tipo;
                    }
                    if($sub->save()){
                        return redirect('/subcategoria')->with('msg', 'Registro atualizado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/subcategoria')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                    }
                }else{
                    return redirect('/subcategoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                }
            }else{
                return redirect('/subcategoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelete($id)
    {
        if(!empty(session('user'))){
            $sub = subcategoria::with('subcategoriaCategoria')->where('id', $id)->get()->first();

            if(!empty($sub) && $sub->AtivoSubCategoria == '1'){
                
                if($sub->familia_id == session('familia')){
                    return view('subcategoria.delete')->with('dados',$sub);
                }else{
                    return redirect('/subcategoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                }
            }else{
                
                return redirect('/subcategoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $sub = subcategoria::find($request->id);

            if(!empty($sub) && $sub->AtivoSubCategoria == '1'){
                
                if($sub->familia_id == session('familia')){
                    $sub->AtivoSubCategoria = '0';

                    if($sub->save()){
                        return redirect('/subcategoria')->with('msg', 'Registro excluído com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/subcategoria')->with('msg', 'Não coonseguimos excluir o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                    }
                }else{
                    return redirect('/subcategoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');     
                }
            }else{
                
                return redirect('/subcategoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
