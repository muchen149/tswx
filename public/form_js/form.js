/**
 * Created by Administrator on 2017/10/12 0012.
 */
var indexCtrl = {
    dom: function() {
        var _this = this;
    },
    bind: function() {
        var _this = this;
    },
    init: function() {
        var _this = this;
        _this.dom();
        _this.bind();

        var swiperSH = new Swiper('.sh-swiper', {
            slidesPerView: 5.5,
            paginationClickable: true,
            observer: true,
            spaceBetween: 5,
            freeMode: true
        });


    }
}
indexCtrl.init();