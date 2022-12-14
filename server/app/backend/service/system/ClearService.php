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

namespace app\backend\service\system;


use app\common\basics\Service;

/**
 * 系统缓存清除服务类
 *
 * Class ClearService
 * @package app\admin\service\system
 */
class ClearService extends Service
{
    /**
     * 清空缓存
     *
     * @param array $post
     * @author windy
     */
    public static function clean(array $post): void
    {
        $dirs = [];
        foreach (scandir(root_path().'runtime') as $dir) {
            if (!in_array($dir, ['.', '..', '.gitignore', 'log', 'session', 'cache'])) {
                $path = str_replace('\\', '/', root_path().'runtime/'.$dir);
                array_push($dirs, $path);
            }
        }

        foreach ($post['type'] as $type) {
            switch (intval($type)) {
                case 1: //系统缓存
                    delete_dir(root_path().'runtime/cache/');
                    foreach ($dirs as $dir) {
                        delete_dir($dir.'/cache/');
                    }
                    break;
                case 2: //登录缓存
                    delete_dir(root_path().'runtime/session/');
                    foreach ($dirs as $dir) {
                        delete_dir($dir.'/session/');
                    }
                    break;
                case 3: //模板缓存
                    delete_dir(public_path().'runtime/temp/');
                    foreach ($dirs as $dir) {
                        delete_dir($dir.'/temp/');
                    }
                    break;
                case 4: //日志文件
                    delete_dir(root_path().'runtime/log/');
                    foreach ($dirs as $dir) {
                        delete_dir($dir.'/log/');
                    }
                    break;
                case 5: //临时图片
                    delete_dir(root_path().'public/storage/temp/');
                    break;
            }
        }
    }
}