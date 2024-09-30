<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categoria;
use App\Models\subcategoria;
use App\Models\categoriaorcamento;
use App\Models\icone;

class CategoriaController extends Controller
{
    //

    public function index()
    {
        if(!empty(session('user'))){
            $categoria = categoria::with('categoriaSubcategoria')
                                    ->where('familia_id',session('familia'))
                                    ->where('AtivoCategoria', '1')
                                    ->get();
            
            return view('categoria.index')->with(['categoria' => $categoria]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }


    }

    public function Insert()
    {
        if(!empty(session('user'))){
            $icone = icone::all();
            $categoria = categoria::all()->where('familia_id',session('familia'))->where('AtivoCategoria', '1');
            return view('categoria.create')->with('icone' , $icone);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            $categoria = new categoria;

            $categoria->familia_id = session('familia');
            $categoria->NomeCategoria = $request->NomeCategoria;
            $categoria->TipoCategoria = $request->TipoCategoria;
            $categoria->IconeCategoria = $request->IconeCategoria;
            

            if($categoria->save()){
                return redirect('/categoria')->with('msg', 'Categoria cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/categoria')->with('msg', 'Não conseguimos cadastrar o registro! Tente novamente mais tarde')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function  show($id)
    {
        if(!empty(session('user'))){
            $categoria = categoria::find($id);

            if(!empty($categoria) && $categoria->AtivoCategoria == '1'){
                if($categoria->familia_id == session('familia')){
                    return view('categoria.show')->with('categoria', $categoria);
                }else{
                    return redirect('/categoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/categoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $categoria = categoria::find($id);
            $icone = icone::all();

            if(!empty($categoria) && $categoria->AtivoCategoria == '1'){
                if($categoria->familia_id == session('familia')){
                    return view('categoria.edit')->with(['categoria' => $categoria, 'icone' => $icone]);
                }else{
                    return redirect('/categoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/categoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $categoria = categoria::find($request->id);

            if(!empty($categoria) && $categoria->AtivoCategoria == '1'){
                if($categoria->familia_id == session('familia')){
                    $categoria->NomeCategoria = $request->NomeCategoria;
                    $categoria->TipoCategoria = $request->TipoCategoria;
                    $categoria->IconeCategoria = $request->IconeCategoria;
                    
                    if($categoria->save()){
                        return redirect('/categoria')->with('msg', 'Registro alterado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/categoria')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/categoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/categoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelete($id)
    {
        if(!empty(session('user'))){
            $categoria = categoria::find($id);
            $icone = icone::all();

            if(!empty($categoria) && $categoria->AtivoCategoria == '1'){
                if($categoria->familia_id == session('familia')){
                    return view('categoria.delete')->with(['categoria' => $categoria, 'icone' => $icone]);
                }else{
                    return redirect('/categoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/categoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $categoria = categoria::with('categoriaSubcategoria')->where('id',$request->id)->get()->first();
            
            if(!empty($categoria) && $categoria->AtivoCategoria == '1'){
                if($categoria->familia_id == session('familia')){
                    $categoria->AtivoCategoria = '0';
                    if($categoria->save()){

                        foreach($categoria->categoriaSubcategoria as $sub){
                            $subc = subcategoria::find($sub->id);
                            $subc->AtivoSubCategoria = '0';
                            $subc->save();
                        }
                        $cat = categoria::with('categoriaCategoriaorcamento')->where('id', $categoria->id)->get();
                        //echo($cat);
                        //dd($cat);
                        foreach($cat as $categ){
                            foreach($categ->categoriaCategoriaorcamento as $itens){
                                $item = categoriaorcamento::find($itens->id);
                                $item->AtivoCategoriaOrcamento = '0';
                                $item->save();

                            }   
                        }
                        

                        return redirect('/categoria')->with('msg', 'Registro excluído com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/categoria')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/categoria')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/categoria')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
