(function ($) {
    $.fn.responsiveCanvas = function (options) {
        // Canvas info
        var canvas = this;

        // Default aspect ratio
        var aspectRatio = 1;


        // Resize canvas with window
        canvas.parent().resize(function (e) {
            var width = canvas.parent().width();
            var height = width / aspectRatio;

            setSize(width, height);
            redraw();
        });

        // Return the mouse/touch location
        function getCursor(element, event) {
            var cur = {x: 0, y: 0};
            if (event.type.indexOf('touch') !== -1) {
                cur.x = event.originalEvent.touches[0].pageX;
                cur.y = event.originalEvent.touches[0].pageY;
            } else {
                cur.x = event.pageX;
                cur.y = event.pageY;
            }
            return {
                x: (cur.x - $(element).offset().left) / $(element).width(),
                y: (cur.y - $(element).offset().top) / $(element).height()
            }
        }

        // Set the canvas size
        function setSize(w, h) {
            canvas.width(w);
            canvas.height(h);

            canvas[0].setAttribute('width', w);
            canvas[0].setAttribute('height', h);
        }

        function redraw() {
            var width = $(canvas).width();
            var height = $(canvas).height();
        }

        function init() {
            if (options.data) {
                aspectRatio = typeof options.data.aspectRatio !== 'undefined' ? options.data.aspectRatio : aspectRatio;
            } else {
                aspectRatio = typeof options.aspectRatio !== 'undefined' ? options.aspectRatio : aspectRatio;
            }

            var canvasColor = typeof options.canvasColor !== 'undefined' ? options.canvasColor : '#fff';
            canvas.css('background-color', canvasColor);

            // Set canvas size
            var width = canvas.parent().width();
            var height = width / aspectRatio;

            setSize(width, height);
            redraw();
        }

        init();

        jQuery(window).resize(function(e) {
            init();
        });

        this.json = function () {
            return JSON.stringify({
                aspectRatio: aspectRatio,
                strokes: strokes
            });
        };
        this.getImage = function () {
            return '<img src="' + canvas[0].toDataURL("image/png") + '"/>';
        };

        return this;
    };
}(jQuery));
