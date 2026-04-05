<?php
namespace App\Models;

use CodeIgniter\Model;

class orderstatus_model extends Model {
    protected $table = 'order_status_tbl';
    protected $primaryKey = 'order_status_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'order_status_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>