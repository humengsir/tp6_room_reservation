<?php
namespace app\controller;

use app\BaseController;
use app\model\GroupNodeModel;
use app\Request;
use think\facade\View;
use think\response\Redirect;

class Permission extends BaseController
{
    protected $user_info = [];
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new GroupNodeModel();
    }

    public function assign(Request $request)
    {
        if ($request->isPost()) {
            // handle for post
            $id = $request->post('id');
            $node_id = $request->post('node_id');
            $this->model->where(['group_id' => $id])->delete();
            foreach ($node_id as $value) {
                $add_data[] = [
                    'group_id' => $id,
                    'node_id' => $value,
                ];
            }
            if ($add_data) {
                $res = $this->model->insert_all_permission($add_data);
                if ($res < 1) {
                    return json(['error_msg' => '更新失败']);
                }
            }
            return json(['error_msg' => '']);
        }
        $all_group = get_all_group();
        $id = $request->get('id');
        if (!isset($all_group[$id])) {
            return Redirect('/group/index');
        }
        View::assign('title', '权限列表 - 管理中心');
        View::assign('my_node', get_group_node($id));
        $node_list = get_all_active_node();
        $result = [];
        foreach ($node_list as $key => $value) {
            $result[$value['node_type']][] = $value;
        }
        View::assign('all_node', $result);
        View::assign('all_group', $all_group);
        View::assign('group_id', $id);
        return view();
    }
}
