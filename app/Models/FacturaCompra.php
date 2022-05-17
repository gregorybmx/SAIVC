<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaCompra extends Model
{
    use HasFactory;
    protected $table='factura_compra';
    protected $fillable=['id','proveedor','fecha_compra','fecha_vencimiento','monto_total'];

    public function proveedor(){
        return $this->belongsTo('App\Models\Proveedores','proveedor');
    }

    public function abonoFactura(){
        return $this->hasMany('App\Models\AbonoFactura');
    }

}
