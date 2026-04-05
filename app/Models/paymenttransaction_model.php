<?php
namespace App\Models;

use CodeIgniter\Model;

class paymenttransaction_model extends Model {
    protected $table = 'payment_transaction_tbl';
    protected $primaryKey = 'transaction_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'order_id','payment_method_id','amount',
        'transaction_type_id','reference_number',
        'paid_at','proof_of_payment'
    ];

    protected bool $allowEmptyInserts = false;
}
?>