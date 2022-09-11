<?php
namespace app\controller;

use app\BaseController;
use app\model\SignLog;
use think\facade\View;
use think\facade\Session;

class Index extends BaseController
{
    protected $user_info = [];

    protected function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        View::assign('title', '首页 - 管理后台');
        return view();
    }
}
