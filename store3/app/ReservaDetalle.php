<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class ReservaDetalle extends Model
{
    protected $table='reserva_detalles';

    protected $primaryKey='idreserva_detalle';

    protected $fillable=[
        'idreserva',
        'idarticulo',
        'cantidad',
        'precio_venta',
        'descuento',
        'estado'
    ];

    public function articulo()
    {
        return $this->hasOne('SisNacho\Articulo', 'idarticulo', 'idarticulo');
    }
}
