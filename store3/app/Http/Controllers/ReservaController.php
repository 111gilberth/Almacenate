<?php

namespace SisNacho\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use SisNacho\Arqueo;
use SisNacho\ArqueoDetalle;
use SisNacho\ArqueoPago;
use SisNacho\Articulo;
use SisNacho\Persona;
use SisNacho\Reserva;
use SisNacho\ReservaDetalle;
use Yajra\DataTables\Facades\DataTables;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $reserva = Reserva::with('detalles', 'cliente')->get();
        return view('reserva.index', compact('reserva'));
    }

    public function create()
    {
        $personas = Persona::where('tipo_persona', 'Cliente')->get();

        $articulos = Articulo::with('detalleIngresos')
            ->where('estado', 'Activo')
            ->where('stock', '>', '0')
            ->select(DB::raw('CONCAT(articulo.codigo, " ",articulo.nombre) AS articulo'), 'articulo.idarticulo', 'articulo.stock',
                DB::raw('(SELECT precio_venta From detalle_ingreso Where idarticulo = articulo.idarticulo order by iddetalle_ingreso desc limit 0,1)
                 as precio_promedio'))
            ->get();

//        dd($articulos);

        $ven = Reserva::all()->last();
        if ($ven == null) {
            $ven = '1';
            return view("reserva.create", compact('personas', 'articulos', 'ven'));
        } else {
            return view("reserva.create", compact('personas', 'articulos', 'ven'));
        }
    }

    public function store(Request $request)
    {
//        $descuento = $request->get('descuento');


//        dd($request->all());


        $val = Arqueo::where('estado', 'Abierto')->first();
        if ($val == null) {
            toastr()->error('Debe iniciar un arqueo, antes de realizar una venta!', 'Atención');
            return Redirect::back();
        }



        $fecha = Reserva::orderBy('idreserva', 'desc')->first();
        $mytime = Carbon::now('America/Argentina/Mendoza');
        $ventaact = $mytime->toDateString();


        $corriente = Reserva::where('idcliente', $request->idcliente)->where('estado', 'Sin Cancelar')->first();
        $ultimo2 = Reserva::orderBy('idreserva', 'desc')->first();


        if ($corriente == null) {
            $venta = new Reserva();
            $venta->idcliente = $request->get('idcliente');
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->num_comprobante = $request->num_comprobante;
            $venta->total_venta = $request->get('total_venta');
            $venta->pago_efectivo = $request->pago_efectivo;
            $venta->pago_debito = $request->pago_credito;
            $venta->pago_credito = $request->pago_credito;
            $mytime = Carbon::now('America/Argentina/Mendoza');
            $venta->fecha_pedido = $mytime->toDateTimeString();
            $venta->estado = 'Sin Cancelar';
            $venta->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $descuento = $request->get('cliente');
            $precio_venta = $request->get('precio_venta');


            if ($request->pago_efectivo != 0) {
                $pagoss = 'Efectivo';
            }
            if ($request->pago_credito != 0) {
                $pagoss = 'Tarjeta de Debito';
            }
            if ($request->pago_credito != 0) {
                $pagoss = 'Tarjeta de Credito';
            }

            $arqueo = Arqueo::where('estado', 'Abierto')->first();

            $ar = Arqueo::find($arqueo->idarqueo);
            $ar->total_dia = $arqueo->total_dia + $request->total_venta;
            $ar->save();

            $pago = New ArqueoPago();
            $pago->idarqueo = $arqueo->idarqueo;
            $pago->idventa = 0;
            $pago->idingreso = 0;
            $pago->tipo_pago = 'Pedido';

            if ($request->pago_credito == 0 and $request->pago_credito == 0 and $request->pago_efectivo != 0) {
//                efectivo
                $pago->pago_efectivo = $request->pago_efectivo;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            }
            elseif ($request->pago_efectivo == 0) {
//                debito y credito
                $pago->pago_efectivo = $request->pago_efectivo;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            } elseif ($request->pago_efectivo != 0 and $request->pago_credito != 0) {
//                credito y efectivo

                $total = (($request->pago_credito + ($request->pago_efectivo - $request->get('total_venta'))) - $request->pago_efectivo) * -1;

                $pago->pago_efectivo = $total;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            } elseif ($request->pago_efectivo != 0 and $request->pago_credito != 0) {
//                debito y efectivo

                $total = (($request->pago_credito + ($request->pago_efectivo - $request->get('total_venta'))) - $request->pago_efectivo) * -1;

                $pago->pago_efectivo = $total;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            }
            $pago->monto = $request->total_venta;
            $pago->save();

            $cont = 0;

            while ($cont < count($idarticulo)) {

                $detalle = new ReservaDetalle();
                $detalle->idreserva = $venta->idreserva;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->descuento = $descuento[$cont];
                $detalle->estado = 'Pedido';
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $ar = Articulo::find($idarticulo[$cont]);
                $ar->stock = $ar->stock - $cantidad[$cont];
                $ar->save();

                $arde = New ArqueoDetalle();
                $arde->idarqueo = $arqueo->idarqueo;
                $arde->monto = $precio_venta[$cont];
                $arde->cantidad = $cantidad[$cont];
                $arde->tipo_venta = 'Pedido';
                $arde->tipo_pago = $pago;
                $arde->descripcion = 'Se reservó: ' . $ar->nombre;
                $arde->total = $cantidad[$cont] * $precio_venta[$cont];
                $arde->save();

                $cont = $cont + 1;
            }
        } else {
            $corriente = Reserva::where('idcliente', $request->idcliente)->where('estado', 'Sin Cancelar')->first();



            $corrienteCorriente = Reserva::find($corriente->idreserva);
            $corrienteCorriente->total_venta = $request->total_venta + $corriente->total_venta;
            $corrienteCorriente->pago_efectivo = $request->pago_efectivo + $corriente->pago_efectivo;
            $corrienteCorriente->pago_debito = $request->pago_debito + $corriente->pago_debito;
            $corrienteCorriente->pago_credito = $request->pago_credito + $corriente->pago_credito;
            $corrienteCorriente->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $descuento = $request->get('cliente');
            $precio_venta = $request->get('precio_venta');


            if ($request->pago_efectivo != 0) {
                $pago = 'Efectivo';
            }
            if ($request->pago_credito != 0) {
                $pago = 'Tarjeta de Debito';
            }
            if ($request->pago_credito != 0) {
                $pago = 'Tarjeta de Credito';
            }

            $arqueo = Arqueo::where('estado', 'Abierto')->first();

            $ar = Arqueo::find($arqueo->idarqueo);
            $ar->total_dia = $arqueo->total_dia + $request->total_venta;
            $ar->save();

            $pago = New ArqueoPago();
            $pago->idarqueo = $arqueo->idarqueo;
            $pago->idventa = 0;
            $pago->idingreso = 0;
            $pago->tipo_pago = 'Pedido';


            if ($request->pago_credito == 0 and $request->pago_credito == 0 and $request->pago_efectivo != 0) {
//                efectivo
                $pago->pago_efectivo = $request->pago_efectivo;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            } elseif ($request->pago_efectivo == 0) {
//                debito y credito
                $pago->pago_efectivo = $request->pago_efectivo;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            } elseif ($request->pago_efectivo != 0 and $request->pago_credito != 0) {
//                credito y efectivo

                $total = (($request->pago_credito + ($request->pago_efectivo - $request->get('total_venta'))) - $request->pago_efectivo) * -1;

                $pago->pago_efectivo = $total;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            } elseif ($request->pago_efectivo != 0 and $request->pago_credito != 0) {
//                debito y efectivo

                $total = (($request->pago_credito + ($request->pago_efectivo - $request->get('total_venta'))) - $request->pago_efectivo) * -1;

                $pago->pago_efectivo = $total;
                $pago->pago_debito = $request->pago_debito;
                $pago->pago_credito = $request->pago_credito;

            }
            $pago->monto = $request->total_venta;
            $pago->save();

            $cont = 0;

            while ($cont < count($idarticulo)) {
                $detalle = new ReservaDetalle();
                $detalle->idreserva = $corriente->idreserva;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->descuento = $descuento[$cont];
                $detalle->estado = 'Pedido';
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $ar = Articulo::find($idarticulo[$cont]);
                $ar->stock = $ar->stock - $cantidad[$cont];
                $ar->save();

                $arde = New ArqueoDetalle();
                $arde->idarqueo = $arqueo->idarqueo;
                $arde->monto = $precio_venta[$cont];
                $arde->cantidad = $cantidad[$cont];
                $arde->tipo_venta = 'Pedido';
                $arde->tipo_pago = $pago;
                $arde->descripcion = 'Se reservó: ' . $ar->nombre;
                $arde->total = $cantidad[$cont] * $precio_venta[$cont];
                $arde->save();

                $cont = $cont + 1;
            }

        }
        toastr()->success('Su pedido se ha creado correctamente!', 'Atención');
        return Redirect::back();
    }

    public function tabla()
    {

        $ventas = Reserva::with('detalles', 'cliente')->orderBy('idreserva', 'desc')->get();

        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');

        if ($start_date && $end_date) {

            $f1 = Carbon::parse($start_date);
            $f2 = Carbon::parse($end_date);

            $ventas = Reserva::with('detalles', 'cliente')
                ->orderBy('idreserva', 'desc')
                ->where("fecha_pedido", ">=", $f1)
                ->where("fecha_pedido", "<=", $f2)
                ->get();

            $start_date = date('Y-m-d', strtotime($f1));
            $end_date = date('Y-m-d', strtotime($f2));
        }


        $usuario = Auth::user();

        $role = $usuario->roles[0]->name;

        return Datatables::of($ventas)
            ->addColumn('opcion', function ($ar) use ($role) {
                if ($role == 'depocito' or $role == 'corredor')
                {
                    return '
                        
                           <a href="' . route('reserva.show', $ar->idreserva) . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Pedido de: ' . $ar->cliente->nombre . '"  class="fa fa-eye"></i></a>
                        
                       ';
                }
                else
                {
                    return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-delete-' . $ar->idreserva . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Anular Pedido de: ' . $ar->cliente->nombre . '"  class="fa fa-trash"></i></a>
                          <a href="' . route('reserva.show', $ar->idreserva) . '" class="btn btn-xs btn-warning"><i data-toggle="tooltip" title="Mirar Pedido de: ' . $ar->cliente->nombre . '"  class="fa fa-eye"></i></a>
                        </div>
                       ';
                }



            })
            ->editColumn('fecha_pedido', function ($art) {
                return date("d-m-Y", strtotime($art->fecha_pedido));
            })
            ->editColumn('cliente', function ($art) {
                return '<label for="' . $art->cliente->nombre . '" style="text-transform: uppercase">' . $art->cliente->nombre . '</label>';
            })
            ->editColumn('comprobante', function ($art) {
                return $art->tipo_comprobante . ': ' . $art->num_comprobante;
            })
            ->editColumn('total_venta', function ($art) {
                return $art->total_venta . ' $';
            })
            ->editColumn('estado', function ($art) {
                if ($art->estado == "Cancelado") {
                    return '<span class="label label-danger">' . $art->estado . '</span>';
                } else {
                    return '<span class="label label-info">' . $art->estado . '</span>';
                }
            })
            ->rawColumns(['opcion', 'cliente', 'fecha', 'comprobante', 'estado'])
            ->make(true);
    }

    public function show($id)
    {
        $venta = Reserva::with('detalles.articulo', 'cliente', 'usuario')->where('idreserva', $id)->orderBy('idreserva', 'desc')->first();
        return view("reserva.show", compact('venta'));

    }

    public function update(Request $request, $id)
    {

        $detalle = ReservaDetalle::find($id);
        if ($request->estado == 'Pedido')
        {
            $detalle->estado = 'Entregado';
        }
        else
        {
            $detalle->estado = 'Pedido';
        }

        $detalle->save();

        toastr()->success('Su pedido se ha entregado correctamente!', 'ENTREGADO');
        return Redirect::back();
    }

    public function updateReserva($id)
    {
        ReservaDetalle::where('idreserva',$id)->update(["estado" => 'Entregado']);
        toastr()->info('Sus pedidos se han entregado correctamente!', 'ENTREGADOS');
        return Redirect::back();
    }

    public function updateReservaPago(Request $request, $id)
    {
        $venta = Reserva::find($id);
        $venta->pago_efectivo = $request->pago_efectivo + $venta->pago_efectivo;
        $venta->pago_debito = $request->pago_debito + $venta->pago_debito;
        $venta->pago_credito = $request->pago_credito + $venta->pago_credito;
        $venta->save();



        $arqueo = Arqueo::where('estado', 'Abierto')->first();

        $ar = Arqueo::find($arqueo->idarqueo);
        $ar->total_dia = $arqueo->total_dia + $request->pago_efectivo + $request->pago_debito + $request->pago_credito;
        $ar->save();

        $pago = New ArqueoPago();
        $pago->idarqueo = $arqueo->idarqueo;
        $pago->idventa = 0;
        $pago->idingreso = 0;
        $pago->tipo_pago = 'Pedido';


        if ($request->pago_credito == 0 and $request->pago_credito == 0 and $request->pago_efectivo != 0) {
//                efectivo
            $pago->pago_efectivo = $request->pago_efectivo;
            $pago->pago_debito = $request->pago_debito;
            $pago->pago_credito = $request->pago_credito;

        } elseif ($request->pago_efectivo == 0) {
//                debito y credito
            $pago->pago_efectivo = $request->pago_efectivo;
            $pago->pago_debito = $request->pago_debito;
            $pago->pago_credito = $request->pago_credito;

        } elseif ($request->pago_efectivo != 0 and $request->pago_credito != 0) {
//                credito y efectivo

            $total = $request->pago_credito + $request->pago_efectivo;

            $pago->pago_efectivo = $total;
            $pago->pago_debito = $request->pago_debito;
            $pago->pago_credito = $request->pago_credito;

        } elseif ($request->pago_efectivo != 0 and $request->pago_credito != 0) {
//                debito y efectivo

            $total = $request->pago_credito + $request->pago_debito;

            $pago->pago_efectivo = $total;
            $pago->pago_debito = $request->pago_debito;
            $pago->pago_credito = $request->pago_credito;

        }
        $pago->monto = $request->pago_efectivo + $request->pago_debito + $request->pago_credito;
        $pago->save();



        toastr()->info('Su pago se ha acreditado correctamente!', 'PAGO');
        return Redirect::back();

    }
    public function destroy($id)
    {
        $venta = Reserva::find($id);
        $venta->estado = 'Cancelado';
        $venta->save();
        toastr()->error('Su pedido se ha cancelado correctamente!', 'Atención');
        return Redirect::back();
    }
}
