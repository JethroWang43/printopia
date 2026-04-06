<?php
namespace App\Models;

use CodeIgniter\Model;

class ordernotification_model extends Model {
    protected $table = 'order_notification_tbl';
    protected $primaryKey = 'order_notification';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'order_id','user_id','message','created_at','trigger_email'
    ];

    protected bool $allowEmptyInserts = false;
}
?>