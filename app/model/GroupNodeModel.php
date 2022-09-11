<?php
namespace app\model;

use think\Model;
use think\facade\Db;

class GroupNodeModel extends Model
{
    protected $name = 'group_node';

    public function get_table_name()
    {
        return $this->name;
    }

    public function insert_all_permission(array $data = [])
    {
        return Db::name($this->name)->insertAll($data);
    }
}