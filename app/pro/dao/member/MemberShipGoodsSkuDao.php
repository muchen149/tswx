<?php

namespace App\pro\dao\member;

use App\pro\dao\BaseDao;
use Illuminate\Support\Facades\DB;

class MemberShipGoodsSkuDao extends BaseDao
{
    /**
     * 获取全部需要展示的会员级别商品
     * @param int $grade 【20, 30, 40】
     * @return array
     */
    public function getGoodsListByGradeForViewData($grade)
    {
        // 普通会员不需要指定会员级别商品
        $class_std_arr = DB::table('plat_setting')
            ->where('name', '<>', 'zero_class_member')
            ->where('name', 'like', '%_class_member')
            ->get()->toArray();

        // 填充会员等级信息
        $member_class_arr = $this->getMemberClass($class_std_arr);

        // 查询各个不同等级下的商品数量信息(将平台定义的会员级别信息置于sql中了)
        $grade_name_case_sql = "(CASE grade";

        foreach ($member_class_arr as $member_class) {
            $grade_name_case_sql .= " WHEN " . $member_class['grade_code'] . " THEN '" . $member_class['grade_name'] . "'";
        }

        $select_sql = "
            SELECT gk.sku_id, if(isnull(gk.sku_title), gk.sku_name, gk.sku_title) AS sku_name, mgk.grade, " . $grade_name_case_sql . " END) AS grade_name,
                   (CASE WHEN isnull(gk.main_image)
                        THEN if(
                               isnull((SELECT gki.image_url FROM " . $this->db_prefix . "goods_sku_images AS gki WHERE gki.sku_id = gk.sku_id AND is_default = 1)), 
                               (SELECT gpi.image_url FROM " . $this->db_prefix . "goods_spu_images AS gpi WHERE gpi.spu_id = gk.spu_id AND gpi.is_default = 1), 
                               (SELECT gki.image_url FROM " . $this->db_prefix . "goods_sku_images AS gki WHERE gki.sku_id = gk.sku_id AND is_default = 1)
                        ) ELSE gk.main_image END) AS main_image, gp.is_virtual 
            FROM " . $this->db_prefix . "goods_sku AS gk
              INNER JOIN " . $this->db_prefix . "membership_goods_sku AS mgk ON gk.sku_id = mgk.sku_id
              INNER JOIN " . $this->db_prefix . "goods_spu AS gp ON gk.spu_id = gp.spu_id
            WHERE gk.use_state = 1 AND gp.state = 1 AND mgk.state = 1 AND mgk.is_show = 1 AND (mgk.grade = ? OR (mgk.grade > ? AND mgk.low_grade_is_show = 1))
            ORDER BY mgk.sort";

        return DB::select($select_sql, [$grade, $grade]);
    }
}