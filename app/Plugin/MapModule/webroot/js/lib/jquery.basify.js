$.fn.basify = function (opts) {
    var $text = this,
        $parent = this.parent();

    $text.css({
        fontSize: opts.fontSize + 'px',
        verticalAlign: 'text-top',
        lineHeight: opts.fontSize + 'px',
        fontFamily: 'Arial'
    });
    $parent.css({
        height: parseFloat(opts.fontSize) * 0.85,
        marginBottom: '1em'
    });
}