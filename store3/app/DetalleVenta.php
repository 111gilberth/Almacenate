<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
	protected $table='detalle_venta';

    protected $primaryKey='iddetalle_venta';

    protected $fillable=[
    	'idventa',
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
