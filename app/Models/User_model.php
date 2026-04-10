<?php
namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model {
    protected $table = 'users_tbl';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'first_name','middle_name','last_name','email','password',
        'phone_number','role_id','date_created','date_updated'
    ];

    protected bool $allowEmptyInserts = false;
}
?>