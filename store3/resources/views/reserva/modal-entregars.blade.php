<div class="modal modal-info" aria-hidden="true" role="dialog" tabindex="-1" id="modal-entregars-{{$venta->idreserva}}">
    {!!Form::model($venta,['route'=>['reserva.reserva.update', $venta->idreserva] , 'method'=>'post'])!!}
    {{Form::token()}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden='true'>x</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-window-close"></i> Entregar Todos Los Pedidos</h4>
            </div>
            <div class="modal-body" style="background-color: #ffffff !important;color: black !important;">
                <h4 style="text-align: center">¿Está segurdo que desea entregar todos los pedidos?</h4>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline pull-left  btn-xs" data-dismiss="modal"><i
                            class="fa fa-window-close"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-outline  btn-xs"><i class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
    {{Form::Close()}}
</div>
