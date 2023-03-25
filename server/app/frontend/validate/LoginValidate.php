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

namespace app\frontend\validate;

use app\common\basics\Validate;

/**
 * 登录参数验证器
 */
class LoginValidate extends Validate
{
    /**
     * 场景验证
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneScene(): LoginValidate
    {
        return $this->only(['scene'])
            ->append('scene', 'require|in:account,mobile');
    }

    /**
     * 账号登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneAccount(): LoginValidate
    {
        $this->message = [
            'account'    => '账号或密码错误',
            'password'   => '账号或密码错误'
        ];

        return $this->only(['account', 'password'])
            ->append('account', 'require|alphaNum|min:2|max:20')
            ->append('password', 'require|alphaNum|min:6|max:20');
    }

    /**
     * 短信登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneMobile(): LoginValidate
    {
        $this->message = [
            'mobile' => '手机号错误',
            'code'   => '验证码错误'
        ];

        return $this->only(['mobile', 'code'])
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 注册账号
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneRegister(): LoginValidate
    {
        $this->field = [
            'account'  => '账号',
            'password' => '密码',
            'mobile'   => '手机号',
            'code'     => '验证码',
        ];
        return $this->only(['account', 'password', 'mobile', 'code'])
            ->append('account', 'require|alphaNum|min:2|max:20')
            ->append('password', 'require|alphaNum|min:6|max:20')
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 忘记密码

     * @return LoginValidate
     * @author windy
     */
    public function sceneForgetPwd(): LoginValidate
    {
        $this->field = [
            'mobile'      => '手机号',
            'code'        => '验证码',
            'newPassword' => '新密码'
        ];

        $rule    = 'require|mobile|min:11|max:11';
        $value   = request()->post('mobile');
        $pattern = '/^1[3456789]\d{9}$/';
        if (!preg_match($pattern, $value)) {
            $this->field['mobile'] = '邮箱号';
            $rule = 'require|email';
        }

        return $this->only(['newPassword', 'mobile', 'code'])
            ->append('newPassword', 'require|alphaDash|min:6|max:20')
            ->append('mobile', $rule)
            ->append('code', 'require|alphaDash|max:6');
    }
}