<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 9/6/16
 * Time: 9:00 AM
 */
return [
    
    /**
     * 上传文件最大长度【限制在5 M】
     * @var int
     */
    'maxSize' => 5242880,

    /**
     * 允许上传文件的后缀
     * @var array
     */
    'allowExt' => array('jpeg', 'jpg', 'png', 'gif'),

    /**
     * 是否检查上传文件的类型
     * @var bool
     */
    'imgFlag' => true,

    /**
     * 指定的上传文件类型
     * @var array
     */
    'allowMime' => array('image/jpeg', 'image/png', 'image/gif'),

    /**
     * 上传文件的保存路径根目录
     * @var string
     */
    'rootPath' => '/w3c/yydimages',
    // 'rootPath' => '/kdata/w3c',             // 本地测试

    /**
     * 文件访问路径
     */
    'imgDomain' => 'http://tsimg.shuitine.com'
    // 'imgDomain' => 'http://localhost',      // 本地测试
];

