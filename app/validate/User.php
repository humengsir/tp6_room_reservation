<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name'  => 'require|max:25',
        'email' => 'email',
        'age'   => 'number|between:1,120',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误', 
        
        // 'name.require' => ['code' => 1001, 'msg' => '名称必须'],
        // 'name.max'     => ['code' => 1002, 'msg' => '名称最多不能超过25个字符'],
        // 'age.number'   => ['code' => 1003, 'msg' => '年龄必须是数字'],
        // 'age.between'  => ['code' => 1004, 'msg' => '年龄必须在1~120之间'],
        // 'email'        => ['code' => 1005, 'msg' =>'邮箱格式错误'],
    ];

    protected $scene = [
        'edit'  =>  ['name','age'],
    ];
}
