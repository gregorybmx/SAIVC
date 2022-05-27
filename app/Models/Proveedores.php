<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;
    protected $table='proveedores';
    protected $fillable=['agente_ventas','nombre','numeroTelefono'];

    public function agentevendedor (){
        return $this->hasOne('App\Models\AgenteVendedor','agente_ventas');
    }
}
