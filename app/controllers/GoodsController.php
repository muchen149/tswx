<?php

namespace App\controllers;

use App\facades\Api;
use App\models\goods\GoodsClass;
use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSpuImages;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;
use App\models\goods\GoodsServe;

use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;
use App\models\member\MemberCollect;

use App\models\member\MemberCart;

use App\models\plat\PlatSetting;
use App\models\tpl\TplTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 *  商品类
 * @author      :lishuo
 * Class        :GoodsController
 * @package     :App\controllers
 */
class GoodsController extends BaseController
{
    /** 商城首页
     * 传入参数：
     *  $pageNumber int 当前页码，默认第一页，可选参数
     *  $pageSize   int 每页数量，默认10行，可选参数
     *  $gcId       int 商品三级分类ID，默认所有（0），可选参数
     *  $goodsName  int 商品搜索词，默认空，可选参数
     *  传出 view 对象，其中数据有：
     *      1、$goodsSpuList     array   SPU列表（数组）
     *      2、$queryParameter   array   查询参数 ；
     *      3、$separatePage     array   分页参数；
     */
    public function index($pageNumber = 1, $pageSize = 10, $gcId = 0, $goodsName = '', $orderBy = 3,$gcType = 0) {
		//工银E生活 返回键
        //return redirect('/elife/eLifeIndex');
		
        // 当前登录用户信息
        $member = Auth::user();
        $grade = 10;
        if ($member) {
            $member_id = $member->member_id;
            $member_exp = $member->exp_member;
            $grade = $member->grade;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }


        /*$queryParameter = array();
        $separatePage = array();
        $goodsSpuList = array();*/
        $advertList = array('advert_a0100' => [], 'advert_a0121_a0131' => [],
            'advert_a0123_a0133' => [], 'advert_a0113' => []);
        $num_gcId_2 = [];
        try {
            // 类别查询
            $num_gcId_1 = GoodsClass::where('pid',0)->where('state',0)->where('is_use',1)->orderBy('sort')->get(['id'])->toArray();
            foreach($num_gcId_1 as $key=>$cId_1){
                $gcId_2 = GoodsClass::where('pid','<>',0)->where('pid',$cId_1['id'])->where('image_url','<>','NULL')->where('state',0)->orderBy('sort');
                $num_gcId = $gcId_2->get(['id','name','image_url','out_url'])->toArray();
                $num_gcId_2 = array_merge($num_gcId_2,$num_gcId);
            }

            // -------------------A、广告信息--------------------
            // 广告信息项：广告ID（advert_id）、广告标题（advert_title）,广告图片地址（images）、外链接（out_url）
            // 1、A0100	APP首页幻灯片(banner) 4张
            $advertList['advert_a0100'] = $this->getAdvertList("'A0100'", 4);

            /* 2、APP首页中部【左上、右上】
            A0121	APP首页中部左上
            A0131	APP首页中部右上
            */
            $advertList['advert_a0121_a0131'] = $this->getAdvertList("'A0121','A0131'", 2);

            /* 2、APP首页中部【左下、右下】
            A0123	APP首页中部左下
            A0133	APP首页中部右下
            */
            $advertList['advert_a0123_a0133'] = $this->getAdvertList("'A0123','A0133'", 2);

            // 4、A0113	APP首页中部通栏    2张
            $advertList['advert_a0113'] = $this->getAdvertList("'A0113'", 2);


            // -------------------B、SPU信息-------------------------
            // $spu_data 格式为：array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
            /*$spu_data = $this->spuQuery($pageNumber, $pageSize, $gcId, $goodsName, 0);
            $goodsSpuList = $spu_data['goodsSpuList'];
            $queryParameter = $spu_data['queryParameter'];
            $separatePage = $spu_data['separatePage'];*/

            //------------------ spu列表 ---------------------------
            /*$data = $this->spuQuery($pageNumber, $pageSize, $gcId, $goodsName, $orderBy,$gcType);
            $goodsSpuList = $data['goodsSpuList'];
            $queryParameter = $data['queryParameter'];
            $separatePage = $data['separatePage'];*/

            //------------------ 商品推荐 --------------------------
            $db_prefix = $this->db_prefix;
            $sql_query = "select index_shop_recommend_id,index_shop_recommend_name,index_shop_recommend_descrition,is_display,out_url,style,gifts_list,image_url
                      from " . $db_prefix . "index_shop_recommend_list
                      where is_usable = 1
                      order by sort desc ";
            $query_result = DB::select($sql_query, []);
            //遍历各个标签，得到标签下各个spu信息
            $label_goods_list = array();
            foreach($query_result as $k => $label){
                $tem = array();
                $tem['index_shop_recommend_id'] = $label->index_shop_recommend_id;
                $tem['index_shop_recommend_name'] = $label->index_shop_recommend_name;
                $tem['index_shop_recommend_descrition'] = $label->index_shop_recommend_descrition;
                $tem['image_url'] = $this->img_domain.'/'.$label->image_url;
                $tem['is_display'] = $label->is_display;
                $tem['out_url'] = $label->out_url;
                $tem['style'] = $label->style;
                $tem['g_list'] = array();
                $goods_list = unserialize($label->gifts_list);
                foreach($goods_list as $key => $good){
                    $g_info = array();
                    //取出每个商品spu的规格
                    $goods = GoodsSpu::select('spu_id', 'spu_code','ad_info',
                        'gc_id', 'gb_name', 'keywords',
                        'spu_name', 'ad_link_url', 'spu_attr',
                        'spec_name', 'spec_value',
                        'spu_market_price', 'spu_plat_price',
                        'spu_groupbuy_price', 'spu_trade_price',
                        'spu_partner_price', 'spu_points_limit', 'is_virtual',
                        'main_image', 'mobile_content', 'state')
                        ->where("spu_id", $good['spu_id'])
                        ->first();
                    // SPU 不存在，直接退出
                    if (!$goods) {
                        continue;
                    }

                    $g_info['spu_id'] = $goods->spu_id;
                    $g_info['spu_name'] = $good['spu_name']; //商品名称和图片读取序列化中的
                    $g_info['ad_info'] = strip_tags($goods->ad_info);
                    $g_info['main_image'] = $this->getFullPictureUrl($good['main_image']);
                    $g_info['spu_plat_price'] = $goods->spu_plat_price;
                    $g_info['spu_market_price'] = $goods->spu_market_price;
                    $g_info['spu_partner_price'] = $goods->spu_partner_price;
                    $g_info['spu_trade_price'] = $goods->spu_trade_price;
                    $g_info['spu_groupbuy_price'] = $goods->spu_groupbuy_price;

                    //$g_info['spu_price'] = $goods->spu_plat_price;//20170911-根据会员等级取得相应价格信息
                    $g_info['spu_price'] = $this->getSpuPrice($grade, $goods);

                    // 3、SPU规格(名称、值)
                    /* SPU规格值（spec_value），序列化数组，数据格式如下：
                    Array (
                        [1] => Array (
                                [126] => 黑西装,
                                [127] => 黑西装+半裙,
                                [128] => 黑西装+衬衫+西裤,
                                [129] => 四件套裙裤),
                        [104] => Array (
                                [114] => 165/92A,
                                [117] => 170/100A)
                        )

                    这里：1、104 为spec_id；
                          126、127、129、114、117等为 spec_value_id
                    */
                    $goods->spec_value = (unserialize($goods->spec_value) == false ?
                        [] : unserialize($goods->spec_value));
                    $spuSpec = [];
                    foreach ($goods->spec_value as $g_key => $item) {
                        // $item 为规格值数组，$g_key 为spec_id；
                        $spec = GoodsSpecDefine::find($g_key);
                        $data = [
                            'data_type' => $spec->data_type,
                            'spec_name' => $spec->name,
                            'spec_value' => []
                        ];

                        foreach ($item as $key1 => $value) {
                            $data['spec_value'][$key1] = $value;
                        }

                        // $data 信息项：规格名、规格值（数组）、规格值类型
                        array_push($spuSpec, $data);
                    }
                    $g_info['spuSpec'] = $spuSpec;

                    array_push($tem['g_list'], $g_info);
                }

                array_push($label_goods_list, $tem);
            }

            //统计购物车商品数量
            $goods_num_in_cart = $this->shop_cart();

            //搜索热词
            $hotSearch = DB::table('goods_search')->where('state','0')->orderBy('sort')->get(['name']);
        } catch (\Exception $e) {
            return view("errors.error");
        }

        //获取虚拟币名称【外部简称】
        /*$plat_vrb_caption = $this->getPlatVrbCaption();
        print_r($goodsSpuList);die;
        $data = array('advert_data' => $advertList, 'spu_data' => $spu_data);
        return Api::responseMessage(0, $data, '测试');

        dd([
                'advertList' => $advertList,
                'goodsSpuList' => $goodsSpuList,
                'queryParameter' => $queryParameter,
                'separatePage' => $separatePage,
                'plat_vrb_caption' => $plat_vrb_caption,
        ]);*/

        //服务板块
        /*$one_serve_class = [];
        $two_serve_class = [];
        $three_serve_class = [];

        $one_class_arr = GoodsServe::where('pid', 0)->where('state', 0)->orderBy('sort')->get()->toArray();
        $one_serve_class = $one_class_arr;

        foreach ($one_class_arr as $k => $v) {
            $two_class_arr = GoodsServe::where('pid', $v['id'])->where('state', 0)->where('image_url','<>','')->orderBy('sort')->get()->toArray();
            $two_serve_class[$v['id']] = $two_class_arr;
            foreach ($two_serve_class[$v['id']] as $kk => $vv) {
                $two_serve_class[$v['id']][$kk]['image_url']= $this->img_domain.'/'.$vv['image_url'];
                $three_class_arr = GoodsServe::where('pid', $vv['id'])->where('state', 0)->where('image_url','<>','')->orderBy('sort')->get()->toArray();
                $three_serve_class[$vv['id']] = $three_class_arr;
                foreach ($three_serve_class[$vv['id']] as $kkk => $vvv){
                    $three_serve_class[$vv['id']][$kkk]['image_url']= $this->img_domain.'/'.$vvv['image_url'];
                }
            }
        }*/

        //微信jsapi
        $signPackage = session('signPackage');

        $subscribe = $this->getSubscribe($member_id);


        return view("sd_mall.welcome", compact('member_exp','advertList','queryParameter', 'separatePage','plat_vrb_caption','signPackage','goods_num_in_cart','subscribe','orderBy','hotSearch','num_gcId_2','label_goods_list'));
    }

    /** 获取某一个广告位可投放的广告，以数组格式返回
     * 传入参数：
     *  $position_code string 广告位，数据格式为：“'A0100','A0111'”，空字符串为所有；
     *  $advert_num  int 广告数量，默认1行
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      $advertList     array   广告列表（数组）
     */
    private function getAdvertList($position_code = '', $advert_num = 1)
    {
        $advertList = array();
        $position_code = trim($position_code . '');
        $advert_num = (int)$advert_num;
        if ($advert_num <= 0) {
            $advert_num = 1;
        }

        // 当前时间
        $current_time = time();
        $db_prefix = $this->db_prefix;
        $where = " where client_flag = 'A'
                    and advert_state = 1
                    and (put_start_time = 0 or put_start_time <= " . $current_time . ")
                    and (put_end_time=0 or put_end_time >=" . $current_time . ")";
        if ($position_code <> "") {
            // 广告位条件
            $where .= " and position_code in (" . $position_code . ")";
        }

        // 1、统计符合查询条件的行数
        $sql_query = "select advert_id,advert_title,images,out_url
                    from " . $db_prefix . "advert " .
            $where .
            " order by put_weight desc
                    limit 0," . $advert_num;
        $query_result = DB::select($sql_query, []);
        foreach ($query_result as $row) {
            // 广告图片地址是相对目录，要添加上域名
            $row->images = $this->getFullPictureUrl($row->images);
        }

        if (count($query_result) > 0) {
            $advertList = $query_result;
        }

        return $advertList;
    }

    private function spuQuery($pageNumber = 1, $pageSize = 10, $gcId = 0, $goodsName = '', $orderBy = 0,$gcType=0)
    {
        // 如果当前有登录用户，要返回商品收藏标识
        $grade = 10;
        $member_id = 0;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        }

        // 接口传出数据
        $return_data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $queryParameter = array();
        $separatePage = array();
        $goodsSpuList = array();

        // 格式化传入参数
        $pageNumber = (int)$pageNumber;
        if ($pageNumber <= 0) {
            $pageNumber = 1;
        }

        $pageSize = (int)$pageSize;
        if ($pageSize <= 0) {
            $pageSize = 10;
        }

        $gcId = (int)$gcId;
        if ($gcId <= 0) {
            $gcId = 0;
        }

        // 商品名称搜索词
        $goodsName = trim((string)$goodsName);
        if ($goodsName == '0') {
            $goodsName = '';
        }

        // 排序项【0综合、1销量、2价格、3人气】
        $orderBy = (int)$orderBy;
        if ($orderBy != 0 && $orderBy != 1 && $orderBy != 2 && $orderBy != 3) {
            $orderBy = 0;
        }

        // 传出的搜索条件及搜索结果排序规格
        $queryParameter = array('gcId' => $gcId, 'goodsName' => $goodsName, 'orderBy' => $orderBy,'gcType'=>$gcType);


        /*
            spu_plat_price      平台价格【官网价】
            spu_market_price    市场价【国内市场价格】
            spu_groupbuy_price  团购价
            spu_trade_price     批发价
            spu_partner_price   (分销伙伴)抄底价
        */
        $db_prefix = $this->db_prefix;
        $stat_item = 'count(a.spu_id) as allrows';
        $show_item = 'a.spu_id,a.spu_code,a.spu_name,
                    b.name as gc_name,a.gb_name,
                    a.spec_name,a.spec_value,
                    a.spu_market_price,a.spu_plat_price,
                    a.spu_groupbuy_price,a.spu_trade_price,
                    a.spu_partner_price,a.spu_points_limit,
                    a.main_image,a.updated_at,
                    a.collect_count as collect ';
        $table = $db_prefix . 'goods_spu as a ';
        $inner_join = ' left join ' . $db_prefix . 'goods_class as b on a.gc_id = b.id ';

        // 收藏状态(0没有收藏;1收藏;)
        if ($member_id) {
            /*
                subject_type tinyint(1)     被收藏主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】
                subject_id  bigint(20)      被收藏主体ID
                collector   int(10)         收藏者（member_id）
                collect_status  tinyint(1)  收藏状态(0取消收藏;1收藏;)
            */
            $show_item .= ',n.collect_status ';
            $inner_join .= ' left join ' . $db_prefix . 'member_collect as n
                            on a.spu_id = n.subject_id
                                and n.subject_type = 2
                                and n.collector = ' . $member_id . ' ';
        } else {
            $show_item .= ',0 as collect_status';
        }

        // 排序项【0综合、1销量、2价格、3人气】
        if ($orderBy == 1) {
            $show_item .= ',m.salenum ';
            $inner_join .= ' left join (
                            select spu_id,sum(salenum_count) as salenum
                            from ' . $db_prefix . 'goods_sku
                            group by spu_id) as m on a.spu_id = m.spu_id ';
        }

        // state 商品状态【0:未上架（待上架）; 1:上架在售; 2:自主下架; 3:系统下架（违规下架）;
        //                5:初建待申请;7:待审核;8:审核不通过;】',
        $where = ' where a.state = 1 ';
        if ($gcId) {
            if($gcType==1){
                $where .= ' and a.gc_id_1 = ' . $gcId;
            }else if($gcType==2){
                $where .= ' and a.gc_id_2 = ' . $gcId;
            }else{
                $where .= ' and a.gc_id = ' . $gcId;
            }
        }

        if ($goodsName <> '') {
            $like_name= $this->like_name($goodsName);
            // 名称、分类、品牌、关键词等模糊匹配查询
            $where .= " and (a.spu_name like '%" . $like_name . "%'
                            or a.gc_name like '%" . $like_name . "%'
                            or a.gb_name like '%" . $like_name . "%'
                            or a.keywords like '%" . $like_name . "%')";
        }

        // 排序语句，排序项【0综合、1销量、2价格、3人气】 m.salenum,m.collect
        $order_sentence = ' order by a.updated_at desc ';
        switch ($orderBy) {
            case 1:
                $order_sentence = ' order by m.salenum desc ';
                break;
            case 2:
                $order_sentence = ' order by a.spu_plat_price desc ';
                break;
            case 3:
                $order_sentence = ' order by a.collect_count desc ';
                break;
        }

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
                $row->spec_name = unserialize($row->spec_name);
                $row->spec_value = unserialize($row->spec_value);

                // 图片地址是相对目录，要添加上域名
                $row->main_image = $this->getFullPictureUrl($row->main_image);

                // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
                // 显示不同的销售价格
                $row->spu_price = $this->getSpuPrice($grade, $row);
            }

            if (count($query_result) > 0) {
                $goodsSpuList = $query_result;
            }
        }

        // 返回数据
        $return_data = array('goodsSpuList' => $goodsSpuList,
            'queryParameter' => $queryParameter,
            'separatePage' => $separatePage);
        return $return_data;
    }

    /**
     * 购物车数量
     * return number
     */
    private function shop_cart()
    {
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        }else {
            redirect('/oauth');
        }
        //统计购物车中商品的总数量，显示出目前购物车中商品中的数量
        $goods_num_in_cart = MemberCart::where('member_id', $member_id)->sum('number');
        return $goods_num_in_cart;
    }

    /**
     * 模糊查询精准定位
     * return array
     */
    private function like_name($str)
    {
        $length = mb_strlen($str, 'utf-8');
        $array = [];
        for ($i=0; $i<$length; $i++)
            $array[] = mb_substr($str, $i, 1, 'utf-8');
        $length = mb_strlen($str, 'utf-8');
        $array = [];
        for ($i=0; $i<$length; $i++){
            $array[] = mb_substr($str, $i, 1, 'utf-8');
        }
        $like_name = implode("%",$array);
        return $like_name;
    }

    /**
     * 获取商品分类列表
     */
    public function goodsClassList()
    {
        $one_goods_class = [];
        $two_goods_class = [];
        $three_goods_class = [];

        //应先从缓存中读取分类，现在暂未实现
        /*...*/

        $one_class_arr = GoodsClass::where('pid', 0)->where('state', 0)->get()->toArray();
        $one_goods_class = $one_class_arr;
        foreach ($one_class_arr as $k => $v) {
            $two_class_arr = GoodsClass::where('pid', $v['id'])->where('state', 0)->get()->toArray();
            $two_goods_class[$v['id']] = $two_class_arr;
            foreach ($two_class_arr as $kk => $vv) {
                $three_class_arr = GoodsClass::where('pid', $vv['id'])->where('state', 0)->get()->toArray();
                $three_goods_class[$vv['id']] = $three_class_arr;
            }
        }

        return view("goods.goods_class", compact('one_goods_class', 'two_goods_class', 'three_goods_class'));
    }

    /** 获取商品SPU列表
     * 传入参数：
     *  $pageNumber int     当前页码，默认第一页
     *  $pageSize   int     每页数量，默认10行
     *  $gcId       int     商品三级分类ID，默认所有（0）
     *  $goodsName  int     商品搜索词，默认空
     *  $orderBy    string  搜索词，如果没有可传入“0”
     *  $orderBy    int     排序项【0综合、1销量、2价格、3人气】
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      1、$goodsSpuList     array   SPU列表（数组）
     *      2、$queryParameter   array   查询参数 ；
     *      3、$separatePage     array   分页参数；
     */
    public function spuList($pageNumber = 1, $pageSize = 10, $gcId = 0, $goodsName = '', $orderBy = 0,$gcType = 0)
    {
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $message = '';

        try {
            $data = $this->spuQuery($pageNumber, $pageSize, $gcId, $goodsName, $orderBy,$gcType);
            $goodsSpuList = $data['goodsSpuList'];
            $queryParameter = $data['queryParameter'];
            $separatePage = $data['separatePage'];
            //统计购物车商品数量
            $goods_num_in_cart = $this->shop_cart();
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        $goodsName = trim((string)$goodsName);
        if ($goodsName == '0') {
            $goodsName = '';
        }

        return view("sd_goods.good_list", compact('goodsSpuList', 'queryParameter', 'separatePage', 'goodsName', 'orderBy','goods_num_in_cart'));
    }


    // 商城商品SPU 查询

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param int $gcId
     * @param string $goodsName
     * @param int $orderBy
     * @return mixed
     * 首页懒加载商品列表
     *
     */

    public function ajax_spuList($pageNumber = 1, $pageSize = 10, $gcId = 0, $orderBy = 0, $goodsName = '',$gcType=0)
    {
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $message = '';

        try {
            $data = $this->spuQuery($pageNumber, $pageSize, $gcId, $goodsName, $orderBy,$gcType);

        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        return Api::responseMessage($code, $data, $message);

    }

    public function ajax_shopSpuList($pageNumber = 1, $pageSize = 10, $gcId = 0, $orderBy = 0, $goodsName = '')
    {
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $message = '';

        try {
            $data = $this->spuShopQuery($pageNumber, $pageSize, $gcId, $goodsName, $orderBy);

        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        return Api::responseMessage($code, $data, $message);

    }

    private function spuShopQuery($pageNumber = 1, $pageSize = 10, $gcId = 0, $goodsName = '', $orderBy = 0)
    {
        // 如果当前有登录用户，要返回商品收藏标识
        $grade = 10;
        $member_id = 0;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        }

        // 接口传出数据
        $return_data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $queryParameter = array();
        $separatePage = array();
        $goodsSpuList = array();

        // 格式化传入参数
        $pageNumber = (int)$pageNumber;
        if ($pageNumber <= 0) {
            $pageNumber = 1;
        }

        $pageSize = (int)$pageSize;
        if ($pageSize <= 0) {
            $pageSize = 10;
        }

        $gcId = (int)$gcId;
        if ($gcId <= 0) {
            $gcId = 0;
        }

        // 商品名称搜索词
        $goodsName = trim((string)$goodsName);
        if ($goodsName == '0') {
            $goodsName = '';
        }

        // 排序项【0综合、1销量、2价格、3人气】
        $orderBy = (int)$orderBy;
        if ($orderBy != 0 && $orderBy != 1 && $orderBy != 2 && $orderBy != 3) {
            $orderBy = 0;
        }

        // 传出的搜索条件及搜索结果排序规格
        $queryParameter = array('gcId' => $gcId, 'goodsName' => $goodsName, 'orderBy' => $orderBy);


        /*
            spu_plat_price      平台价格【官网价】
            spu_market_price    市场价【国内市场价格】
            spu_groupbuy_price  团购价
            spu_trade_price     批发价
            spu_partner_price   (分销伙伴)抄底价
        */
        $db_prefix = $this->db_prefix;
        $stat_item = 'count(a.spu_id) as allrows';
        $show_item = 'a.spu_id,a.spu_code,a.spu_name,
                    b.name as gc_name,a.gb_name,
                    a.spec_name,a.spec_value,
                    a.spu_market_price,a.spu_plat_price,
                    a.spu_groupbuy_price,a.spu_trade_price,
                    a.spu_partner_price,a.spu_points_limit,
                    a.main_image,a.updated_at,
                    a.collect_count as collect ';
        $table = $db_prefix . 'goods_spu as a ';
        $inner_join = ' left join ' . $db_prefix . 'goods_class as b on a.gc_id = b.id ';

        // 收藏状态(0没有收藏;1收藏;)
        if ($member_id) {
            /*
                subject_type tinyint(1)     被收藏主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】
                subject_id  bigint(20)      被收藏主体ID
                collector   int(10)         收藏者（member_id）
                collect_status  tinyint(1)  收藏状态(0取消收藏;1收藏;)
            */
            $show_item .= ',n.collect_status ';
            $inner_join .= ' left join ' . $db_prefix . 'member_collect as n
                            on a.spu_id = n.subject_id
                                and n.subject_type = 2
                                and n.collector = ' . $member_id . ' ';
        } else {
            $show_item .= ',0 as collect_status';
        }

        // 排序项【0综合、1销量、2价格、3人气】
        if ($orderBy == 1) {
            $show_item .= ',m.salenum ';
            $inner_join .= ' left join (
                            select spu_id,sum(salenum_count) as salenum
                            from ' . $db_prefix . 'goods_sku
                            group by spu_id) as m on a.spu_id = m.spu_id ';
        }

        // state 商品状态【0:未上架（待上架）; 1:上架在售; 2:自主下架; 3:系统下架（违规下架）;
        //                5:初建待申请;7:待审核;8:审核不通过;】',
        $where = ' where a.state = 1 ';
        if ($gcId) {
            $where .= ' and a.gc_id_3 = ' . $gcId;
        }

        if ($goodsName <> '') {
            // 名称、分类、品牌、关键词等模糊匹配查询
            $where .= " and (a.spu_name like '%" . $goodsName . "%'
                            or a.gc_name like '%" . $goodsName . "%'
                            or a.gb_name like '%" . $goodsName . "%'
                            or a.keywords like '%" . $goodsName . "%')";
        }

        // 排序语句，排序项【0综合、1销量、2价格、3人气】 m.salenum,m.collect
        $order_sentence = ' order by a.updated_at desc ';
        switch ($orderBy) {
            case 1:
                $order_sentence = ' order by m.salenum desc ';
                break;
            case 2:
                $order_sentence = ' order by a.spu_plat_price desc ';
                break;
            case 3:
                $order_sentence = ' order by a.collect_count desc ';
                break;
        }

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
                $row->spec_name = unserialize($row->spec_name);
                $row->spec_value = unserialize($row->spec_value);

                // 图片地址是相对目录，要添加上域名
                $row->main_image = $this->getFullPictureUrl($row->main_image);

                // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
                // 显示不同的销售价格
                $row->spu_price = $this->getSpuPrice($grade, $row);
            }

            if (count($query_result) > 0) {
                $goodsSpuList = $query_result;
            }
        }

        // 返回数据
        $return_data = array('goodsSpuList' => $goodsSpuList,
            'queryParameter' => $queryParameter,
            'separatePage' => $separatePage);
        return $return_data;
    }

    /** 商品SPU详情
     *  传入参数
     *      $spuId  int     商品SPU id
     *  传出关联的SPU 详情信息
     */
    public function spuDetail($spuId = 0)
    {
        // 会员类别($member_grade)【10:普通会员;20:黄金会员;30:钻石会员;40:黑卡VIP】
        $grade = 10;
        $member_id = 0;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        }
        $member_class_arr = PlatSetting::memberClassWithNotFree()->get()->toArray();
        $member_class_arr = $this->getMemberClass($member_class_arr);

        /*if($grade==10)*/
           /* $supG=$grade+20;
        else*/
        if($grade <30){
            $supG=$grade+10;
        }else{
            $supG=$grade;
        }

        $isHighest=0;
        $nextPriv=[];
        if($supG!=50 && $supG < 40)
            $nextPriv=$member_class_arr[$supG];
        else
            $isHighest=1;

        // 1、SPU基本信息
        /* spu 价格体系：
        spu_market_price	市场价【通过模型计算的国内市场均价，指导价】
        spu_plat_price		平台价格【官网价、官网零售价】
        spu_groupbuy_price	团购价
        spu_trade_price		批发价
        spu_partner_price	(分销伙伴)抄底价【成本价 + 管理费】
        spu_cost_price		成本价【平台进价 + 运费】
        */
        $spuId = (int)$spuId;
        $goods = GoodsSpu::select('spu_id', 'spu_code',
            'gc_id', 'gb_name', 'keywords',
            'spu_name', 'ad_info' ,'ad_link_url', 'spu_attr',
            'spec_name', 'spec_value',
            'spu_market_price', 'spu_plat_price',
            'spu_groupbuy_price', 'spu_trade_price',
            'spu_partner_price', 'spu_points_limit', 'is_virtual',
            'main_image', 'mobile_content', 'state','from_plat_code',
            'from_plat_name','tpl_transport_id',
            'importType')
            ->where("spu_id", $spuId)
            ->first();

        // SPU 不存在，直接退出
        if (!$goods) {
            return view('errors.error');
        }

        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $spbsel= "SELECT spb.supplier_name FROM  ".$db_prefix."supplier_baseinfo spb where spb.supplier_id in(
            SELECT  spsku.supplier_id from ".$db_prefix."supplier_goods_sku spsku where sku_id in
            (SELECT sku_id from ".$db_prefix."goods_sku sku where sku.spu_id=".$spuId."))";
        $spb_name=DB::select($spbsel);
        $p_name='';
        if(!empty($spb_name)){
            $p_name=$spb_name[0]->supplier_name.$p_name;
            $goods->from_plat_name=$p_name;
        }
        //运费模板
        if($goods->tpl_transport_id != 0){
            $tpl = TplTransport::select('isFreeDelivery','limitNum')->where('id',$goods->tpl_transport_id)->first();
        }
        //相似推荐
        $rmdData=$this->spuLikeRmdQuery($grade,$spuId);
        $rmdSpuList=$rmdData['likeRmdSpuList'];
        // 1、处理图片地址
        $goods->main_image = $this->getFullPictureUrl($goods->main_image);

        // 2、价格【不存在规格的时候，显示sku的价格】，(衡问：)为什么这样处理？不明白，暂时屏蔽
        /* SKU 价格体系：
                market_price		市场价【通过模型计算的国内市场均价，指导价】
                price			    商品价格【官网价、官网零售价】
                groupbuy_price		团购价
                trade_price		    批发价
                partner_price		(分销伙伴)抄底价【成本价 + 管理费】
                cost_price		    成本价【平台进价 + 运费】
        */
        /*
        if (count(unserialize($goods->spec_value)) == 0) {
            $goods_sku = GoodsSku::where('spu_id', $goods->spu_id)->first();
            $goods->spu_market_price = $goods_sku->market_price;

            // SKU 的销售价格
            $goods->spu_plat_price = $goods_sku->price;
            $goods->spu_groupbuy_price = $goods_sku->groupbuy_price;
            $goods->spu_trade_price = $goods_sku->trade_price;
            $goods->spu_partner_price = $goods_sku->partner_price;
        }
        */

        // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
        // 显示不同的销售价格
        $goods->spu_price = $this->getSpuPrice($grade, $goods);
        $goods->supper_spu_price=$this->getSupperSpuPrice($supG,$goods);

        /*if($grade==10){
            $goods->sup_discount= $goods->spu_market_price - $goods->supper_spu_price;
        }else{
            $goods->sup_discount= $goods->spu_market_price - $goods->spu_price;
        }*/
        if($grade < 30){
            $goods->sup_discount= $goods->spu_price - $goods->supper_spu_price;
        }else{
            $goods->sup_discount= $goods->spu_market_price - $goods->spu_price;
        }

        // 该商品的虚拟币支付限额
        $goods->spu_points = $this->getPointsLimit($goods->spu_points_limit, $goods->spu_price);

        // 3、SPU规格(名称、值)
        /* SPU规格值（spec_value），序列化数组，数据格式如下：
		Array (
			[1] => Array (
					[126] => 黑西装,
					[127] => 黑西装+半裙,
					[128] => 黑西装+衬衫+西裤,
					[129] => 四件套裙裤),
			[104] => Array (
					[114] => 165/92A,
					[117] => 170/100A)
			)

		这里：1、104 为spec_id；
		      126、127、129、114、117等为 spec_value_id
        */
        $goods->spec_value = (unserialize($goods->spec_value) == false ?
            [] : unserialize($goods->spec_value));
        $spuSpec = [];
        foreach ($goods->spec_value as $key => $item) {
            // $item 为规格值数组，$key 为spec_id；
            $spec = GoodsSpecDefine::find($key);

            $data = [
                'data_type' => $spec->data_type,
                'spec_name' => $spec->name,
                'spec_value' => []
            ];

            foreach ($item as $key1 => $value) {
                $data['spec_value'][$key1] = $value;
            }

            // $data 信息项：规格名、规格值（数组）、规格值类型
            array_push($spuSpec, $data);
        }

        // 4、商品SPU属性(spu_attr),数据格式为：
        /*
            Array([80768] => Array ([name] => 风格,[445080] => 淑女),
                      [80769] => Array ([name] => 裙长, [445095] => 短裙 ),
                      [80770] => Array ([name] => 版型, [445100] => 修身型))
           其中：
			“80768”为属性ID（attr_id）
			“风格”为属性名称（attr_name）

			“445080”为属性值ID（attr_value_id）
			“淑女”为属性值ID名称（attr_value_name）
        */
        $spuAttr = (unserialize($goods->spu_attr) == false ?
            [] : unserialize($goods->spu_attr));

        // 5、SPU 图片，注意图片地址是相对目录
        $spuImages = GoodsSpuImages::where('spu_id', $spuId)->get();
        if ($spuImages) {
            // 处理图片地址，由相对地址处理成绝对地址
            foreach ($spuImages as $row) {
                $row->image_url = $this->getFullPictureUrl($row->image_url);
            }
        }

        // 6、微信分享
        /*
        $url = empty(Auth::user()) ? $url = 'http://ynmo.yininet.com/shop/product/' . $spuId : $url = 'http://ynmo.yininet.com/shop/product/' . $spuId . '?member=' . Auth::user()->member_id;
        session(['wxfx' => [
            'url' => $url,
            'desc' => $goods->spu_name,
            'imgUrl' => $goods->main_image
        ]]);
        */

        // 7、保存浏览足迹
        // $subject_type    主题类型【1品牌;2商品SPU;3商品SKU;4分销商;】
        $obj_BrowseController = new MemberBrowseController();
        $obj_BrowseController->saveMyBrowse(2, $spuId);

        // 8、判断该商品是否收藏  collect_status【0:未收藏;1:已收藏;】
        // subject_type tinyint(1)  被收藏主体类型【1品牌;2商品SPU;3商品SKU;4分销商;】
        $collect_status = MemberCollect::where('collector', $member_id)
            ->where('subject_type', 2)
            ->where('subject_id', $spuId)
            ->value('collect_status');

        $collect_status = (int)$collect_status;

        // 9、虚拟币名称
        $plat_vrb_caption = $this->getPlatVrbCaption();

        //统计购物车中商品的总数量，显示出目前购物车中商品中的数量
        $goods_num_in_cart = MemberCart::where('member_id', $member_id)->sum('number');

        //微信jsapi
        $signPackage = session('signPackage');
        //微信分享链接
        $stoken = !empty($_GET['stoken']) ? $_GET['stoken'] : '';
        /*
        $request_uri = substr($signPackage['url'], 0, strpos($signPackage['url'], '?'));
        $share_link = $request_uri . '?stoken=' . openssl_encrypt($grade, config('yydwx')['cipher'], config('yydwx')['key'], 0, config('yydwx')['iv']);
        */
        $url = "http://$_SERVER[HTTP_HOST]";
        $share_link = $url . "/shop/goods/spuDetail/" . $goods->spu_id;
        //点击关注
        $subscribe = $this->getSubscribe($member_id);

        return view("sd_goods.good_detail",
            compact('goods', 'spuSpec', 'spuAttr', 'spuImages', 'collect_status', 'plat_vrb_caption', 'goods_num_in_cart',
                'signPackage', 'share_link', 'stoken','isHighest','nextPriv','grade','tpl','rmdSpuList','member_class_arr','subscribe'));
    }

    private function spuLikeRmdQuery($grade=10,$spuId=0)
    {
        $db_prefix = $this->db_prefix;
        $show_item = 'spu_id,spu_name,main_image,
                      spu_market_price,spu_plat_price,spu_groupbuy_price,spu_trade_price';
        $table = $db_prefix . 'goods_spu ';

        $a_where = 'where spu_id='.$spuId;
        $b_where = ' order by rand() limit 3';
        $state = ' and state = 1';
        $sql_query = 'select '.$show_item.' from '.$table.'where gc_id=(select gc_id from '.$table.
            $a_where.' and state=1 and is_commend=0) and '.'spu_id !='.$spuId.$state.$b_where;
        $query_result = DB::select($sql_query, []);
        if(empty($query_result)){
            $sql_query = 'select '.$show_item.' from '.$table.'where gc_id_2=(select gc_id_2 from '.$table.
                $a_where.' and state=1 and is_commend=0) and '.'spu_id !='.$spuId.$state.$b_where;
            $query_result = DB::select($sql_query, []);
        }
        foreach ($query_result as & $row) {

            // 图片地址是相对目录，要添加上域名
            $row->main_image = $this->getFullPictureUrl($row->main_image);

            // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
            // 显示不同的销售价格
            $row->spu_price = $this->getSpuPrice($grade, $row);
        }
        if (count($query_result) > 0) {
            $likeRmdSpuList = $query_result;
            // 返回数据
            $return_data = array('likeRmdSpuList' => $likeRmdSpuList);
            return $return_data;
        }

        return false;

    }
    /**
     * 好物推荐
     * @param int $gcId
     * @return array
     */
    private function spuRmdQuery($gcId = 10)
    {
        // 如果当前有登录用户，要返回商品收藏标识
        $grade = 10;
        $member_id = 0;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        }

        $rmdSpuList = array();
        $gcId = (int)$gcId;
        if ($gcId <= 0) {
            $gcId = 10;
        }


        /*
            spu_plat_price      平台价格【官网价】
            spu_market_price    市场价【国内市场价格】
            spu_groupbuy_price  团购价
            spu_trade_price     批发价
            spu_partner_price   (分销伙伴)抄底价
        */
        $db_prefix = $this->db_prefix;

        $show_item = 'a.spu_id,a.spu_code,a.spu_name,
                    b.name as gc_name,a.gb_name,
                    a.spec_name,a.spec_value,
                    a.spu_market_price,a.spu_plat_price,
                    a.spu_groupbuy_price,a.spu_trade_price,
                    a.spu_partner_price,a.spu_points_limit,
                    a.main_image,a.updated_at,
                    a.collect_count as collect ';
        $table = $db_prefix . 'goods_spu as a ';
        $inner_join = ' left join ' . $db_prefix . 'goods_class as b on a.gc_id = b.id ';
        $show_item .= ',0 as collect_status';


        // state 商品状态【0:未上架（待上架）; 1:上架在售; 2:自主下架; 3:系统下架（违规下架）;
        //                5:初建待申请;7:待审核;8:审核不通过;】',
        $where = ' where a.state = 1 ';
        if ($gcId) {
            $where .= ' and a.gc_id_1 = ' . $gcId;
        }



        $sql_query = 'select ' . $show_item . '
            from ' . $table . ' ' .
            $inner_join . ' ' .
            $where . ' '  . '
                order by rand() LIMIT 3';
        $query_result = DB::select($sql_query, []);
        foreach ($query_result as & $row) {
            $row->spec_name = unserialize($row->spec_name);
            $row->spec_value = unserialize($row->spec_value);

            // 图片地址是相对目录，要添加上域名
            $row->main_image = $this->getFullPictureUrl($row->main_image);

            // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
            // 显示不同的销售价格
            $row->spu_price = $this->getSpuPrice($grade, $row);
        }

        if (count($query_result) > 0) {
            $rmdSpuList = $query_result;
        }


        // 返回数据
        $return_data = array('rmdSpuList' => $rmdSpuList);
        return $return_data;
    }


    //======================================================共用方法

    /**
     * 根据当前登录用户类型, 获取spu商品对应价格
     * @param int $member_grade 用户会员等级
     * @param GoodsSpu $obj_spu 商品spu
     * @return float 返回对应商品spu价格
     */
    function getSupperSpuPrice($member_grade = 10, $obj_spu)
    {
        /*
            会员类别(grade) => 10:普通会员(市场价); 20:一级会员(平台价格); 30:二级会员(团购价); 40:三级会员(批发价)
            spu 价格体系：
            spu_market_price	市场价 10
            spu_plat_price		平台价格 20
            spu_groupbuy_price	团购价 30
            spu_trade_price		批发价 40
            spu_partner_price	(分销伙伴)抄底价【成本价 + 管理费】
            spu_cost_price		成本价【平台进价 + 运费】
        */
        $spu_price = 0.00;

        switch ($member_grade) {
            case 10 :
                $spu_price = isset($obj_spu->spu_market_price) ? $obj_spu->spu_market_price : $spu_price;
                break;

            case 20 :
                $spu_price = isset($obj_spu->spu_plat_price) ? $obj_spu->spu_plat_price : $spu_price;
                break;

            case 30 :
                $spu_price = isset($obj_spu->spu_groupbuy_price) ? $obj_spu->spu_groupbuy_price : $spu_price;
                break;

            case 40 :
                $spu_price = isset($obj_spu->spu_trade_price) ? $obj_spu->spu_trade_price : $spu_price;
                break;
        }

        return $spu_price;
    }


    /* 获取一个规格值ID 集合，这些规格值ID 应满足下列条件：
        1、和传入的规格值 $id  属于同一个规格
        2、这些规格值在当前SPU中已经应用【没用的，不在此列】
        传入参数：
        $id         当前SPU的一个规格值ID
        $all_ids    当前SPU 已经使用的规格值ID集合
    */

    public function goodDetail(Request $request)
    {
        //dd($request->all());
        $mobilecontent = $request->input('mobilecontent');
        $spuid= $request->input('spu_id');
        $from_plat_code=$request->input('from_plat_code');

        if($from_plat_code=='2002'){
            return view('sd_goods.good_mobile_content_yx', compact('mobilecontent'));
        }else
            return view('sd_goods.good_mobile_content', compact('mobilecontent'));
        /*        if($from_plat_code=='2002'){
                    //取得指定位址的內容，并储存至 $text
                    $url='http://you.163.com/item/detail?id='.$spuid;
                    $text = file_get_contents($url);
                    $regex4 = "/<div class=\"j-itemDetail\".*?>.*?<\/div>/ism";

                    //dd($text);
                    $slidelist = "/<div class=\"list j-sthumbs\".*?>.*?<\/div>/ism";
                    preg_match($regex4, $text, $match);
                    //preg_match($slidelist, $text, $slidediv);
                    //dd($match);
                    $aaa = str_replace('_src', 'aa_bb', $match[0]);
                    $aaa = str_replace('src', 'tt_ss', $aaa);
                    $aaa = str_replace('aa_bb', 'src', $aaa);
                    $detail = str_replace('class="img-lazyload short"', '', $aaa);

                    return view('sd_goods.good_mobile_content_wyyx', compact('detail'));

                }else{
                    return view('sd_goods.good_mobile_content', compact('mobilecontent'));
                }*/

    }


    // 获取若干个规格值($ids)关联的规格，在某一个SPU内这些规格定义的所有规格值ID，以数组形式传入
    // $all_ids 为某一个SPU内有效的SKU的所有规格值ID【无效的SKU不含在内】

    /** 根据产品规格属性列表，动态确定一个SKU。本接口调用方法 post
     * 传入参数：
     *
     *  $spu_id     int     Spu id
     *  $ids        array   一个完整的SKU规格值ID数组
     *  传出参数 $sku或$spu(该产品没有规格)数据，Json 格式
     */
    public function getSku(Request $request)
    {
        // 当前登录用户信息，会员类别(grade)【10:普通会员;20:股东;30:分销伙伴;40:合伙人】
        $grade = 10;
        $member = Auth::user();
        if ($member) {
            $grade = $member->grade;
            $member_id = $member->member_id;
        }

        // 虚拟币名称
        $plat_vrb_caption = $this->getPlatVrbCaption();

        // 传入参数【分享者等会员等级加密token、产品spu_id、拟确认的某一个SKU规格值ID(数组)】
        $stoken = $request->input('stoken');
        $spuId = $request->input('spu_id');
        $ids = (array)$request->input('ids');

        // 获取指定的某一个产品【SPU】信息
        $spuId = (int)$spuId;
        $spu = GoodsSpu::select('spu_id', 'spu_name',
            'ad_info', 'ad_link_title', 'ad_link_url',
            'gc_id', 'gc_name', 'gb_id', 'gb_name',
            'spu_attr', 'spec_name', 'spec_value',
            'main_image', 'mobile_content', 'keywords',
            'producter', 'spu_code',
            'spu_market_price', 'spu_plat_price',
            'spu_groupbuy_price', 'spu_trade_price', 'spu_partner_price',
            'spu_points_limit', 'spu_storage_num',
            'click_count', 'collect_count',
            'is_commend', 'state')
            ->where('spu_id', $spuId)
            ->first();

        // A、如果SPU 不存在，直接退出
        if ($spu) {
            // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
            // 显示不同的销售价格
            $spu->spu_price = $this->getSpuPrice($grade, $spu);

            // 该商品的虚拟币支付限额
            $spu->spu_points = $this->getPointsLimit($spu->spu_points_limit, $spu->spu_price);

            // SPU 主图地址
            $spu->main_image = $this->getFullPictureUrl($spu->main_image);
        } else {
            // 返回空的产品信息
            return response()->json([
                'type' => 0,
                'spu' => [],
                'ids' => [],
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        }

        // $result 保存一些规格值ID，这些规格值ID 的条件
        //  1、和 $ids 规格值不属于同一个规格系列
        //  2、不在 $ids 关联的有效SKU的规格值ID集合内
        $result = [];

        /* 该产品定义的规格名称数组、规格值数组
            1、SPU规格名称（spec_name）数组格式为：
                    Array([1] => 颜色, [23] => 铝锭规格)
                    其中：“1”和“23”为sp_id，“颜色”和“铝锭规格”为sp_name

            2、SPU规格值（spec_value）格式为：
                    Array([1] => Array([1529] => 纯白),
                         [23] => Array ([1523]=>1000*500*200,[1524] => 1000*1000*500))
                    其中：“1”和“23”为sp_id，
                          “1529”、“1523”、“1524”等为规格值ID（sp_value_id）
                          “纯白”、“1000*500*200”、“1000*1000*500”等为规格值名称（sp_value_name）
        */
        // B、如果SPU 没有定义规格或传入的规格值（$ids）不完备，例如：维数和SPU定义的不符，直接退出
        $spec_name = unserialize($spu->spec_name);
        $spec_value = unserialize($spu->spec_value);
        if (count($spec_name) == 0 ||
            count($ids) == 0 ||
            count($spec_name) != count($ids)
        ) {
            return response()->json([
                'type' => 0,
                'spu' => $spu,
                'ids' => array_unique($result),
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        }


        // C、确定一个完备的SKU信息
        $sku_item = [];

        //获取所有sku ,状态为启用（1）;sku_spec 规格值(数组序列化)

        $db_prefix = config('database')['connections']['mysql']['prefix'];

        $info_sql = "SELECT g.sku_id,g.sku_name,g.sku_title,
                      g.market_price,g.price,g.groupbuy_price,g.trade_price,
                      g.partner_price,g.points_limit,g.sku_spec,
                      m.member_id,m.minimum_limit,m.storage_num,m.base_discount_rate,m.use_state,g.grade_limit
                     FROM " . $db_prefix . "goods_sku AS g
                        LEFT JOIN ( select sku_id,member_id,minimum_limit,storage_num,base_discount_rate,use_state
                                    from " . $db_prefix . "member_goods_sku
                                    where member_id = " . $member_id . " and use_state = 1) m
                        ON g.sku_id = m.sku_id
                     WHERE g.spu_id =" . $spuId . " AND g.use_state = 1 ORDER BY g.sku_id";
        $skus = DB::select($info_sql);


//        $skus = GoodsSku::select('goods_sku.sku_id', 'goods_sku.sku_name', 'goods_sku.sku_title',
//            'goods_sku.market_price',
//            'goods_sku.price', 'goods_sku.groupbuy_price',
//            'goods_sku.trade_price', 'goods_sku.partner_price',
//            'goods_sku.points_limit', 'goods_sku.sku_spec',
//            'member_goods_sku.member_id','member_goods_sku.minimum_limit','member_goods_sku.storage_num','member_goods_sku.base_discount_rate','member_goods_sku.use_state')
//            ->leftJoin('member_goods_sku',function($leftJoin) use($member_id){
//                $leftJoin->on('member_goods_sku.sku_id', '=', 'goods_sku.sku_id')
//                         ->on('member_goods_sku.member_id', '=',$member_id); //左连接时没有判断member_goods_sku的use_state，若加这个判断会把左边的表记录失去，所以后面判断sku价格时候要加上use_state是否为有效1
//            })
//
//            ->where('goods_sku.spu_id', $spuId)
//            ->where('goods_sku.use_state', 1)
//            ->orderBy('goods_sku.sku_id')
////            ->toSql();
//            ->get();

        // 获取当前spu下的有效的skus的所有规格值ID【数组，不含无效的SKU的规格值ID】
        $all_ids = $this->getAllSpec($skus);

        // 获取虚拟币与人民币间的汇率【1元（人民币）等于多少依你币】，计算商品价格兑换成虚拟币数值用
        $plat_vrb_rate = $this->getPlatVrbRate();

        // 1、根据传入的规格值ID【$ids，数组】确定关联的SKU对象
        foreach ($skus as & $sku) {
            /*
                SKU规格值(sku_spec)数组格式为：
                Array ([1529] => 纯白,[1523] => 1000*500*200 )
                其中：“1529”、“1523”等为规格值ID（sp_value_id）
                      “纯白”、“1000*500*200”等为规格值名称（sp_value_name）
            */
            // 1、获取当前行SKU的规格值ID，并压入数组 $all_ids 中
            /*foreach (array_keys(unserialize($sku->sku_spec)) as $item)
            {
                array_push($all_ids, $item);
            }*/

            // $current_spec_ids(当前SKU的规格值ID列，数组)、$ids（拟确认SKU的规格值ID列，数组）
            $current_spec_ids = array_keys(unserialize($sku->sku_spec));
            if (count(array_diff($ids, $current_spec_ids)) == 0 &&
                count(array_diff($current_spec_ids, $ids)) == 0
            ) {
                // 说明当前sku就是拟确认的SKU
                $sku_id = $sku->sku_id;
                $sku_name = $sku->sku_title;
                if (!$sku_name) {
                    $sku_name = $sku->sku_name;
                }

                // 根据当前登录用户级别，获取其应享受的价格，非登录情况下，显示平台价【官网零售价】
                $price = $this->getSkuPrice($grade, $sku);

                // 该商品的虚拟币支付限额
                $sku_points = $this->getPointsLimit($sku->points_limit, $price, $plat_vrb_rate);

                // SKU 信息项：ID、名称、价格、虚拟币限额、规格
                $sku_item['sku_id'] = $sku_id;
                $sku_item['sku_name'] = $sku_name;
                $sku_item['price'] = $price;
                $sku_item['sku_points'] = $sku_points;
                $sku_item['points_limit'] = $sku->points_limit;
                $sku_item['sku_spec'] = $sku->sku_spec;
                $sku_item['minimum_limit'] = 1; //默认为1
                $sku_item['grade_limit'] = $sku->grade_limit;
                //团采会员，代理会员 用户能享受的折扣率，每个商品的购买下线
                if (($grade == 20 || $grade == 30) && $sku->use_state == 1) {
                    $sku_item['minimum_limit'] = $sku->minimum_limit;
                }

                /*
                |--------------------------------------------------------------------------
                | 如果是分享的链接，需要比较分享者与被分享者的会员等级，较大的与SKU购买等级下限作比较
                |--------------------------------------------------------------------------
                */
                $sgrade = !empty($stoken) ? openssl_decrypt($stoken, config('yydwx')['cipher'], config('yydwx')['key'], 0, config('yydwx')['iv']) : 0;
                $grade = !empty($sgrade) && in_array(intval($sgrade), [10, 20, 30, 40]) && $sgrade > $grade ? $sgrade : $grade;
                $sku_item['is_can_buy'] = $sku->grade_limit <= $grade ? true : false;
                break;
            }
        }

        //取sku的图片，当图片为空的时候，取spu的图片
        if (count($sku_item) > 0) {
            $img = GoodsSkuImages::where('sku_id', $sku_item['sku_id'])
                ->where('is_default', 1)
                ->value('image_url');
            $img = trim($img . '');
            if ($img == '') {
                // 如果SKU没有图片，启用SPU关联的图片
                $img = GoodsSpu::where('spu_id', $spuId)->value('main_image');
            }

            $sku_item['img'] = $this->getFullPictureUrl($img);
        }


        // D、获取与$ids 规格值无关的其它规格值集合【2017-01-19 经阅读代码，好似 $result 永远为空】
        // $ids 为规格值ID数组，这个数组可确定一个唯一的SKU 对象
        foreach ($ids as $id) {
            // 获取某一个规格值ID($id)关联的规格，在某一个SPU内此规格定义的所有已经使用的规格值ID集合【没有使用的，不在此列】
            $ids_array = $this->getSelfGradeById($id, $all_ids);
            foreach ($skus as $sku) {
                // $current_spec_ids(当前SKU的规格值ID列，数组)
                $current_spec_ids = array_keys(unserialize($sku->sku_spec));
                $array_spec_id = [];
                array_push($array_spec_id, $id);
                if (count(array_diff($array_spec_id, $current_spec_ids)) == 0) {
                    //说明当前sku包含：ids
                    foreach ($current_spec_ids as $value) {
                        array_push($ids_array, $value);
                    }
                }
            }

            // $ids_array 由两部分组成：
            //  1、与 $id（铁）同规格的所有规格值ID，例如“金属含量”为一个规格，那么“金”、“银”等都含在内
            //  2、含$id（铁）规格值ID的若干个有效SKU的所有规格值ID，例如：红（颜色）、水淬火（加工工艺）、铁（金属含量）
            $ids_array = array_values(array_unique($ids_array));
            //找出2个索引数组的不同
            foreach ($all_ids as $item) {
                if (!in_array($item, $ids_array)) {
                    array_push($result, $item);
                }
            }
        }

        // E、返回一个完整的SKU 或 SPU 对象
        if (count($sku_item) == 0) {
            return response()->json([
                'type' => 0,
                'spu' => $spu,
                'ids' => array_unique($result),
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        } else {
            // 返回某一个 SKU
            return response()->json([
                'type' => 1,
                'sku' => $sku_item,
                'ids' => array_unique($result),
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        }
    }

    /**
     * 返回某一个SPU下的有效的skus的所有规格值ID
     * 输入：skus（对象列）
     * 输出：所有sku的所有规格值IDs（索引数组）
     * @param $skus
     * @return  array
     */
    public function getAllSpec($skus)
    {
        /*
            SKU规格值(sku_spec)数组格式为：
            Array ([1529] => 纯白,[1523] => 1000*500*200 )
            其中：“1529”、“1523”等为规格值ID（sp_value_id）
                  “纯白”、“1000*500*200”等为规格值名称（sp_value_name）
        */
        $all_ids = [];
        foreach ($skus as $sku) {
            foreach (array_keys(unserialize($sku->sku_spec)) as $item) {
                array_push($all_ids, $item);
            }
        }

        $all_ids = array_values(array_unique($all_ids));
        return $all_ids;
    }

    public function getSelfGradeById($id, $all_ids)
    {
        $ids_array = [];

        // 1、根据规格值获取规格ID
        $sp_id = GoodsSpecValueDct::where('id', $id)->value('sp_id');

        // 2、根据规格ID获取所有的规格值
        $specs = GoodsSpecValueDct::where('sp_id', $sp_id)->get();
        foreach ($specs as $spec) {
            if (in_array($spec->id, $all_ids)) {
                array_push($ids_array, $spec->id);
            }
        }

        // SPU定义的同一个规格的值ID
        return $ids_array;
    }

    public function getSelfGrade($ids, $all_ids)
    {
        $ids_array = [];
        foreach ($ids as $item) {
            $sp_id = GoodsSpecValueDct::where('id', $item)->value('sp_id');
            $specs = GoodsSpecValueDct::where('sp_id', $sp_id)->get();
            foreach ($specs as $spec) {
                if (in_array($spec->id, $all_ids)) {
                    array_push($ids_array, $spec->id);
                }
            }
        }

        return $ids_array;
    }

    //统计购物车中商品的总数量

    public function shop($gcId = 0,$pageNumber = 1, $pageSize = 10,$goodsName = '', $orderBy = 0)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $message = '';
        /*$num_gcId_1 = [];
        $num_gcId_2 = [];
        $num_gcId_3 = [];*/
        $num_gcId_2 = [];
        try {

            $num_gcId_1 = GoodsClass::where('pid',0)->where('state',0)->where('is_use',1)->orderBy('sort')->get(['id'])->toArray();
            foreach($num_gcId_1 as $key=>$cId_1){
                $gcId_2 = GoodsClass::where('pid','<>',0)->where('pid',$cId_1['id'])->where('image_url','<>','NULL')->where('state',0)->orderBy('sort');
                $num_gcId = $gcId_2->get(['id','name','image_url'])->toArray();
                $num_gcId_2 = array_merge($num_gcId_2,$num_gcId);
            }
            if($num_gcId_1){
                $goodClassId_2 = GoodsClass::where('pid','<>',0)->where('id',$gcId)->where('image_url','<>','NULL')->where('state',0)->orderBy('sort')->first(['id','name','image_url']);
                $first_gcId_2 = $gcId;
                $gcId_3 = GoodsClass::where('pid','<>',0)->where('pid',$first_gcId_2)->where('image_url','<>','NULL')->where('state',0)->orderBy('sort');
                $num_gcId_3 = $gcId_3->get(['id','image_url']);
                if(empty($num_gcId_3[0])){
                    $first_gcId_3 = $gcId;
                }else{
                    $first_gcId_3 = $gcId_3->first()->id;
                }
            }
            /*foreach($num_gcId_1 as $key=>$cId_1){
                $gcId_2 = GoodsClass::where('pid','<>',0)->where('pid',$cId_1['id'])->where('image_url','<>','NULL')->where('state',0)->orderBy('sort');
                $num_gcId = $gcId_2->get(['id','name','image_url'])->toArray();
                $num_gcId_2 = array_merge($num_gcId_2,$num_gcId);
            }
            if($num_gcId_1){
                $gcId_3 = GoodsClass::where('pid','<>',0)->where('pid',$num_gcId_2[0]['id'])->where('image_url','<>','NULL')->where('state',0)->orderBy('sort');
                $num_gcId_3 = $gcId_3->get(['id','image_url']);
                $first_gcId_3 = $gcId_3->first()->id;
            }*/
            /*foreach ($num_gcId_one as $k=>$v) {
                $num_gcId_two = GoodsClass::where('pid','<>',0)->where('pid',$v['id'])->where('image_url','<>','NULL')->where('state',0)->orderBy('sort')->get(['id','name','image_url'])->toArray();
                $num_gcId_2[$v['id']] = $num_gcId_two;
                foreach ($num_gcId_2[$v['id']] as $kk => $vv) {
                    $num_gcId_2[$v['id']][$kk]['image_url']= $this->img_domain.'/'.$vv['image_url'];
                    $num_gcId_three = GoodsClass::where('pid','<>',0)->where('pid',$vv['id'])->where('image_url','<>','NULL')->where('state',0)->orderBy('sort')->get(['id','image_url'])->toArray();
                    $num_gcId_3[$vv['id']] = $num_gcId_three;
                    foreach ($num_gcId_3[$vv['id']] as $kkk => $vvv){
                        $num_gcId_3[$vv['id']][$kkk]['image_url']= $this->img_domain.'/'.$vvv['image_url'];
                    }
                }
            }*/
            /*if(empty($num_gcId_3[0])){
                $first_gcId_3 = $gcId;
            }else{
                $first_gcId_3 = $num_gcId_3[1]->id;
            }*/
            /*if(!empty($num_gcId_three)){
                $first_gcId_3 = $num_gcId_three[0]['id'];
            }*/

            $data = $this->spuShopQuery($pageNumber, $pageSize, $first_gcId_3, $goodsName, $orderBy);
            $goodsSpuList = $data['goodsSpuList'];
            $queryParameter = $data['queryParameter'];
            $separatePage = $data['separatePage'];
            $rmdData=$this->spuRmdQuery(10);
            $rmdSpuList=$rmdData['rmdSpuList'];
            //统计购物车商品数量
            $goods_num_in_cart = 0;//$this->shop_cart();
            //搜索热词
            $hotSearch = DB::table('goods_search')->where('state','0')->orderBy('sort')->get(['name']);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        $goodsName = trim((string)$goodsName);
        if ($goodsName == '0') {
            $goodsName = '';
        }

        //$toGoodsClass = GoodsClass::where('pid',10)->where('state',0)->where('image_url','<>','NULL')->where('image_url','<>','default.jpg')->get();//二级类
        $img_domain = $this->img_domain.'/';//图片地址
        //商品二级类
        /*$sql = 'select * from '.$this->db_prefix.'goods_class where pid in(select id from '.$this->db_prefix.'goods_class where pid =0)';
        $toGoodsClass = DB::select($sql);
        if($toGoodsClass){
            $threeGoodsClass = GoodsClass::where('pid',$toGoodsClass[0]->id)->get();
        }*/
        // -------------------A、广告信息--------------------
        // 广告信息项：广告ID（advert_id）、广告标题（advert_title）,广告图片地址（images）、外链接（out_url）
        //商城轮播图处理
        /*$advertList = array('advert_a0401' => [], 'advert_a0402' => [],
            'advert_a0403' => [], 'advert_a0404' => []);
        // 1、A0401	APP商城页第一TAB幻灯片(banner) 4张
        $advertList['advert_a0401'] = $this->getAdvertList("'A0401'", 4);
        // 1、A0402	APP商城页第二TAB幻灯片(banner) 4张
        $advertList['advert_a0402'] = $this->getAdvertList("'A0402'", 4);
        // 1、A0403	APP商城页第三TAB幻灯片(banner) 4张
        $advertList['advert_a0403'] = $this->getAdvertList("'A0403'", 4);
        // 1、A0404	APP商城页第四TAB幻灯片(banner) 4张
        $advertList['advert_a0404'] = $this->getAdvertList("'A0404'", 4);*/
        //点击关注
        $subscribe = $this->getSubscribe($member_id);
        return view("sd_mall.shop", compact('gcId','goodsSpuList', 'queryParameter', 'separatePage', 'goodsName', 'orderBy','rmdSpuList','num_gcId_2','num_gcId_3','goodClassId_2','img_domain','goods_num_in_cart','subscribe','advertList','hotSearch'));
    }

    public function ajax_toGoodsClass($id)
    {
        // 接口传出数据
        $code = 0;
        $message = '';
        try {
            $img_domain = $this->img_domain;
            $gcs = GoodsClass::where('pid',$id)->where('state',0)->where('image_url','<>','NULL')->orderBy('sort')->get();
            foreach($gcs as $gc ){
                $gc->image_url=$img_domain.'/'.$gc->image_url;
            }
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        return Api::responseMessage($code, $gcs, $message);

    }

    public function hotSearch()
    {
        $hotSearch = DB::table('goods_search')->where('state','0')->orderBy('sort')->get(['name']);

        return view("sd_goods.search", compact('hotSearch'));
    }

    public function search($pageNumber = 1, $pageSize = 10, $gcId = 0, $goodsName = '', $orderBy = 0)
    {
        // 接口传出数据
        $code = 0;
        $data = array('queryParameter' => [], 'separatePage' => [], 'goodsSpuList' => []);
        $message = '';

        try {
            $data = $this->spuQuery($pageNumber, $pageSize, $gcId, $goodsName, $orderBy);
            $goodsSpuList = $data['goodsSpuList'];
            $queryParameter = $data['queryParameter'];
            $separatePage = $data['separatePage'];
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        $goodsName = trim((string)$goodsName);
        if ($goodsName == '0') {
            $goodsName = '';
        }
        //统计购物车商品数量
        $goods_num_in_cart = $this->shop_cart();
        //如果商品为空,定向到提示页
        if(empty($goodsSpuList)){

            $errorData = [
                'message' => '非常抱歉,没有搜到相关商品'
            ];
            return view("errors.error", compact('errorData'));
        }
        return view("sd_goods.search_result", compact('goodsSpuList', 'queryParameter', 'separatePage', 'goodsName', 'orderBy','goods_num_in_cart'));
    }

    public function ajax_rmdSpuList($gcId = 10)
    {
        // 接口传出数据
        $code = 0;
        $data = array('rmdSpuList' => []);
        $message = '';

        try {
            $data = $this->spuRmdQuery($gcId);

        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
        }

        return Api::responseMessage($code, $data, $message);

    }


}
