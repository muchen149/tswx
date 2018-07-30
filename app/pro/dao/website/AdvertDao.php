<?php

namespace App\pro\dao\website;

use App\pro\dao\BaseDao;
use Illuminate\Support\Facades\DB;

class AdvertDao extends BaseDao
{
    public function getMemberCenterAdvert()
    {
        $select_sql = "
            SELECT images, out_url
            FROM " . $this->db_prefix . "advert
            WHERE position_code = 'A0134' 
            AND (put_start_time = 0 OR put_start_time <= unix_timestamp()) 
            AND (put_end_time = 0 OR put_end_time >= unix_timestamp())
            AND advert_state = 1 
            AND supplier_id = 0
            ORDER BY put_weight;";

        return DB::select($select_sql, []);
    }
}