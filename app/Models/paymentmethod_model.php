<?php
namespace App\Models;

use CodeIgniter\Model;

class paymentmethod_model extends Model {
    protected $table = 'payment_method_tbl';
    protected $primaryKey = 'payment_method_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'payment_provider_id'
    ];

    protected bool $allowEmptyInserts = false;
}
?>