<?php
namespace App\Models;

use CodeIgniter\Model;

class elementtype_model extends Model {
    protected $table = 'element_type_tbl';
    protected $primaryKey = 'element_type_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['element_type_name'];

    protected bool $allowEmptyInserts = false;
}
?>