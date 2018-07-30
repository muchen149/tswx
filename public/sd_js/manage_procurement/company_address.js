/**
 * Created by Administrator on 2017/5/8.
 */
$(function () {


    var slide_one;
    var slide_two;
    var slide_three;
    var mySwiper1 = new Swiper('.swiper-container1', {
        direction: 'vertical',
        slidesPerView: 'auto',
        centeredSlides: true,
        observer: true,         //修改swiper自己或子元素时，自动初始化swiper
        observeParents: true,   //修改swiper的父元素时，自动初始化swiper

        onSlideChangeEnd: function (swiper) {
            // $('.swiper-wrapper2').css('transform', 'translate3d(0px, 105px, 0px)');
            var one_id = $('.swiper-slide-active').find('.one-id').val();
            //console.log(one_id);
            slide_one = one_id;
            $('.swiper-container2').find('.swiper-wrapper').html('<div class="swiper-slide">' +
                '<span>-请选择-</span>' +
                ' </div>');
            $.get('/personal/address/child/' + one_id, function (data) {
                // console.log(data);

                var str = '<div class="swiper-slide">' +
                    '<span>-请选择-</span>' +
                    ' </div>';

                $.each(data['data'], function (index, item) {
                    str += '<div class="swiper-slide">' +
                        ' <span class="two-address">' + item['name'] + '</span>' +
                        '<input type="hidden" class="two-id" value="' + item['id'] + '"/>' +
                        ' <input type="hidden" class="two-pid" value="' + item['pid'] + '"/>' +
                        '</div>';

                });

                $('.swiper-container2').find('.swiper-wrapper').html(str);
                $('.swiper-container3').find('.swiper-wrapper')
                    .html('<div class="swiper-slide">' +
                        '<span>-请选择-</span>' +
                        ' </div>');

            })
        }
    });
    var mySwiper2 = new Swiper('.swiper-container2', {

        direction: 'vertical',
        slidesPerView: 'auto',
        centeredSlides: true,
        observer: true,            //修改swiper自己或子元素时，自动初始化swiper
        observeParents: true,      //修改swiper的父元素时，自动初始化swiper

        onSlideChangeEnd: function (swiper) {
            $('.swiper-container3').find('.swiper-wrapper').html('<div class="swiper-slide">' +
                '<span>-请选择-</span>' +
                ' </div>');
            var two_id = $('.swiper-slide-active').find('.two-id').val();
            //console.log(two_id);
            slide_two = two_id;
            $.get('/personal/address/child/' + two_id, function (data) {

                var str = '<div class="swiper-slide default">' +
                    '<span>-请选择-</span>' +
                    ' </div>';

                $.each(data['data'], function (index, item) {
                    str += '<div class="swiper-slide">' +
                        ' <span class="three-address">' + item['name'] + '</span>' +
                        '<input type="hidden" class="three-id" value="' + item['id'] + '"/>' +
                        ' <input type="hidden" class="three-pid" value="' + item['pid'] + '"/>' +
                        '</div>';

                });
                $('.swiper-container3').find('.swiper-wrapper').html(str);
            })

        }
    });
    var mySwiper3 = new Swiper('.swiper-container3', {

        direction: 'vertical',
        slidesPerView: 'auto',
        centeredSlides: true,
        observer: true,         //修改swiper自己或子元素时，自动初始化swiper
        observeParents: true,   //修改swiper的父元素时，自动初始化swiper

        onSlideChangeEnd: function (swiper) {
            var three_id = $('.swiper-slide-active').find('.three-id').val();
            //console.log(three_id);
            slide_three = three_id;
        }
    });


    $('#choose-address').on('click', function () {
        $('.choose-address').css('bottom', 0);
        $('body').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});
        $('html').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});
        $('.xiaoguo').addClass('yinying');  //阴影层效果
        $('#right-btn').css('transform', 'rotate(90deg)').css('transition', 'all 0.5s');

    });

    $('.address-sure-btn').on('click', function () {

        $('.xiaoguo').removeClass('yinying');
        $('#right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');

        var active = $('.swiper-slide-active');
        var one_id = active.find('.one-id').val();
        var two_id = active.find('.two-id').val();
        var three_id = active.find('.three-id').val();
        var one_address = active.find('.one-address').text();
        var two_address = active.find('.two-address').text();
        var three_address = active.find('.three-address').text();
        var address = one_address + two_address + three_address;
        $('#choose-address').text(address);
        //$('.edit-pc').text(address);

        if (one_id == undefined || two_id == undefined || three_id == undefined) {
            message('请选择有效的收货地址！');
        } else {
            $('body').css('overflow', 'hidden');
            $('#province').val(one_id);
            $('#city').val(two_id);
            $('#area').val(three_id);
            $('.choose-address').css('bottom', '-330px');
        }
    });

});