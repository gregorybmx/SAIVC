<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonoFactura extends Model
{
    use HasFactory;
    protected $table='abono_factura';
    protected $fillable=[
        'factura_compra_id',
        'fechaA_bono',
        'saldo_Anterior',
        'monto_Abono',
        'saldo_Actual'];
    
    public function facturaCompra(){
        return $this->belogsTo('\App\Models\FacturaCompra','factura_compra_id');
    }
}
