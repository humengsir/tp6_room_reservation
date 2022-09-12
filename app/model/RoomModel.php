<?php
namespace app\model;

use think\facade\Db;
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

    public function check_room_is_active($reservation_model, array $room_info = [], int $start_time = 0, int $end_time = 0)
    {
        if ($room_info['lock_start_time'] > 0 && $room_info['lock_end_time'] > 0) {
            // 锁定还没开始
            if ($end_time < $room_info['lock_start_time']) {
                return true;
            }
            // 锁定已经过了
            if ($start_time > $room_info['lock_end_time']) {
                return true;
            }
        }
        // 判断该会议室是否已经被别人使用了
        $where1 = [
            ['start_time', '>=', $start_time],
            ['end_time', '<=', $end_time],
            ['status', '=', 1],
        ];
        $where2 = [
            ['start_time', '<=', $start_time],
            ['end_time', '>=', $start_time],
            ['status', '=', 1],
        ];
        $where3 = [
            ['start_time', '>=', $start_time],
            ['start_time', '<=', $end_time],
            ['status', '=', 1],
        ];
        $use_list = Db::table($reservation_model->getTablename())->whereOr([$where1, $where2, $where3])->select()->toArray();
        if (!$use_list) {
            return true;
        }
        return false;
    }
}