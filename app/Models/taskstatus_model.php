<?php
namespace App\Models;

use CodeIgniter\Model;

class taskstatus_model extends Model {
    protected $table = 'task_status_tbl';
    protected $primaryKey = 'task_status_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'task_status_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>