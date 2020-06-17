<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class CuentaCorrienteDetalle extends Model
{
    protected $table='cuenta_corriente_detalles';

    protected $primaryKey='idcuenta_corriente_detalle';

    protected $fillable=[
        'idcuenta_corriente',
        'idarticulo',
        'cantidad',
        'precio_venta',
        'descuento'
    ];

    public function articulo()
    {
        return $this->hasOne('SisNacho\Articulo', 'idarticulo', 'idarticulo');
    }
}
