<?php
namespace app\controller;

use app\model\UserModel;
use app\Request;
use think\facade\Session;

class Login
{
    public function index()
    {
        return view();
    }

    public function login_check(Request $request)
    {
        $data = [
            'error_msg' => '',
        ];
        $username = $request->post('username');
        $password = $request->post('password');
        $user_info = UserModel::field('id, username, password')->where(['username' => $username])->find();
        if (!$user_info) {
            $data['error_msg'] = '用户不存在';
            return json($data);
        }
        $user_info = $user_info->toArray();
        if ($user_info['password'] != create_password($password)) {
            $data['error_msg'] = '密码错误';
            return json($data);
        }
        unset($user_info['password']);
        Session::set('user_info', $user_info);
        return json($data);
    }

    public function logout()
    {
        Session::set('user_info', null);
        return redirect("/login");
    }
}
