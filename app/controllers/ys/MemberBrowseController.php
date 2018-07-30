<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/1/16
 * Time: 11:46
 */
namespace App\controllers\ys;

use App\facades\Api;
use App\models\member\MemberBrowse;

use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberBrowseController extends BaseController
{
    /** 获取当前登录用户浏览主题列表
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
    public function index($subject_type=1, $pageNumber=1, $pageSize=10, $subjectName='')
    {
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'subjectList' => []);
        $message = '';

        $member_id = $this->getLoginUserId();
        if ($member_id == 0)
        {
            // 当前用户没有登录，跳到登录页面进行登陆
            return redirect('/oauth');
        }

        try {
            $data = $this->subjectQuery($subject_type, $pageNumber, $pageSize, $subjectName, $member_id);
            $subjectList = $data['subjectList'];
            $queryParameter = $data['queryParameter'];
            $separatePage = $data['separatePage'];
        }
        catch (Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            return view("errors.error", compact('code','message'));
        }

//        return Api::responseMessage($code, $data, $message);
         return view("user.my_browse_list", compact('subjectList','queryParameter','separatePage'));
    }


    /** 浏览某一个主题
     * 传入参数：
     *  $subject_type tinyint(1) 主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
     *  $subject_id  bigint(20) 被浏览主体ID
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      1、$code             int     操作结果代码
     *      2、$data             object  操作主题对象
     *      3、$message          string  出错信息
     */
    public function add(Request $request)
    {
        $code = 0;
        $data = null;
        $message = '';

        try
        {
            $subject_type = (int)($request->input('subject_type'));
            $subject_id = (int)($request->input('subject_id'));
            $data = $this->saveMyBrowse($subject_type, $subject_id, $code, $message);
        }
        catch (Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        return Api::responseMessage($code, $data, $message);
    }


    /** 保存我的浏览（足迹），参数合法性由外部验证，如果参数不合格，传出空足迹对象
     * 传入参数：
     *  $subject_type tinyint(1) 主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
     *  $subject_id  bigint(20) 被浏览主体ID
     *  $err_code   int         参数验证错误码
     *  $err_message string     参数验证错误信息
     *  传出参数
     *      $obj_browse object  足迹对象
     */
    public function saveMyBrowse($subject_type = 2, $subject_id = 0, & $err_code = 0, & $err_message = '')
    {
        $grade = 10;
        $member_id = 0;
        $obj_browse = null;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        }
        else
        {
            // 102 => '用户未登录'
            $err_code = 102;
            $err_message = '当前没有登录用户';
            return $obj_browse;
        }

        $subject_type = (int)$subject_type;
        if ($subject_type != 2 && $subject_type != 3)
        {
            // 2 => "传入参数格式不对"
            $err_code = 2;
            $err_message = '当前只支持对商品SPU、SKU的浏览';
            return $obj_browse;
        }

        $subject_id = (int)$subject_id;
        if ($subject_id <= 0)
        {
            // 2 => "传入参数格式不对"
            $err_code = 2;
            $err_message = '传入的主题ID格式不对，传入的为:' . $subject_id;
            return $obj_browse;
        }

        // 如果已经保存有足迹（浏览）信息，直接退出
        $obj_browse = MemberBrowse::where('browser', $member_id)
            ->where('subject_type', $subject_type)
            ->where('subject_id',$subject_id)
            ->first();
        if ($obj_browse)
        {
            // 数据库中已保存有记录，直接退出
            return $obj_browse;
        }
        else
        {
            $obj_browse = null;
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
            // 50000 => '商品sku_id不存在'
            $err_code = 50000;
            $err_message = '传入的主题ID不存在，传入的为:' . $subject_id;
            return $obj_browse;
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

        /* 我的浏览表信息项设置
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
          `subject_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '被浏览主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】',
          `subject_id` bigint(20) unsigned NOT NULL COMMENT '被浏览主体ID',
          `subject_name` varchar(250) NOT NULL COMMENT '被浏览主体名称',
          `subject_data` text COMMENT '被浏览主体数据【序列化数组字符串】',
          `browser` int(10) DEFAULT '0' COMMENT '浏览者（member_id）',
          `browse_time` int(10) DEFAULT '0' COMMENT '浏览时间',
        */
        try
        {
            if (!$obj_browse)
            {
                $obj_browse = MemberBrowse::create([
                    'subject_type'=>$subject_type,
                    'subject_id'=>$subject_id,
                    'subject_name'=>$subject_name,
                    'subject_data'=>$subject_data_str,

                    'browser' => $member_id,
                    'browse_time'=>time()
                ]);

                // 更新主题信息，例如 SPU 的浏览总数
                $this->updateSubjectInfo($subject_type, $subject_id, 1);
            }
        }
        catch (Exception $e) {
            return null;
        }

        return $obj_browse;
    }


    // 浏览主体【1品牌;2商品SPU;3商品SKU;4分销商;】查询
    private function subjectQuery($subject_type=1, $pageNumber=1, $pageSize=10, $subjectName='', $member_id=0)
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

        /* 我的浏览表信息项设置
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
            `subject_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '被浏览主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】',
            `subject_id` bigint(20) unsigned NOT NULL COMMENT '被浏览主体ID',
            `subject_name` varchar(250) NOT NULL COMMENT '被浏览主体名称',
            `subject_data` text COMMENT '被浏览主体数据【序列化数组字符串】',
            `browser` int(10) DEFAULT '0' COMMENT '浏览者（member_id）',
            `browse_time` int(10) DEFAULT '0' COMMENT '浏览时间'
        */
        $db_prefix = $this->db_prefix;
        $stat_item = 'count(id) as allrows';
        $show_item = 'id,subject_id,subject_name,subject_data,browse_time ';
        $table = $db_prefix . 'member_browse';
        $inner_join = ' ';

        // 浏览状态 collect_status(0取消浏览;1浏览;)
        $where = ' where subject_type = ' . $subject_type . '
                 and browser = ' . $member_id;
        if ($subjectName <> '')
        {
            $where .= " and subject_name like '%" . $subjectName . "%' ";
        }

        // 按照浏览时间倒序排列
        $order_sentence = ' order by browse_time desc ';

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


    /* 更新主题信息，主要是浏览总数
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
                // update yyd_goods_spu set click_count = click_count + 1 where spu_id = 135;
                $table = $db_prefix . 'goods_spu';
                $field = 'click_count';
                $primaryKey = 'spu_id';
                break;
            case 3:
                // update yyd_goods_sku set click_count = click_count + 1 where sku_id = 135;
                $table = $db_prefix . 'goods_sku';
                $field = 'click_count';
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