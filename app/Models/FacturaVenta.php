<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaVenta extends Model
{
    use HasFactory;
    protected $table = 'factura_venta';
    protected $fillable = ['vendedor', 'fecha_venta','subtotal','iva', 'total'];

    public function facturaVenta()
    {
        return $this -> belongsTo('App\Models\User','id');
    }

    public function detalleFacturasVenta()
    {
        return $this -> hasMany('App\Models\DetalleFacturasVenta', 'id');
    }
}
