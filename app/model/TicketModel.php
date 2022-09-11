<?php
namespace app\model;

use think\Model;

class TicketModel extends Model
{
    protected $name = 'ticket';

    public function list_data(array $where = [])
    {
        return self::where($where)->field("id, uid, title, content, ticket_type, point_uid, leader_uid, approve_uid, status, add_time")->order('id', 'desc')->paginate(10, true);
    }
}