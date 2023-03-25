<?php

namespace app\frontend\service;

use app\api\widgets\UserWidget;
use app\common\basics\Service;
use app\common\enums\NoticeEnum;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\service\msg\MsgDriver;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class LoginService extends Service
{
    /**
     * 注册账号
     *
     * @param array $post   (参数)
     * @param int $terminal (设备)
     * @return array
     * @throws OperateException
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function register(array $post, int $terminal): array
    {
        // 接收参数
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $account  = $post['account'] ?? '';
        $password = $post['password'] ?? '';
        $modelUser = new User();

        // 手机验证
        if (!$modelUser->where(['mobile'=>trim($mobile)])->findOrEmpty()->isEmpty()) {
            throw new OperateException('手机已被占用!');
        }

        // 账号验证
        if (!$modelUser->where(['account'=>trim($account)])->findOrEmpty()->isEmpty()) {
            throw new OperateException('账号已被占用!');
        }

        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::REGISTER, $code)) {
            throw new OperateException('验证码错误!');
        }

        // 创建账号
        $userId = UserWidget::createUser([
            'mobile'   => trim($mobile),
            'account'  => trim($account),
            'password' => trim($password),
            'terminal' => $terminal
        ]);

        // 登录账号
        $token = UserWidget::granToken($userId, $terminal);
        return ['token'=>$token];
    }

    /**
     * 账号登录
     *
     * @param $account  (账号)
     * @param $password (密码)
     * @param $terminal (设备)
     * @return array
     * @throws OperateException
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function accountLogin(string $account, string $password, int $terminal): array
    {
        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,account,password,salt,is_disable'])
            ->where(['account'=>$account])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在了!');
        }

        // 验证密码
        $password = make_md5_str($password, $userInfo['salt']);
        if ($userInfo['password'] !== $password) {
            throw new OperateException('账号或密码错误!');
        }

        // 验证状态
        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        // 登录账户
        $token = UserWidget::granToken(intval($userInfo['id']), $terminal);
        return ['token'=>$token];
    }

    /**
     * 短信登录
     *
     * @param string $mobile (手机号)
     * @param string $code   (验证码)
     * @param int $terminal  (设备)
     * @return array
     * @throws OperateException
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function mobileLogin(string $mobile, string $code, int $terminal): array
    {
        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::LOGIN, $code)) {
            throw new OperateException('验证码错误了!');
        }

        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,mobile,is_disable'])
            ->where(['mobile'=>$mobile])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在了!');
        }

        // 验证状态
        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        // 登录账户
        $token = UserWidget::granToken(intval($userInfo['id']), $terminal);
        return ['token'=>$token];
    }

    /**
     * 重置密码
     *
     * @param array $post (参数)
     * @throws OperateException
     * @author windy
     */
    public static function forgetPwd(array $post): void
    {
        // 接收参数
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $password = $post['newPassword'];

        // 验证类型
        $field = 'mobile';
        if (!preg_match('/^1[3456789]\d{9}$/', $mobile)) {
            $field = 'email';
        }

        // 编码验证
        if (!MsgDriver::checkCode(NoticeEnum::FORGET_PWD, $code)) {
            throw new OperateException('验证码错误!');
        }

        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser->field(['id,account,mobile'])
            ->where([$field=>trim($mobile)])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在!');
        }

        // 设置密码
        $salt = make_rand_char(6);
        $password = make_md5_str($password, $salt);
        User::update([
            'salt'        => $salt,
            'password'    => $password,
            'update_time' => time()
        ], ['id'=>$userInfo['id']]);
    }

}