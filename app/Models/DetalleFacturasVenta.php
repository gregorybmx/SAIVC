<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFacturasVenta extends Model
{
    use HasFactory;
    protected $table = 'detalle_factura_ventas';
    protected $fillable = [
        'factura', 
        'producto', 
        'cantidad', 
        'precio_Unitario', 
        'precio_Total'];

    public  function facturaVenta()
    {
        return $this -> belongsTo('App\Models\FacturaVenta', 'numeroFactura');
    }

    public  function producto()
    {
        return $this -> belongsTo('App\Models\Producto', 'codigo');
    }
}
