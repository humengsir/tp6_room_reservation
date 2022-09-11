<?php
namespace app\model;

use think\Model;

class RoomModel extends Model
{
    protected $name = 'room';

    public function check_room_is_use(int $room_id = 0)
    {
        $info = self::where(['id' => $room_id, 'status' => 1])->find();
        if (!$info) {
            return false;
        }
        $info = $info->toArray($info);
        return $info;
    }

    public function check_room_is_active(array $room_info = [], int $start_time = 0, int $end_time = 0)
    {
        if ($room_info['lock_start_time'] < $start_time && $room_info['lock_end_time'] > $end_time) {
            return false;
        }
        return true;
    }
}