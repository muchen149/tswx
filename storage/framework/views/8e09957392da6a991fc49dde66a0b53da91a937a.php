<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('form_css/jquery-ui-1.9.2.custom.css')); ?>"/>
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('form_css/orgcardview.css')); ?>"/>
    <title><?php echo e($orgCard->card_name); ?></title>
    <style>
        body{
            background: url("/orgcard_img/orgcard_background.png") no-repeat;
            background-size: 100%;
        }
        .orgCard-top{
            margin-top: 5%;
            text-align: center;
        }
        .orgCard-top img{
            width: 90%;
        }

        .orgCard-top-1{
            margin-top: 5%;
            text-align: center;
        }

        .orgCard-top-2{
            margin-bottom: 10%;
        }


        .btn-Submit {
            position: fixed;
            z-index: 11;
            width: 100%;
            bottom: 0;
            left: 0;
            display: block;
        }

        .btn-Submit img{
            width: 90%;
        }

        .btn-Submit div {
            /*color: #fff;*/
            flex: 1;
            text-align: center;
            box-sizing: border-box;
            font-weight: 600;
            font-size: 16px;
            font-family: 微软雅黑;
        }




    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <div class="orgCard-top" >
        <img  src="<?php echo e($orgCard->card_image); ?>" alt="">
    </div>
    <div class="orgCard-top-1" >
        <p style="font: 微软雅黑;font-size: medium;font-weight: bold;"><?php echo e($orgCard->card_desc); ?></p>
    </div>

    <input id="hasFlag" name="hasFlag" value="<?php echo e($hasFlag); ?>" type="hidden"/>
    <div class="orgCard-top-2" >
        <form id="form1" class="form" action="/orgcards/add" method="post" name="form1" >
            <?php if($hasFlag==1): ?>
                <div style="width:100%;margin-top: 10px;;align-content: center;text-align:center;">
                <span  style="font: 微软雅黑;font-size: medium;">所属组织
                    </span><span>
                <select id="org_flag" name="org_flag"  required="required">
                 <option value="0">-请选择-</option>
                    <?php $__currentLoopData = $orgFlags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orgFlag): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <option value="<?php echo e($orgFlag->id); ?>"><?php echo e($orgFlag->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                 </select>
                    </span>
                </div>
            <?php endif; ?>
            <div id="formHeader" class="info">
                <h2 id="fTitle"></h2>
                <div id="DESC"></div>
            </div>
            <div class="formData" >
                <ul id="fields" class="fields" style="margin-top: 20px;">
                </ul>
            </div>

                <?php if($form): ?>
                <input id="FRMID" name="FRMID" autofill="" value="<?php echo e($form->fid); ?>" type="hidden"/>
                <?php endif; ?>
                <input id="card_id" name="card_id" autofill="" value="<?php echo e($orgCard->id); ?>" type="hidden"/>
                <input id="orgid" name="orgid" autofill="" value="<?php echo e($orgCard->orgid); ?>" type="hidden"/>

            <div id="btnSubmit" class="btn-Submit">
                <div><img src="<?php echo e(asset('orgcard_img/btnGetOrgCard.png')); ?>" /></div>
            </div>

        </form>
    </div>


    </body>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

    <script type="text/javascript" src="<?php echo e(asset('/form_js/head.load.min.js')); ?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //分享相关数据——start
        var appId = '<?php echo e($signPackage['appId']); ?>';
        var timestamp = '<?php echo e($signPackage['timestamp']); ?>';
        var nonceStr = '<?php echo e($signPackage['nonceStr']); ?>';
        var signature = '<?php echo e($signPackage['signature']); ?>';
        //var link = 'http://tswx.shuitine.com';
        //var title = '水丁管家--精致生活服务管家';
        var imgUrl = '<?php echo e($orgCard->card_image); ?>';
        //var desc = '精挑细选，甄选优质商品及服务；定制生活、尽情享受；一对一贴心服务，省去后顾之忧。';

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
                //title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                //link:link,
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
                //desc: desc,//'分享送好礼', // 分享描述
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
        <?php if($form): ?>
        var F = eval('<?php echo $form->formfields; ?>');
        <?php else: ?>
        var F = '';
        <?php endif; ?>


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
        M.GID = "";

        var isForMobile = false;

        var IMAGEURL = "#", FILEIMAGEEDITSTYLE = "@100w_90Q";
        head.js("/form_js/jquery-1.7.2.min.js", '/form_js/address-cn.js?v=20160929',
                "/form_js/utils.js?v=20160929",
                "/orgcard_js/orgcardformview.js?v=20160929");



    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>