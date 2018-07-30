var detailCtrl = {
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

        var swiperTopB = new Swiper('.detail-banner', {
            pagination: '.detail-banner .swiper-pagination',
            paginationClickable: true
        });

    }
};
detailCtrl.init();