/**
 * jQuery spin - v1.0.1
 * Simple spinner plugin for jQuery
 * 
 * Creates a loading spinner overlay
 */
(function($) {
    $.fn.spin = function(opts, color) {
        var defaults = {
            lines: 12,
            length: 7,
            width: 4,
            radius: 10,
            corners: 1,
            rotate: 0,
            direction: 1,
            color: '#fff',
            speed: 1,
            trail: 100,
            opacity: 0.25,
            top: '50%',
            left: '50%',
            position: 'absolute'
        };
        
        if (opts === false) {
            this.each(function() {
                $(this).children('.spinner-container').remove();
            });
            return this;
        }
        
        if (typeof opts === 'string') {
            color = opts;
            opts = {};
        }
        
        opts = $.extend(defaults, opts || {});
        
        return this.each(function() {
            var $this = $(this);
            var $container = $('<div class="spinner-container"></div>');
            
            $container.css({
                position: opts.position,
                top: opts.top,
                left: opts.left,
                marginTop: -(opts.length + opts.width) + 'px',
                marginLeft: -(opts.length + opts.width) + 'px',
                width: (opts.length + opts.width) * 2 + 'px',
                height: (opts.length + opts.width) * 2 + 'px',
                textAlign: 'center'
            });
            
            var spinner = $('<div class="spinner-gif"></div>');
            
            spinner.css({
                display: 'inline-block',
                width: (opts.length + opts.width) * 2 + 'px',
                height: (opts.length + opts.width) * 2 + 'px',
                borderRadius: '50%',
                border: opts.width + 'px solid ' + opts.color,
                borderTopColor: 'transparent',
                opacity: opts.opacity,
                animation: 'spin ' + (1 / opts.speed) + 's linear infinite'
            });
            
            $container.append(spinner);
            $this.append($container);
            
            if (!$('#spinner-styles').length) {
                $('head').append('<style id="spinner-styles">@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>');
            }
        });
    };
    
    $.fn.spin.defaults = {
        lines: 12,
        length: 7,
        width: 4,
        radius: 10,
        corners: 1,
        rotate: 0,
        direction: 1,
        color: '#fff',
        speed: 1,
        trail: 100,
        opacity: 0.25
    };
})(jQuery);

/**
 * Función global para mostrar/ocultar spinner
 */
function jsSpinner(action, target) {
    if(target == null) {
        target = 'spinner';
    }
    if(action == 'show') {
        $("#spinner").attr('style','top: 50%; left:50%; margin-left:-50px; margin-top:-50px;');
        $("#loading-content").show();
        $("#"+target).show().spin('large', 'white');
    } else {
        $("#loading-content").hide();
        $("#"+target).hide().spin(false);
    }
}
