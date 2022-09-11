<?php
namespace app\controller;

use app\BaseController;
use app\model\RoomModel;
use app\Request;
use think\facade\View;
use think\response\Redirect;

class Room extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new RoomModel();
    }

    public function index()
    {
        View::assign('title', '会议室列表 - 管理中心');
        View::assign('list', get_all_room());
        return view();
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $room_name = $request->post('room_name');
            $lock_start_time = (int)strtotime($request->post('lock_start_time'));
            $lock_end_time = (int)strtotime($request->post('lock_end_time'));
            if ($lock_end_time < $lock_start_time) {
                return json(['error_msg' => '结束时间必须大于开始时间']);
            }
            $room_people_num = (int)$request->post('room_people_num');
            $status = (int)$request->post('status');
            $data = compact('room_name', 'lock_start_time', 'lock_end_time', 'room_people_num', 'status');
            $res = $this->model->save($data);
            if ($res) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('title', '添加会议室 - 管理中心');
        return view();
    }

    public function edit(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $id = $request->post('id');
            $room_name = $request->post('room_name');
            $lock_start_time = (int)strtotime($request->post('lock_start_time'));
            $lock_end_time = (int)strtotime($request->post('lock_end_time'));
            if ($lock_end_time < $lock_start_time) {
                return json(['error_msg' => '结束时间必须大于开始时间']);
            }
            $room_people_num = (int)$request->post('room_people_num');
            $status = (int)$request->post('status');
            $data = compact('room_name', 'lock_start_time', 'lock_end_time', 'room_people_num', 'status');
            if ($status == 0) {
                // 当前会议室必须无人使用才能禁用
            }
            $res = $this->model->where(['id' => $id])->update($data);
            if ($res !== false) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '更新失败']);
        }
        $id = $request->get('id');
        $info = $this->model->where(['id' => $id])->find();
        if (!$info) {
            return Redirect('/room/index');
        }
        $info = $info->toArray();
        View::assign('title', '编辑会议室 - 管理中心');
        View::assign('info', $info);
        return view();
    }
}
