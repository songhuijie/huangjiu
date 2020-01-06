//移动
HTMLElement.prototype.tweenTranslateXAnimate = function(start, end, callback) {
    var duration = 50;
    var t = 0;
    var vv = end - start;
    var Tween = {
        Quad: {
            easeOut: function(t, b, c, d) {
                return -c * (t /= d) * (t - 2) + b;
            }
        }
    };

    this.timer = setInterval(function() {
        var dis = start + Tween.Quad.easeOut(++t, 0, vv, duration);
        this.style.transform = 'translate3d(' + dis + 'px, 0, 0)';
        if (vv > 0 && parseInt(this.style.transform.slice(12)) >= end) {
            this.style.transform = 'translate3d(' + parseInt(dis) + 'px, 0, 0)';
            clearInterval(this.timer);
            callback && callback();
        }
        if (vv < 0 && parseInt(this.style.transform.slice(12)) <= end) {
            this.style.transform = 'translate3d(' + parseInt(dis) + 'px, 0, 0)';
            clearInterval(this.timer);
            callback && callback();
        }
    }.bind(this), 4);
}
//touch事件部分
~function() {
    var lastPX = 0; // 上一次触摸的位置x坐标, 需要计算出手指每次移动的一点点距离
    var movex = 0; // 记录手指move的x方向值
    var imgWrap = document.getElementById('imgWrap');
    var startX = 0; // 开始触摸时手指所在x坐标
    var endX = 0; // 触摸结束时手指所在的x坐标位置
    var imgSize = imgWrap.children.length - 2; // 图片个数
    var t1 = 0; // 记录开始触摸的时刻
    var t2 = 0; // 记录结束触摸的时刻
    var width = window.innerWidth; // 当前窗口宽度
    var nodeList = document.querySelectorAll('#imgWrap img'); // 所有轮播图节点数组 NodeList

    // 给图片设置合适的left值, 注意 querySelectorAll返回 NodeList, 具有 forEach方法
    nodeList.forEach(function(node, index) {
        node.style.left = (index - 1) * width + 'px';
    });

    /**
     * 移动图片到当前的 tIndex索引所在位置
     * @param {number} tIndex 要显示的图片的索引
     * */
    function toIndex(tIndex) {
        var dis = -(tIndex * width);
        var start = parseInt(imgWrap.style.transform.slice(12));
        // 动画移动
        imgWrap.tweenTranslateXAnimate(start, dis, function() {
            setTimeout(function() {
                movex = dis;
                if (tIndex === imgSize) {
                    imgWrap.style.transform = 'translate3d(0, 0, 0)';
                    movex = 0;
                }
                if (tIndex === -1) {
                    imgWrap.style.transform = 'translate3d(' + width * (1 - imgSize) + 'px, 0, 0)';
                    movex = -width * (imgSize - 1);
                }
            }, 0);
        });
    }

    /**
     * 处理各种触摸事件 ,包括 touchstart, touchend, touchmove, touchcancel
     * @param {Event} evt 回调函数中系统传回的 js 事件对象
     * */
    function touch(evt) {
        var touch = evt.targetTouches[0];
        var tar = evt.target;
        var index = parseInt(tar.getAttribute('data-index'));
        if (evt.type === 'touchmove') {
            var di = parseInt(touch.pageX - lastPX);
            endX = touch.pageX;
            movex += di;
            imgWrap.style.webkitTransform = 'translate3d(' + movex + 'px, 0, 0)';
            lastPX = touch.pageX;
        }
        if (evt.type === 'touchend') {
            var minus = endX - startX;
            t2 = new Date().getTime() - t1;
            if (Math.abs(minus) > 0) { // 有拖动操作
                if (Math.abs(minus) < width * 0.4 && t2 > 500) { // 拖动距离不够,返回!
                    toIndex(index);
                } else { // 超过一半,看方向
                    console.log(minus);
                    if (Math.abs(minus) < 20) {
                        console.log('距离很短' + minus);
                        toIndex(index);
                        return;
                    }
                    if (minus < 0) { // endX < startX,向左滑动,是下一张
                        toIndex(index + 1)
                    } else { // endX > startX ,向右滑动, 是上一张
                        toIndex(index - 1)
                    }
                }
            } else { //没有拖动操作

            }
        }
        if (evt.type === 'touchstart') {
            lastPX = touch.pageX;
            startX = lastPX;
            endX = startX;
            t1 = new Date().getTime();
        }
        return false;
    }

    imgWrap.addEventListener('touchstart', touch, false);
    imgWrap.addEventListener('touchmove', touch, false);
    imgWrap.addEventListener('touchend', touch, false);
    imgWrap.addEventListener('touchcancel', touch, false);

}();