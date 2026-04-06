<?php
namespace App\Models;

use CodeIgniter\Model;

class tasknotification_model extends Model {
    protected $table = 'task_notification';
    protected $primaryKey = 'task_notification_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'task_id','user_id','message','created_at','trigger_email'
    ];

    protected bool $allowEmptyInserts = false;
}
?>