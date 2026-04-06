<?php
namespace App\Models;

use CodeIgniter\Model;

class transactiontype_model extends Model {
    protected $table = 'transaction_type_tbl';
    protected $primaryKey = 'transaction_type_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'transaction_type_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>