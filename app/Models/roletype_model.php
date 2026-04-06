<?php
namespace App\Models;

use CodeIgniter\Model;

class roletype_model extends Model {
    protected $table = 'role_type_tbl';
    protected $primaryKey = 'role_type_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'role_type_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>