<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table='devolucions';

    protected $primaryKey='iddevolucion';

    protected $fillable=[
        'num_factura',
        'idcliente',
        'fecha_devolucion',
        'num_comprobante',
    ];

    public function personas()
    {
        return $this->hasOne('SisNacho\Persona', 'idpersona', 'idcliente');
    }
}