<?php
namespace App\Models;

use CodeIgniter\Model;

class employeerole_model extends Model {
    protected $table = 'employee_role_tbl';
    protected $primaryKey = 'employee_role_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'role_id','employee_role_type_id'
    ];

    protected bool $allowEmptyInserts = false;
}
?>