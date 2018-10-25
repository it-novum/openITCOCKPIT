$(document).ready(function(){
    //Fix left-panel height
    $('#left-panel').css('height', $(document).innerHeight() + 'px');

    setTimeout(function(){
        var maxHeightBody = $('nav.ng-isolate-scope').height() > $(document).innerHeight() ? $('nav.ng-isolate-scope').height() : $(document).innerHeight();
        $('#left-panel').css('height', maxHeightBody + 'px');
    }, 1000);
    //Fix drop down issue in mobile tables
    //$('.dropdown-toggle').off();

    /*
     * Set an id for all the drop-down menus
     */
    $('.dropdown-menu').each(function(key, object){
        if(typeof $(object).attr('id') === 'undefined'){
            $(object).attr('id', (Math.floor(Math.random() * (100000000 - 1)) + 1));
        }
    });

    //$('table .dropdown-toggle').click(function (){
    $(document).on('click', 'table .dropdown-toggle', function(){
        //This is hacky shit and need to get frefactored ASAP!!

        if($('#uglyDropdownMenuHack').html() != ''){
            //Avoid that the menu destroy it self if the user press twice on the 'open menu' arrow
            return false;
        }

        var $ul = $(this).next('ul');

        //$ul.hide();
        var offset = $(this).offset(),
            right = $('body').width() - 26 - parseInt(offset.left);
        $('#uglyDropdownMenuHack').attr('sourceId', $ul.attr('id'));
        $('#uglyDropdownMenuHack').html($ul.clone(true, true).attr('id', 'foobarclonezilla'));

        //Remove orginal element for postLinks (duplicate form is bad)
        $ul.html('');

        if($ul.hasClass("pull-right")){
            $('#uglyDropdownMenuHack')
                .children('ul')
                .addClass('animated flipInX')
                .show()
                .css({
                    'position': 'absolute',
                    'top': parseInt(offset.top + 20) + 'px',
                    'left': 'auto',
                    'right': right,
                    'animation-duration': '0.4s'
                });
        }else{
            $('#uglyDropdownMenuHack')
                .children('ul')
                .addClass('animated flipInX')
                .show()
                .css({
                    'position': 'absolute',
                    'top': parseInt(offset.top + 20) + 'px',
                    'left': parseInt(offset.left - 20) + 'px',
                    'animation-duration': '0.4s'
                });
        }
    });


    $(document).on('hidden.bs.dropdown', function(){
        //Restore orginal menu content

        if(typeof $('#uglyDropdownMenuHack').attr('sourceId') === 'undefined' || $('#uglyDropdownMenuHack').attr('sourceId') === ''){
            //Drop down menu is not inside a table
            return;
        }

        $('#' + $('#uglyDropdownMenuHack').attr('sourceId')).html($('#foobarclonezilla').children().clone(true, true));
        $('#uglyDropdownMenuHack').html('');
        $('#uglyDropdownMenuHack').attr('sourceId', '');
    });

    //Scroll back to top
    var scrollTopVisable = false; //Avoid millions of fadeIn actions
    $(window).scroll(function(){
        if($(document).scrollTop() > 150){
            if(scrollTopVisable === false){
                $('#scroll-top-container').fadeIn();
                scrollTopVisable = true;
            }
        }else{
            if(scrollTopVisable === true){
                $('#scroll-top-container').fadeOut();
                scrollTopVisable = false;
            }
        }
    });

    $('#scroll-top-container').click(function(){
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    /*
    $(document).on('show.bs.dropdown', function (e) {
        $('.mobile_table table').css('margin-bottom', '250px');
    });

    $(document).on('hidden.bs.dropdown', function (e) {
        $('.mobile_table table').css('margin-bottom', '0');
    });
    */

});
