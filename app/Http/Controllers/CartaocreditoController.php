<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cartaocredito;
use App\Models\categoria;
use App\Models\banco;
use App\Models\movimentacaocartao;
use App\Models\movimentacaofinanceira;
use App\Models\faturacartao;

class CartaocreditoController extends Controller
{
    //

    public function index()
    {
        if(!empty(session('user'))){
            $dados = cartaocredito::with(['cartaoFatura'=> function($query){
                $query->where('StatusFatura','Fechada');
            }])->where('AtivoCartao', '1')->where('familia_id', session('familia'))->get();
            
            return view('cartao.index')->with(['dados'=>$dados]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            $cartão = new cartaocredito;

            $cartão->familia_id = session('familia');
            $cartão->NomeCartao = $request->NomeCartao;
            $cartão->DataVencimentoCartao = $request->DataVencimentoCartao ;
            $cartão->DataFechamentoCartao = $request->DataFechamentoCartao;

            if($cartão->save()){
                return redirect('/cartao')->with('msg', 'Cartão de crédito cadastrado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
            }else{
                return redirect('/cartao')->with('msg', 'Não conseguimos cadastrar o cartão! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }

        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $cartao = cartaocredito::find($id);
            $movCard = movimentacaocartao::with('movcardCategoria')
                                            ->with('movcardSub')
                                            ->where('AtivoMovimentacaoCartao', '1')
                                            ->where('TemFatMovimentacaoCartao','0')
                                            ->where('cartaocredito_id',$id)
                                            ->get();
            $cartaos = cartaocredito::all()->where('familia_id', session('familia'))->where('AtivoCartao', '1');
            
            $categoria = categoria::where('familia_id', session('familia'))
                                    ->where('AtivoCategoria', '1')
                                    ->where('TipoCategoria','D')
                                    ->get();
            $banco = banco::
                            where('AtivoBanco', '1')
                            ->where('familia_id', session('familia'))
                            ->get();
            $fat = faturacartao::
                                where('AtivoFatura', '1')
                                ->where('familia_id',session('familia'))
                                ->where('cartaocredito_id', $id)
                                ->get();

            if(!empty($cartao) && $cartao->AtivoCartao == '1'){
                if($cartao->familia_id == session('familia')){
                    return view('cartao.show')
                    ->with([
                        'cartao'=> $cartao, 
                        'movCard'=>$movCard, 
                        'cartaos'=>$cartaos, 
                        'categoria'=>$categoria, 
                        'banco'=>$banco,
                        'fat' => $fat
                    ]);
                }else{
                    return redirect('/cartao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                }
            }else{
                return redirect('/cartao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $cartao = cartaocredito::find($id);

            if(!empty($cartao)&& $cartao->AtivoCartao == '1'){
                if($cartao->familia_id == session('familia')){
                    return view('cartao.edit')->with('cartao', $cartao);
                }else{
                    return redirect('/cartao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/cartao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $cartao = cartaocredito::find($request->id);

            if(!empty($cartao)&& $cartao->AtivoCartao == '1'){
                if($cartao->update($request->all())){
                    return redirect('/cartao')->with('msg', 'Registro atualizado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                }else{
                    return redirect('/cartao')->with('msg', 'Não conseguimos atualizar o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/cartao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelet($id)
    {
        if(!empty(session('user'))){
            $cartao = cartaocredito::find($id);
            if(!empty($cartao)&& $cartao->AtivoCartao == '1'){
                if($cartao->familia_id == session('familia')){
                    return view('cartao.delete')->with('cartao', $cartao);
                }else{
                    return redirect('/cartao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/cartao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $cartao = cartaocredito::find($request->id);
            if(!empty($cartao)&& $cartao->AtivoCartao == '1'){
                if($cartao->familia_id == session('familia')){
                    $cartao->AtivoCartao = '0';
                    if($cartao->save()){
                        return redirect('/cartao')->with('msg', 'Registro deletado com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/cartao')->with('msg', 'Não conseguimos deleter o registro! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }else{
                    return redirect('/cartao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/cartao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function closeFat(Request $request)
    {
        if(!empty(session('user'))){
            $card = cartaocredito::find($request->cartaocredito_id);

            if($card->AtivoCartao == '1'){
                if($card->familia_id == session('familia')){
                    
                    //Verificar se essa fatura já está fechada
                    $fatura = faturacartao::
                                        where('AtivoFatura', '1')
                                        ->where('MesFatura', $request->mes .'/'. $request->ano)
                                        ->where('cartaocredito_id', $request->cartaocredito_id)
                                        ->get();
                    

                    
                    if(count($fatura)==0){
                        if($card->DataVencimentoCartao < $card->DataFechamentoCartao){
                            $dataEnd = date(
                                            'Y',
                                            strtotime(
                                                '-1 months',
                                                strtotime(
                                                    $request->ano.'-'.$request->mes.'-1'
                                                )
                                            )
                                        )
                                        .'-'.
                                        date(
                                            'm',
                                            strtotime(
                                                '-1 months',
                                                strtotime(
                                                    $request->ano.'-'.$request->mes.'-1'
                                                )
                                            )
                                        )
                                        .'-'. $card->DataFechamentoCartao;
                                            
                                    
                            $dataStart = date(
                                            'Y-m-d',
                                            strtotime(
                                                '-1 months',
                                                strtotime($dataEnd)
                                            )
                                        );
                            $dataVenc = $request->ano .'-'. $request->mes . '-' . $card->DataVencimentoCartao;
                        }else{
                            
                            $dataEnd = date(
                                            'Y-m-d',
                                            strtotime(
                                                $request->ano
                                                .'-'.
                                                $request->mes
                                                .'-'. 
                                                $card->DataFechamentoCartao
                                            )
                                        );
                            $dataStart = date(
                                            'Y-m-d',
                                            strtotime(
                                                '-1 months',
                                                strtotime(
                                                    $dataEnd
                                                )
                                            )
                                        );
                            $dataVenc = $request->ano .'-'. $request->mes . '-' . $card->DataVencimentoCartao;
                        }
                        
                        $movCartao = movimentacaocartao::
                                                        where('AtivoMovimentacaoCartao', '1')
                                                        ->where('cartaocredito_id', $request->cartaocredito_id)
                                                        ->whereBetween('DataMovimentacaoCartao', [$dataStart, $dataEnd])
                                                        ->get();
                        foreach($movCartao as $itens){
                            $itens->TemFatMovimentacaoCartao = '1';
                            $itens->save();
                        }

                        $movFin = new movimentacaofinanceira;

                        $movFin->familia_id = session('familia');
                        $movFin->categoria_id = $request->categoria_id2;
                        $movFin->subcategoria_id = $request->subcategoria_id2;
                        $movFin->banco_id = $request->banco_id;
                        $movFin->TipoMovimentacaoFinanc = 'D';
                        $movFin->DataVencimentoMovimentacaoFinanc = $dataVenc;
                        $movFin->ValorMovimentacaoFinanc = $movCartao->sum('ValorMovimentacaoCartao');
                        $movFin->ObsMovimentacaoFinanc = 'Fatura do cartão '.$card->NomeCartao.' referente ao mês '. $request->mes . '/'. $request->ano;
                        $movFin->DataMovimentacaoFinanc = $dataVenc;
                        $movFin->ValorFimMovimentacaoFinanc = $movCartao->sum('ValorMovimentacaoCartao');
                        $movFin->FaturaCardMovimentacaoFinanc = '1';

                        $movFin->save();



                        $fat = new faturacartao;

                        $fat->cartaocredito_id = $request->cartaocredito_id;
                        $fat->familia_id = session('familia');
                        $fat->DataVencimento = $dataVenc;
                        $fat->ValorFatura = $movCartao->sum('ValorMovimentacaoCartao');
                        $fat->MesFatura = $request->mes . '/'. $request->ano;
                        $fat->DateStartFatura = $dataStart;
                        $fat->DateEndFatura = $dataEnd;

                        if($fat->save()){
                            return redirect($request->return)->with('msg', 'Fatura fechada com sucesso')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }else{
                            return redirect($request->return)->with('msg', 'Não conseguimos gerar a fatura no momento! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                        }
                    }else{
                        return redirect($request->return)->with('msg', 'A fatura do mês '.$request->mes .'/'. $request->ano.' ja está fechada!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                    
                }else{
                    return redirect($request->return)->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect($request->return)->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function deleteFat($id)
    {
        if(!empty(session('familia'))){
            $fatura = faturacartao::find($id);
            if($fatura->AtivoFatura == '1'){
                if($fatura->familia_id == session('familia')){
                    $cartao = cartaocredito::find($fatura->cartaocredito_id);
                    $movFin = movimentacaofinanceira::
                                                    where('AtivoMovimentacaoFinanc','1')
                                                    ->where('DataVencimentoMovimentacaoFinanc', $fatura->DataVencimento)
                                                    ->where('ValorMovimentacaoFinanc', $fatura->ValorFatura)
                                                    ->where('ObsMovimentacaoFinanc', 'Fatura do cartão '.$cartao->NomeCartao.' referente ao mês '. $fatura->MesFatura)
                                                    ->get()
                                                    ->first();
                    $movFin->AtivoMovimentacaoFinanc = '0';
                    
                    $movFin->save();
                    
                    $movCartao = movimentacaocartao::
                                                    where('AtivoMovimentacaoCartao', '1')
                                                    ->where('cartaocredito_id', $fatura->cartaocredito_id)
                                                    ->where('TemFatMovimentacaoCartao', '1')
                                                    ->whereBetween('DataMovimentacaoCartao', [$fatura->DateStartFatura, $fatura->DateEndFatura])
                                                    ->get();
                    
                    foreach($movCartao as $itens){
                        $itens2 = movimentacaocartao::find($itens->id);
                        $itens2->TemFatMovimentacaoCartao = '0';
                        $itens2->save();
                    }
                    
                    $fatura->AtivoFatura = '0';

                    if($fatura->save()){
                        return redirect('/cartao/'.$fatura->cartaocredito_id)->with('msg', 'Fatura excluída com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/cartao/'.$fatura->cartaocredito_id)->with('msg', 'Não conseguimos excluir sua fatura! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                    
                                                    
                }else{
                    return redirect('/cartao/'.$fatura->cartaocredito_id)->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/cartao/'.$fatura->cartaocredito_id)->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
