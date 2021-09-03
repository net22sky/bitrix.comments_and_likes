if (typeof Onizamov == 'undefined') var Onizamov = {};
if (typeof Onizamov.Like == 'undefined') {
    Onizamov.Like = {
        init: function () {
            let all = $('button.button.like.toggle');
            $.each(all, function (i) {
                $(this).bind('click', Onizamov.Like.toggle);
            });
        },

        toggle: function (event) {
            event.preventDefault();
            let target = event.currentTarget;
            BX.ajax.runComponentAction('onizamov:likes',
                'tooglelike', {
                    mode: 'class',
                    data: {post: $(target).attr('entity')},
                })
                .then(function (response) {
                });
            let text = $(target).find('.like__count');
            if ($(target).hasClass('like--liked')) {
                $(text).html(parseInt($(text).html()) - 1);
                $(target).removeClass('like--liked');
                $(target).attr('title', BX.message('ONIZAMOV_LIKE_LIKE'));
            } else {
                $(text).html(parseInt($(text).html()) + 1);
                $(target).addClass('like--liked');
                $(target).attr('title', BX.message('ONIZAMOV_LIKE_DISLIKE'));
            }
        }
    };
    $(function () {
        Onizamov.Like.init();
    });
}


$(document).ready(function () {
    $('div [id^="div-button-"]').each(function () {
        var left = $(this).position().left;
        var top = $(this).position().top;
        var idName = "#popup-" + $(this).attr('id');
        $(idName).css({top: top + 25, left: left, position: 'absolute'});
    });
});