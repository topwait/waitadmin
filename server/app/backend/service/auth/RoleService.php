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

namespace app\backend\service\auth;


use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\auth\AuthAdmin;
use app\common\model\auth\AuthMenu;
use app\common\model\auth\AuthPerm;
use app\common\model\auth\AuthRole;
use app\common\utils\ArrayUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 角色服务类
 *
 * Class RoleService
 * @package app\admin\service\auth
 */
class RoleService extends Service
{
    /**
     * 所有角色
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function all(): array
    {
        $model = new AuthRole();
        return $model
            ->field('id,name,is_disable')
            ->where(['is_delete' => 0])
            ->order('sort desc, id desc')
            ->select()
            ->toArray();
    }

    /**
     * 角色列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author windy
     */
    #[ArrayShape(['count' => "mixed", 'list' => "mixed"])]
    public static function lists(array $get): array
    {
        self::setSearch([
            '='      => ['status@is_disable'],
            '%like%' => ['name']
        ]);

        $model = new AuthRole();
        $lists = $model
            ->field('id,name,describe,sort,is_disable,create_time')
            ->where(self::$searchWhere)
            ->where(['is_delete' => 0])
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        $modelAdmin = new AuthAdmin();
        foreach ($lists['data'] as &$item) {
            $item['adminCount'] = $modelAdmin
                ->where(['role_id'=>$item['id']])
                ->where(['is_delete'=>0])
                ->count();
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 获取选中的角色权限ID
     * 主要是为了处理编辑时tree选中的问题
     *
     * @param array $menuIds
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function checked(array $menuIds): array
    {
        $model = new AuthMenu();
        $lists = $model
            ->withoutField('is_delete,crate_time,update_time,delete_time')
            ->whereIn('id', $menuIds)
            ->where(['is_delete'=>0])
            ->select()
            ->toArray();

        $treeJson = ArrayUtils::toTreeJson($lists);
        return self::getCheckedMenuIds($treeJson);
    }

    /**
     * 角色详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function detail(int $id): array
    {
        $model = new AuthRole();
        return $model->field('id,name,describe,sort,is_disable')
            ->where(['id'=>intval($id)])
            ->where(['is_delete'=>0])
            ->withAttr('menu_ids', function ($value, $data) {
                unset($value);
                $model = new AuthPerm();
                return $model->where(['role_id'=>$data['id']])->column('menu_id');
            })
            ->append(['menu_ids'])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 角色新增
     *
     * @param array $post
     * @throws Exception
     * @author windy
     */
    public static function add(array $post): void
    {
        $role = AuthRole::create([
            'name'        => $post['name'],
            'sort'        => $post['sort'] ?? 0,
            'describe'    => $post['describe'] ?? '',
            'is_disable'  => $post['is_disable'],
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);

        $menus = [];
        foreach (self::getHandleMenuIds($post) as $menuId) {
            $menus[] = [
                'role_id' => $role['id'],
                'menu_id' => $menuId
            ];
        }

        if (!empty($menus)) {
            $authPermModel = new AuthPerm();
            $authPermModel->saveAll($menus);
        }
    }

    /**
     * 角色编辑
     *
     * @param array $post
     * @throws OperateException
     * @throws Exception
     * @author windy
     */
    public static function edit(array $post): void
    {
        $model = new AuthRole();
        $model->checkDataDoesNotExist();

        AuthRole::update([
            'name'        => $post['name'],
            'sort'        => $post['sort'] ?? 0,
            'describe'    => $post['describe'] ?? '',
            'is_disable'  => $post['is_disable'],
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);

        $menus = [];
        foreach (self::getHandleMenuIds($post) as $menuId) {
            $menus[] = [
                'role_id' => intval($post['id']),
                'menu_id' => $menuId
            ];
        }

        $authPermModel = new AuthPerm();
        $authPermModel->where(['role_id'=>intval($post['id'])])->delete();
        if (!empty($menus)) {
            $authPermModel->saveAll($menus);
        }
    }

    /**
     * 角色删除
     *
     * @param array $ids
     * @throws DbException
     * @throws OperateException
     * @author windy
     */
    public static function del(array $ids): void
    {
        $modelAdmin = new AuthAdmin();
        foreach ($ids as $id) {
            $result = $modelAdmin
                ->where(['role_id'=>$id])
                ->where(['is_delete'=>0])
                ->count();

            if ($result) {
                throw new OperateException('ID：' . $id . ' 角色已被使用不能删除!');
            }
        }

        AuthRole::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], array(['id', 'in', $ids]));
    }

    /**
     * 处理获取规格ID
     * 先删除角色相关字段,剩余的是规格字段
     * 规格字段是这样的: layuiTreeCheck_2: "2"; layuiTreeCheck_3: "5"
     * 返回菜单ID数组
     *
     * @param array $post
     * @return array
     * @author windy
     */
    private static function getHandleMenuIds(array $post): array
    {
        unset($post['describe']);
        unset($post['name']);
        unset($post['sort']);
        unset($post['is_disable']);
        return !empty($post) ? array_unique(array_values($post)) : [];
    }

    /**
     * 递归处理选角色选中权限ID
     * 只取出子级ID,父级ID不要
     *
     * @param array $treeJson
     * @return array
     * @author windy
     */
    private static function getCheckedMenuIds(array $treeJson): array
    {
        static $data = array();
        foreach ($treeJson as $item) {
            if (!empty($item['children'])) {
                self::getCheckedMenuIds($item['children']);
            } else {
                array_push($data, $item['id']);
            }
        }

        return $data;
    }
}