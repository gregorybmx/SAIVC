<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedoresProductos extends Model
{
    use HasFactory;
    protected $table='proveedores_productos';
    protected $fillable=['proveedor','producto'];

    public function proveedorProducto ()
    {
        return $this->hasOne('App\Models\Proveedores','proveedor');
    }

    public function productoProveedor ()
    {
        return $this->hasOne('App\Models\Productos','producto');
    }
}
