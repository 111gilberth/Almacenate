<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class Estimacion extends Model
{
  protected $table='estimacion';

  protected $primaryKey='idestimacion';

  protected $fillable=[
    'idusuario',
    'fecha_hora',
    'impuesto',
    'total_venta',
    'estado'
  ];
}
