<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\movimentacaofinanceira;
use App\Models\banco;
use App\Models\divida;
use App\Models\meta;
use App\Models\investimento;
use App\Models\categoria;
use App\Models\subcategoria;
use App\Models\cartaocredito;
use App\Models\movimentacaocartao;
use App\Models\movimentacaodivida;
use App\Models\movimentacaometa;
use App\Models\movimentacaoinvestimento;
use App\Models\faturacartao;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovimentacaofinanceiraController extends Controller
{
    //
    public function index()
    {
        if(!empty(session('user'))){
            // Define as datas de 6 meses atrás e 3 meses à frente
            $dataInicio = Carbon::now()->subMonths(6)->startOfDay();
            $dataFim = Carbon::now()->addMonths(3)->endOfDay();

            $mov = movimentacaofinanceira::
                                            with('movCategoria')
                                            ->with('movSubcategoria')
                                            ->with('movBanco')
                                            ->where('familia_id', session('familia'))
                                            ->where('AtivoMovimentacaoFinanc', '1')
                                            ->whereBetween('DataMovimentacaoFinanc',[$dataInicio, $dataFim])
                                            ->get();
            $categoria = categoria::
                                    all()
                                    ->where('familia_id', session('familia'))
                                    ->where('AtivoCategoria', '1');
            $subcategoria = subcategoria::
                                        all()
                                        ->where('familia_id', session('familia'))
                                        ->where('AtivoSubCategoria', '1');
            $banco = banco::
                            all()
                            ->where('familia_id', session('familia'))
                            ->where('AtivoBanco','1');
            
            return view('movimentacao.index')->with(['mov'=> $mov, 'categoria' => $categoria, 'subcategoria' => $subcategoria, 'banco'=>$banco]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function create()
    {
        if(!empty(session('user'))){
            $banco = banco::all()->where('familia_id', session('familia'))->where('AtivoBanco', '1');
            $divida = divida::all()->where('familia_id', session('familia'))->where('AtivoDivida', '1');
            $meta = meta::all()->where('familia_id', session('familia'))->where('AtivoMeta', '1');
            $inv = investimento::all()->where('familia_id', session('familia'))->where('AtivoInvestimento', '1');
            $cartao = cartaocredito::all()->where('familia_id', session('familia'))->where('AtivoCartao', '1');

            return view('movimentacao.create')->with(['banco' => $banco, 'divida'=> $divida, 'meta'=>$meta, 'investimento' => $inv, 'cartao'=>$cartao]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function getCategoria(Request $request)
    {
        $cat = categoria::all()
                        ->where('TipoCategoria',$request->TipoMovimentacaoFinanc)
                        ->where('AtivoCategoria','1')
                        ->where('familia_id', session('familia'));
        if(count($cat)>0){
            
            return response()->json($cat);
        }
        
    }

    public function store(Request $request)
    {
        if(!empty(session('user'))){
            

            if($request->boolean('cartao')){
                //caso seja lançamento no cartão
                if($request->boolean('recorrente')){
                    //caso seja recorrente
                    $data = $request->DataVencimentoMovimentacaoFinanc;
                    for($i=0; $i<$request->parcelasNum; $i++){
                        
                        $movCard = new movimentacaocartao;
                        $cartao = cartaocredito::find($request->cartaocredito_id);


                        $movCard->familia_id = session('familia');
                        $movCard->cartaocredito_id = $request->cartaocredito_id;
                        $movCard->categoria_id = $request->categoria_id;
                        $movCard->subcategoria_id = $request->subcategoria_id;
                        $movCard->DataMovimentacaoCartao = date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)));
                        $movCard->ValorMovimentacaoCartao = Str::replace([','], '.', Str::replace(['.'], '',$request->ValorMovimentacaoFinanc));
                        $movCard->ObsMovimentacaoCartao = $request->ObsMovimentacaoFinanc;

                        if(
                            date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc))) < 
                            date(
                                'Y-m-d', 
                                strtotime(
                                    date('Y', strtotime(date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc))))) 
                                    . '-' . date('m', strtotime(date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc))))) 
                                    . '-' .  $cartao->DataFechamentoCartao
                                )
                            )
                        ){
                            $dataPg = date(
                                            'Y-m-d',
                                            strtotime(
                                                date('Y', strtotime(date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)))))
                                                .'-'.
                                                date('m', strtotime(date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)))))
                                                .'-'.
                                                $cartao->DataVencimentoCartao
                                            )
                                      );
                        }else{
                            $dataPg = date(
                                        'Y-m-d',
                                        strtotime(
                                            date('Y', strtotime('+1 months', strtotime(date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc))))))
                                            .'-'.
                                            date('m', strtotime('+1 months', strtotime(date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc))))))
                                            .'-'.
                                            $cartao->DataVencimentoCartao
                                        )
                                      );
                        }
    
                        $movCard->DataPgMovimentacaoCartao = $dataPg;

                        if($movCard->save()){
                            $c[$i] = true;
                        }else{
                            $c[$i] = false;
                        }
                    }

                    if (in_array(false, $c)) {
                        return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');;
                    } else {
                        return redirect($request->return)->with('msg', 'Movimentação cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }
                }else{
                    //caso não seja recorrente
                    $movCard = new movimentacaocartao;

                    $cartao = cartaocredito::find($request->cartaocredito_id);

                    $movCard->familia_id = session('familia');
                    $movCard->cartaocredito_id = $request->cartaocredito_id;
                    $movCard->categoria_id = $request->categoria_id;
                    $movCard->subcategoria_id = $request->subcategoria_id;
                    $movCard->DataMovimentacaoCartao = $request->DataVencimentoMovimentacaoFinanc;
                    $movCard->ValorMovimentacaoCartao = Str::replace([','], '.', Str::replace(['.'], '',$request->ValorMovimentacaoFinanc));
                    $movCard->ObsMovimentacaoCartao = $request->ObsMovimentacaoFinanc;

                    if(
                        $request->DataVencimentoMovimentacaoFinanc < 
                        date(
                            'Y-m-d', 
                            strtotime(
                                date('Y', strtotime($request->DataVencimentoMovimentacaoFinanc)) 
                                . '-' . date('m', strtotime($request->DataVencimentoMovimentacaoFinanc)) 
                                . '-' .  $cartao->DataFechamentoCartao
                            )
                        )
                    ){
                        $dataPg = date(
                                        'Y-m-d',
                                        strtotime(
                                            date('Y', strtotime($request->DataVencimentoMovimentacaoFinanc))
                                            .'-'.
                                            date('m', strtotime($request->DataVencimentoMovimentacaoFinanc))
                                            .'-'.
                                            $cartao->DataVencimentoCartao
                                        )
                                  );
                    }else{
                        $dataPg = date(
                                    'Y-m-d',
                                    strtotime(
                                        date('Y', strtotime('+1 months', strtotime($request->DataVencimentoMovimentacaoFinanc)))
                                        .'-'.
                                        date('m', strtotime('+1 months', strtotime($request->DataVencimentoMovimentacaoFinanc)))
                                        .'-'.
                                        $cartao->DataVencimentoCartao
                                    )
                                  );
                    }

                    $movCard->DataPgMovimentacaoCartao = $dataPg;

                    if($movCard->save()){
                        return redirect($request->return)->with('msg', 'Movimentação cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    }
                }
            }else{
                //caso não seja cartão de crédito
                if($request->boolean('recorrente')){
                    //Caso seja recorrente
                    if($request->boolean('PagoMovimentacaoFinanc')){
                        //Caso o valor esteja marcado como pago
                        $data = $request->DataVencimentoMovimentacaoFinanc;
                        for($i=0;$i<$request->parcelasNum;$i++){
                            $movFin = new movimentacaofinanceira;

                            if($i == 0){
                                $pg = '1';
                                $Dtpg = date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)));
                                $token = Str::uuid();
                                $VlPg = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                                
                            }else{
                                $pg = '0';
                                $Dtpg = null;
                                $VlPg = null;

                            }

                        
                            $movFin->RecorrenteMovimentacaoFinanc = '1';
                            $movFin->QntParcelasMovimentacaoFinanc = $i+1 . '-' .  $request->parcelasNum;
                            $movFin->familia_id = session('familia');
                            $movFin->categoria_id = $request->categoria_id;
                            $movFin->subcategoria_id = $request->subcategoria_id;
                            $movFin->banco_id = $request->banco_id;
                            $movFin->TipoMovimentacaoFinanc = $request->TipoMovimentacaoFinanc;
                            $movFin->DataVencimentoMovimentacaoFinanc = date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)));
                            $movFin->DataPagamentoMovimentacaoFinanc = $Dtpg;
                            $movFin->DataMovimentacaoFinanc = date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)));
                            $movFin->ValorMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movFin->ValorPagoMovimentacaoFinanc = $VlPg;
                            $movFin->ValorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movFin->ObsMovimentacaoFinanc = $request->ObsMovimentacaoFinanc;
                            $movFin->PagoMovimentacaoFinanc = $pg;
                            $movFin->TokenRecorrenteMovimentacaoFinanc = $token;

                            if($i == 0){
                                if($request->boolean('DividaMovimentacaoFinanc')){
                                    $movFin->DividaMovimentacaoFinanc = '1';
                                    $movFin->QualDividaMovimentacaoFinanc = $request->divida_id;
        
                                    if($request->TipoMovimentacaoFinanc == 'D'){
                                        $tipo = 'S';
                                    }else{
                                        $tipo = 'E';
                                    }
        
                                    $movDiv = new movimentacaodivida;
                                    $movDiv->familia_id = session('familia');
                                    $movDiv->divida_id = $request->divida_id;
                                    $movDiv->categoria_id = $request->categoria_id;
                                    $movDiv->subcategoria_id = $request->subcategoria_id;
                                    $movDiv->TipoMovimentacaoDivida = $tipo;
                                    $movDiv->DataMovimentacaoDivida = $request->DataVencimentoMovimentacaoFinanc;
                                    $movDiv->ValorMovimentacaoDivida = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                                    $movDiv->ObservacaoMovimentacaoDivida = $request->ObsMovimentacaoFinanc;
        
                                    if($movDiv->save()){
                                        $c[$i][0] = true;
                                    }else{
                                        $c[$i][0] = false;
                                    }
                                }else{
                                    $c[$i][0] = true;
                                }
        
                                if($request->boolean('MetaMovimentacaoFinanc')){
                                    $movFin->MetaMovimentacaoFinanc = '1';
                                    $movFin->QualMetaMovimentacaoFinanc = $request->meta_id;
        
                                    if($request->TipoMovimentacaoFinanc == 'D'){
                                        $tipo = 'E';
                                    }else{
                                        $tipo = 'S';
                                    }
        
                                    $movDiv = new movimentacaometa;
                                    $movDiv->familia_id = session('familia');
                                    $movDiv->meta_id = $request->meta_id;
                                    $movDiv->categoria_id = $request->categoria_id;
                                    $movDiv->subcategoria_id = $request->subcategoria_id;
                                    $movDiv->TipoMovimentacaoMeta = $tipo;
                                    $movDiv->DataMovimentacaoMeta = $request->DataVencimentoMovimentacaoFinanc;
                                    $movDiv->ValorMovimentacaoMeta = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                                    $movDiv->ObsMovimentacaoMeta = $request->ObsMovimentacaoFinanc;
        
                                    if($movDiv->save()){
                                        $c[$i][1] = true;
                                    }else{
                                        $c[$i][1] = false;
                                    }
                                }else{
                                    $c[$i][1] = true;
                                }
        
                                if($request->boolean('InvestimentoMovimentacaoFinanc')){
                                    $movFin->InvestimentoMovimentacaoFinanc = '1';
                                    $movFin->QualInvestimentoMovimentacaoFinanc = $request->investimento_id;
        
                                    if($request->TipoMovimentacaoFinanc == 'D'){
                                        $tipo = 'E';
                                    }else{
                                        $tipo = 'S';
                                    }
        
                                    $movDiv = new movimentacaoinvestimento;
                                    $movDiv->familia_id = session('familia');
                                    $movDiv->investimento_id = $request->investimento_id;
                                    $movDiv->categoria_id = $request->categoria_id;
                                    $movDiv->subcategoria_id = $request->subcategoria_id;
                                    $movDiv->TipoMovimentacaoInvestimento = $tipo;
                                    $movDiv->DataMovimentacaoInvestimento = $request->DataVencimentoMovimentacaoFinanc;
                                    $movDiv->ValorMovimentacaoInvestimento = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                                    $movDiv->ObsMovimentacaoInvestimento = $request->ObsMovimentacaoFinanc;
        
                                    if($movDiv->save()){
                                        $c[$i][2] = true;
                                    }else{
                                        $c[$i][2] = false;
                                    }
                                }else{
                                    $c[$i][2] = true;
                                }

                                
                            }

                            if($movFin->save()){
                                $c[$i] = true;
                            }else{
                                $c[$i] = true;
                            }
                        }
                        if (in_array(false, $c)) {
                            return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                        } else {
                            return redirect($request->return)->with('msg', 'Movimentação cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }
                    }else{
                        //caso o valor não esteja marcado como pago
                        $data = $request->DataVencimentoMovimentacaoFinanc;
                        for($i=0; $i<$request->parcelasNum;$i++){
                            if($i == 0){
                                $token = Str::uuid();
                            }
                            $movFin = new movimentacaofinanceira;

                            $movFin->RecorrenteMovimentacaoFinanc = '1';
                            $movFin->QntParcelasMovimentacaoFinanc = $i+1 . '-' .  $request->parcelasNum;

                            $movFin->familia_id = session('familia');
                            $movFin->categoria_id = $request->categoria_id;
                            $movFin->subcategoria_id = $request->subcategoria_id;
                            $movFin->banco_id = $request->banco_id;
                            $movFin->TipoMovimentacaoFinanc = $request->TipoMovimentacaoFinanc;
                            $movFin->DataVencimentoMovimentacaoFinanc = date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)));
                            $movFin->DataMovimentacaoFinanc = date('Y-m-d', strtotime('+'.$i.' months', strtotime($request->DataVencimentoMovimentacaoFinanc)));
                            $movFin->ValorMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movFin->ValorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movFin->ObsMovimentacaoFinanc = $request->ObsMovimentacaoFinanc;
                            $movFin->TokenRecorrenteMovimentacaoFinanc = $token;

                            if($request->boolean('DividaMovimentacaoFinanc')){
                                $movFin->DividaMovimentacaoFinanc = '1';
                                $movFin->QualDividaMovimentacaoFinanc = $request->divida_id;
                            }

                            if($request->boolean('MetaMovimentacaoFinanc')){
                                $movFin->MetaMovimentacaoFinanc = '1';
                                $movFin->QualMetaMovimentacaoFinanc = $request->meta_id;
                            }

                            if($request->boolean('InvestimentoMovimentacaoFinanc')){
                                $movFin->InvestimentoMovimentacaoFinanc = '1';
                                $movFin->QualInvestimentoMovimentacaoFinanc = $request->investimento_id;
                            }

                            if($movFin->save()){
                                $c[$i] = true;
                            }else{
                                $c[$i] = false;
                            }
                        }

                        if (in_array(false, $c)) {
                            return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                        } else {
                            return redirect($request->return)->with('msg', 'Movimentação cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }
                    }

                }else{
                    //Caso não seja recorrente
                    if($request->boolean('PagoMovimentacaoFinanc')){
                        //caso o valor esteja marcado como pago
                        $movFin = new movimentacaofinanceira;

                        $movFin->familia_id = session('familia');
                        $movFin->categoria_id = $request->categoria_id;
                        $movFin->subcategoria_id = $request->subcategoria_id;
                        $movFin->banco_id = $request->banco_id;
                        $movFin->TipoMovimentacaoFinanc = $request->TipoMovimentacaoFinanc;
                        $movFin->DataVencimentoMovimentacaoFinanc = $request->DataVencimentoMovimentacaoFinanc;
                        $movFin->DataPagamentoMovimentacaoFinanc = $request->DataVencimentoMovimentacaoFinanc;
                        $movFin->DataMovimentacaoFinanc = $request->DataVencimentoMovimentacaoFinanc;
                        $movFin->ValorMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movFin->ValorPagoMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movFin->ValorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movFin->ObsMovimentacaoFinanc = $request->ObsMovimentacaoFinanc;
                        $movFin->PagoMovimentacaoFinanc = '1';

                        if($request->boolean('DividaMovimentacaoFinanc')){
                            $movFin->DividaMovimentacaoFinanc = '1';
                            $movFin->QualDividaMovimentacaoFinanc = $request->divida_id;

                            if($request->TipoMovimentacaoFinanc == 'D'){
                                $tipo = 'S';
                            }else{
                                $tipo = 'E';
                            }

                            $movDiv = new movimentacaodivida;
                            $movDiv->familia_id = session('familia');
                            $movDiv->divida_id = $request->divida_id;
                            $movDiv->categoria_id = $request->categoria_id;
                            $movDiv->subcategoria_id = $request->subcategoria_id;
                            $movDiv->TipoMovimentacaoDivida = $tipo;
                            $movDiv->DataMovimentacaoDivida = $request->DataVencimentoMovimentacaoFinanc;
                            $movDiv->ValorMovimentacaoDivida = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movDiv->ObservacaoMovimentacaoDivida = $request->ObsMovimentacaoFinanc;

                            if($movDiv->save()){
                                $c[0] = true;
                            }else{
                                $c[0] = false;
                            }
                        }else{
                            $c[0] = true;
                        }

                        if($request->boolean('MetaMovimentacaoFinanc')){
                            $movFin->MetaMovimentacaoFinanc = '1';
                            $movFin->QualMetaMovimentacaoFinanc = $request->meta_id;

                            if($request->TipoMovimentacaoFinanc == 'D'){
                                $tipo = 'E';
                            }else{
                                $tipo = 'S';
                            }

                            $movDiv = new movimentacaometa;
                            $movDiv->familia_id = session('familia');
                            $movDiv->meta_id = $request->meta_id;
                            $movDiv->categoria_id = $request->categoria_id;
                            $movDiv->subcategoria_id = $request->subcategoria_id;
                            $movDiv->TipoMovimentacaoMeta = $tipo;
                            $movDiv->DataMovimentacaoMeta = $request->DataVencimentoMovimentacaoFinanc;
                            $movDiv->ValorMovimentacaoMeta = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movDiv->ObsMovimentacaoMeta = $request->ObsMovimentacaoFinanc;

                            if($movDiv->save()){
                                $c[1] = true;
                            }else{
                                $c[1] = false;
                            }
                        }else{
                            $c[1] = true;
                        }

                        if($request->boolean('InvestimentoMovimentacaoFinanc')){
                            $movFin->InvestimentoMovimentacaoFinanc = '1';
                            $movFin->QualInvestimentoMovimentacaoFinanc = $request->investimento_id;

                            if($request->TipoMovimentacaoFinanc == 'D'){
                                $tipo = 'E';
                            }else{
                                $tipo = 'S';
                            }

                            $movDiv = new movimentacaoinvestimento;
                            $movDiv->familia_id = session('familia');
                            $movDiv->investimento_id = $request->investimento_id;
                            $movDiv->categoria_id = $request->categoria_id;
                            $movDiv->subcategoria_id = $request->subcategoria_id;
                            $movDiv->TipoMovimentacaoInvestimento = $tipo;
                            $movDiv->DataMovimentacaoInvestimento = $request->DataVencimentoMovimentacaoFinanc;
                            $movDiv->ValorMovimentacaoInvestimento = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                            $movDiv->ObsMovimentacaoInvestimento = $request->ObsMovimentacaoFinanc;

                            if($movDiv->save()){
                                $c[2] = true;
                            }else{
                                $c[2] = false;
                            }
                        }else{
                            $c[2] = true;
                        }

                        if (in_array(false, $c)) {
                            return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                        } else {
                            if($movFin->save()){
                                return redirect($request->return)->with('msg', 'Movimentação cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                            }else{
                                return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                            }
                        }

                        

                    }else{
                        //caso o valor não esteja marcado como pago
                        $movFin = new movimentacaofinanceira;

                        $movFin->familia_id = session('familia');
                        $movFin->categoria_id = $request->categoria_id;
                        $movFin->subcategoria_id = $request->subcategoria_id;
                        $movFin->banco_id = $request->banco_id;
                        $movFin->TipoMovimentacaoFinanc = $request->TipoMovimentacaoFinanc;
                        $movFin->DataVencimentoMovimentacaoFinanc = $request->DataVencimentoMovimentacaoFinanc;
                        $movFin->DataMovimentacaoFinanc = $request->DataVencimentoMovimentacaoFinanc;
                        $movFin->ValorMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movFin->ValorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movFin->ObsMovimentacaoFinanc = $request->ObsMovimentacaoFinanc;

                        if($request->boolean('DividaMovimentacaoFinanc')){
                            $movFin->DividaMovimentacaoFinanc = '1';
                            $movFin->QualDividaMovimentacaoFinanc = $request->divida_id;
                        }

                        if($request->boolean('MetaMovimentacaoFinanc')){
                            $movFin->MetaMovimentacaoFinanc = '1';
                            $movFin->QualMetaMovimentacaoFinanc = $request->meta_id;
                        }

                        if($request->boolean('InvestimentoMovimentacaoFinanc')){
                            $movFin->InvestimentoMovimentacaoFinanc = '1';
                            $movFin->QualInvestimentoMovimentacaoFinanc = $request->investimento_id;
                        }

                        if($movFin->save()){
                            return redirect($request->return)->with('msg', 'Movimentação cadastrada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }else{
                            return redirect($request->return)->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                        }
                        
                    }
                }
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function show($id)
    {
        if(!empty(session('user'))){
            $mov = movimentacaofinanceira::
                                            with('movCategoria')
                                            ->with('movSubcategoria')
                                            ->with('movBanco')
                                            ->where('familia_id', session('familia'))
                                            ->where('AtivoMovimentacaoFinanc', '1')
                                            ->where('id',$id)
                                            ->get()
                                            ->first();

            if(!empty($mov) && $mov->AtivoMovimentacaoFinanc == '1'){

                if($mov->familia_id == session('familia')){
                    return view('movimentacao.show')->with('mov', $mov);
                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    //load por ajax
    public function pesquisar(Request $request) 
    {
        if(!empty(session('user'))){


            $categoriaS = categoria::
                                    all()
                                    ->where('familia_id', session('familia'))
                                    ->where('AtivoCategoria', '1');
            $subcategoriaS = subcategoria::
                                        all()
                                        ->where('familia_id', session('familia'))
                                        ->where('AtivoSubCategoria', '1');
            $bancoS = banco::
                            all()
                            ->where('familia_id', session('familia'))
                            ->where('AtivoBanco','1');

            // Define as datas de 6 meses atrás e 3 meses à frente
            $obs = $request->descricaoFilter;
            $dataInicio = $request->dataStart;
            $dataFim = $request->dataEnd;
            $categoria = $request->categoria_id;
            $sub = $request->subcategoria_id;
            $banco = $request->banco_id;

            $mov = movimentacaofinanceira::with('movCategoria', 'movSubcategoria', 'movBanco')
                        ->where('familia_id', session('familia'))
                        ->where('AtivoMovimentacaoFinanc', '1')
                        ->whereBetween('DataVencimentoMovimentacaoFinanc', [$dataInicio, $dataFim])
                        ->when(!empty($categoria), function($query) use ($categoria) {
                            $query->whereHas('movCategoria', function($query) use ($categoria) {
                                $query->where('NomeCategoria', 'like', '%' . $categoria . '%');
                            });
                        })
                        ->when(!empty($sub), function($query) use ($sub) {
                            $query->whereHas('movSubcategoria', function($query) use ($sub) {
                                $query->where('NomeSubCategoria', 'like', '%' . $sub . '%');
                            });
                        })
                        ->when(!empty($banco), function($query) use ($banco) {
                            $query->whereHas('movBanco', function($query) use ($banco) {
                                $query->where('NomeBanco', 'like', '%' . $banco . '%');
                            });
                        })
                        ->where('ObsMovimentacaoFinanc','like','%'.$obs.'%')
                        ->get();
    
            // Aplica os filtros vindos do front-end, se existirem
            //echo($mov);
            //dd($mov);

            return view('movimentacao.index')->with(['mov'=> $mov, 'categoria' => $categoriaS, 'subcategoria' => $subcategoriaS, 'banco'=>$bancoS]);
        } else {
            return response()->json(['error' => 'Você precisa estar logado para fazer essa operação!'], 403);
        }
    }

    public function transferencia(Request $request)
    {
        if(!empty(session('user'))){
            $banco1 = banco::find($request->banco_id);
            $banco2 = banco::find($request->banco_id2);

            if($banco1->AtivoBanco == '1' && $banco2->AtivoBanco == '1'){
                if($banco1->familia_id == session('familia') && $banco2->familia_id == session('familia')){
                    for($i = 0; $i < 2; $i++){
                        $mov = new movimentacaofinanceira;

                        $mov->familia_id = session('familia');
                        $mov->categoria_id = $request->categoria_id;
                        $mov->subcategoria_id = $request->subcategoria_id;
                        $mov->DataVencimentoMovimentacaoFinanc = $request->data;
                        $mov->DataPagamentoMovimentacaoFinanc = $request->data;
                        $mov->DataMovimentacaoFinanc = $request->data;
                        $mov->ValorMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $mov->ValorPagoMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $mov->ValorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $mov->PagoMovimentacaoFinanc = '1';
                        if($i == 0){
                            $mov->banco_id = $request->banco_id;
                            $mov->TipoMovimentacaoFinanc = 'D';
                            $mov->ObsMovimentacaoFinanc = 'Saída - Transferência (O:'.$banco1->NomeBanco.' - D:'.$banco2->NomeBanco.')';
                        }else{
                            $mov->banco_id = $request->banco_id2;
                            $mov->TipoMovimentacaoFinanc = 'R';
                            $mov->ObsMovimentacaoFinanc = 'Entrada - Transferência (O:'.$banco1->NomeBanco.' - D:'.$banco2->NomeBanco.')';
                        }

                        if($mov->save()){
                            $c[$i] = true;
                        }else{
                            $c[$i] = false;
                        }
                    }

                    if (in_array(false, $c)) {
                        return redirect('/movimentacao')->with('msg', 'Não conseguimos cadastrar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                    } else {
                        return redirect('/movimentacao')->with('msg', 'Transferência realizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }
                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');        
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showEdit($id)
    {
        if(!empty(session('user'))){
            $mov = movimentacaofinanceira::with(['movBanco', 'movCategoria', 'movSubcategoria'])->where('id', $id)->get()->first();
            $banco = banco::all()->where('familia_id', session('familia'))->where('AtivoBanco', '1');
            //echo($mov);
            //dd($mov);
            if(!empty($mov) && $mov->AtivoMovimentacaoFinanc == '1'){
                if($mov->familia_id == session('familia')){
                    return view('movimentacao.edit')->with(['mov' => $mov, 'banco' => $banco]);
                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function update(Request $request)
    {
        if(!empty(session('user'))){
            $mov = movimentacaofinanceira::find($request->id);
            if(!empty($mov) && $mov->AtivoMovimentacaoFinanc == '1'){
                if($mov->familia_id = session('familia')){
                    //*caso tenha vinculo com alguma dívida
                    
                    if($mov->DividaMovimentacaoFinanc == '1'){
                        if($request->TipoMovimentacaoFinanc == 'D'){
                            $tipo = 'S';
                        }else{
                            $tipo = 'E';
                        }
                        $movDivida = movimentacaodivida::where('divida_id', $mov->QualDividaMovimentacaoFinanc)
                                                            ->where('DataMovimentacaoDivida', $mov->DataVencimentoMovimentacaoFinanc)
                                                            ->where('ObservacaoMovimentacaoDivida', $mov->ObsMovimentacaoFinanc)
                                                            ->where('ValorMovimentacaoDivida', $mov->ValorMovimentacaoFinanc)
                                                            ->first();
                        
                                                            
                        $movDivida->TipoMovimentacaoDivida = $tipo;
                        $movDivida->categoria_id = $request->categoria_id;
                        $movDivida->subcategoria_id = $request->subcategoria_id;
                        $movDivida->DataMovimentacaoDivida = $request->DataVencimentoMovimentacaoFinanc;
                        $movDivida->ValorMovimentacaoDivida = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movDivida->ObservacaoMovimentacaoDivida = $request->ObsMovimentacaoFinanc;

                        $movDivida->save();
                    }

                    //caso tenho vinculo com algum investimento
                    if($mov->InvestimentoMovimentacaoFinanc == '1'){
                        if($request->TipoMovimentacaoFinanc == 'D'){
                            $tipo = 'E';
                        }else{
                            $tipo = 'S';
                        }
                        $movInv = movimentacaoinvestimento::where('investimento_id', $mov->QualInvestimentoMovimentacaoFinanc)
                                                            ->where('DataMovimentacaoInvestimento', $mov->DataVencimentoMovimentacaoFinanc)
                                                            ->where('ObsMovimentacaoInvestimento', $mov->ObsMovimentacaoFinanc)
                                                            ->where('ValorMovimentacaoInvestimento', $mov->ValorMovimentacaoFinanc)
                                                            ->first();
                        
                                                            
                        $movInv->TipoMovimentacaoInvestimento = $tipo;
                        $movInv->categoria_id = $request->categoria_id;
                        $movInv->subcategoria_id = $request->subcategoria_id;
                        $movInv->DataMovimentacaoInvestimento = $request->DataVencimentoMovimentacaoFinanc;
                        $movInv->ValorMovimentacaoInvestimento = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movInv->ObsMovimentacaoInvestimento = $request->ObsMovimentacaoFinanc;

                        $movInv->save();
                    }

                    //caso seja relacionado a uma meta
                    if($mov->MetaMovimentacaoFinanc=='1'){
                        if($request->TipoMovimentacaoFinanc == 'D'){
                            $tipo = 'E';
                        }else{
                            $tipo = 'S';
                        }
                        $movMeta = movimentacaometa::where('meta_id', $mov->QualMetaMovimentacaoFinanc)
                                                            ->where('DataMovimentacaoMeta', $mov->DataVencimentoMovimentacaoFinanc)
                                                            ->where('ObsMovimentacaoMeta', $mov->ObsMovimentacaoFinanc)
                                                            ->where('ValorMovimentacaoMeta', $mov->ValorMovimentacaoFinanc)
                                                            ->first();
                        
                                                            
                        $movMeta->TipoMovimentacaoMeta = $tipo;
                        $movMeta->categoria_id = $request->categoria_id;
                        $movMeta->subcategoria_id = $request->subcategoria_id;
                        $movMeta->DataMovimentacaoMeta = $request->DataVencimentoMovimentacaoFinanc;
                        $movMeta->ValorMovimentacaoMeta = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                        $movMeta->ObsMovimentacaoMeta = $request->ObsMovimentacaoFinanc;

                        $movMeta->save();
                    }

                    $mov->TipoMovimentacaoFinanc = $request->TipoMovimentacaoFinanc;
                    $mov->categoria_id = $request->categoria_id;
                    $mov->subcategoria_id = $request->subcategoria_id;
                    $mov->DataMovimentacaoFinanc = $request->DataVencimentoMovimentacaoFinanc;
                    $mov->ValorMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                    $mov->valorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                    if($mov->PagoMovimentacaoFinanc == '1'){
                        $mov->ValorPagoMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->ValorMovimentacaoFinanc));
                    }
                    $mov->ObsMovimentacaoFinanc = $request->ObsMovimentacaoFinanc;

                    if($mov->save()){
                        return redirect('/movimentacao')->with('msg', 'Atualização realizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/movimentacao')->with('msg', 'Não conseguimos atualizar a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                    }
                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function showDelete($id)
    {
        if(!empty(session('user'))){
            $mov = movimentacaofinanceira::
                                            with('movCategoria')
                                            ->with('movSubcategoria')
                                            ->with('movBanco')
                                            ->where('familia_id', session('familia'))
                                            ->where('AtivoMovimentacaoFinanc', '1')
                                            ->where('id',$id)
                                            ->get()
                                            ->first();

            if(!empty($mov) && $mov->AtivoMovimentacaoFinanc == '1'){

                if($mov->familia_id == session('familia')){
                    return view('movimentacao.delete')->with('mov', $mov);
                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function delete(Request $request)
    {
        if(!empty(session('user'))){
            $mov = movimentacaofinanceira::
                                            with('movCategoria')
                                            ->with('movSubcategoria')
                                            ->with('movBanco')
                                            ->where('familia_id', session('familia'))
                                            ->where('AtivoMovimentacaoFinanc', '1')
                                            ->where('id',$request->ids)
                                            ->get()
                                            ->first();

            if(!empty($mov) && $mov->AtivoMovimentacaoFinanc == '1'){

                if($mov->familia_id == session('familia')){
                    //caso tenha divida atreado
                    if($mov->DividaMovimentacaoFinanc == '1'){
                        $movDivida = movimentacaodivida::where('divida_id', $mov->QualDividaMovimentacaoFinanc)
                                                            ->where('DataMovimentacaoDivida', $mov->DataVencimentoMovimentacaoFinanc)
                                                            ->where('ObservacaoMovimentacaoDivida', $mov->ObsMovimentacaoFinanc)
                                                            ->where('ValorMovimentacaoDivida', $mov->ValorMovimentacaoFinanc)
                                                            ->first();
                        $movDivida->AtivoMovimentacaoDivida = '0';
                        $movDivida->save();
                    }
                    //caso tenha investimento atrelado
                    if($mov->InvestimentoMovimentacaoFinanc == '1'){
                        $movInv = movimentacaoinvestimento::where('investimento_id', $mov->QualInvestimentoMovimentacaoFinanc)
                                                            ->where('DataMovimentacaoInvestimento', $mov->DataVencimentoMovimentacaoFinanc)
                                                            ->where('ObsMovimentacaoInvestimento', $mov->ObsMovimentacaoFinanc)
                                                            ->where('ValorMovimentacaoInvestimento', $mov->ValorMovimentacaoFinanc)
                                                            ->first();
                        $movInv->AtivoMovimentacaoInvestimento = '0';
                        $movInv->save();
                    }
                    //caso tenha meta atrelado
                    if($mov->MetaMovimentacaoFinanc=='1'){
                        $movMeta = movimentacaometa::where('meta_id', $mov->QualMetaMovimentacaoFinanc)
                                                            ->where('DataMovimentacaoMeta', $mov->DataVencimentoMovimentacaoFinanc)
                                                            ->where('ObsMovimentacaoMeta', $mov->ObsMovimentacaoFinanc)
                                                            ->where('ValorMovimentacaoMeta', $mov->ValorMovimentacaoFinanc)
                                                            ->first();
                        $movMeta->AtivoMovimentacaoMeta = '0';
                        $movMeta->save();
                    }
                     $mov->AtivoMovimentacaoFinanc = '0';

                     //caso tenha recorrente e foi marcado para apagar
                     if($request->boolean('recorrente')){
                        $movR = movimentacaofinanceira::where('TokenRecorrenteMovimentacaoFinanc', $mov->TokenRecorrenteMovimentacaoFinanc)
                                                        ->where('DataMovimentacaoFinanc', '>', $mov->DataMovimentacaoFinanc)
                                                        ->get();
                        //echo($movR);
                        //dd($movR);
                        foreach($movR as $dados){
                            $dados->AtivoMovimentacaoFinanc = '0';

                            $dados->save();
                        }
                     }

                     if($mov->save()){
                        return redirect('/movimentacao')->with('msg', 'Movimentação excluída com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                        }else{
                            return redirect('/movimentacao')->with('msg', 'Não conseguimos excluir a movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                        }
                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function baixa(Request $request)
    {
        if(!empty(session('user'))){
            $mov = movimentacaofinanceira::
                                            where('familia_id', session('familia'))
                                            ->where('AtivoMovimentacaoFinanc', '1')
                                            ->where('id',$request->id)
                                            ->get()
                                            ->first();

            if(!empty($mov) && $mov->AtivoMovimentacaoFinanc == '1'){

                if($mov->familia_id == session('familia')){
                    if($mov->DividaMovimentacaoFinanc == '1'){
                        if($mov->TipoMovimentacaoFinanc == 'D'){
                            $tipo = 'S';
                        }else{
                            $tipo = 'E';
                        }
                        $movDivida = new movimentacaodivida;
                                                    
                        $movDivida->divida_id = $mov->QualDividaMovimentacaoFinanc;
                        $movDivida->familia_id = session('familia');
                        $movDivida->TipoMovimentacaoDivida = $tipo;
                        $movDivida->categoria_id = $mov->categoria_id;
                        $movDivida->subcategoria_id = $mov->subcategoria_id;
                        $movDivida->DataMovimentacaoDivida = $request->dataBaixa;
                        $movDivida->ValorMovimentacaoDivida = Str::replace([','],'.',Str::replace(['.'],'',$request->Valor));
                        $movDivida->ObservacaoMovimentacaoDivida = $mov->ObsMovimentacaoFinanc;

                        $movDivida->save();
                    }

                    if($mov->InvestimentoMovimentacaoFinanc == '1'){
                        if($mov->TipoMovimentacaoFinanc == 'D'){
                            $tipo = 'E';
                        }else{
                            $tipo = 'S';
                        }
                        $movInv = new movimentacaoinvestimento;
                                 
                        $movInv->investimento_id = $mov->QualInvestimentoMovimentacaoFinanc;
                        $movInv->familia_id = session('familia');
                        $movInv->TipoMovimentacaoInvestimento = $tipo;
                        $movInv->categoria_id = $mov->categoria_id;
                        $movInv->subcategoria_id = $mov->subcategoria_id;
                        $movInv->DataMovimentacaoInvestimento = $request->dataBaixa;
                        $movInv->ValorMovimentacaoInvestimento = Str::replace([','],'.',Str::replace(['.'],'',$request->Valor));
                        $movInv->ObsMovimentacaoInvestimento = $mov->ObsMovimentacaoFinanc;

                        $movInv->save();
                    }

                    if($mov->MetaMovimentacaoFinanc=='1'){
                        if($mov->TipoMovimentacaoFinanc == 'D'){
                            $tipo = 'E';
                        }else{
                            $tipo = 'S';
                        }
                        $movMeta = new movimentacaometa ;                       
                                 
                        $movMeta->meta_id = $mov->QualMetaMovimentacaoFinanc;
                        $movMeta->familia_id = session('familia');
                        $movMeta->TipoMovimentacaoMeta = $tipo;
                        $movMeta->categoria_id = $mov->categoria_id;
                        $movMeta->subcategoria_id = $mov->subcategoria_id;
                        $movMeta->DataMovimentacaoMeta = $request->dataBaixa;
                        $movMeta->ValorMovimentacaoMeta = Str::replace([','],'.',Str::replace(['.'],'',$request->Valor));
                        $movMeta->ObsMovimentacaoMeta = $mov->ObsMovimentacaoFinanc;

                        $movMeta->save();
                    }

                    $mov->PagoMovimentacaoFinanc = '1';
                    $mov->DataPagamentoMovimentacaoFinanc = $request->dataBaixa;
                    $mov->ValorPagoMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->Valor));
                    $mov->ValorFimMovimentacaoFinanc = Str::replace([','],'.',Str::replace(['.'],'',$request->Valor));
                    $mov->DataMovimentacaoFinanc = $request->dataBaixa;

                    if($mov->FaturaCardMovimentacaoFinanc == '1'){
                        $fatura = faturacartao::
                                                where('AtivoFatura', '1')
                                                ->where('DataVencimento', $mov->DataVencimentoMovimentacaoFinanc)
                                                ->where('ValorFatura', $mov->ValorMovimentacaoFinanc)
                                                ->get()
                                                ->last();
                        
                        if($mov->ValorPagoMovimentacaoFinanc < $mov->ValorMovimentacaoFinanc){
                            $status = 'Parcial';

                            $cartao = cartaocredito::find($fatura->cartaocredito_id);

                            $movCard = new movimentacaocartao;

                            $movCard->familia_id = session('familia');
                            $movCard->cartaocredito_id = $fatura->cartaocredito_id;
                            $movCard->categoria_id = $request->categoriaSaldo2;
                            $movCard->subcategoria_id = $request->subSaldo2;
                            $movCard->DataMovimentacaoCartao = date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura)));
                            $movCard->ValorMovimentacaoCartao = $mov->ValorMovimentacaoFinanc - $mov->ValorPagoMovimentacaoFinanc;
                            $movCard->ObsMovimentacaoCartao = 'Residual da fatura anterior do mês '. $fatura->MesFatura;
                            
                            if(
                                date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura))) < 
                                date(
                                    'Y-m-d', 
                                    strtotime(
                                        date('Y', strtotime(date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura))))) 
                                        . '-' . date('m', strtotime(date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura))))) 
                                        . '-' .  $cartao->DataFechamentoCartao
                                    )
                                )
                            ){
                                $dataPg = date(
                                                'Y-m-d',
                                                strtotime(
                                                    date('Y', strtotime(date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura)))))
                                                    .'-'.
                                                    date('m', strtotime(date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura)))))
                                                    .'-'.
                                                    $cartao->DataVencimentoCartao
                                                )
                                          );
                            }else{
                                $dataPg = date(
                                            'Y-m-d',
                                            strtotime(
                                                date('Y', strtotime('+1 months', strtotime(date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura))))))
                                                .'-'.
                                                date('m', strtotime('+1 months', strtotime(date('Y-m-d', strtotime('+1 days', strtotime($fatura->DateEndFatura))))))
                                                .'-'.
                                                $cartao->DataVencimentoCartao
                                            )
                                          );
                            }
        
                            $movCard->DataPgMovimentacaoCartao = $dataPg;
                            
                            $movCard->save();

                        }else{
                            $status = 'Pago';
                        }

                        $fatura->StatusFatura = $status;

                        $fatura->save();
                    }

                    if($mov->save()){
                        return redirect('/movimentacao')->with('msg', 'Baixa realizada com sucesso!')->with('icon', 'success')->with('textB', 'Ok')->with('colorB', '#28a745')->with('title', 'Sucesso!');
                    }else{
                        return redirect('/movimentacao')->with('msg', 'Não conseguimos realizar a baixa da movimentação! Tente novamente mais tarde.')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');    
                    }

                }else{
                    return redirect('/movimentacao')->with('msg', 'Você não tem acesso a esse registro!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
                }
            }else{
                return redirect('/movimentacao')->with('msg', 'Registro não encontrado!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
            }
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }
}
