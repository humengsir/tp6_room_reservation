<?php
namespace app\model;

use think\Model;

class ReservationModel extends Model
{
    protected $name = 'reservation';

    public function list_data(array $where = [])
    {
        return self::where($where)->field("id, room_id, uid, start_time, end_time, join_uid, status")->order('id', 'desc')->paginate(10, true);
    }
}