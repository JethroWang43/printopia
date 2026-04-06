<?php
namespace App\Models;

use CodeIgniter\Model;

class paymentprovider_model extends Model {
    protected $table = 'payment_provider_tbl';
    protected $primaryKey = 'payment_provider_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'payment_provider_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>