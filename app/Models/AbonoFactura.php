<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonoFactura extends Model
{
    use HasFactory;
    protected $table='abono_factura';
    protected $fillable=['factura','fechaAbono','saldoAnterior','montoAbono','saldoActual'];
    
    public function factura(){
        return $this->belogsTo('\App\Models\FacturaCompra','factura');
    }



}
