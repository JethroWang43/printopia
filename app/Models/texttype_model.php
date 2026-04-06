<?php
namespace App\Models;

use CodeIgniter\Model;

class texttype_model extends Model {
    protected $table = 'text_type_tbl';
    protected $primaryKey = 'canvas_element_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'text_element','font_size'
    ];

    protected bool $allowEmptyInserts = false;
}
?>