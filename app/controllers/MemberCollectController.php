<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/1/16
 * Time: 11:46
 */

namespace App\controllers;

use App\facades\Api;
use App\models\member\MemberCollect;

use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberCollectController extends BaseController
{
    /** 获取当前登录用户收藏主题列表
     * 传入参数：
     *  $subject_type tinyint(1) 主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
     *  $pageNumber  int     当前页码，默认第一页
     *  $pageSize    int     每页数量，默认10行
     *  $subjectName string  搜索词，如果没有可传入“0”
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      1、$subjectList     array   主题列表（数组）
     *      2、$queryParameter   array   查询参数 ；
     *      3、$separatePage     array   分页参数；
     */
    public function index($subjectType=1, $pageNumber=1, $pageSize=10, $subjectName='')
    {
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'subjectList' => []);
        $message = '';

        $member_id = $this->getLoginUserId();
        if ($member_id == 0)
        {
            // 当前用户没有登录，跳到登录页面进行登陆
            return view('user.wx_user_login');
        }

        try {
            $data = $this->subjectQuery($subjectType, $pageNumber, $pageSize, $subjectName, $member_id);
            $subjectList = $data['subjectList'];
            $queryParameter = $data['queryParameter'];
            $separatePage = $data['separatePage'];
        }
        catch (Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            return view("errors.error", compact('code','message'));
        }

        // return Api::responseMessage($code, $data, $message);
        return view("user.my_collect_list", compact('subjectList','queryParameter','separatePage'));
    }


    /** 收藏某一个主题
     * 传入参数：
     *  $subject_type tinyint(1) 主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
     *  $subject_id  bigint(20) 被收藏主体ID
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      1、$code             int     操作结果代码
     *      2、$data             object  操作主题对象
     *      3、$message          string  出错信息
     */
    function add(Request $request)
    {
        $code = 0;
        $data = null;
        $message = '';

        $grade = 10;
        $member_id = 0;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        }
        else
        {
            // 102 => '用户未登录'
            $code = 102;
            return Api::responseMessage($code, $data, '当前用户没有登录');
        }

        $subject_type = (int)($request->input('subject_type'));
        if ($subject_type != 2 && $subject_type != 3)
        {
            // 2 => "传入参数格式不对",
            $code = 2;
            return Api::responseMessage($code, $data, '当前只支持对商品SPU、SKU的收藏');
        }

        $subject_id = (int)($request->input('subject_id'));
        if ($subject_id <= 0)
        {
            // 2 => "传入参数格式不对",
            $code = 2;
            return Api::responseMessage($code, $data, '传入的主题ID格式不对，传入的为:' . $subject_id);
        }

        // 1品牌;2商品SPU;3商品SKU;4分销商;
        $subject = null;
        switch ($subject_type)
        {
            case 1:
                break;
            case 2:
                $subject = GoodsSpu::select('spu_id', 'spu_name',
                    'spu_plat_price','spu_groupbuy_price',
                    'spu_trade_price','spu_partner_price','main_image')
                    ->where("spu_id", $subject_id)
                    ->first();
                break;
            case 3:
                $subject = GoodsSku::select('sku_id','sku_name','sku_title',
                    'price','groupbuy_price','trade_price','partner_price',
                    'main_image','spu_id')
                    ->where('sku_id', $subject_id)
                    ->first();
                break;
            case 4:
                break;
        }

        if (!$subject)
        {
            // 2 => "传入参数格式不对",
            $code = 2;
            return Api::responseMessage($code, $data, '传入的主题ID不存在，传入的为:' . $subject_id);
        }

        $subject_data = array();
        $subject_name = '';
        switch ($subject_type)
        {
            case 1:
                break;
            case 2:
                $subject_name = $subject->spu_name;
                $price = $subject->spu_plat_price;
                if ($grade > 10) {
                    $price = $this->getSpuPrice($grade, $subject);
                }

                $main_image = $this->getFullPictureUrl($subject->main_image);
                $subject_data = array('price' => $price,'main_image' => $main_image);
                break;
            case 3:
                $subject_name = $subject->sku_title;
                if (!$subject_name) {
                    $subject_name = $subject->sku_name;
                }

                $price = $subject->price;
                if ($grade > 10) {
                    $price = $this->getSkuPrice($grade, $subject);
                }

                $main_image = trim($subject->main_image . '');
                if ($main_image == '')
                {
                    // 主表没有图，从明细表中取
                    $main_image = GoodsSkuImages::where('sku_id', $subject_id)
                        ->where('is_default', 1)
                        ->value('image_url');
                    $main_image = trim($main_image . '');
                    if ($main_image == '')
                    {
                        // 如果SKU没有图片，启用SPU关联的图片
                        $main_image = GoodsSpu::where('spu_id', $subject->spu_id)
                            ->value('main_image');
                    }
                }

                $main_image = $this->getFullPictureUrl($main_image);
                $subject_data = array('price' => $price,'main_image' => $main_image);
                break;
            case 4:
                break;
        }

        // 数组序列化
        $subject_data_str = serialize($subject_data);

        /* 我的收藏表信息项设置
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
          `subject_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '被收藏主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】',
          `subject_id` bigint(20) unsigned NOT NULL COMMENT '被收藏主体ID',
          `subject_name` varchar(250) NOT NULL COMMENT '被收藏主体名称',
          `subject_data` text COMMENT '被收藏主体数据【序列化数组字符串】',
          `description` varchar(500) DEFAULT '' COMMENT '收藏说明',
          `collector` int(10) DEFAULT '0' COMMENT '收藏者（member_id）',
          `collect_time` int(10) DEFAULT '0' COMMENT '收藏时间',
          `collect_status` tinyint(1) DEFAULT '1' COMMENT '收藏状态(0取消收藏;1收藏;)',
        */
        $obj_collect = MemberCollect::where('collector', $member_id)
            ->where('subject_type', $subject_type)
            ->where('subject_id',$subject_id)
            ->first();
        try
        {
            // 是否更新主题信息
            $isNotUpdateSubjectInfo = 0;
            if ($obj_collect)
            {
                $collect_status = (int)($obj_collect->collect_status);
                if ($collect_status == 0)
                {
                    $obj_collect->collect_time = time();
                    $obj_collect->collect_status = 1;
                    $obj_collect->save();

                    $isNotUpdateSubjectInfo = 1;
                }
            }
            else
            {
                $obj_collect = MemberCollect::create([
                    'subject_type'=>$subject_type,
                    'subject_id'=>$subject_id,
                    'subject_name'=>$subject_name,
                    'subject_data'=>$subject_data_str,
                    'description'=>'',

                    'collector' => $member_id,
                    'collect_time'=>time(),
                    'collect_status'=>1
                ]);

                $isNotUpdateSubjectInfo = 1;
            }

            // 更新主题信息，例如 SPU 的收藏总数
            if ($isNotUpdateSubjectInfo)
            {
                // $add_flag       tinyint(1)  增减标识【0减少;1增加】
                $this->updateSubjectInfo($subject_type, $subject_id, 1);
            }

            // 收藏成功
            $code = 0;
        }
        catch (Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        $data = $obj_collect;
        return Api::responseMessage($code, $data, $message);
    }


    /** 撤销对某一个主题的收藏
     * 传入参数：
     *  $subject_type tinyint(1) 主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
     *  $subject_id  bigint(20) 被撤销的主体ID
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      1、$code             int     操作结果代码
     *      2、$data             object  操作主题对象
     *      3、$message          string  出错信息
     */
    function cancel(Request $request)
    {
        $code = 0;
        $data = null;
        $message = '';

        $member_id = $this->getLoginUserId();
        if ($member_id <= 0) {
            // 102 => '用户未登录'
            $code = 102;
            return Api::responseMessage($code, $data, '当前用户没有登录');
        }

        $subject_type = (int)($request->input('subject_type'));
        if ($subject_type != 2 && $subject_type != 3)
        {
            // 2 => "传入参数格式不对",
            $code = 2;
            return Api::responseMessage($code, $data, '当前只支持对商品SPU、SKU的收藏撤销');
        }

        $subject_id = (int)($request->input('subject_id'));
        if ($subject_id <= 0)
        {
            // 2 => "传入参数格式不对",
            $code = 2;
            return Api::responseMessage($code, $data, '传入的主题ID格式不对，传入的为:' . $subject_id);
        }

        $obj_collect = MemberCollect::where('collector', $member_id)
            ->where('subject_type', $subject_type)
            ->where('subject_id',$subject_id)
            ->first();

        // 数据不存在，要给出提示
        if (!$obj_collect)
        {
            // 10001 => '无效的参数数据',
            $code = 10001;
            return Api::responseMessage($code, $data, '传入的主题没有被当前用户收藏');
        }

        try
        {
            // collect_status   收藏状态(0取消收藏;1收藏;)，只有已经收藏的，才能取消
            $collect_status = (int)($obj_collect->collect_status);
            if ($collect_status == 1)
            {
                $obj_collect->collect_time = time();
                $obj_collect->collect_status = 0;
                $obj_collect->save();

                // 更新主题信息，例如 SPU 的收藏总数
                // $add_flag       tinyint(1)  增减标识【0减少;1增加】
                $this->updateSubjectInfo($subject_type, $subject_id, 0);
            }

            $code = 0;
        }
        catch (Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        $data = $obj_collect;
        return Api::responseMessage($code, $data, $message);
    }


    // 收藏主体【1品牌;2商品SPU;3商品SKU;4分销商;】查询
    private function subjectQuery($subject_type=2, $pageNumber=1, $pageSize=10, $subjectName='', $member_id=0)
    {
        // 接口传出数据
        $return_data = array('queryParameter' => [], 'separatePage' => [], 'subjectList' => []);
        $queryParameter = array();
        $separatePage = array();
        $subjectList = array();

        // 格式化传入参数
        $pageNumber = (int)$pageNumber;
        if ($pageNumber <= 0 )
        {
            $pageNumber = 1;
        }

        $pageSize = (int)$pageSize;
        if ($pageSize <= 0)
        {
            $pageSize = 10;
        }

        // 商品名称搜索词
        $subjectName = trim((string)$subjectName);
        if ($subjectName == '0')
        {
            $subjectName = '';
        }

        // 传出的搜索条件及搜索结果排序规格
        $queryParameter = array('subjectName' => $subjectName);

        /* 我的收藏表信息项设置
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
          `subject_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '被收藏主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】',
          `subject_id` bigint(20) unsigned NOT NULL COMMENT '被收藏主体ID',
          `subject_name` varchar(250) NOT NULL COMMENT '被收藏主体名称',
          `subject_data` text COMMENT '被收藏主体数据【序列化数组字符串】',
          `description` varchar(500) DEFAULT '' COMMENT '收藏说明',
          `collector` int(10) DEFAULT '0' COMMENT '收藏者（member_id）',
          `collect_time` int(10) DEFAULT '0' COMMENT '收藏时间',
          `collect_status` tinyint(1) DEFAULT '1' COMMENT '收藏状态(0取消收藏;1收藏;)',
        */
        $db_prefix = $this->db_prefix;
        $stat_item = 'count(id) as allrows';
        $show_item = 'id,subject_id,subject_name,subject_data,description,collect_time ';
        $table = $db_prefix . 'member_collect';
        $inner_join = ' ';

        // 收藏状态 collect_status(0取消收藏;1收藏;)
        $where = ' where collect_status = 1
                 and subject_type = ' . $subject_type . '
                 and collector = ' . $member_id;
        if ($subjectName <> '')
        {
            $where .= " and subject_name like '%" . $subjectName . "%' ";
        }

        // 按照收藏时间倒序排列
        $order_sentence = ' order by collect_time desc ';

        // 1、统计符合查询条件的行数
        $sql_stat = 'select ' . $stat_item . ' from ' . $table . ' ' . $inner_join . ' ' . $where;
        $obj_result = DB::select($sql_stat, []);
        $all_rows = $obj_result[0]->allrows;
        $separatePage = array('allRows' => $all_rows, 'pageNumber' => $pageNumber, 'pageSize' => $pageSize);

        // 2、查询符合条件的商品SPU，以分页列表形式存在
        if ($all_rows > 0) {
            $start_position = ($pageNumber - 1) * $pageSize;
            $sql_query = 'select ' . $show_item . '
            from ' . $table . ' ' .
                $inner_join . ' ' .
                $where . ' ' .
                $order_sentence . '
              limit ' . $start_position . ',' . $pageSize;
            $query_result = DB::select($sql_query, []);
            foreach ($query_result as & $row) {
                $subject_data = unserialize($row->subject_data);
                $this->setSubjectData($row, $subject_type, $subject_data);

                // 删除 subject_data 属性
                unset($row->subject_data);
            }

            if (count($query_result) > 0)
            {
                $subjectList = $query_result;
            }
        }

        // 返回数据
        $return_data = array('subjectList' => $subjectList,
            'queryParameter' => $queryParameter,
            'separatePage' => $separatePage);
        return $return_data;
    }


    /* 根据对象类型及对象属性、属性值，形成完整的对象
        传入参数：
        $subject        原对象     地址操作
        $subject_type    对象类型【1品牌;2商品SPU;3商品SKU;4分销商;】
        $subject_data   需要补充的属性值,数组
    */
    private function setSubjectData(& $subject, $subject_type=1, $subject_data = array())
    {
        // $subject_type 主体【1品牌;2商品SPU;3商品SKU;4分销商;】
        switch ($subject_type)
        {
            case 1:
                break;
            case 4:
                break;
            default:
                // price	    decimal(10,2)   价格
                // main_image	text 		    主图地址
                $subject->price = 0.00;
                $subject->main_image = "";
                if (count($subject_data) > 0)
                {
                    if (array_key_exists('price', $subject_data)) {
                        $subject->price = $subject_data['price'];
                    }

                    if (array_key_exists('main_image', $subject_data)) {
                        $subject->main_image =$this->getFullPictureUrl($subject_data['main_image']);
                    }
                }
                break;
        }
    }


    /* 更新主题信息，主要是收藏总数
        传入参数：
        $subject_type   tinyint(1)  主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
        $subject_id     bigint(20)  主题ID
        $add_flag       tinyint(1)  增减标识【0减少;1增加】
        $subject_data   需要补充的属性值,数组
    */
    private function updateSubjectInfo($subject_type = 2, $subject_id = 0, $add_flag = 1)
    {
        $sql = '';
        $return_value = 0;

        $table = '';
        $field = '';
        $primaryKey = '';
        $db_prefix = $this->db_prefix;
        switch ($subject_type)
        {
            case 1:
                break;
            case 2:
                // update yyd_goods_spu set collect_count = collect_count + 1 where spu_id = 135;
                $table = $db_prefix . 'goods_spu';
                $field = 'collect_count';
                $primaryKey = 'spu_id';
                break;
            case 3:
                // update yyd_goods_sku set collect_count = collect_count + 1 where sku_id = 135;
                $table = $db_prefix . 'goods_sku';
                $field = 'collect_count';
                $primaryKey = 'sku_id';
                break;
            case 4:
                break;
            default:
                break;
        }

        if ($table != '' && $field != '' && $primaryKey)
        {
            $expression = $field . ' + 1 ';
            if ($add_flag == 0)
            {
                $expression = $field . ' - 1';
            }

            $sql = 'update ' . $table . '
                  set ' . $field . ' = ' . $expression .'
                  where ' . $primaryKey . ' = ' . $subject_id ;
        }

        if ($sql != '')
        {
            $return_value = DB::update($sql, []);
        }

        return $return_value;
    }
}