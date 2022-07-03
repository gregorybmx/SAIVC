<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;
    protected $table='proveedores';
    protected $fillable=
    [
    'id',
    'nombre_Proveedor',
    'nombre_Agente',
    'apellidos_Agente',
    'numero_Telefonico_Proveedor',
    'numero_Telefonico_Agente'
    ];

    public function producto (){
        return $this->hasMany('App\Models\Producto');
    }
    public function factura_venta (){
        return $this->hasMany('App\Models\FacturaVenta');
    }
    public function factura_compra (){
        return $this->hasMany('App\Models\FacturaCompra');
    }
}
