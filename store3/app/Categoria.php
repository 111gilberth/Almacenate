<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table='categoria';

    protected $primaryKey='idcategoria';

    protected $fillable=[
    	'nombre',
    	'descripcion',
    	'condicion'
    ];

}
