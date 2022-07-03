<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table='productos';

    protected $fillable=[
        'id',
        'descripcion',
        'porcentaje_Ganancia',
        'precio_Venta',
        'cantidad_Minima',
        'stock'
];

    public function detalleFacturaVenta()
    {
        return $this->hasMany('App\Models\DetalleFacturasVenta');
    }

    public function proveedorProducto()
    {
        return $this->hasMany('App\Models\ProveedoresProductos');
    }
}
