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

    public function check_room_active(int $room_id = 0)
    {
        $map['room_id'] = $room_id;
        $map['status'] = 1;
        $now = time();
        $list = self::where($map)->where('start_time', '<', $now)->where('end_time', '>', $now)->select()->toArray();
        return count($list);
    }
}