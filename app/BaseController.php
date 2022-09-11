<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\Session;
use think\exception\HttpResponseException;
use think\facade\View;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        if (!$this->checkLogin()) {
            throw new HttpResponseException(redirect('/login'));
        }
        View::assign('user_info', $this->user_info);
        $user_all_group = get_all_user_group();
        $current_group = $user_all_group[$this->user_info['id']] ?? [];
        $node_list = [];
        foreach ($current_group as $key => $group_id) {
            $node_list = array_merge($node_list, get_group_node($group_id));
        }
        $node_list = array_unique($node_list);
        $this->checkAccess($node_list);//检验用户是否有权限访问该链接
        $sidebar_list = tree_menu(get_all_menu());
        foreach ($sidebar_list as $key => $value) {
            foreach ($value as $k => $v) {
                if ($v['is_nav'] == 0 || !in_array($v['node_id'], $node_list)) {
                    unset($sidebar_list[$key][$k]);
                }
            }
        }
        foreach ($sidebar_list as $key => $value) {
            if (count($value) < 1) {
                unset($sidebar_list[$key]);
            }
        }
        View::assign('sidebar_list', $sidebar_list);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    protected function checkLogin()
    {
        return $this->user_info = Session::get('user_info');
    }

    protected function checkAccess(array $node_list = [])
    {
        $access_url = "/" . $this->request->controller(true) . "/" . $this->request->action(true);
        $access_node_id = get_node_id_by_url($access_url);
        if ($access_node_id && !in_array($access_node_id, $node_list)) {
            // 没有权限
            echo "no access! will return last page after 3 seconds!<script>setTimeout(() => {window.history.back(-1);}, 3000);</script>";
            exit;
        }
    }

}
