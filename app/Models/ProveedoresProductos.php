<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedoresProductos extends Model
{
    use HasFactory;
    protected $table='proveedores_de_productos';
    protected $fillable=['proveedor_id','producto_id'];

    public function proveedorProducto ()
    {
        return $this->hasOne('App\Models\Proveedores','proveedor_id');
    }

    public function productoProveedor ()
    {
        return $this->hasOne('App\Models\Productos','producto_id');
    }
}
