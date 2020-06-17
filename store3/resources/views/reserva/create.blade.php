@extends('layouts.app')
@section('content')
    <style>
        .rect-checkbox {
            float: left;
            margin-left: 130px;
        }

        .span {
            margin-left: -161px;
        }
    </style>
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3>Nuevo Pedido</h3>
                        @if (count($errors)>0)
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                {!! Form::open(['route' => 'reserva.store', 'method'=>'POST', 'autocomplete' => 'off', 'id'=>'cre', 'onSubmit'=>'return pagos()'])!!}
                {{Form::token()}}
                <div class="row">

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group" id="clidv">
                            <label for="cliente">Cliente</label>
                            <select name="idcliente" class="form-control selectpicker" id="idcliente" required data-live-search="true">
                                @foreach($personas as $persona)
                                    <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Tipo Comprobante</label>
                            <select name="tipo_comprobante" class="form-control">
                                <option value="Factura A">Factura A</option>
                                <option value="Factura B">Factura B</option>
                                <option value="Ticket">Ticket</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="num_comprobante">Número Comprobante</label>
                            @if ($ven == '1')
                                <input type="text" readonly value="0-0" name="num_comprobante" class="form-control"
                                       placeholder="Numero Comprobante">
                            @else
                                <input type="text" readonly value="0-{{$ven->idreserva}}" name="num_comprobante"
                                       class="form-control" placeholder="Numero Comprobante">
                            @endif
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Articulo</label>
                                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo"
                                                data-live-search="true">
                                            <option value="0"></option>
                                            @foreach($articulos as $articulo)
                                                <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_promedio}}">{{$articulo->articulo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad</label>
                                        <input type="number" step=".01" name="pcantidad" id="pcantidad"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" step=".01" disabled name="pstock" id="pstock"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group">
                                        <label for="precio_venta">Precio Venta</label>
                                        <input type="number" step=".01" name="pprecio_venta" id="pprecio_venta"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group">
                                        <label for="descuento">Det. del Pedido</label>
                                        <input type="text" name="pcliente" id="pcliente"
                                               class="form-control" placeholder="Detalle de Pedido ... ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="form-group">
                                        <button type="button" id="bt-add" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Agregar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                                        <table class="table table-striped table-bordered table-condensed table-hover">
                                            <thead style="background-color: #A9D0F5">
                                            <th>Opciones</th>
                                            <th>Artículos</th>
                                            <th>Cantidad</th>
                                            <th>Precio Venta</th>
                                            <th>Det. del Pedido</th>
                                            <th>Subtotal</th>
                                            </thead>
                                            <tbody id="detalles">
                                            </tbody>
                                            <tbody>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><h4 id="total">$ 0.00</h4><input type="hidden" name="total_venta"
                                                                                 id="total_venta"></th>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="form-group">
                                    <h4 style="font-weight: 900;" id="total2"> Total Compra: $ 0.00</h4>
                                </div>
                                @role('admin|vendedor')
                                <div class="form-group" id="pa1">
                                    <label for="tarjeta_debito">Tarjeta de Debito</label>
                                    <input type="number" form="cre" class="form-control" value="0" id="tarjeta_debito"
                                           placeholder="Tarjeta de Debito" min="0" name="pago_debito">
                                </div>
                                <div class="form-group" id="pa2">
                                    <label for="tarjeta_credito">Tarjeta de Credito</label>
                                    <input type="number" form="cre" class="form-control" value="0" id="tarjeta_credito"
                                           placeholder="Tarjeta de Credito" min="0" name="pago_credito">
                                </div>
                                <div class="form-group" id="pa3">
                                    <label for="efectivo">Efectivo</label>
                                    <input type="number" form="cre" class="form-control" value="0" id="paga"
                                           placeholder="Efectivo" min="0" name="pago_efectivo">
                                </div>
                                @endrole
                            </div>
                        </div>
                        <div class="" id="mens">

                        </div>
                        <div class="container-fluid" id="guardar">
                            <div class="form-group">
                                <input form="cre" type="hidden" name="_token" value="{{csrf_token()}}">
                                <button form="cre" class="btn btn-primary pull-left btn-xs btn-flat" type="submit"><i class="fa fa-save"></i> Guardar </button>
                                <button form="cre" class="btn btn-danger pull-right btn-xs btn-flat" type="reset"><i class="fa fa-window-close"></i> Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="idusuario" value="{{Auth::user()->id}}">
                {!!Form::close()!!}
            </div>
        </div>
    </section>
    @push('scripts')
        <script>



            function pagos()
            {
                return true;
            }


        </script>

        <script>
            $(document).ready(function () {
                $('#bt-add').click(function () {
                    agregar();
                });
            });
            var cont = 0;
            total = 0;
            subtotal = [];
            total = 0;
            $("#guardar").hide();
            $("#pidarticulo").change(mostrarValores);

            function mostrarValores() {
                datosArticulo = document.getElementById('pidarticulo').value.split('_');
                $("#pprecio_venta").val(datosArticulo[2]);
                $("#pstock").val(datosArticulo[1]);
            }

            function agregar() {

                var idarticulo = datosArticulo[0];
                var articulo = $("#pidarticulo option:selected").text();
                var cantidad = $("#pcantidad").val();
                var cliente = $("#pcliente").val();
                var precio_venta = parseFloat($("#pprecio_venta").val());
                var stock = $("#pstock").val();
                var stock_numero = parseInt(stock);
                var stock_cantidad = parseInt(cantidad);

                if (idarticulo != "" && cantidad != "" && cantidad > 0 && precio_venta != "") {
                    if (stock_numero >= stock_cantidad) {
                        subtotal[cont] = (cantidad * precio_venta);
                        total = total + subtotal[cont];
                        var fila = '<tr class="selected" id="fila' + cont + '"><td><button type="button" class="btn btn-danger btn-xs" onclick="eliminar(' + cont + ');">X</button></td><td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td><td><input readonly type="number" name="cantidad[]" value="' + cantidad + '"></td><td><input readonly type="number" name="precio_venta[]" value="' + precio_venta + '"></td><td><input readonly type="text" name="cliente[]" value="' + cliente + '"></td><td>' + subtotal[cont] + '</td></tr>';
                        cont++;
                        limpiar();
                        $('#total').html("$ " + total);
                        $('#total2').html("Total Compra: $" + total);
                        $('#total_venta').val(total);
                        evaluar();
                        $('#detalles').append(fila);
                    } else {
                        alert('La cantidad a vender supera el stock');
                    }

                } else {
                    alert("Error al ingresar el detalle de la venta, revise los datos del artículo");
                }
            }

            function limpiar() {
                $('#pcantidad').val("");
                $('#pstock').val("");
                $('#pcliente').val("");
                $('#pprecio_venta').val("");
                $('#pidarticulo').selectpicker('val', '0');
            }

            function evaluar() {
                if (total > 0) {
                    $("#guardar").show();
                } else {
                    $("#guardar").hide();
                }
            }

            function eliminar(index) {
                total = total - subtotal[index];
                $("#total").html("$ " + total);
                $('#total_venta').val(total);
                $("#fila" + index).remove();
                evaluar();
            }
        </script>
    @endpush
@endsection
