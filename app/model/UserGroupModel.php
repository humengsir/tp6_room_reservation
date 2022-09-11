<?php
namespace app\model;

use think\Model;
use think\facade\Db;

class UserGroupModel extends Model
{
    protected $name = 'user_group';

    public function insert_many_group(array $data = [])
    {
        return Db::name($this->name)->insertAll($data);
    }
}