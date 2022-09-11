<?php
namespace app\controller;

use app\BaseController;
use app\model\UserModel;
use app\Request;

class Api extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
    }

    public function search_user()
    {
        $username = $this->request->post('name', '');
        $user = new UserModel();
        $list = $user->field('id, username')->whereLike('username', '%' . $username . '%')->select()->toArray();
        $data = ['error_msg' => ''];
        if ($list) {
            $data['data'] = $list;
        } else {
            $data['error_msg'] = '没有找到类似数据';
        }
        return json($data);
    }
}
