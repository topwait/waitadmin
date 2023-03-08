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

namespace app\api\service;

use app\api\cache\EnrollCache;
use app\api\widgets\UserWidget;
use app\common\basics\Service;
use app\common\enums\NoticeEnum;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\msg\MsgDriver;
use app\common\service\wechat\WeChatService;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 * 登录服务类
 */
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
        $account  = $post['account'];
        $password = $post['password'];

        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::REGISTER, $code)) {
            throw new OperateException('验证码错误');
        }

        // 创建账号
        $userId = UserWidget::createUser([
            'mobile'   => $mobile,
            'account'  => $account,
            'password' => $password,
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
            ->field(['id,password,salt,is_disable'])
            ->where(['account'=>$account])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在!');
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
            throw new OperateException('验证码错误!');
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
            throw new OperateException('账号不存在!');
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
     * 绑定登录
     *
     * @param string $mobile (手机号)
     * @param string $code   (验证码)
     * @param string $sign   (签名值)
     * @param int $terminal  (设备)
     * @return array
     * @throws OperateException
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function baLogin(string $mobile, string $code, string $sign, int $terminal): array
    {
        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::BIND_MOBILE, $code)) {
            throw new OperateException('验证码错误', 1);
        }

        // 登录数据
        $response = EnrollCache::get($sign);
        if (!$response) {
            throw new OperateException('首次登录绑定手机号异常', 1);
        }

        // 设置参数
        $response['terminal'] = $terminal;
        $response['mobile'] = $mobile;

        // 验证账户
        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $response['user_id'] = intval($userInfo['id']);
            $userId = UserWidget::updateUser($response);
        }

        // 登录账户
        $token = UserWidget::granToken($userId, $terminal);
        return ['token'=>$token];
    }

    /**
     * 微信登录
     *
     * @param string $code (微信小程序编码)
     * @param string $wxCode (微信手机号编码)
     * @param int $terminal (客户端[1=微信小程序, 2=微信公众号, 3=H5, 4=PC, 5=安卓, 6=苹果])
     * @return array
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function wxLogin(string $code, string $wxCode, int $terminal): array
    {
        // 微信授权
        $response = WeChatService::wxJsCode2session($code);
        $response['terminal'] = $terminal;

        // 获取手机
        if ($wxCode) {
            $phoneArr = WeChatService::wxPhoneNumber($wxCode);
            $response['mobile'] = $phoneArr['phoneNumber'];
        }

        // 验证账户
        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $response['user_id'] = $userInfo['id'];
            $userId = UserWidget::updateUser($response);
        }

        // 登录账户
        $token = UserWidget::granToken($userId, $terminal);
        return ['token'=>$token];
    }

    /**
     * 公众号登录
     *
     * @param string $code
     * @param int $terminal
     * @return array
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function oaLogin(string $code, int $terminal): array
    {
        // 微信授权
        $response = WeChatService::oaAuth2session($code);
        $response['terminal'] = $terminal;

        // 验证账户
        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $userId = UserWidget::updateUser($response);
        }

        // 登录账户
        $token = UserWidget::granToken($userId, $terminal);
        return ['token'=>$token];
    }

    /**
     * 公众号授权链接
     *
     * @param string $url
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['url' => "string"])]
    public static function oaCodeUrl(string $url): array
    {
        return ['url'=>WeChatService::oaBuildAuthUrl($url)];
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

        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::FORGET_PWD, $code)) {
            throw new OperateException('验证码错误');
        }

        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser->field(['id,mobile'])
            ->where(['mobile'=>trim($mobile)])
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

    /**
     * 修改密码
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author windy
     */
    public static function changePwd(array $post, int $userId): void
    {
        // 接收参数
        $newPassword = $post['newPassword'];
        $oldPassword = $post['oldPassword'];

        // 查询账户
        $modelUser = new User();
        $user = $modelUser->field(['id,password,salt'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$user) {
            throw new OperateException('检测到用户已不存在!');
        }

        // 验证密码
        $originalPwd = make_md5_str($oldPassword, $user['salt']);
        if ($user['password'] !== $originalPwd) {
            throw new OperateException('检测到旧密码不正确!');
        }

        // 更新密码
        $salt = make_rand_char(6);
        User::update([
            'salt'        => $salt,
            'password'    => make_md5_str($newPassword, $salt),
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 绑定微信
     *
     * @param array $post (参数)
     * @param int $userId (用户ID)
     * @throws OperateException
     * @throws Exception
     * @author windy
     */
    public static function bindWeChat(array $post, int $userId): void
    {
        // 接收参数
        $code = $post['code'];

        // 微信授权
        $response = WeChatService::wxJsCode2session($code);

        // 验证账户
        $modeUserAuth = new UserAuth();
        $userAuth = $modeUserAuth->field(['id,openid,unionid,terminal'])
            ->where(['id'=>$userId])
            ->where(['terminal'=>1])
            ->findOrEmpty()
            ->toArray();

        // 验证绑定
        if ($userAuth
            && $userAuth['openid'] == $response['openid']
            && $userAuth['unionid'] == $response['unionid']) {
            throw new OperateException('已绑定,请勿重复操作!');
        }

        //更新授权
        if ($userAuth) {
            UserAuth::update([
                'openid'      => $response['openid'] ?? $userAuth['openid'],
                'unionid'     => $response['unionid'] ?? $userAuth['unionid'],
                'update_time' => time(),
            ], ['id'=>intval($userAuth['id'])]) ;
        } else {
            UserAuth::create([
                'user_id'  => $userId,
                'openid'   => $response['openid'],
                'unionid'  => $response['unionid'],
                'terminal' => 1,
                'create_time' => time(),
                'update_time' => time()
            ]);
        }
    }

    /**
     * 绑定手机
     *
     * @param array $post (参数)
     * @param int $userId (用户ID)
     * @throws OperateException
     * @throws Exception
     * @author windy
     */
    public static function bindMobile(array $post, int $userId): void
    {
        // 接收参数
        $mobile = $post['mobile']??'';
        $code   = $post['code']??'';
        $type   = $post['type']??'';

        if ($type === 'code') {
            // 微信验证
            $phoneArr = WeChatService::wxPhoneNumber($code);
            $mobile = $phoneArr['phoneNumber'];
        } else {
            // 短信验证
            $nCode = $type === 'change' ? NoticeEnum::CHANGE_MOBILE : NoticeEnum::BIND_MOBILE;
            if (!MsgDriver::checkCode($nCode, $code)) {
                throw new OperateException('验证码错误');
            }
        }

        // 查询用户
        $modeUser = new User();
        $user = $modeUser->field(['id,mobile'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>$userId])
            ->findOrEmpty()
            ->toArray();

        // 验证用户
        if (!$user) {
            throw new OperateException('检测到用户已不存在!');
        }

        // 查询手机
        $mobileCheck = $modeUser->field(['id'])
            ->where(['id', '<>', $userId])
            ->where(['mobile'=>$mobile])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证手机
        if ($mobileCheck) {
            throw new OperateException('检测到手机号已被占用!');
        }

        // 更新信息
        User::update([
            'mobile' => $mobile,
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 绑定邮箱
     *
     * @param array $post (参数)
     * @param int $userId (用户ID)
     * @throws OperateException
     * @author windy
     */
    public static function bindEmail(array $post, int $userId): void
    {
        // 接收参数
        $email = $post['email'];
        $code   = $post['code'];

        // 短信验证
        if (MsgDriver::checkCode(NoticeEnum::BIND_EMAIL, $code)) {
            throw new OperateException('验证码错误', 1);
        }

        // 查询用户
        $modeUser = new UserAuth();
        $user = $modeUser->field(['id,email'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>$userId])
            ->findOrEmpty()
            ->toArray();

        // 验证用户
        if (!$user) {
            throw new OperateException('检测到用户已不存在!');
        }

        // 查询邮箱
        $emailCheck = $modeUser->field(['id'])
            ->where(['id', '<>', $userId])
            ->where(['email'=>$email])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证邮箱
        if ($emailCheck) {
            throw new OperateException('检测到邮箱号已被占用!');
        }

        // 更新信息
        User::update([
            'email' => $email,
            'update_time' => time()
        ], ['id'=>$userId]);
    }
}