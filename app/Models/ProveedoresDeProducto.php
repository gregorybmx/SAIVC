<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedoresDeProducto extends Model
{
    use HasFactory;
    protected $table='lista_proveedores_productos';
    protected $fillable=['provedor','producto'];

    public function proveedores (){
        return $this->belongsTo('App\Models\Proveedores','proveedor');
    }

    public function productos(){
        return $this->belongsTo('App\Models\Producto','producto');
    }


}
