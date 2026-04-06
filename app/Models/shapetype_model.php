<?php
namespace App\Models;

use CodeIgniter\Model;

class shapetype_model extends Model {
    protected $table = 'shape_type_tbl';
    protected $primaryKey = 'canvas_element_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'shape_type_id','shape_size'
    ];

    protected bool $allowEmptyInserts = false;
}
?>