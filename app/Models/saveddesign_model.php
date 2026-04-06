<?php
namespace App\Models;

use CodeIgniter\Model;

class saveddesign_model extends Model {
    protected $table = 'saved_design_tbl';
    protected $primaryKey = 'saved_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['user_id','product_id','saved_item_id'];

    protected bool $allowEmptyInserts = false;
}
?>