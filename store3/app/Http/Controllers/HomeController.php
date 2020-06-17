<?php

namespace SisNacho\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use SisNacho\Articulo;
use SisNacho\Presupuesto;
use SisNacho\EstadisticasVentas;
use SisNacho\Config;
use  Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function avisos()
    {
    
      $aviso= Articulo::orderBy('stock', 'asc')->get();

      $estadistica = DB::table('estadistica_venta as es')
       ->join('articulo as a','es.idarticulo','=','a.idarticulo')
       ->limit(7)
       ->get();
      $promedioventa = DB::table('presupuesto')
                    ->orderBy('fecha_hora', 'asc')
                    ->limit(7)
                    ->get();

      return view('index' ,['aviso'=>$aviso, 'estadistica'=>$estadistica, 'promedioventa'=>$promedioventa]);
    }

}
