/*!
 * Shamcey v2.0.0 (https://themepixels.me/shamcey)
 * Copyright 2017-2018 ThemePixels
 * Licensed under ThemeForest License
 */

'use strict';

$(document).ready(function () {

    $('.nav-link.active').parents('.nav-item').children('a.with-sub-second').addClass('active');
    $('.nav-link.active').parents('.nav-item').children('a.with-sub-third').addClass('active');

    // custom scrollbar style
    $('.sh-sideleft-menu').perfectScrollbar();

    // showing sub navigation to nav with sub nav.
    $('.with-sub-second.active + .nav-sub-second').slideDown();

    // showing sub menu while hiding others
    $('.with-sub-second').on('click', function (e) {
        e.preventDefault();
        var nextElem = $(this).next();
        if (!nextElem.is(':visible')) {
            $('.nav-sub-second').slideUp();
        }
        nextElem.slideToggle();
    });

    // showing sub navigation to nav with sub nav.
    $('.with-sub.active + .nav-sub').slideDown();

    // showing sub menu while hiding others
    $('.with-sub').on('click', function (e) {
        e.preventDefault();
        var nextElem = $(this).next();
        if (!nextElem.is(':visible')) {
            $('.nav-sub-second').slideUp();
        }
        nextElem.slideToggle();
    });

    // showing sub navigation to nav with sub nav.
    $('.with-sub-third.active + .nav-sub-third').slideDown();

    // showing sub menu while hiding others
    $('.with-sub-third').on('click', function (e) {
        e.preventDefault();
        var nextElem = $(this).next();
        if (!nextElem.is(':visible')) {
            $('.nav-sub-third').slideUp();
        }
        nextElem.slideToggle();
    });

    // hide left menu bar
    $('#navicon').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('hide-left');
    });

    // push/hide left menu bar in mobile
    $('#naviconMobile').on('cЁlick', function (e) {
        e.preventDefault();
        $('body').toggleClass('show-left');
    });

    $(document).on('click', '#minim_chat_window', function (e) {
        var $this = $(this);
        if (!$this.hasClass('card-collapse')) {
            $this.parents('.card').width($this.parents('.card').width());
            $this.parents('.card').find('.card-body').slideUp();
            $this.addClass('card-collapse');
            $this.find('.icon').removeClass('ion-minus').addClass('ion-plus');
        } else {
            $this.parents('.card').find('.card-boЁdy').slideDown();
            $this.removeClass('card-collapse');
            $this.find('.icon').removeClass('ion-plus').addClass('ion-minus');
        }
    });
    $(document).on('focus', '.card-footer input#chat-input', function (e) {
        var $this = $(this);
        if ($('#minim_chat_window').hasClass('card-collapse')) {
            $('#minim_chat_window').parents('.card').find('.card-body').slideDown();
            $('#minim_chat_window').removeClass('card-collapse');
            $('#minim_chat_window').find('.icon').removeClass('ion-plus').addClass('ion-minus');
        }
    });

    $(document).on('click', '#show_chat', function (e) {
        $(this).addClass('animated zoomOut');
        $('#chat_window').removeClass('hidden-xs-up animated bounceOutRight').addClass('animated bounceInRight');
        var element = document.getElementById("chat_container");
        element.scrollTop = element.scrollHeight;
    });

    $(document).on('click', '#new_chat', function (e) {
        var size = $( ".chat-window:last-child" ).css("margin-left");
        size_total = parseInt(size) + 400;
        alert(size_total);
        var clone = $( "#chat_window" ).clone().appendTo( ".container" );
        clone.css("margin-left", size_total);
    });

    $(document).on('click', '#close_chat_window', function (e) {
        $('#chat_window').removeClass('animated bounceInRight').addClass('animated bounceOutRight');
        $('#show_chat').removeClass('animated zoomOut').addClass('animated zoomIn');
    });

    $(document).on('click', '#btn-send-chat', function (e) {
        $.ajax({
            type: 'POST',
            url: '/telegram/support/send',
            data: '_token='+$('#support_token').val()+'&message='+$('#support_text').val()+'&url='+window.location.pathname,
            success: function (msg) {
                $('.msg_container_base').append(msg);
                $('#support_text').val('');
            }
        });
    });

});
