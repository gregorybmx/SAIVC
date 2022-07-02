<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonoFactura extends Model
{
    use HasFactory;
    protected $table='abono_factura';
    protected $fillable=['factura','fechaA_bono','saldo_Anterior','monto_Abono','saldo_Actual'];
    
    public function factura(){
        return $this->belogsTo('\App\Models\FacturaCompra','factura');
    }



}
