<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $primaryKey = 'idreserva';

    protected $fillable = [
        'iduser',
        'idcliente',
        'fecha_pedido',
        'fecha_entrega',
        'estado',
        'tipo_comprobante',
        'num_comprobante',
        'total_venta',
        'pago_efectivo',
        'pago_debito',
        'pago_credito',
    ];

    public function detalles()
    {
        return $this->hasMany('SisNacho\ReservaDetalle', 'idreserva', 'idreserva');
    }
    public function usuario()
    {
        return $this->hasOne('SisNacho\User', 'id', 'iduser');
    }
    public function cliente()
    {
        return $this->hasOne('SisNacho\Persona', 'idpersona', 'idcliente');
    }
}
