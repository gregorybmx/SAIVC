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
        'precio_compra',
        'porcentaje_ganancia',
        'precio_venta',
        'cantidadMinima',
        'stock'
];

public function detalleFacturaVenta(){
    return $this->hasMany('App\Models\DetalleFacturasVenta');
}


}
