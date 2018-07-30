<?php $__env->startSection('title'); ?>
    <?php echo e($article->article_title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/header.css')); ?>">

    <style type="text/css">
        .content{
            margin: 0 auto;
            min-width: 320px;
            overflow: hidden;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>

    <div class="content">
        <div class="pddetail-cnt">
            <div class="pd-detail-tab">
                <div  id="fixed-tab-pannel">
                    <div class="fixed-tab-pannel" style="padding: 0 0px;">
                            <?php echo $article->content; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //分享相关数据——start
        var appId = '<?php echo e($signPackage['appId']); ?>';
        var timestamp = '<?php echo e($signPackage['timestamp']); ?>';
        var nonceStr = '<?php echo e($signPackage['nonceStr']); ?>';
        var signature = '<?php echo e($signPackage['signature']); ?>';
        var link = '<?php echo e($share_link); ?>';
        var title = '<?php echo e($article->article_title); ?>';
        var imgUrl = '<?php echo e($article->image_url_1); ?>';
        var desc = '精挑细选，甄选优质商品及服务；定制生活、尽情享受；一对一贴心服务，省去后顾之忧。';

        wx.config({
            debug: false,
            appId: appId,
            timestamp: timestamp,
            nonceStr: nonceStr,
            signature: signature,
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                link:link,
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });


            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc,//'分享送好礼', // 分享描述
                imgUrl: imgUrl, // 分享图标
                link:link,
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });
        });

        //分享相关数据——end

        $(function () {
            $(".content").css({"font-size":"14px"}).find("img").attr("height","").css({"max-width":"640px",width:"100%"});
            $(".content").find("div").attr("height","").css({"max-width":"640px",width:"100%"});
            $("table").attr("height","").css({"max-width":"640px",width:"100%","font-size":"14px"});
        });

    </script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>