<?php
namespace App\Models;

use CodeIgniter\Model;

class shapetypename_model extends Model {
    protected $table = 'shape_type_name_tbl';
    protected $primaryKey = 'shape_type_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'shape_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>