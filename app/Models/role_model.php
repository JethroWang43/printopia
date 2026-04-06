<?php
namespace App\Models;

use CodeIgniter\Model;

class role_model extends Model {
    protected $table = 'role_tbl';
    protected $primaryKey = 'role_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'role_type_id'
    ];

    protected bool $allowEmptyInserts = false;
}
?>