<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\movimentacaofinanceira;
use App\Models\movimentacaocartao;
use App\Models\divida;
use App\Models\categoria;
use App\Models\subcategoria;
use App\Models\meta;
use App\Models\investimento;
use App\Models\faturacartao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SistemaController extends Controller
{
    //
    public function home()
    {
        if(!empty(session('user'))){
            if(empty($start)){
                $startOfMonth = Carbon::now()->startOfMonth()->toDateString(); // Primeiro dia do mês
            }else{
                $startOfMonth = $start;
            }

            if(empty($end)){
                $endOfMonth = Carbon::now()->endOfMonth()->toDateString();     // Último dia do mês
            }else{
                $endOfMonth = $end;
            }
            
            $mov = movimentacaofinanceira::
                                        where('familia_id', session('familia'))
                                        ->where('AtivoMovimentacaoFinanc', '1')
                                        ->where('PagoMovimentacaoFinanc','1')
                                        ->where('FaturaCardMovimentacaoFinanc','0')
                                        ->whereBetween('DataMovimentacaoFinanc', [$startOfMonth, $endOfMonth]) // Filtro entre início e fim do mês
                                        ->get();
            $movCard = movimentacaocartao::
                                        Where('familia_id', session('familia'))
                                        ->where('AtivoMovimentacaoCartao','1')
                                        ->whereBetween('DataPgMovimentacaoCartao', [$startOfMonth, $endOfMonth])
                                        ->get();
            $divida = divida::
                            with('dividaMovimentacaodivida')
                            ->where('AtivoDivida', '1')
                            ->where('familia_id', session('familia'))
                            ->where('VisivelDashDivida','1')
                            ->get();
            
            $categoria = categoria::
                                with([
                                    'categoriaMovFin' => function ($query) {
                                    // Filtra somente registros ativos e dentro do mês atual
                                    $startOfMonth = Carbon::now()->startOfMonth()->toDateString(); // Primeiro dia do mês
                                    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();  
                                    $query->where('AtivoMovimentacaoFinanc', '1')
                                          ->where('PagoMovimentacaoFinanc','1')
                                          ->where('TipoMovimentacaoFinanc', 'D')
                                          ->where('FaturaCardMovimentacaoFinanc','0')
                                          ->whereBetween('DataMovimentacaoFinanc', [$startOfMonth, $endOfMonth]);
                                },
                                'categoriaMovCard'=>function ($query){
                                    $startOfMonth = Carbon::now()->startOfMonth()->toDateString(); // Primeiro dia do mês
                                    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();  
                                    $query ->where('AtivoMovimentacaoCartao','1')
                                           ->where('familia_id', session('familia'))
                                           ->whereBetween('DataPgMovimentacaoCartao', [$startOfMonth, $endOfMonth]);
                                },
                                'categoriaItensOrcamento'
                            ])
                            ->where('AtivoCategoria', '1')
                            ->where('familia_id', session('familia'))
                            ->where('ShowOrcamentoCategoria','1')
                            ->get();


            $year = Carbon::now()->year;
            // Consulta para obter receitas e despesas por mês do ano atual
            $movGraph = movimentacaofinanceira::select(
                DB::raw('MONTH(DataMovimentacaoFinanc) as mes'),
                DB::raw("SUM(CASE WHEN TipoMovimentacaoFinanc = 'R' THEN ValorFimMovimentacaoFinanc ELSE 0 END) as total_receita"),
                DB::raw("SUM(CASE WHEN TipoMovimentacaoFinanc = 'D' THEN ValorFimMovimentacaoFinanc ELSE 0 END) as total_despesa")
            )
            ->where('familia_id', session('familia'))
            ->where('AtivoMovimentacaoFinanc', '1')
            ->where('PagoMovimentacaoFinanc', '1')
            ->where('FaturaCardMovimentacaoFinanc','0')
            ->whereYear('DataMovimentacaoFinanc', $year) // Filtro para o ano atual
            ->groupBy(DB::raw('MONTH(DataMovimentacaoFinanc)'))
            ->get()
            ->keyBy('mes'); // Agrupando por mês

            $movGraph2 = movimentacaocartao::select(
                DB::raw('MONTH(DataPgMovimentacaoCartao) as mes'),
                DB::raw("SUM(0) as total_receita"),
                DB::raw("SUM(ValorMovimentacaoCartao) as total_despesa")
            )
            ->where('familia_id', session('familia'))
            ->where('AtivoMovimentacaoCartao', '1')
            ->whereYear('DataPgMovimentacaoCartao', $year) // Filtro para o ano atual
            ->groupBy(DB::raw('MONTH(DataPgMovimentacaoCartao)'))
            ->get()
            ->keyBy('mes'); // Agrupando por mês

            $combinedMovGraph = $movGraph->map(function($item) use ($movGraph2) {
                $mes = $item->mes;
                
                // Verificar se o mês existe em $movGraph2 e adicionar as despesas
                if (isset($movGraph2[$mes])) {
                    $item->total_despesa += $movGraph2[$mes]->total_despesa;
                }
            
                return $item;
            });
            
            // Adicionar os meses que existem apenas em $movGraph2
            $movGraph2->each(function($item) use ($combinedMovGraph) {
                if (!$combinedMovGraph->has($item->mes)) {
                    $combinedMovGraph->put($item->mes, $item); // Adicionar os meses restantes
                }
            });
            
            // Ordenar pelo mês
            $combinedMovGraph = $combinedMovGraph->sortBy('mes');
            
            // Formatando o mês para o nome completo
            $combinedMovGraph = $combinedMovGraph->map(function ($item) {
                $item->mes = Carbon::create()->month($item->mes)->format('F');
                return $item;
            });
            

            // Formatar os dados para o gráfico
            $meses = [];
            $receitas = [];
            $despesas = [];
            $saldos = [];

            foreach ($combinedMovGraph as $item) {
                $meses[] = $item->mes; // Número do mês
                $receitas[] = $item->total_receita;
                $despesas[] = $item->total_despesa;
                $saldos[] = $item->total_receita - $item->total_despesa;
            }

            $receitas = array_map(function ($receita) {
                return number_format($receita, 2, ',', '');
            }, $receitas);
            
            $despesas = array_map(function ($despesa) {
                return number_format($despesa, 2, ',', '');
            }, $despesas);
            
            $saldos = array_map(function ($saldo) {
                return number_format($saldo, 2, ',', '');
            }, $saldos);

            
            $gastoCat = DB::table('subcategorias')
            ->join('movimentacaofinanceiras', 'subcategorias.id', '=', 'movimentacaofinanceiras.subcategoria_id')
            ->select('subcategorias.CategorizacaoSubCategoria', DB::raw('SUM(movimentacaofinanceiras.ValorFimMovimentacaoFinanc) as total'))
            ->where('movimentacaofinanceiras.AtivoMovimentacaoFinanc', '1')
            ->where('movimentacaofinanceiras.TipoMovimentacaoFinanc', 'D')
            ->where('movimentacaofinanceiras.PagoMovimentacaoFinanc', '1')
            ->where('movimentacaofinanceiras.FaturaCardMovimentacaoFinanc','0')
            ->where('movimentacaofinanceiras.familia_id', session('familia'))
            ->whereBetween('movimentacaofinanceiras.DataMovimentacaoFinanc', [$startOfMonth, $endOfMonth]) // Filtro entre início e fim do mês
            ->groupBy('subcategorias.CategorizacaoSubCategoria')
            ->get()
            ->keyBy('CategorizacaoSubCategoria'); // Agrupar por subcategoria

            $gastoCatCard = DB::table('subcategorias')
                            ->join('movimentacaocartaos', 'subcategorias.id', '=', 'movimentacaocartaos.subcategoria_id')
                            ->select('subcategorias.CategorizacaoSubCategoria',DB::raw('SUM(movimentacaocartaos.ValorMovimentacaoCartao) as total'))
                            ->where('movimentacaocartaos.AtivoMovimentacaoCartao','1')
                            ->where('movimentacaocartaos.familia_id', session('familia'))
                            ->whereBetween('movimentacaocartaos.DataPgMovimentacaoCartao', [$startOfMonth, $endOfMonth])
                            ->groupBy('subcategorias.CategorizacaoSubCategoria')
                            ->get()
                            ->keyBy('CategorizacaoSubCategoria'); // Agrupar por subcategoria

                            $combinedGastoCat = $gastoCat->map(function ($item) use ($gastoCatCard) {
                                $subcategoria = $item->CategorizacaoSubCategoria;
                                
                                // Verificar se a subcategoria também existe em $gastoCatCard e somar os valores
                                if (isset($gastoCatCard[$subcategoria])) {
                                    $item->total += $gastoCatCard[$subcategoria]->total;
                                }
                            
                                return $item;
                            });
                            
                            // Adicionar as subcategorias que estão apenas em $gastoCatCard
                            $gastoCatCard->each(function ($item) use ($combinedGastoCat) {
                                if (!$combinedGastoCat->has($item->CategorizacaoSubCategoria)) {
                                    $combinedGastoCat->put($item->CategorizacaoSubCategoria, $item); // Adicionar as categorias que não estão presentes
                                }
                            });
                            


            if(empty($combinedGastoCat) || count($combinedGastoCat)<1){
                $TipoGasto[] = null;
                $Vl[] = null;
            }

            foreach($combinedGastoCat as $itens){
                if($itens->CategorizacaoSubCategoria <> 'Transferência - Variável' && $itens->CategorizacaoSubCategoria <> 'Transferência - Fixa' && $itens->CategorizacaoSubCategoria <> 'Transferência - Extra' ){
                    $TipoGasto[] = $itens->CategorizacaoSubCategoria;
                    $Vl[] = $itens->total;
                }
            }
            

            $meta = meta::with('metaMovimentacao')->where('AtivoMeta','1')->where('familia_id',session('familia'))->get();
            
            $investimento = investimento::with('investimentoMovimentacao')->where('AtivoInvestimento', '1')->where('familia_id',session('familia'))->get();
            
            $fat = faturacartao::
                                with('faturaCartao')
                                ->where('AtivoFatura', '1')
                                ->where('familia_id',session('familia'))
                                ->get();

            //echo($gastoCat);
            //dd($gastoCat);
            return view('sistema.home')->with([
                'mov'=>$mov, 
                'movCard'=>$movCard,
                'div' => $divida, 
                'categoria'=>$categoria, 
                'meses' => $meses, 
                'receitas'=>$receitas, 
                'despesas'=>$despesas, 
                'saldos'=>$saldos, 
                'TipoGasto'=>$TipoGasto,
                'Vl'=>$Vl,
                'meta'=>$meta,
                'investimento'=>$investimento,
                'fat' => $fat,
                'start'=>$startOfMonth,
                'end' => $endOfMonth
            ]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function homeFilter(Request $request)
    {
        if(!empty($request->start)){
            
            if(empty($request->start)){
                $startOfMonth = Carbon::now()->startOfMonth()->toDateString(); // Primeiro dia do mês
            }else{
                $startOfMonth = $request->start;
            }

            if(empty($request->end)){
                $endOfMonth = Carbon::now()->endOfMonth()->toDateString();     // Último dia do mês
            }else{
                $endOfMonth = $request->end;
            }
            //echo($startOfMonth.'<br>');
            //echo($endOfMonth.'<br>');
            //dd($request->all());
            $mov = movimentacaofinanceira::
                                        where('familia_id', session('familia'))
                                        ->where('AtivoMovimentacaoFinanc', '1')
                                        ->where('PagoMovimentacaoFinanc','1')
                                        ->where('FaturaCardMovimentacaoFinanc','0')
                                        ->whereBetween('DataMovimentacaoFinanc', [$startOfMonth, $endOfMonth]) // Filtro entre início e fim do mês
                                        ->get();
            $movCard = movimentacaocartao::
                                        Where('familia_id', session('familia'))
                                        ->where('AtivoMovimentacaoCartao','1')
                                        ->whereBetween('DataPgMovimentacaoCartao', [$startOfMonth, $endOfMonth])
                                        ->get();
            $divida = divida::
                            with('dividaMovimentacaodivida')
                            ->where('AtivoDivida', '1')
                            ->where('familia_id', session('familia'))
                            ->where('VisivelDashDivida','1')
                            ->get();
            
            $categoria = categoria::
                                with([
                                    'categoriaMovFin' => function ($query) use ($startOfMonth, $endOfMonth) {
                                    // Filtra somente registros ativos e dentro do mês atual
                                    $query->where('AtivoMovimentacaoFinanc', '1')
                                          ->where('PagoMovimentacaoFinanc','1')
                                          ->where('TipoMovimentacaoFinanc', 'D')
                                          ->where('FaturaCardMovimentacaoFinanc','0')
                                          ->whereBetween('DataMovimentacaoFinanc', [$startOfMonth, $endOfMonth]);
                                },
                                'categoriaMovCard'=>function ($query) use ($startOfMonth, $endOfMonth){
                                    $query ->where('AtivoMovimentacaoCartao','1')
                                           ->where('familia_id', session('familia'))
                                           ->whereBetween('DataPgMovimentacaoCartao', [$startOfMonth, $endOfMonth]);
                                },
                                'categoriaItensOrcamento'
                            ])
                            ->where('AtivoCategoria', '1')
                            ->where('familia_id', session('familia'))
                            ->where('ShowOrcamentoCategoria','1')
                            ->get();


            $year = Carbon::now()->year;
            // Consulta para obter receitas e despesas por mês do ano atual
            $movGraph = movimentacaofinanceira::select(
                DB::raw('MONTH(DataMovimentacaoFinanc) as mes'),
                DB::raw("SUM(CASE WHEN TipoMovimentacaoFinanc = 'R' THEN ValorFimMovimentacaoFinanc ELSE 0 END) as total_receita"),
                DB::raw("SUM(CASE WHEN TipoMovimentacaoFinanc = 'D' THEN ValorFimMovimentacaoFinanc ELSE 0 END) as total_despesa")
            )
            ->where('familia_id', session('familia'))
            ->where('AtivoMovimentacaoFinanc', '1')
            ->where('PagoMovimentacaoFinanc', '1')
            ->where('FaturaCardMovimentacaoFinanc','0')
            ->whereYear('DataMovimentacaoFinanc', $year) // Filtro para o ano atual
            ->groupBy(DB::raw('MONTH(DataMovimentacaoFinanc)'))
            ->get()
            ->keyBy('mes'); // Agrupando por mês

            $movGraph2 = movimentacaocartao::select(
                DB::raw('MONTH(DataPgMovimentacaoCartao) as mes'),
                DB::raw("SUM(0) as total_receita"),
                DB::raw("SUM(ValorMovimentacaoCartao) as total_despesa")
            )
            ->where('familia_id', session('familia'))
            ->where('AtivoMovimentacaoCartao', '1')
            ->whereYear('DataPgMovimentacaoCartao', $year) // Filtro para o ano atual
            ->groupBy(DB::raw('MONTH(DataPgMovimentacaoCartao)'))
            ->get()
            ->keyBy('mes'); // Agrupando por mês

            $combinedMovGraph = $movGraph->map(function($item) use ($movGraph2) {
                $mes = $item->mes;
                
                // Verificar se o mês existe em $movGraph2 e adicionar as despesas
                if (isset($movGraph2[$mes])) {
                    $item->total_despesa += $movGraph2[$mes]->total_despesa;
                }
            
                return $item;
            });
            
            // Adicionar os meses que existem apenas em $movGraph2
            $movGraph2->each(function($item) use ($combinedMovGraph) {
                if (!$combinedMovGraph->has($item->mes)) {
                    $combinedMovGraph->put($item->mes, $item); // Adicionar os meses restantes
                }
            });
            
            // Ordenar pelo mês
            $combinedMovGraph = $combinedMovGraph->sortBy('mes');
            
            // Formatando o mês para o nome completo
            $combinedMovGraph = $combinedMovGraph->map(function ($item) {
                $item->mes = Carbon::create()->month($item->mes)->format('F');
                return $item;
            });
            

            // Formatar os dados para o gráfico
            $meses = [];
            $receitas = [];
            $despesas = [];
            $saldos = [];

            foreach ($combinedMovGraph as $item) {
                $meses[] = $item->mes; // Número do mês
                $receitas[] = $item->total_receita;
                $despesas[] = $item->total_despesa;
                $saldos[] = $item->total_receita - $item->total_despesa;
            }

            $receitas = array_map(function ($receita) {
                return number_format($receita, 2, ',', '');
            }, $receitas);
            
            $despesas = array_map(function ($despesa) {
                return number_format($despesa, 2, ',', '');
            }, $despesas);
            
            $saldos = array_map(function ($saldo) {
                return number_format($saldo, 2, ',', '');
            }, $saldos);

            
            $gastoCat = DB::table('subcategorias')
            ->join('movimentacaofinanceiras', 'subcategorias.id', '=', 'movimentacaofinanceiras.subcategoria_id')
            ->select('subcategorias.CategorizacaoSubCategoria', DB::raw('SUM(movimentacaofinanceiras.ValorFimMovimentacaoFinanc) as total'))
            ->where('movimentacaofinanceiras.AtivoMovimentacaoFinanc', '1')
            ->where('movimentacaofinanceiras.TipoMovimentacaoFinanc', 'D')
            ->where('movimentacaofinanceiras.PagoMovimentacaoFinanc', '1')
            ->where('movimentacaofinanceiras.FaturaCardMovimentacaoFinanc','0')
            ->where('movimentacaofinanceiras.familia_id', session('familia'))
            ->whereBetween('movimentacaofinanceiras.DataMovimentacaoFinanc', [$startOfMonth, $endOfMonth]) // Filtro entre início e fim do mês
            ->groupBy('subcategorias.CategorizacaoSubCategoria')
            ->get()
            ->keyBy('CategorizacaoSubCategoria'); // Agrupar por subcategoria

            $gastoCatCard = DB::table('subcategorias')
                            ->join('movimentacaocartaos', 'subcategorias.id', '=', 'movimentacaocartaos.subcategoria_id')
                            ->select('subcategorias.CategorizacaoSubCategoria',DB::raw('SUM(movimentacaocartaos.ValorMovimentacaoCartao) as total'))
                            ->where('movimentacaocartaos.AtivoMovimentacaoCartao','1')
                            ->where('movimentacaocartaos.familia_id', session('familia'))
                            ->whereBetween('movimentacaocartaos.DataPgMovimentacaoCartao', [$startOfMonth, $endOfMonth])
                            ->groupBy('subcategorias.CategorizacaoSubCategoria')
                            ->get()
                            ->keyBy('CategorizacaoSubCategoria'); // Agrupar por subcategoria

                            $combinedGastoCat = $gastoCat->map(function ($item) use ($gastoCatCard) {
                                $subcategoria = $item->CategorizacaoSubCategoria;
                                
                                // Verificar se a subcategoria também existe em $gastoCatCard e somar os valores
                                if (isset($gastoCatCard[$subcategoria])) {
                                    $item->total += $gastoCatCard[$subcategoria]->total;
                                }
                            
                                return $item;
                            });
                            
                            // Adicionar as subcategorias que estão apenas em $gastoCatCard
                            $gastoCatCard->each(function ($item) use ($combinedGastoCat) {
                                if (!$combinedGastoCat->has($item->CategorizacaoSubCategoria)) {
                                    $combinedGastoCat->put($item->CategorizacaoSubCategoria, $item); // Adicionar as categorias que não estão presentes
                                }
                            });
                            


            if(empty($combinedGastoCat) || count($combinedGastoCat)<1){
                $TipoGasto[] = null;
                $Vl[] = null;
            }

            foreach($combinedGastoCat as $itens){
                if($itens->CategorizacaoSubCategoria <> 'Transferência - Variável' && $itens->CategorizacaoSubCategoria <> 'Transferência - Fixa' && $itens->CategorizacaoSubCategoria <> 'Transferência - Extra' ){
                    $TipoGasto[] = $itens->CategorizacaoSubCategoria;
                    $Vl[] = $itens->total;
                }
            }
            

            $meta = meta::with('metaMovimentacao')->where('AtivoMeta','1')->where('familia_id',session('familia'))->get();
            
            $investimento = investimento::with('investimentoMovimentacao')->where('AtivoInvestimento', '1')->where('familia_id',session('familia'))->get();
            
            $fat = faturacartao::
                                with('faturaCartao')
                                ->where('AtivoFatura', '1')
                                ->where('familia_id',session('familia'))
                                ->get();

            //echo($gastoCat);
            //dd($gastoCat);
            return view('sistema.home')->with([
                'mov'=>$mov, 
                'movCard'=>$movCard,
                'div' => $divida, 
                'categoria'=>$categoria, 
                'meses' => $meses, 
                'receitas'=>$receitas, 
                'despesas'=>$despesas, 
                'saldos'=>$saldos, 
                'TipoGasto'=>$TipoGasto,
                'Vl'=>$Vl,
                'meta'=>$meta,
                'investimento'=>$investimento,
                'fat' => $fat,
                'start'=>$startOfMonth,
                'end' => $endOfMonth
            ]);
        }else{
            return redirect('/login')->with('msg', 'Você precisa estar logado para fazer essa operação!')->with('icon', 'error')->with('textB', 'Ok')->with('colorB', '#dc3545')->with('title', 'Erro!');
        }
    }

    public function base()
    {
        if(!empty(session('user'))){
            return redirect('/home');
        }else{
            return redirect('/login');
        }
    }
}
