<?php
namespace App\Models;

use CodeIgniter\Model;

class employeeroletype_model extends Model {
    protected $table = 'employee_role_type_tbl';
    protected $primaryKey = 'employee_role_type_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'employee_role_type_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>