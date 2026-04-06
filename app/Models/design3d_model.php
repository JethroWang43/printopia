<?php
namespace App\Models;

use CodeIgniter\Model;

class design3d_model extends Model {
    protected $table = '3D_design_tbl';
    protected $primaryKey = '3D_design_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['product_id','color','image','text'];

    protected bool $allowEmptyInserts = false;
}
?>