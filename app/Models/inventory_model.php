<?php
namespace App\Models;

use CodeIgniter\Model;

class inventory_model extends Model {
    protected $table = 'inventory_tbl';
    protected $primaryKey = 'inventory_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id','stock_qty','reorder_level',
        'date_created','date_updated'
    ];

    protected bool $allowEmptyInserts = false;
}
?>