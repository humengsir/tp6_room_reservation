<?php
namespace app\controller;

use app\BaseController;
use app\model\NodeModel;
use app\Request;
use think\facade\View;
use think\response\Redirect;

class Node extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new NodeModel();
    }

    public function index()
    {
        View::assign('title', '权限列表 - 管理中心');
        View::assign('list', get_all_node());
        return view();
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $node_name = $request->post('node_name');
            $node_url = $request->post('node_url');
            $node_sort = $request->post('node_sort');
            $node_type = (int)$request->post('node_type');
            $status = (int)$request->post('status');
            $data = compact('node_name', 'node_url', 'node_sort', 'node_type', 'status');
            $res = $this->model->save($data);
            if ($res) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('title', '添加权限 - 管理中心');
        return view();
    }

    public function edit(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $id = $request->post('id');
            $node_name = $request->post('node_name');
            $node_url = $request->post('node_url');
            $node_sort = $request->post('node_sort');
            $node_type = (int)$request->post('node_type');
            $status = (int)$request->post('status');
            $data = compact('node_name', 'node_url', 'node_sort', 'node_type', 'status');
            $res = $this->model->where(['id' => $id])->update($data);
            if ($res !== false) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '更新失败']);
        }
        $id = $request->get('id');
        $info = $this->model->where(['id' => $id])->find();
        if (!$info) {
            return Redirect('/node/index');
        }
        $info = $info->toArray();
        View::assign('title', '编辑权限 - 管理中心');
        View::assign('info', $info);
        return view();
    }

    public function del(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->update(['status' => 0]);
        return Redirect('/node/index');
    }
}
