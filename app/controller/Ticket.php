<?php
namespace app\controller;

use app\BaseController;
use app\model\TicketModel;
use app\Request;
use think\facade\View;
use app\common\TicketLink;

class Ticket extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new TicketModel();
    }

    public function index()
    {
        View::assign('title', '工单列表 - 管理中心');
        $list_data = $this->model->list_data();
        View::assign('list', $list_data->items());
        View::assign('pages', $list_data->render());
        return view();
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $point_uid = 0;
            $title = $request->post('title');
            $content = $request->post('content');
            $ticket_type = (int)$request->post('ticket_type');
            $leader_uid = (string)$request->post('leader_uid');
            if (empty($leader_uid)) {
                return json(['error_msg' => '审核人必须选择']);
            }
            $data = [
                'uid' => $this->user_info['id'],
                'title' => $title,
                'content' => $content,
                'ticket_type' => $ticket_type,
                'point_uid' => current(explode(',', $leader_uid)),
                'leader_uid' => $leader_uid,
                'approve_uid' => '',
                'status' => 1,
                'add_time' => time(),
            ];
            $res = $this->model->save($data);
            if ($res) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('title', '添加工单 - 管理中心');
        return view();
    }

    public function judge(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $id = $request->post('id');
            $judge_status = $request->post('judge_status');
            $info = $this->model->where(['id' => $id])->find();
            if (!$info) {
                return json(['error_msg' => '该工单不存在']);
            }
            $info = $info->toArray();
            if ($info['status'] == -1) {
                return json(['error_msg' => '该工单状态被取消']);
            }
            if ($info['status'] == 3) {
                return json(['error_msg' => '该工单状态已结束']);
            }
            if ($info['point_uid'] != $this->user_info['id']) {
                return json(['error_msg' => '该工单目前不能被您审核']);
            }
            $ticket_link = new TicketLink($info['leader_uid'], $info['approve_uid'], $info['point_uid'], $info['status']);
            if ($judge_status) {
                //通过
                $ticket_link->forward();
            } else {
                // 拒绝
                $ticket_link->back();
            }
            $data = [
                'point_uid' => $ticket_link->get_point_uid(),
                'approve_uid' => $ticket_link->get_approve_uid(),
                'status' => $ticket_link->get_status(),
            ];
            $res = $this->model->where(['id' => $id])->update($data);
            if ($res !== false) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '更新失败']);
        }
        $id = $request->get('id');
        $info = $this->model->where(['id' => $id])->find();
        if (!$info) {
            return Redirect('/ticket/index');
        }
        $info = $info->toArray();
        $info['leader_uid'] = array_filter(explode(',', $info['leader_uid']));
        $info['approve_uid'] = array_filter(explode(',', $info['approve_uid']));
        View::assign('title', '审核查看工单 - 管理中心');
        View::assign('info', $info);
        return view();
    }

    public function cancel(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->update(['status' => -1]);
        return Redirect('/ticket/index');
    }
}
