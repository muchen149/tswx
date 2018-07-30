<?php

namespace App\lib;

use Cache;

class YnCacheManage
{
    /*
    |--------------------------------------------------------------------------
    | 平台缓存配置
    |--------------------------------------------------------------------------
    */
    static public function getUserByToken($token)
    {
        return Cache::tags(config('ynes.' . config('ynes.program') . '_cache_tags'))->
        get(config('ynes.program') . '.user.' . $token);
    }

    static public function saveUserByToken($user)
    {
        return Cache::tags(config('ynes.' . config('ynes.program') . '_cache_tags'))->
        put(config('ynes.program') . '.user.' . $user['token'], $user, config('ynes.cache_expire'));
    }

    static public function removeUserByToken($token)
    {
        //ynes:5f05aec578022982a61ff2e092ecbab0f2f46f88:yncd.user.cf6d1c932d83e3a2cb6067000f2f3fe8"
        return Cache::tags('ynes:' . $token)->flush();//从缓存中获取缓存项然后删除，
    }

    static public function getCaptchaByIdentity($identity)
    {
        return Cache::tags(config('yydwx.sdwx_captcha_cache_tags'))->
        get('sdwx.captcha.' . $identity);
    }

    static public function saveCaptchaByIdentity($captcha)
    {
        return Cache::tags(config('yydwx.sdwx_captcha_cache_tags'))->
        put('sdwx.captcha.' . $captcha['identity'], $captcha, config('yydwx.captcha_cache_expire'));
    }

    static public function saveJsApiTicketByAppId($jsapi_ticket)
    {
        return Cache::tags(config('yydwx.sdwx_jsapi_ticket_cache_tags'))->
        put('sdwx.jsapi_ticket.' . $jsapi_ticket['appId'], $jsapi_ticket, 110);
    }

    static public function getJsApiTicketByAppId($appId)
    {
        return Cache::tags(config('yydwx.sdwx_jsapi_ticket_cache_tags'))->
        get('sdwx.jsapi_ticket.' . $appId);
    }
    /*
    |--------------------------------------------------------------------------
    | 供货商缓存配置
    |--------------------------------------------------------------------------
    */
    static public function getGhsUserByToken($token)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynghs_program') . '_cache_tags'))->
        get(config('ynes.ynghs_program') . '.user.' . $token);
    }

    static public function saveGhsUserByToken($user)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynghs_program') . '_cache_tags'))->
        put(config('ynes.ynghs_program') . '.user.' . $user['token'], $user, config('ynes.cache_expire'));
    }

    static public function getGhsCaptchaByIdentity($identity)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynghs_program') . '_captcha_cache_tags'))->
        get(config('ynes.ynghs_program') . '.captcha.' . $identity);
    }

    static public function saveGhsCaptchaByIdentity($captcha)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynghs_program') . '_captcha_cache_tags'))->
        put(config('ynes.ynghs_program') . '.captcha.' . $captcha['identity'], $captcha, config('ynes.captcha_cache_expire'));
    }


    /*
    |--------------------------------------------------------------------------
    | 仓点缓存配置
    |--------------------------------------------------------------------------
    */
    static public function getCdUserByToken($token)
    {
        return Cache::tags(config('ynes.' . config('ynes.yncd_program') . '_cache_tags'))->
        get(config('ynes.yncd_program') . '.user.' . $token);
    }

    static public function saveCdUserByToken($user)
    {
        return Cache::tags(config('ynes.' . config('ynes.yncd_program') . '_cache_tags'))->
        put(config('ynes.yncd_program') . '.user.' . $user['token'], $user, config('ynes.cache_expire'));
    }

    static public function getCdCaptchaByIdentity($identity)
    {
        return Cache::tags(config('ynes.' . config('ynes.yncd_program') . '_captcha_cache_tags'))->
        get(config('ynes.yncd_program') . '.captcha.' . $identity);
    }

    static public function saveCdCaptchaByIdentity($captcha)
    {
        return Cache::tags(config('ynes.' . config('ynes.yncd_program') . '_captcha_cache_tags'))->
        put(config('ynes.yncd_program') . '.captcha.' . $captcha['identity'], $captcha, config('ynes.captcha_cache_expire'));
    }

    
    /*
    |--------------------------------------------------------------------------
    | 分销商缓存配置
    |--------------------------------------------------------------------------
    */
    static public function getFxsUserByToken($token)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynfxs_program') . '_cache_tags'))->
        get(config('ynes.ynfxs_program') . '.user.' . $token);
    }

    static public function saveFxsUserByToken($user)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynfxs_program') . '_cache_tags'))->
        put(config('ynes.ynfxs_program') . '.user.' . $user['token'], $user, config('ynes.cache_expire'));
    }

    static public function getFxsCaptchaByIdentity($identity)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynfxs_program') . '_captcha_cache_tags'))->
        get(config('ynes.ynfxs_program') . '.captcha.' . $identity);
    }

    static public function saveFxsGhsCaptchaByIdentity($captcha)
    {
        return Cache::tags(config('ynes.' . config('ynes.ynfxs_program') . '_captcha_cache_tags'))->
        put(config('ynes.ynfxs_program') . '.captcha.' . $captcha['identity'], $captcha, config('ynes.captcha_cache_expire'));
    }
}