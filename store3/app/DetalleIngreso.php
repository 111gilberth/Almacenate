<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table='detalle_ingreso';

    protected $primaryKey='iddetalle_ingreso';

    protected $fillable=[
    	'idingreso',
    	'idarticulo',
    	'cantidad',
    	'precio_compra',
    	'precio_venta'
    ];

    public function articulo()
    {
        return $this->hasOne('SisNacho\Articulo', 'idarticulo', 'idarticulo');
    }
}
