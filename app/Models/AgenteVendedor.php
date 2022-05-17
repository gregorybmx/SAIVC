<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenteVendedor extends Model
{
    use HasFactory;
    protected $table='agentes_vendedores';
    protected $fillable=['id','nombre','apellidos','telefono'];

    public function proveedores(){
        return $this->hasOne('App\Models\Proveedores');
    }
}
