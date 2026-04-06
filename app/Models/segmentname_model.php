<?php
namespace App\Models;

use CodeIgniter\Model;

class segmentname_model extends Model {
    protected $table = 'segment_name_tbl';
    protected $primaryKey = 'segment_name_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'segment_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>