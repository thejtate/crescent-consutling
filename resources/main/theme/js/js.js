(function ($) {

  if (typeof Drupal != 'undefined') {
    Drupal.behaviors.crescent = {
      attach: function (context, settings) {
        init();
      },

      completedCallback: function () {
        // Do nothing. But it's here in case other modules/themes want to override it.
      }
    }
  }

  $(function () {
    if (typeof Drupal == 'undefined') {
      init();
    }
  });

  $(window).load(function () {

  });

  function init() {
    initTabsBg();
    //  initMobileBtn();
    initFlexSlider();
    initSelectTabs();
    //initMenu();
    initMobileNav();
    initMobileFooter();
    initCloneSlider();
    initSlickSlider();
    initElmsAnimation();
    initScrollR();
  }

  function initScrollR() {

    if(document.documentElement.classList.contains('tablet') || document.documentElement.classList.contains('mobile')) {
      return;
    }

    var s = skrollr.init({
      render: function (data) {

      },
      forceHeight: false,
      smoothScrolling: false
    });
  }

  function initElmsAnimation() {
    var $elms = $('.el-with-animation');
    var animationEnd = [];

    $(window).on('resize scroll', checkScroll);

    checkScroll();

    function checkScroll() {
      if (animationEnd.length === $elms.length) return;

      for (var i = 0; i < $elms.length; i++) {
        var $currentEl = $elms.eq(i);

        if (!$currentEl.hasClass('animating-end') && $(window).height() + $(window).scrollTop() > $currentEl.offset().top + $currentEl.height() / 2 + 50) {
          animate($currentEl);
        }
      }
    }

    function animate(el) {
      el.addClass('animating-end');
      animationEnd.push(1);
    }
  }

  function initCloneSlider() {
    var $carousel = $('.slider-nav'),
      $slider = $('.slider-for');

    if ($carousel.find('>div').length <= 3) return;

    if (!$carousel || !$slider) return;

    $slider.append($slider.find('>div').clone().addClass('clone'));
    $carousel.append($carousel.find('>div').clone().addClass('clone'));

  }

  function initSlickSlider() {

    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      autoplay: true,
      autoplaySpeed: 2000,
      speed:600,
      pauseOnHover:true,
      asNavFor: '.slider-nav'
    });

    $('.slider-nav').slick({
      slidesToShow: 6,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: false,
      arrows: false,
      centerMode: true,
      autoplay: true,
      autoplaySpeed: 2000,
      speed:600,
      focusOnSelect: true,
      infinite: true,

      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true
          }
        },

        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true
          }
        },

        {
          breakpoint: 550,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true
          }
        }
      ]
    });
  }

  function initMobileFooter() {
    var $wrapper = $('.site-footer'),
      $listExpanded = $wrapper.find('.menu li.expanded>a');


    $listExpanded.append('<span class="icon-next-menu"></span>');

    var $btn = $('.icon-next-menu');

    $btn.on('click touch', function (e) {
      e.preventDefault();

      var $subMenu = $(this).closest('.expanded').find('.menu');

      if ($subMenu.hasClass('active')) {
        $('.expanded .menu').removeClass('active');
        $btn.removeClass('active');
      } else {
        $('.expanded .menu').removeClass('active');
        $btn.removeClass('active');
        $subMenu.addClass('active');
        $(this).addClass('active')
      }
    })
  }

  function initSelectTabs() {
    var $wrapper = $('.b-tabs-wrapper'),
      $elements = $wrapper.find('.quicktabs-tabs');

    $wrapper.prepend('<div class="tabs-list"><div class="title"></div> <ul class="quicktabs-tabs">' + $elements.html() + ' </ul></div>');

    var $title = $('.tabs-list .title');

    $title.html($('.tabs-list .quicktabs-tabs li.active').html());

    $title.on('click touch', function (e) {
      e.preventDefault();

      $('.tabs-list').toggleClass('open-tabs');

    })

  }

  function initFlexSlider() {
    $('.flexslider').flexslider({
      animation: "slide",
      prevText: "",           //String: Set the text for the "previous" directionNav item
      nextText: "",
      pauseOnAction: false,
      slideshowSpeed: 5000
    });
  }

  function initTabsBg() {
    var $wrapper = $('.b-tabs-wrapper');

    if ($('body').hasClass('mobile-device')) return;

    if (!$wrapper.length || $wrapper.hasClass('tabs-wrapper-processed')) return;

    $wrapper.addClass('tabs-wrapper-processed');

    var height = 0;

    height += $wrapper.find('.quicktabs-tabs').outerHeight(true);

    if ($wrapper.siblings('.content').children().length > 0) {
      height += $wrapper.siblings('.content').outerHeight(true);
    }

    height += $wrapper.siblings('.title').outerHeight(true);

    if ($(window).outerWidth() < 980) {
      return;
    } else {
      $wrapper.parent().prepend('<span style="height:' + height + 'px;" class="bg"></span>');
    }
  }

  function initMobileBtn() {

    var $btn = $('.btn-menu');

    $btn.on('click touch', function (e) {
      e.preventDefault();

      $('body').toggleClass('mobile-nav-active');
    })
  }

  function initMenu() {
    var $elem = $('.nav ul .expanded');

    $elem.append('<a href="#" class="btn-open-expanded"></a>');

    var $btn = $('.btn-open-expanded');

    $btn.on('click touch', function (e) {
      e.preventDefault();

      $(this).parent('li').siblings().removeClass('open-menu');

      $(this).parent().toggleClass('open-menu');
    })
  }

  function initMobileNav() {
    var $wrapper = $('.nav');
    var $listWrapper = $wrapper.find('.menu-block-wrapper ul');
    var $list = $listWrapper.find('li');
    var $listLinks = $list.find('a');
    var $btn = $('.btn-mobile');
    var $body = $('body');

    if ($wrapper.hasClass('nav-processed')) return;

    $wrapper.addClass('nav-processed');

    for (var i = 0; i < $listLinks.length; i++) {
      var $current = $listLinks.eq(i);

      if (!$current.siblings('.sublevel').length) continue;

      $current.siblings('.sublevel').find('ul').eq(0).prepend('<li class="back-nav-btn"><a href="#">back</a></li>');
      $current.append('<span class="icon-next-menu"></span>');
    }

    var $btnNextMenu = $wrapper.find('.icon-next-menu');
    var $btnBack = $wrapper.find('.back-nav-btn a');

    $btnNextMenu.on('click touch', function (e) {
      e.preventDefault();

      var $this = $(this).parents('li');

      //if ($this.hasClass('active-next-level')) {
      //  $this.removeClass('active-next-level');
      //  $body.removeClass('second-nav-level-active');
      //  return;
      //}

      $wrapper.height($(this).closest('li').children('.sublevel').outerHeight());

      if ($(this).parents('div').hasClass('sublevel')) {
        $body.removeClass('second-nav-level-active');
        $body.toggleClass('third-nav-level-active');

      } else {
        $body.toggleClass('second-nav-level-active');
      }

      //$list.removeClass('active-next-level');
      $this.addClass('active-next-level');

    });

    $btnBack.on('click touch', function (e) {
      e.preventDefault();

      var $this = $(this).parents('.active-next-level');

      //setTimeout(function () {
      //  $this.removeClass('active-next-level');
      //}, 300);

      if ($(this).parents('.sublevel').length > 1) {
        $body.addClass('second-nav-level-active');
        $body.removeClass('third-nav-level-active');


        $wrapper.height($(this).closest('.active-next-level').closest('.sublevel').outerHeight());

        $(this).closest('.active-next-level').removeClass('active-next-level');
      } else {
        $body.removeClass('second-nav-level-active');
        $wrapper.height($wrapper.find('.menu-block-wrapper').outerHeight());

        setTimeout(function () {
          $this.removeClass('active-next-level');
        }, 300);
      }

      //$body.removeClass('third-nav-level-active');
      //$body.removeClass('second-nav-level-active');
    });

    $btn.on('click touch', function (e) {
      e.preventDefault();

      $body.toggleClass('mobile-nav-active');
    });

    //$body.on('click touch', function (e) {
    //  if (!$(e.target).closest($wrapper).length) {
    //    if($body.hasClass('mobile-nav-active')) $body.removeClass('mobile-nav-active');
    //  }
    //});
  }

})(jQuery);