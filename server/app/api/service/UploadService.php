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

use app\common\basics\Service;
use app\common\enums\AttachEnum;
use app\common\exception\UploadException;
use app\common\model\attach\Attach;
use app\common\service\storage\StorageDriver;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\facade\Filesystem;

/**
 * 上传服务类
 */
class UploadService extends Service
{
    /**
     * 永久存储
     *
     * @param string $type (类型: picture/video/document/package)
     * @param int $userId  (用户ID)
     * @return array
     * @throws UploadException
     */
    #[ArrayShape(['name' => "string", 'ext' => "string", 'size' => "int", 'path' => "string", 'url' => "string"])]
    public static function permanent(string $type, int $userId): array
    {
        try {
            // 存储引擎
            $engine = ConfigUtils::get('storage', 'default', 'local');
            $params = ConfigUtils::get('storage', $engine, []);

            // 上传调用
            $storageDriver = new StorageDriver(['engine' => $engine, 'params' => $params]);
            $fileInfo = $storageDriver->upload($type);

            // 记录信息
            Attach::create([
                'cid'       => 0,
                'uid'       => $userId,
                'file_type' => AttachEnum::getCodeByMsg($type),
                'file_path' => $fileInfo['fileName'],
                'file_name' => $fileInfo['name'],
                'file_ext'  => $fileInfo['ext'],
                'file_size' => $fileInfo['size'],
                'is_user'   => 1,
                'is_attach' => 0
            ]);

            // 返回信息
            return [
                'name' => $fileInfo['name'],
                'ext'  => $fileInfo['ext'],
                'size' => $fileInfo['size'],
                'path' => $fileInfo['fileName'],
                'url'  => UrlUtils::toAbsoluteUrl($fileInfo['fileName'])
            ];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }

    /**
     * 临时上传
     *
     * @param string $type (类型: image/video)
     * @return array
     * @throws UploadException
     * @author zero
     */
    #[ArrayShape(['name' => "string", 'ext' => "string", 'size' => "int", 'url' => "string"])]
    public static function temporary(string $type): array
    {
        try {
            $storageConfig = ['engine' => 'local', 'params' => []];
            $storageDriver = new StorageDriver($storageConfig);
            $storageDriver->validates($type);

            $file = request()->file('file');
            $name = $storageDriver->buildSaveName($file->getRealPath(), $file->extension());

            $filesystem = Filesystem::instance();
            $filesystem->disk('temporary')->putFileAS('', $file, $name);

            return [
                'name' => $file->getOriginalName(),
                'size' => $file->getSize(),
                'ext'  => $file->extension(),
                'url'  => UrlUtils::toAbsoluteUrl('temporary/' . $name)
            ];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }
}