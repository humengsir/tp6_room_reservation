<?php
namespace app\controller;

use app\BaseController;
use app\model\UserModel;
use app\model\UserGroupModel;
use app\Request;
use think\facade\View;

class User extends BaseController
{
    protected $user_info = [];

    protected function initialize()
    {
        parent::initialize();
        $this->model = new UserModel();
    }

    public function index()
    {
        View::assign('title', '用户列表 - 管理中心');
        View::assign('list', get_all_user());
        return view();
    }
   
    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $group_id = $request->post('group_id');
            $username = $request->post('username');
            $email = $request->post('email');
            $status = (int)$request->post('status');
            $data = compact('username', 'email', 'status');
            $password = $request->post('password');
            if ($password) {
                $data['password'] = create_password($password);
            }
            $res = $this->model->save($data);
            if ($res !== false) {
                // assign group
                $user_group_data = [];
                foreach ($group_id as $value) {
                    $user_group_data[] = [
                        'user_id' =>  $this->model->id,
                        'group_id' => $value,
                    ];
                }
                $add_group_result = (new UserGroupModel())->insert_many_group($user_group_data);
                if (!$add_group_result) {
                    return json(['error_msg' => '添加用户所在组失败']);
                }
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('title', '添加用户 - 管理中心');
        View::assign('group', get_all_group());
        return view();
    }

    public function edit(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $id = $request->post('id');
            $group_id = $request->post('group_id');
            $username = $request->post('username');
            $email = $request->post('email');
            $status = (int)$request->post('status');
            $data = compact('username', 'email', 'status');
            $password = $request->post('password');
            if ($password) {
                $data['password'] = create_password($password);
            }
            $res = $this->model->where(['id' => $id])->update($data);
            if ($res !== false) {
                // assign group
                $group_model = new UserGroupModel();
                $group_model->where(['user_id' => $id])->delete();
                $user_group_data = [];
                foreach ($group_id as $value) {
                    $user_group_data[] = [
                        'user_id' => $id,
                        'group_id' => $value,
                    ];
                }
                $add_group_result = $group_model->insert_many_group($user_group_data);
                if ($add_group_result === false) {
                    return json(['error_msg' => '更新用户所在组失败']);
                }
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '更新失败']);
        }
        $id = $request->get('id');
        $info = $this->model->where(['id' => $id])->find();
        if (!$info) {
            return Redirect('/user/index');
        }
        $info = $info->toArray();
        $all_user_group = get_all_user_group();
        $current_user_group = $all_user_group[$id] ?? [];
        $info['group_id'] = $current_user_group;
        View::assign('title', '编辑用户 - 管理中心');
        View::assign('info', $info);
        View::assign('group', get_all_group());
        return view();
    }

    public function del(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->update(['status' => 0], ['id' => $id]);
        return Redirect('/user/index');
    }

}
