<?php
namespace app\controller;

use app\BaseController;
use app\model\MenuModel;
use app\Request;
use think\facade\View;
use think\response\Redirect;

class Menu extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new MenuModel();
    }

    public function index()
    {
        View::assign('title', '菜单列表 - 管理中心');
        View::assign('list', get_all_menu());
        return view();
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $menu_name = $request->post('menu_name');
            $node_id = (int)$request->post('node_id');
            $is_nav = (int)$request->post('is_nav');
            $menu_sort = $request->post('menu_sort');
            $pid = (int)$request->post('pid');
            $data = compact('menu_name', 'node_id', 'is_nav', 'menu_sort', 'pid');
            $res = $this->model->save($data);
            if ($res) {
                return json(['error_msg' => '']);
            }
            return json(['error_msg' => '添加失败']);
        }
        View::assign('node_list', get_all_active_node());
        View::assign('menu_list', tree_menu(get_all_menu()));
        View::assign('title', '添加菜单 - 管理中心');
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
            return Redirect('/menu/index');
        }
        $info = $info->toArray();
        View::assign('node_list', get_all_active_node());
        View::assign('menu_list', tree_menu(get_all_menu()));
        View::assign('title', '编辑菜单 - 管理中心');
        View::assign('info', $info);
        return view();
    }

    public function del(Request $request)
    {
        $id = $request->get('id');
        $this->model->where(['id' => $id])->delete();
        return Redirect('/menu/index');
    }
}
