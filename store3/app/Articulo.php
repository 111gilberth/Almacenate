<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table='articulo';

    protected $primaryKey='idarticulo';

    protected $fillable=[
    	'idcategoria',
    	'codigo',
    	'nombre',
    	'stock',
    	'descripcion',
    	'imagen',
    	'estado'
    ];

    public function categorias()
    {
        return $this->hasOne('SisNacho\Categoria', 'idcategoria', 'idcategoria');
    }

    public function detalleIngresos()
    {
        return $this->hasMany('SisNacho\DetalleIngreso', 'idarticulo', 'idarticulo');
    }

    public function detalleVentas()
    {
        return $this->hasMany('SisNacho\DetalleVenta',  'idarticulo', 'idarticulo');
    }

    public function detalleDevoluciones()
    {
        return $this->hasMany('SisNacho\DevolucionDetalle',  'idarticulo', 'idarticulo');
    }
}
