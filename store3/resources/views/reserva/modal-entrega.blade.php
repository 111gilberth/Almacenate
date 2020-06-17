@if ($det->estado == 'Pedido')
    <div class="modal modal-warning" aria-hidden="true" role="dialog" tabindex="-1"
     id="modal-entrega-{{$det->idreserva_detalle}}">
@else
    <div class="modal modal-success" aria-hidden="true" role="dialog" tabindex="-1"
             id="modal-entrega-{{$det->idreserva_detalle}}">
@endif
        {!!Form::model($det,['route'=>['reserva.detalle.update', $det->idreserva_detalle] , 'method'=>'post'])!!}
        {{Form::token()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden='true'>x</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-window-close"></i> Entrega del
                        Producto: {{$det->articulo->nombre}}</h4>
                </div>
                <div class="modal-body" style="background-color: #ffffff !important;color: black !important;">
                    <h4 style="text-align: center">¿Está segurdo que desea entregar {{intval($det->cantidad)}}
                        del producto {{$det->articulo->nombre}}?</h4>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                                class="fa fa-window-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline  btn-xs"><i class="fa fa-save"></i> Guardar
                    </button>
                </div>
                <input type="hidden" value="{{$det->estado}}" name="estado">
            </div>
        </div>
        {{Form::Close()}}
    </div>
