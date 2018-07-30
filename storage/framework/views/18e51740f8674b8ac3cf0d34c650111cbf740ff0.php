<?php $__env->startSection('title'); ?>
    <?php echo e($form->title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('form_css/jquery-ui-1.9.2.custom.css')); ?>"/>
    
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('form_css/default.css')); ?>"/>
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('form_css/formview.css')); ?>"/>
    <style>

        /*阴影*/
        .yinying {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 5;
            background-color: #000;
            opacity: 0.4; }

        .noscroll,
        .noscroll body {
            overflow: hidden;
        }
        .noscroll body {
            position: relative;
        }

        .bottom-menu {
            position: fixed;
            z-index: 11;
            width: 100%;
            bottom: 0;
            left: 0;
            height: 45px;
            line-height: 45px;
            background: #257cd4;
            display: block;
        }

        .bottom-menu div {
            color: #fff;
            flex: 1;
            text-align: center;
            box-sizing: border-box;
            font-weight: 600;
            font-size: 16px;
            font-family: 微软雅黑;
        }

        .formData {
            position: relative;
            z-index: 10;
            bottom: 0;
            left: 0;
            overflow: hidden;
            background: #fff;
            width: 100%;
        }
        /*关闭表单按钮*/
        .text-right {
            position: fixed;
            z-index: 11;
            height:5%;
            width:100%;
            background:#fff;
        }
        .content{
            margin: 0 auto;
            min-width: 320px;
            overflow: hidden;
        }
        .content p img{
            width:100%;
        }
        .details {
            padding:10px;
        }
        .details li {
            padding-top:10px;
            height:10%;
            width:100%;

        }
        .details li img {
            width:10%;
        }
        .details li span {
            padding-left: 5px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <body>


    <div id="container">

        <div><img src="<?php echo e($form->image_url); ?>" alt="" width="100%"></div>
        <ul class="details">
            <li><img src="<?php echo e(asset('form_img/01.png')); ?>" alt=""><span><?php echo e($form->sponsor); ?></span></li>
            <li><img src="<?php echo e(asset('form_img/02.png')); ?>" alt=""><span><?php echo e($form->etime); ?></span></li>
            <li><img src="<?php echo e(asset('form_img/03.png')); ?>" alt=""><span><?php echo e($form->address); ?></span></li>
        </ul>
        <div>
            <div id="list-detail" class="i-showTabList" style="margin-bottom: 20px;margin-left:10px;">
                <div style="margin-bottom: 10px;font-weight: 700;margin-left: 10px;margin-top: 10px;font-size: 14px;">
                    已报名人数(<?php echo e($users); ?>)
                </div>
                <div class="swiper-container sh-swiper base">
                    <div class="swiper-wrapper">
                        <?php if($user_data): ?>
                            <?php $__currentLoopData = $user_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <div class="swiper-slide" style="font-size:12px;overflow:hidden;display: inline-block;">
                                    <div style="vertical-align: middle;display: table-cell;text-align: center;padding: 0 8px;">
                                        <?php if($val->avatar): ?>
                                            <div style="width:50px; height:50px; border-radius:50%; overflow:hidden;text-align: center;">
                                                <img src="<?php echo e($val->avatar); ?>" width="100%" alt="">
                                            </div>
                                        <?php endif; ?>
                                        <h5 style="text-decoration:none;text-align: center;width:10px;">
                                            <nobr><?php echo e($val->displayName); ?></nobr>
                                        </h5>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <?php echo $form->content; ?>

        </div>

        <form id="form1" class="form" action="/form/add" method="post" name="form1">
            <div id="formHeader" class="info">
                <h2 id="fTitle"></h2>
                <div id="DESC"></div>
            </div>

            <div id="list-detail" class="i-showTabList" style="margin-bottom: 20px;margin-left:10px;">
                
                <a href="/index"><div style="text-align: center;color:#999;padding-bottom: 15px;">—本活动由<span style="font-weight: 600;color:black">&nbsp;水丁网&nbsp;</span>提供技术支持—</div></a>
            </div>

            <div class="formData" style="height:60%;overflow-y: scroll;">
                <div class="text-right close-choose">
                    <img src="<?php echo e(asset('sd_img/close.png')); ?>" height="20" width="20" style="margin-right: 10px;margin-top: 5px;"/>
                </div>
                <ul id="fields" class="fields" style="margin-top: 20px;">
                </ul>
                <?php if($form->is_show == 1): ?>
                    <div style="padding-bottom: 60px;padding-left:15px;">
                        <input type="checkbox" name="anonymous" value="1" style="border:0;outline:none;"
                               id="1"><label for="1" style="vertical-align:middle;padding-left: 5px;">匿名</label>
                    </div>
                <?php endif; ?>
            </div>

            <input id="FRMID" name="FRMID" autofill="" value="<?php echo e($form->eid); ?>" type="hidden"/>

            <div id="baoming" class="bottom-menu">
                <div>立即报名</div>
            </div>

        </form>
    </div>


    <div id="stage" class="clearfix hide">
        <img class="img-option" src=""/>
    </div>
    <div class="hide" id="divtmp"></div>
    <div id="overlay" class="overlay hide"></div>
    <div id="lightBox" class="lightbox hide">
        <div id="lbContent" class="lbcontent clearfix"></div>
    </div>

    <!------------------------选择规格时body的阴影部分  START!---------------------------->
    <div class="xiaoguo">

    </div>
    <!--======================选择规格时body的阴影部分  END!============================-->
    </body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

    
    <script type="text/javascript" src="<?php echo e(asset('/form_js/head.load.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/form_js/form.js')); ?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">


        var stoken = '<?php echo e($stoken); ?>';
        var appId = '<?php echo e($signPackage['appId']); ?>';
        var timestamp = '<?php echo e($signPackage['timestamp']); ?>';
        var nonceStr = '<?php echo e($signPackage['nonceStr']); ?>';
        var signature = '<?php echo e($signPackage['signature']); ?>';
        var link = '<?php echo e($share_link); ?>';
        var title = '<?php echo e($form->title); ?>';
        var imgUrl = '<?php echo e(asset('sd_img/neida_baoming.jpg')); ?>';
        var desc = '在此诚挚邀请各位校友为我们的队员加油，本周六我们不见不散！';
        //alert(imgUrl);
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
                desc: desc,
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



        //报名表单
        $('.formData').hide();
        $('#baoming').click(function () {
            $('.formData').show();
            $('#baoming').hide();
            $('.formData').css('position', 'relative');
            $('.formData').css('position', 'fixed');
        });
        $('.text-right').click(function () {
            $('.formData').hide();
            $('#baoming').show();
            //$('html,body').removeClass('ovfHiden'); //使网页恢复可滚
            //$('#container').removeClass('noscroll');
            $('.xiaoguo').removeClass('yinying');
            $('html,body').removeAttr('style');
            $(window).scrollTop(window.lastScrollTop);
        });
        //表单浮动
        $('#baoming').click(function () {
            $('#fields').css('bottom', 0);

            $('.xiaoguo').addClass('yinying');
            window.lastScrollTop = sTop = document.body.scrollTop || document.documentElement.scrollTop;
            var wHeight = $(window).height();
            $('html').css({'height':wHeight,'overflow':'hidden'});
            $('body').css({'height':wHeight,'overflow':'hidden'});
        });

        //苹果手机返回时页面刷新
        $(function () {
            var isPageHide = false;
            window.addEventListener('pageshow', function () {
                if (isPageHide) {
                    window.location.reload();
                }
            });
            window.addEventListener('pagehide', function () {
                isPageHide = true;
            });
        });


        //编辑表单
        var isEmbed = false, F = {"DISSHARE": ""};
        var RULE = {FIELDSRULE: []};
        var ADVPERM = {};
        //debugger;
        var M = {};
        /*eval('(' + '' + ')');*/

        /*$('#DESC').html('');*/
        var F = eval('<?php echo $form->formfields; ?>');

        /* var M = {"FRMNM":"12345","DESC":"","LANG":"cn","LBLAL":"T","CFMTYP":"T","CFMMSG":"提交成功。","SDMAIL":"0","CAPTCHA":"1","IPLMT":"0","SCHACT":"0","INSTR":"0","ISPUB":"1","GID":"","HEIGHT":933};//{ FRMNM: "表单名称", DESC: "", LANG: "cn", LBLAL: "T", CFMTYP: "T", CFMMSG: "提交成功。", SDMAIL: "0", CAPTCHA: "1", IPLMT: "0", SCHACT: "0", INSTR: "0", ISPUB: "1" }
         var F = [{"LBL":"1多选框","TYP":"checkbox","LAY":"one","REQD":"0","SCU":"pub","INSTR":"","CSS":"","ITMS":[{"VAL":"选项 1","CHKED":"0"},{"VAL":"选项 2","CHKED":"0"},{"VAL":"选项 3","CHKED":"0"}]},
         {"LBL":"1多选框","TYP":"checkbox","LAY":"one","REQD":"0","SCU":"pub","INSTR":"","CSS":"","ITMS":[{"VAL":"选项 1","CHKED":"0"},{"VAL":"选项 2","CHKED":"0"},{"VAL":"选项 3","CHKED":"0"}]},
         {"LBL":"数字框","TYP":"number","FLDSZ":"s","REQD":"0","UNIQ":"0","SCU":"pub"},
         {"LBL":"单行文本","TYP":"text","FLDSZ":"m","REQD":"0","UNIQ":"0","SCU":"pub"},
         {"LBL":"多行文本","TYP":"textarea","FLDSZ":"s","REQD":"0","UNIQ":"0","SCU":"pub","MIN":"","MAX":"","DEF":"","INSTR":"","CSS":""},
         {"LBL":"单选框","TYP":"radio","LAY":"one","REQD":"0","OTHER":"0","RDM":"0","SCU":"pub","INSTR":"","CSS":"","ITMS":[{"VAL":"选项 1","CHKED":"0"},{"VAL":"选项 2","CHKED":"0"},{"VAL":"选项 3","CHKED":"0"}]},
         {"LBL":"单选框","TYP":"radio","LAY":"one","REQD":"0","OTHER":"0","RDM":"0","SCU":"pub","INSTR":"","CSS":"","ITMS":[{"VAL":"选项 1","CHKED":"0"},{"VAL":"选项 2","CHKED":"0"},{"VAL":"选项 3","CHKED":"0"}]},
         {"LBL":"单选框","TYP":"radio","LAY":"one","REQD":"0","OTHER":"0","RDM":"0","SCU":"pub","INSTR":"","CSS":"","ITMS":[{"VAL":"选项 1","CHKED":"0"},{"VAL":"选项 2","CHKED":"0"},{"VAL":"选项 3","CHKED":"0"}]},
         {"LBL":"多行文本","TYP":"textarea","FLDSZ":"s","REQD":"0","UNIQ":"0","SCU":"pub","MIN":"","MAX":"","DEF":"","INSTR":"","CSS":""},
         {"LBL":"单行文本","TYP":"text","FLDSZ":"m","REQD":"0","UNIQ":"0","SCU":"pub"},
         {"LBL":"姓名","TYP":"name","REQD":"0","UNIQ":"0","SCU":"pub","FMT":"short","DEF":"","INSTR":"","CSS":""}];
         */
        var fieldsLimit = 150;
        var goodsNumber = 60;
        var imageNumber = 10;
        var LVL = 4;
        var fieldsLimit = 150;
        var goodsNumber = 60;
        var imageNumber = 10;
        var LVL = 4;

        var isForTemplate = false;
        M.GID = "23423werfwetwe";

        var isForMobile = false;

        var IMAGEURL = "#", FILEIMAGEEDITSTYLE = "@100w_90Q";
        head.js("/form_js/jquery-1.7.2.min.js", '/form_js/address-cn.js?v=20160929',
                "/form_js/utils.js?v=20160929",
                "/form_js/formview.js?v=20160929");

        /*indexCtrl.init();*/
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>