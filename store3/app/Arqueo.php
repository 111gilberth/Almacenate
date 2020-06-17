<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class Arqueo extends Model
{
    protected $table='arqueos';

    protected $primaryKey='idarqueo';

    protected $fillable=[
        'usuario',
        'fecha_hora',
        'descripcion',
        'estado',
        'total_dia'
    ];

    public function detalle()
    {
        return $this->hasMany('SisNacho\ArqueoDetalle', 'idarqueo', 'idarqueo');
    }
        public function pago()
    {
        return $this->hasMany('SisNacho\ArqueoPago', 'idarqueo', 'idarqueo');
    }
}
