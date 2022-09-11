<?php
namespace app\controller;

use app\BaseController;
use app\model\GroupModel;
use app\Request;
use think\facade\View;
use think\response\Redirect;

class Group extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new GroupModel();
    }

    public function index()
    {
        View::assign('title', '用户组列表 - 管理中心');
        View::assign('list', get_all_group());
        return view();
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $group_name = $request->post('group_name');
            $res = $this->model->save(['name' => $group_name]);
            if ($res) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('title', '添加用户组 - 管理中心');
        return view();
    }

    public function edit(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $id = $request->post('id');
            $group_name = $request->post('group_name');
            $res = $this->model->where(['id' => $id])->update(['name' => $group_name]);
            if ($res !== false) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '更新失败']);
        }
        $id = $request->get('id');
        $info = $this->model->where(['id' => $id])->find();
        if (!$info) {
            return Redirect('/group/index');
        }
        $info = $info->toArray();
        View::assign('title', '编辑用户组 - 管理中心');
        View::assign('info', $info);
        return view();
    }

    public function del(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->delete();
        return Redirect('/group/index');
    }
}
