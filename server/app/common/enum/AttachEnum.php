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

namespace app\common\enum;


/**
 * 附件枚举类
 *
 * Class AttachEnum
 * @package app\common\enum
 */
class AttachEnum
{
    const IMAGE = 10;
    const VIDEO = 20;

    /**
     * 根据描述获取Code
     *
     * @author windy
     * @param string $msg
     * @return int
     */
    public static function getCodeByMsg(string $msg): int
    {
        $desc = [
            'image' => self::IMAGE,
            'video' => self::VIDEO
        ];

        return $desc[$msg] ?? 0;
    }
}