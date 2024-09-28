<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categoria;
use App\Models\categoriaorcamento;
use App\Models\orcamento;
use Illuminate\Support\Str;


class OrcamentoController extends Controller
{
    //
    public function index()
    {
        if(!empty(session('user'))){
            $orcamento = categoriaorcamento::with('categoriaorcamentoCategoria')
                                            ->where('AtivoCategoriaOrcamento', '1')
                                            ->where('familia_id', session('familia'))
                                            ->get();
            //echo($orcamento);
            return view('orcamento.index')->with('orcamento', $orcamento);

        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store()
    {
        if(!empty(session('user'))){
            $orc = orcamento::all()->where('AtivoOrcamento', '1')->where('familia_id', session('familia'))->first();
            if(empty($orc)){
                $orcamento = new orcamento;
                $orcamento->familia_id = session('familia');
                $orcamento->ValorOrcamento = 0;
                if($orcamento->save()){
                    $categoria = categoria::all()
                                            ->where('AtivoCategoria', 1)
                                            ->where('familia_id', session('familia'))
                                            ->where('ShowOrcamentoCategoria','1')
                                            ->where('TipoCategoria', 'D');
                    foreach($categoria as $dados){
                        $item = new categoriaorcamento;
                        $item->orcamento_id = $orcamento->id;
                        $item->familia_id = session('familia');
                        $item->categoria_id = $dados->id;
                        $item->ValorItemOrc = 0;
                        $item->save();
                        unset($item);
                    }
                    return redirect('/orcamento')->with('msg', 'Orçamento criado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                }else{
                    return redirect('/orcamento')->with('msg', 'Não conseguimos cadastrar o orçamento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/orcamento')->with('msg', 'Não conseguimos cadastrar o orçamento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit()
    {
        if(!empty(session('user'))){
            $orcamento = categoriaorcamento::with('categoriaorcamentoCategoria')
                                            ->where('AtivoCategoriaOrcamento', '1')
                                            ->where('familia_id', session('familia'))
                                            ->get();
            $categoria = categoria::with('categoriaCategoriaorcamento')
                                    ->where('AtivoCategoria','1')
                                    ->where('familia_id', session('familia'))
                                    ->where('TipoCategoria', 'D')
                                    ->where('ShowOrcamentoCategoria','1')
                                    ->get();
            //echo($orcamento.'----------<br>');
            //echo($categoria);
            return view('orcamento.edit')->with(['orcamento'=> $orcamento, 'categoria' => $categoria]);

        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function salvar(Request $request)
    {
        if(!empty(session('user'))){
            //echo($request);

            //dd($request);
            
            foreach($request->valor as $dadosId => $valor){
                if(empty($valor)){
                    $valorC = 0;
                }else{
                    $valorC = $valor;
                }
                $ItemOrc = categoriaorcamento::find($dadosId);
                $ItemOrc->ValorItemOrc = Str::replace(['.'],'',$valorC);
                $ItemOrc->save();
            }

            if(!empty($request->valorNew)){
                foreach($request->valorNew as $dadosIds => $valors){
                    if(empty($valors)){
                        $valorC2 = 0;
                    }else{
                        $valorC2 = $valors;
                    }
                    $ItemN = new categoriaorcamento;
                    $ItemN->categoria_id = $dadosIds;
                    $ItemN->ValorItemOrc = Str::replace(['.'],'',$valorC2);
                    $ItemN->orcamento_id = $ItemOrc->orcamento_id;
                    $ItemN->familia_id = session('familia');
                    $ItemN->save();
                }
            }
            return redirect('/orcamento')->with('msg', 'Orçamento atualizado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
