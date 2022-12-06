<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\backend\validate\setting;


use app\common\basics\Validate;

class NavigationValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|posInteger',
        'pid'        => 'require|number',
        'name'       => 'require|max:30',
        'target'     => 'require|in:_self,_blank,_parent,_top',
        'url'        => 'require|max:250',
        'sort'       => 'require|number',
        'is_disable' => 'require|in:0,1',
    ];

    public function __construct()
    {
        $this->field = [
            'pid'        => '导航父级',
            'name'       => '导航名称',
            'target'     => '跳转方式',
            'url'        => '跳转链接',
            'sort'       => '排序编号',
            'is_disable' => '友链状态'
        ];

        parent::__construct();
    }
}