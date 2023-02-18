<?php

namespace app\api\controller;


use app\api\service\ArticleService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

class ArticleController extends Api
{
    protected array $notNeedLogin = ['category', 'lists', 'detail'];

    /**
     * 文章分类
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function category(): Json
    {
        $list = ArticleService::category();
        return AjaxUtils::success($list);
    }

    /**
     * 文章列表
     *
     * @return Json
     * @throws DbException
     * @author windy
     */
    public function lists(): Json
    {
        $list = ArticleService::lists($this->request->get());
        return AjaxUtils::success($list);
    }

    /**
     * 文章详情
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function detail(): Json
    {
        $id = intval($this->request->get('id'));
        $detail = ArticleService::detail($id);
        return AjaxUtils::success($detail);
    }
}