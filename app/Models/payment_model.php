<?php
namespace App\Models;

use CodeIgniter\Model;

class payment_model extends Model {
    protected $table = 'payment_tbl';
    protected $primaryKey = 'payment_status_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'payment_status_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>