<?php $__env->startSection('title'); ?>
    图文详情
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

    <style type="text/css">
        .content{
            margin: 0 auto;
            min-width: 320px;
            overflow: hidden;
        }
        img{
            width:100%;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <div class="content">
        <div class="pddetail-cnt">
            <div class="pd-detail-tab">
                <div  id="fixed-tab-pannel">
                    <div class="fixed-tab-pannel">
                        <?php if(!$mobilecontent): ?>
                            <div style="margin:0 auto;height:400px;width:200px;margin-top:40px;text-align: center">
                                <img style="width:100%" src="/img/empty_quan.png">
                                <p class="mt20" style="color:#323232;">亲，暂时还没有图文详情介绍！</p>
                            </div>
                        <?php else: ?>
                            <?php echo $mobilecontent; ?>

                        <?php endif; ?>

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

    <script type="text/javascript">

        $(function () {
            $(".content").css({"font-size":"14px"}).find("img").attr("height","").css({"max-width":"640px",width:"100%"});
            $(".content").find("div").attr("height","").css({"max-width":"640px",width:"100%"});
            $("table").attr("height","").css({"max-width":"640px",width:"100%","font-size":"14px"});
        });

    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>