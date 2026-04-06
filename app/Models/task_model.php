<?php
namespace App\Models;

use CodeIgniter\Model;

class task_model extends Model {
    protected $table = 'task_tbl';
    protected $primaryKey = 'task_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'order_id','user_id','description','due_date',
        'priority','task_status_id','date_created','date_updated'
    ];

    protected bool $allowEmptyInserts = false;
}
?>