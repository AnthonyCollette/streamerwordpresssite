jQuery(document).ready( function($) {
    wptuts_open_pointer(0);
    function wptuts_open_pointer(i) {
    $.each(wpctgPointer.pointers, function (index, value) {

        $( value.target ).pointer( 
        {
            content: value.options.content,
            position: {
            edge: value.options.position.edge,
            align: value.options.position.align
            
            },

            close: $.proxy(function () {
                $.post(ajaxurl, this);
            }, {
                pointer: value.pointer_id,
                action: 'dismiss-wp-pointer'
            }),

        }).pointer('open');
    });
}
});