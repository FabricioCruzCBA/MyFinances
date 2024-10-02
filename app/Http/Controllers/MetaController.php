<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\meta;
use App\Models\categoria;
use App\Models\movimentacaometa;
use Illuminate\Support\Str;

class MetaController extends Controller
{
    //
    public function index()
    {
        if(!empty(session('user'))){
            $meta = meta::with('metaMovimentacao')
                            ->where('AtivoMeta','1')
                            ->where('familia_id',session('familia'))
                            ->get();
            return view('meta.index')->with('meta',$meta);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function create()
    {
        if(!empty(session('user'))){
            return view('meta.create');
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            $meta = new meta;
            $meta->familia_id = session('familia');
            $meta->NomeMeta = $request->NomeMeta;
            $meta->DataFimMeta = $request->DataFimMeta;
            $meta->ValorMeta = Str::replace(['.'],'',$request->ValorMeta);

            if($meta->save()){
                return redirect('/meta')->with('msg', 'Meta cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/meta')->with('msg', 'Não conseguimos cadastrar a meta! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $meta = meta::with('metaMovimentacao')->where('id', $id)->get()->first();
            $categoria = categoria::all()->where('AtivoCategoria','1')->where('familia_id',session('familia'));

            if(!empty($meta) && $meta->AtivoMeta == '1'){

                if($meta->familia_id == session('familia')){
                  return view('meta.show')->with(['meta'=>$meta,'categoria'=>$categoria]);
                }else{
                    return redirect('/meta')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/meta')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function atualizar(Request $request)
    {
        if(!empty(session('user'))){
            $meta = meta::with('metaMovimentacao')->where('id', $request->id)->get()->first();

            if(!empty($meta) && $meta->AtivoMeta == '1'){

                if($meta->familia_id == session('familia')){
                    $mov = new movimentacaometa;
                    $mov->meta_id = $meta->id;
                    $mov->familia_id = session('familia');
                    $mov->categoria_id = $request->categoria_id;
                    $mov->subcategoria_id = $request->subcategoria_id;
                    $mov->TipoMovimentacaoMeta = $request->TipoMovimentacaoMeta;
                    $mov->DataMovimentacaoMeta = $request->DataMovimentacaoMeta;
                    $mov->ValorMovimentacaoMeta = Str::replace(['.'],'',$request->ValorMovimentacaoMeta);
                    $mov->ObsMovimentacaoMeta = $request->ObsMovimentacaoMeta;

                    if($mov->save()){
                        return redirect('/meta/'.$meta->id)->with('msg', 'Meta atualizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/meta/'.$meta->id)->with('msg', 'Não conseguimos atualizar a meta! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }

                }else{
                    return redirect('/meta')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/meta')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function upMovMeta(Request $request)
    {
        echo('deu bom'.$request->id);
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $meta = meta::find($id);

            if(!empty($meta) && $meta->AtivoMeta == '1'){

                if($meta->familia_id == session('familia')){
                    return view('meta.edit')->with('meta', $meta);
                }else{
                    return redirect('/meta')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/meta')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $meta = meta::find($request->id);

            if(!empty($meta) && $meta->AtivoMeta == '1'){

                if($meta->familia_id == session('familia')){
                    $meta->NomeMeta = $request->NomeMeta;
                    $meta->DataFimMeta = $request->DataFimMeta;
                    $meta->ValorMeta = Str::replace(['.'],'', $request->ValorMeta);

                    if($meta->save()){
                        return redirect('/meta')->with('msg', 'Meta atualizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/meta')->with('msg', 'Não conseguimos atualizar a meta! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                    }
                }else{
                    return redirect('/meta')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/meta')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelete($id)
    {
        if(!empty(session('user'))){
            $meta = meta::find($id);

            if(!empty($meta) && $meta->AtivoMeta == '1'){

                if($meta->familia_id == session('familia')){
                    return view('meta.delete')->with('meta', $meta);
                }else{
                    return redirect('/meta')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/meta')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $meta = meta::find($request->id);

            if(!empty($meta) && $meta->AtivoMeta == '1'){

                if($meta->familia_id == session('familia')){
                    $meta->AtivoMeta = '0';
                    if($meta->save()){
                        $mov = movimentacaometa::all()->where('meta_id',$meta->id)->where('AtivoMovimentacaoMeta','1');
                        //echo($mov);
                        //dd($mov);
                        if(count($mov)>0){
                            foreach($mov as $itens){
                                $itens->AtivoMovimentacaoMeta = '0';
                                $itens->save();
                            }
                            return redirect('/meta')->with('msg', 'Meta excluída com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }else{
                            return redirect('/meta')->with('msg', 'Meta excluída com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }
                    }else{
                        return redirect('/meta')->with('msg', 'Não conseguimos excluir a meta! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                    }
                }else{
                    return redirect('/meta')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/meta')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
