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

namespace app\frontend\controller;

use app\common\basics\Frontend;
use app\common\service\msg\MsgDriver;
use app\common\utils\AjaxUtils;
use app\frontend\service\ArticleService;
use app\frontend\service\IndexService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 主页管理
 *
 * Class IndexController
 * @package app\frontend\controller
 */
class IndexController extends Frontend
{
    protected array $notNeedLogin = ['index', 'sendSms', 'sendEmail'];

    /**
     * @return View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function index(): View
    {
        return view('', [
            'loginPop' => $this->request->get('loginPop', false),
            'links'    => IndexService::getLinks(),
            'banner'   => IndexService::getBanner(1),
            'adv'      => IndexService::getBanner(2),
            'topping'  => ArticleService::recommend('topping', 6),
            'everyday' => ArticleService::recommend('everyday', 8),
            'lately'   => ArticleService::recommend('lately', 8),
            'ranking'  => ArticleService::recommend('ranking', 8)
        ]);
    }

    /**
     * 发送短信
     *
     * @return Json
     * @author windy
     */
    public function sendSms(): Json
    {
        $scene  = $this->request->post('scene');
        $mobile = $this->request->post('mobile');

        MsgDriver::send(intval($scene), [
            'mobile' => $mobile,
            'code'   => make_rand_code(null, '', 6)
        ]);

        return AjaxUtils::success();
    }

    /**
     * 发送邮件
     *
     * @return Json
     * @author windy
     */
    public function sendEmail(): Json
    {
        $scene = $this->request->post('scene');
        $email = $this->request->post('email');

        MsgDriver::send(intval($scene), [
            'email' => $email,
            'code'  => make_rand_code(null, '', 6)
        ]);

        return AjaxUtils::success();
    }
}