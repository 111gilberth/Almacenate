<div class="modal fade modal-info" id="modal-pagos-{{$venta->idreserva}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Pagos del Cliente: {{$venta->cliente->nombre}}</h5>
            </div>
            <div style="overflow-y: auto !important;background-color: #ffffff !important;color: black !important;" class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <th>Pagos Del Efectivo</th>
                        <th>Pagos De Crédito</th>
                        <th>Pagos De Débito</th>
                        <th>Total Pagado</th>
                    </thead>
                    <tbody>
                        <td>{{$venta->pago_efectivo}} $</td>
                        <td>{{$venta->pago_credito}} $</td>
                        <td>{{$venta->pago_debito}} $</td>
                        <td>{{number_format($venta->pago_efectivo+$venta->pago_credito+$venta->pago_debito, 2, '.', '')}} $</td>
                    </tbody>
                </table>
                {!!Form::model($venta,['route'=>['reserva.pago.update', $venta->idreserva] , 'id'=>'edit-'.$venta->idreserva.'', 'method'=>'post', 'enctype'=>'multipart/form-data'])!!}
                    <table class="table table-bordered">
                        <thead>
                            <th>Pagos Del Efectivo</th>
                            <th>Pagos De Crédito</th>
                            <th>Pagos De Débito</th>
                        </thead>
                        <tbody>
                            <td><input type="number" step=".01" min="0" value="0.00" name="pago_efectivo" class="form-control"></td>
                            <td><input type="number" step=".01" min="0" value="0.00" name="pago_credito" class="form-control"></td>
                            <td><input type="number" step=".01" min="0" value="0.00" name="pago_debito" class="form-control"></td>
                        </tbody>
                    </table>
                {!!Form::close()!!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" form="edit-{{$venta->idreserva}}" class="btn btn-outline  btn-xs"><i
                            class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>