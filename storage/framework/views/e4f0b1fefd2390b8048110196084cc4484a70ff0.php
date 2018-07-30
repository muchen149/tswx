<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <title><?php echo e($orgCard->card_name); ?></title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
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
            height: 40px;;
        }

        .orgCard-top-1 .cloumn{
            margin-bottom:0;
            padding-bottom:0;
            text-align:center;
            float:left;
            width:49%;
        }

        .orgCard-top-1 .fenge{
            float:left;
            width: 0.5%;
            height: 100%;
            background: #000;
        }

        .orgCard-top-2{
            padding-top: 5%;
            text-align: center;
            /*position: fixed;*/
            z-index: 11;
            width: 100%;
            display: block;
        }
        .orgCard-top-2 img{
             width: 80%;
         }

        .orgCard-top-3{
            align-items: center;
            padding-top: 20px;
            text-align: center;
            z-index: 11;
            width: 100%;
            display: block;
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

        /*点击关注*/
        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:url(/sd_img/subscribe_background.jpg);background-repeat : no-repeat;background-size: 100%,100%;  display:none;}


        #popup
        {
            display:none;
            /*border:1em solid #3366FF;*/
            height:20%;
            width:60%;
            position:absolute;/*让节点脱离文档流,我的理解就是,从页面上浮出来,不再按照文档其它内容布局*/
            top:36%;/*节点脱离了文档流,如果设置位置需要用top和left,right,bottom定位*/
            left:20%;
            z-index:2;/*个人理解为层级关系,由于这个节点要在顶部显示,所以这个值比其余节点的都大*/
            background: white;
        }
        #over
        {
            width: 100%;
            height: 100%;
            opacity:0.8;/*设置背景色透明度,1为完全不透明,IE需要使用filter:alpha(opacity=80);*/
            filter:alpha(opacity=80);
            display: none;
            position:absolute;
            top:0;
            left:0;
            z-index:1;
            background: silver;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body style="overflow: hidden">
    <div class="container" style="margin-top:0px;">
        <?php if($subscribe==0): ?>
            <div style="position: fixed;z-index: 10;top:0">
                <a href="javascript:showSubscribe();"><img src="<?php echo e(asset('sd_img/t_banner.jpg')); ?>" alt="" width="100%"></a>
            </div>
        <?php endif; ?>
    <div class="orgCard-top" >
        <img  src="<?php echo e($orgCard->card_image); ?>" alt="">
    </div>
        <?php if($hasFlag==1): ?>
            <div style="width:100%;margin-top: -30px;;align-content: center;text-align:center;">
                <span  style="font: 微软雅黑;font-size: medium;">所属组织:<?php echo e($flagName); ?></span><?php if($orgCard->edit_flag==1): ?><a href="javascript:show()"> 修改 </a><?php endif; ?>
            </div>
        <?php endif; ?>
    <div class="orgCard-top-1" >
        <div class="cloumn">
            <p>积分</p>
            <p><?php echo e($memberOrgCard->org_points); ?></p>
        </div>
        <div class="fenge" ></div>
        <div class="cloumn">
            <p>余额</p>
            <p><?php echo e($memberOrgCard->orgcard_balance); ?></p>

        </div>
        
    </div>

    <div class="orgCard-top-2" >
        <a href="<?php echo e(asset('/index')); ?>"><img src="<?php echo e(asset('orgcard_img/btnUseCard.png')); ?>"  /></a>
    </div>

    <div class="orgCard-top-3" >
        <ul class="fields" style="margin: 0 auto;display:inline;">
            

            <?php if($orgCard->edit_flag!=1): ?>
                <a href="<?php echo e(asset('/orgcards/orgcardGoods/'.$orgCard->id)); ?>"><li style="margin: 5px 0 5px 15px;width:90%;border-bottom: solid; border-top: solid;border-style: ridge;font-size: 20px;padding:3px;text-align: left;" >专属商品<img style="margin: 5px 0 0 5px;float:right;" src="<?php echo e(asset('orgcard_img/img_right.png')); ?>" /></li></a>
            <?php endif; ?>
            <?php if($orgCard->card_detail_link!=''): ?>
            <a href="<?php echo e($orgCard->card_detail_link); ?>"><li style="margin: 5px 0 5px 15px;width:90%;border-bottom: solid; border-top: solid;border-style: ridge;font-size: 20px;padding:3px;text-align: left;">卡详情 <img style="margin: 5px 0 0 5px;float:right;" src="<?php echo e(asset('orgcard_img/img_right.png')); ?>" /></li></a>
            <?php endif; ?>
        </ul>
    </div>
            <div class="lanrenzhijia" id="pop-ad">
                <div class="content">
                    <img width="270px;" height="332px" src="<?php echo e(asset('/sd_img/subscribe_share.jpg')); ?>" usemap="#Map" />
                    <map name="Map">
                        <area shape="rect" coords="234,5,265,31" href="javascript:hideSubscribe();">
                    </map>
                </div>
            </div>
            <div class="content_mark"></div>
    </div>

    <div id="popup">
        <ul class="flex-between" style="margin: 5px;"><li style="float: right;padding-left: 10px; "><a href="javascript:hide()">关闭</a></li><li style="padding-right: 10px;"><a href="javascript:javascript:updateFlag();">保存</a></li></ul>
        <div><?php if($hasFlag==1): ?>
                <form id="form1" class="form" action="/orgcards/updateFlag" method="post" name="form1" >
                    <div style="width:100%;margin-top: 36px;;align-content: center;text-align:center;">
                        <input type="hidden" name="card_id" value="<?php echo e($orgCard->id); ?>" />
                        <input type="hidden" name="orgid" value="<?php echo e($orgCard->orgid); ?>" />
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
                </form>
            <?php endif; ?></div>
    </div>
    <div id="over"></div>

    </body>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //苹果手机返回时页面刷新
       /* $(function () {
            var isPageHide = false;
            window.addEventListener('pageshow', function () {
                if (isPageHide) {
                    window.location.reload();
                }
            });
            window.addEventListener('pagehide', function () {
                isPageHide = true;
            });
        });*/



        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }

        if('<?php echo e($subscribe); ?>'==0){
            $('.container').css('marginTop',35);
        }


        var popup = document.getElementById('popup');
        var over = document.getElementById('over');
        function show()
        {
            popup.style.display = "block";
            over.style.display = "block";
        }
        function hide()
        {
            popup.style.display = "none";
            over.style.display = "none";
        }
        function updateFlag(){
            if($('#org_flag').val()==0){
                message("请选择所属组织！");
                return;
            }
            if(confirm("是否确认修改？")){
                $('#form1').submit();
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>