<?php
namespace App\Models;

use CodeIgniter\Model;

class imagetype_model extends Model {
    protected $table = 'image_type_tbl';
    protected $primaryKey = 'canvas_element_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'image_path'
    ];

    protected bool $allowEmptyInserts = false;
}
?>