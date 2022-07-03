<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFacturasVenta extends Model
{
    use HasFactory;
    protected $table = 'detalle_factura_ventas';
    protected $fillable = [
        'factura_venta_id', 
        'producto_id',
        'descripcion', 
        'cantidad', 
        'precio_Unitario', 
        'subtotal'];

    public  function facturaVenta()
    {
        return $this -> belongsTo('App\Models\FacturaVenta', 'factura_venta_id');
    }

    public  function producto()
    {
        return $this -> belongsTo('App\Models\Producto', 'producto_id');
    }
}
