<?php
namespace App\Models;

use CodeIgniter\Model;

class inventory_model extends Model {
    protected $table = 'inventory_tbl';
    protected $primaryKey = 'inventory_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'product_name','stock_qty','reorder_level',
        'description','date_created','date_updated'
    ];

    protected bool $allowEmptyInserts = false;
}
?>