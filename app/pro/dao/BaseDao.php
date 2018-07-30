<?php

namespace App\pro\dao;

use Illuminate\Support\Facades\DB;

class BaseDao
{
    /**
     * 数据库前缀
     *
     * @var string
     */
    public $db_prefix;

    /**
     * BaseDao constructor.
     */
    public function __construct()
    {
        $this->db_prefix = DB::connection()->getConfig('prefix');
    }

    /**
     * 根据平台设置的会员等级信息, 为不同会员添加等级码(grade)
     * @param array $class_arr
     * @return array
     */
    protected function getMemberClass($class_std_arr = [])
    {
        if (!$class_std_arr) {
            $class_std_arr = DB::table('plat_setting')->where('name', 'like', '%_class_member')->get()->toArray();
        }

        $new_class_arr = [];
        foreach ($class_std_arr as $class_std) {

            switch ($class_std->name) {

                case 'first_class_member':
                    $grade_code = 20;
                    break;

                case 'second_class_member':
                    $grade_code = 30;
                    break;

                case 'third_class_member':
                    $grade_code = 40;
                    break;

                default :
                    $grade_code = 10;
                    break;
            }

            $new_class_arr[$grade_code] = [
                'class_code' => $class_std->name,
                'class_name' => $class_std->value,
                'description' => $class_std->description,
                'grade_code' => $grade_code,
                'grade_name' => $class_std->value // 现在暂时和class_name一样, 到时候优化可以用于丰富前台页面展示
            ];
        }

        return $new_class_arr;
    }

}
