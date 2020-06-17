<?php

namespace SisNacho;

use Illuminate\Database\Eloquent\Model;


class Venta extends Model
{
    protected $table = 'venta';

    protected $primaryKey = 'idventa';

    protected $fillable = [
        'idcliente',
        'idusuario',
        'tipo_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total_venta',
        'paga',
        'tarjeta_debito',
        'tarjeta_credito',
        'estado',
        'monto_porcentaje',
        'porcentaje_credito'
    ];

        public function detalles()
        {
            return $this->hasMany('SisNacho\DetalleVenta', 'idventa', 'idventa');
        }

        public function cliente()
        {
            return $this->hasOne('SisNacho\Persona', 'idpersona', 'idcliente');
        }
        public function usuario()
        {
            return $this->hasOne('SisNacho\User', 'id', 'idusuario');
        }
}

