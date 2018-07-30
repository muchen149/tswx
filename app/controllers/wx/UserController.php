<?php

namespace App\controllers\wx;

use EasyWeChat\Foundation\Application;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $userApi;

    public function __construct(Application $app)
    {
        // 得到EasyWeChat的用户接口
        $this->userApi = $app->user;
    }

    /**
     * 获取用户列表(openid列表)
     * @return \EasyWeChat\Support\Collection
     */
    public function users()
    {
        $users = $this->userApi->lists();

        return $users;
    }

    /**
     * 获取用户信息
     * @param $open_id
     * @return array
     */
    public function getUserInfoByOpenid($open_id)
    {
        $user = $this->userApi->get($open_id);

        return $user;
    }

    /**
     * 获取用户列表中每个用户的详细信息
     * @return array
     */
    public function getUsersInfo()
    {
        // 获取公众号用户列表
        $users = $this->users();
        $open_ids = $users->data['openid'];

        // 组装用户详细信息
        $users_info = array();
        foreach ($open_ids as $open_id){
            $user = $this->getUserInfoByOpenid($open_id);
            array_push($users_info, $user);
        }

        return $users_info;
    }
}