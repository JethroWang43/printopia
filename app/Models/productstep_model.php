<?php
namespace App\Models;

use CodeIgniter\Model;

class productstep_model extends Model {
    protected $table = 'product_step_tbl';
    protected $primaryKey = 'product_step_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id','product_id','step_name','assigned_to'
    ];

    protected bool $allowEmptyInserts = false;
}
?>