<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaVenta extends Model
{
    use HasFactory;
    protected $table = 'factura_venta';
    protected $fillable = 
    ['user_id', 
    'subtotal',
    'iva', 
    'total'];

    public function vendedor()
    {
        return $this -> belongsTo('App\Models\User','user_id');
    }

    public function detalleFactura()
    {
        return $this -> hasMany('App\Models\DetalleFacturasVenta');
    }
}
