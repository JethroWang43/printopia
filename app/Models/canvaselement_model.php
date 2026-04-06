<?php
namespace App\Models;

use CodeIgniter\Model;

class canvaselement_model extends Model {
    protected $table = 'canvas_elements_tbl';
    protected $primaryKey = 'canvas_element_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'element_type_id','position_x','position_y',
        'width','height','rotation','color','created_at'
    ];

    protected bool $allowEmptyInserts = false;
}
?>