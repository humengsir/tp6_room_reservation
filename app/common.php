<?php
// 应用公共文件

use app\model\GroupModel;
use app\model\UserGroupModel;
use app\model\GroupNodeModel;
use app\model\UserModel;
use app\model\NodeModel;
use app\model\MenuModel;
use app\model\RoomModel;


function create_password(string $password = '')
{
    return md5($password . '^)(*GYOYU^()((**');
}

function format_ticket_status(int $status = 0)
{
    $list = [
        -1 => '已取消',
        1 => '初始化',
        2 => '处理中',
        3 => '已完结',
    ];
    return $list[$status] ?? '未知状态';
}

function format_status(int $status = 0)
{
    $list = [
        0 => '禁用',
        1 => '启用',
    ];
    return $list[$status] ?? '未知状态';
}

function format_room_status(int $status = 0)
{
    $list = [
        0 => '不可用',
        1 => '可用',
    ];
    return $list[$status] ?? '未知状态';
}

function format_reservation_status(int $status = 0)
{
    $list = [
        0 => '已取消',
        1 => '使用中',
        2 => '已结束',
    ];
    return $list[$status] ?? '未知状态';
}

function format_user_name(int $uid = 0)
{
    $list = get_all_hash_user();
    return $list[$uid]['username'] ?? '';
}

function format_leader_uid_user_name(string $uids = '')
{
    $uids = array_filter(explode(',', $uids));
    $string = '';
    foreach ($uids as $uid) {
        $string .= "<label class='btn btn-small'>" . format_user_name($uid) . "</label> ";
    }
    return $string;
}

function format_join_uid_user_name(string $uids = '')
{
    $uids = array_filter(explode(',', $uids));
    $string = '';
    foreach ($uids as $uid) {
        $string .= "<label class='btn btn-small'>" . format_user_name($uid) . "</label> ";
    }
    return $string;
}



function format_group(int $uid = 0)
{
    $user_group = get_all_user_group();
    $group_ids = $user_group[$uid] ?? [];
    if ($group_ids) {
        $group = get_all_group();
        foreach ($group_ids as $key => $group_id) {
            $group_ids[$key] = $group[$group_id] ?? '未知分组';
        }
    }
    return implode(' ', $group_ids);
}

function get_all_hash_user()
{
    static $list = [];
    if (!$list) {
        $result = get_all_user();
        foreach ($result as $value) {
            $list[$value['id']] = $value;
        }
    }
    return $list;
}

function get_all_user()
{
    static $list = [];
    if (!$list) {
        $list = (new UserModel())->field('id, username, email, status')->select()->toArray();
    }
    return $list;
}
function get_all_group()
{
    static $list = [];
    if (!$list) {
        $list = (new GroupModel())->field('id, name')->column('name', 'id');
    }
    return $list;
}

function get_all_user_group()
{
    static $list = [];
    if (!$list) {
        $result = (new UserGroupModel())->field('user_id, group_id')->select()->toArray();
        if ($result) {
            foreach ($result as $value) {
                $list[$value['user_id']][] = $value['group_id'];
            }
        }
    }
    return $list;
}

function get_all_node()
{
    static $list = [];
    if (!$list) {
        $list = (new NodeModel())->field('id, node_name, node_url, node_sort, node_type, status')->order('node_sort desc')->select()->toArray();
    }
    return $list;
}

function get_all_room()
{
    static $list = [];
    if (!$list) {
        $list = (new RoomModel())->field('id, room_name, lock_start_time, lock_end_time, room_people_num, status')->order('id asc')->select()->toArray();
    }
    return $list;
}

function get_node_id_by_url(string $url = '')
{
    $list = get_all_node();
    foreach ($list as $value) {
        if ($value['node_url'] === $url) {
            return $value['id'];
        }
    }
    return 0;
}

function get_all_active_node()
{
    static $list = [];
    if (!$list) {
        $list = get_all_node();
        foreach ($list as $key => $value) {
            if ($value['status'] != 1) {
                unset($list[$key]);
            }
        }
    }
    return $list;
}

function get_group_node(int $group_id = 0)
{
    static $list = [];
    if (!isset($list[$group_id])) {
        $result = (new GroupNodeModel())->field('group_id, node_id')->where(['group_id' => $group_id])->select()->toArray();
        if ($result) {
            foreach ($result as $value) {
                $list[$group_id][] = $value['node_id'];
            }
        }
    }
    return $list[$group_id] ?? [];
}

function get_all_menu()
{
    static $list = [];
    if (!$list) {
        $list = (new MenuModel())->field('id, menu_name, node_id, is_nav, menu_sort, pid')->order('menu_sort desc')->select()->toArray();
    }
    return $list;
}

function get_node_type()
{
    return [
        1 => '用户模块',
        2 => '用户组模块',
        3 => '权限模块',
        4 => '菜单模块',
        5 => '工单模块',
        6 => '会议室模块',
    ];
}

function get_ticket_type()
{
    return [
        1 => '故障报错',
        2 => '改进反馈',
        3 => '想法建议',
        4 => '个人需求',
    ];
}

function format_node_type(int $type = 0)
{
    $list = get_node_type();
    return $list[$type] ?? '未知模块';
}

function format_is_nav(int $is_nav = 0)
{
    $list = [
        0 => '否',
        1 => '是',
    ];
    return $list[$is_nav] ?? '未知状态';
}

function format_time(int $time = 0)
{
    return $time > 0 ? date('Y-m-d H:i:s', $time) : '';
}

function format_menu_pid_name(int $pid = 0)
{
    if ($pid == 0)
    {
        return '';
    }
    $menu_list = get_all_menu();
    foreach ($menu_list as $key => $value) {
        if ($pid == $value['id']) {
            return $value['menu_name'];
        }
    }
    return '';
}

function format_node_id(int $node_id = 0)
{
    if ($node_id == 0)
    {
        return '';
    }
    $node_list = get_all_active_node();
    foreach ($node_list as $key => $value) {
        if ($node_id == $value['id']) {
            return $value['node_name'];
        }
    }
    return '';
}

function format_room_name(int $room_id = 0)
{
    if ($room_id == 0)
    {
        return '';
    }
    $node_list = get_all_room();
    foreach ($node_list as $key => $value) {
        if ($room_id == $value['id']) {
            return $value['room_name'];
        }
    }
    return '';
}

function tree_menu(array $tree = [])
{
    $list = [];
    foreach ($tree as $key => $value) {
        if ($value['pid'] == 0) {
            continue;
        }
        $list[$value['pid']][] = $value;
    }
    return $list;
}

function format_node_url(int $node_id = 0)
{
    if ($node_id == 0)
    {
        return '';
    }
    $node_list = get_all_active_node();
    foreach ($node_list as $key => $value) {
        if ($node_id == $value['id']) {
            return $value['node_url'];
        }
    }
    return '';
}
