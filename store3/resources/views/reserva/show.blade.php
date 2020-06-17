@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <div class="container-fluid">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label for="proveedor">Cliente</label>
                            <p>{{$venta->cliente->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label for="fecha_hora">Fecha del Pedido</label>
                            <p>{{date("d-m-Y", strtotime($venta->fecha_pedido))}}</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label>Tipo Comprobante</label>
                            <p>{{$venta->tipo_comprobante}}</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label for="num_comprobante">Número Comprobante</label>
                            <p>{{$venta->num_comprobante}}</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group">
                            <label for="num_comprobante">Total Venta</label>
                            <p>{{$venta->total_venta}} $</p>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="row table-responsive">
                            <table class="table table-bordered">
                                <thead style="background-color: #A9D0F5">
                                @role('admin|depocito|vendedor')
                                <th>Opción</th>
                                @endrole
                                <th>Fecha Pedido</th>
                                <th>Fecha Entregado</th>
                                <th>Estado</th>
                                <th>Artículos</th>
                                <th>Cantidad</th>
                                <th>Precio Venta</th>
                                <th>Det. del Pedido</th>
                                <th>Subtotal</th>
                                </thead>
                                <tbody>
                                @foreach($venta->detalles as $det)
                                    <tr style="{{ $det->estado == 'Pedido' ? 'background: #bcf312a6;' : '' }} {{ $det->estado === 'Entregado' ? 'background-color: #59f77d;' : "" }}">
                                        @role('admin|depocito|vendedor')
                                        <td>
                                            @if ($det->estado == 'Pedido')
                                                <a data-toggle="modal"
                                                   data-target="#modal-entrega-{{$det->idreserva_detalle}}"
                                                   class="btn btn-xs btn-warning">
                                                    <i data-toggle="tooltip"
                                                       title="Entregar Pedido de: {{$det->articulo->nombre}}"
                                                       class="fa fa-window-close"> Entregar Pedido</i>
                                                </a>
                                            @else
                                                <a data-toggle="modal"
                                                   data-target="#modal-entrega-{{$det->idreserva_detalle}}"
                                                   class="btn btn-xs btn-success">
                                                    <i data-toggle="tooltip"
                                                       title="Pedido Entregado de: {{$det->articulo->nombre}}"
                                                       class="fa fa-check"> Entregado</i>
                                                </a>
                                            @endif

                                        </td>
                                        @endrole
                                        <td>{{date("d-m-Y", strtotime($det->created_at))}}</td>
                                        <td>{{date("d-m-Y", strtotime($det->updated_at))}}</td>

                                        <td>
                                            @if ($det->estado == 'Pedido')
                                                <span class="label label-warning">{{$det->estado}}</span>
                                            @else
                                                <span class="label label-success">{{$det->estado}}</span>
                                            @endif
                                        </td>
                                        <td>{{$det->articulo->nombre}}</td>
                                        <td class="text-derecha">{{$det->cantidad}}</td>
                                        <td class="text-derecha">{{$det->precio_venta}}$</td>
                                        <td class="text-derecha" style="font-weight: bolder;">{{$det->descuento}}
                                        </td>
                                        <td class="text-derecha">{{number_format($det->cantidad*$det->precio_venta, 2, '.', '')}}</td>
                                    </tr>
                                    @include('reserva.modal-entrega')
                                @endforeach
                                </tbody>

                                <tbody>
                                <th>
                                    @role('admin|depocito|vendedor')
                                    <a data-toggle="modal" data-target="#modal-entregars-{{$venta->idreserva}}"
                                       class="btn btn-xs btn-info">
                                        <i data-toggle="tooltip" title="Aceptar a Todos" class="fa fa-window-close">
                                            Entregar Todos Pedido</i>
                                    </a>
                                    @include('reserva.modal-entregars')
                                    @endrole
                                </th>
                                @role('admin|depocito|vendedor')
                                <th></th>
                                @endrole
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-derecha">{{number_format($venta->detalles->sum('cantidad'), 2, '.', '')}}</th>
                                <th class="text-derecha"></th>
                                <th></th>
                                <th class="text-derecha">{{number_format($venta->total_venta, 2, '.', '')}}$</th>
                                </tbody>


                                <tbody>
                                <th>
                                    
                                    <a data-toggle="modal" data-target="#modal-pagos-{{$venta->idreserva}}"
                                       class="btn btn-xs btn-info">
                                        <i data-toggle="tooltip" title="Aceptar a Todos" class="fa fa-window-close">
                                            Pagos</i>
                                    </a>
                                    @include('reserva.modal-pagos')
                                    
                                </th>
                                <th></th>
                                @role('admin|depocito|vendedor')
                                <th></th>
                                @endrole
                                <th></th>
                                <th></th>
                                <th class="text-derecha"><h5>Debito: {{$venta->pago_debito}} $</h5></th>
                                <th class="text-derecha"><h5>Credito: {{$venta->pago_credito}} $</h5></th>
                                <th class="text-derecha"><h5>Efectivo: {{$venta->pago_efectivo}} $</h5></th>
                                <th class="text-derecha"><h4>
                                        Total: {{$venta->pago_efectivo + $venta->pago_credito + $venta->pago_debito}}
                                        $</h4></th>

                                </tbody>
                                <tbody>
                                <th>SALDO PENDIENTE</th>
                                <th></th>
                                <th></th>
                                @role('admin|depocito|vendedor')
                                <th></th>
                                @endrole
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-derecha">
                                    <h4>{{number_format($venta->pago_efectivo + $venta->pago_credito + $venta->pago_debito - $venta->total_venta, 2, '.', '')}}</h4>
                                </th>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div>
                    {{--<a href="{{route('pdf.venta',$venta->idventa)}}" class="btn btn-xs btn-info pull-left"><i class="fa fa-print"></i> Descargar PDF--}}
                    {{--</a>--}}
                </div>
            </div>
        </div>
    </section>

@endsection
