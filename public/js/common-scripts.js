/*---LEFT BAR ACCORDION----*/
$(function() {

    $("#cnpj").mask("99.999.999/9999-99");

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
    
    
});


var Script = function () {
    

//    sidebar toggle
    
    $('.fa-bars').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-210px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

// custom scrollbar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#4ECDC4", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});
    $(".detailsPedido").niceScroll({styler:"fb",cursorcolor:"#4ECDC4", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});
    $("html").niceScroll({styler:"fb",cursorcolor:"#4ECDC4", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});
    

//    tool tips

    $('.tooltips').tooltip();

//    popovers

    $('.popovers').popover();



}();

// loader

function  loader() {



    var container = document.getElementById('container');
    var drop = document.getElementById('drop');
    var drop2 = document.getElementById('drop2');
    var outline = document.getElementById('outline');

    TweenMax.set(['svg'], {
        position: 'absolute',
        top: '50%',
        left: '50%',
        xPercent: -50,
        yPercent: -50
    });

    TweenMax.set([container], {
        position: 'absolute',
        top: '50%',
        left: '50%',
        xPercent: -50,
        yPercent: -50
    });

    TweenMax.set(drop, {
        transformOrigin: '50% 50%'
    });

    var tl = new TimelineMax({
        repeat: -1,
        paused: false,
        repeatDelay: 0,
        immediateRender: false
    });

    tl.timeScale(3);

    tl.to(drop, 4, {
        attr: {
            cx: 250,
            rx: '+=10',
            ry: '+=10'
        },
        ease: Back.easeInOut.config(3)
    })
        .to(drop2, 4, {
            attr: {
                cx: 250
            },
            ease: Power1.easeInOut
        }, '-=4')
        .to(drop, 4, {
            attr: {
                cx: 125,
                rx: '-=10',
                ry: '-=10'
            },
            ease: Back.easeInOut.config(3)
        })
        .to(drop2, 4, {
            attr: {
                cx: 125,
                rx: '-=10',
                ry: '-=10'
            },
            ease: Power1.easeInOut
        }, '-=4')
}