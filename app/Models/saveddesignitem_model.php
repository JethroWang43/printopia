<?php
namespace App\Models;

use CodeIgniter\Model;

class saveddesignitem_model extends Model {
    protected $table = 'saved_design_item_tbl';
    protected $primaryKey = 'saved_item_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['3D_design_id','canvas_element_id'];

    protected bool $allowEmptyInserts = false;
}
?>