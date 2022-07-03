<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaCompra extends Model
{
    use HasFactory;
    protected $table='factura_compra';
    protected $fillable=[
        'id',
        'proveedor_id',
        'fecha_Compra',
        'fecha_Vencimiento',
        'monto_Total'];

    public function proveedor(){
        return $this->belongsTo('App\Models\Proveedores','proveedor_id');
    }

    public function abonoFactura(){
        return $this->hasMany('App\Models\AbonoFactura');
    }

}
