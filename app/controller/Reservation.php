<?php
namespace app\controller;

use app\BaseController;
use app\model\ReservationModel;
use app\model\RoomModel;
use app\Request;
use think\facade\View;
use think\response\Redirect;

class Reservation extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new ReservationModel();
    }

    public function index()
    {
        View::assign('title', '预约列表 - 管理中心');
        $list_data = $this->model->list_data();
        View::assign('list', $list_data->items());
        View::assign('pages', $list_data->render());
        return view();
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $room_id = (int)$request->post('room_id');
            $start_time = (int)strtotime($request->post('start_time'));
            $end_time = (int)strtotime($request->post('end_time'));
            if ($start_time < 1 || $end_time < 1) {
                return json(['error_msg' => '开始时间和结束时间必须设置']);
            }
            if ($end_time < $start_time) {
                return json(['error_msg' => '结束时间必须大于开始时间']);
            }
            $room_model = new RoomModel();
            $room_info = $room_model->check_room_is_use($room_id);
            if (!$room_info) {
                return json(['error_msg' => '该会议室不可用']);
            }
            $is_active = $room_model->check_room_is_active($room_info, $start_time, $end_time);
            if (!$is_active) {
                return json(['error_msg' => '该会议室在您预约的这段时间不可用']);
            }
            $join_uid = (string)$request->post('join_uid');
            $join_uid = explode(",", $join_uid);
            $join_uid = implode(",", array_unique(array_filter($join_uid)));
            $uid = $this->user_info['id'];
            $data = compact('room_id', 'uid', 'start_time', 'end_time', 'join_uid');
            $res = $this->model->save($data);
            if ($res) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('title', '添加预约 - 管理中心');
        return view();
    }

    public function cancel(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->update(['status' => 0]);
        return Redirect('/reservation/index');
    }

    public function completed(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->update(['status' => 2]);
        return Redirect('/reservation/index');
    }
}
