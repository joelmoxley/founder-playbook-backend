window.jQuery = window.$ = require('jquery');

require('jquery-touchswipe');

require('slick-carousel/slick/slick.min.js');
$(document).ready(function () {
  var container  = $('#container'),
      content = $('#content'),
      playbookNav = $('.playbook-nav'),
      loadFileExts = function () {
        $('.play-content li .filename')
          // .attr('target', 'blank')
          .each(function () {
            var el = $(this).parent(),
                href = $(this).attr('href');

            if (href.indexOf('youtube.com') !== -1) {
              return el.addClass('youtube');
            }

            if (href.substr(0, 4) === 'http') {
              return el.addClass('external');
            }

            href.replace(/\.[a-z]{2,6}$/, function (m) {
              el.addClass(m.substr(1, 3));
            });
          });
      };

  if (['/', '/index', '/tree'].indexOf(window.location.pathname) === -1) {
    $('.footer-about, .join-community, #footer hr').hide();
  }

  loadFileExts();

  $('#tree-view').find(content).on('click', 'a:not(.file)', function (e) {
    var parent = $(e.currentTarget).parent();

    parent.toggleClass('expanded');

    return e.preventDefault();
  });

  $(content).on('click', '.show-more > a', function (e) {
    var parent = $(this).parent().parent();

    e.preventDefault();

    if (parent.hasClass('target-active')) {
      parent.removeClass('target-active');
      $(this).text(this.oldText);
    } else {
      parent.addClass('target-active');
      this.oldText = $(this).text();
      $(this).text('Hide');
    }
  });

  $('.playbook-nav li > a').on('click', function (e) {
    var href = $(this).attr('href');

    if (/^https?:/.test(href) || href.indexOf('.') !== -1) {
      return;
    }

    $('#content').addClass('loading');

    e.preventDefault();
    history.pushState({}, $(this).text(), href);

    var parent = $(this).parent();

    parent.parent().find('.selected').removeClass('selected');

    var nextContent = $(this).parent().find('.content').html();

// var spiralHeight = $('.playbook').height() - 100;
//             var adjustedHeight = Math.ceil(spiralHeight / 23) * 23;

//             $('.spiral').height(adjustedHeight);
    if (nextContent && nextContent.length > 0 ) {
      parent.addClass('selected');
      content.html(nextContent);
      getAdjacentPlays();
      loadFileExts();
    } else {
      parent.addClass('selected');
      content.load(href + '?ajax', function () {
        getAdjacentPlays();
        loadFileExts();
      });
    }
  });

  if (playbookNav.length > 0) {
    getAdjacentPlays();

    $(document.body).swipe({
      swipe: onSwipe
    });
  }


  // $(".network-experts ~ ul").slick({
  //   infinite: false,
  //   rows: 2,
  //   slidesPerRow: 4,
  //   vertical: true,
  //   swipe: false,
  //   prevArrow: '<i class="fa fa-chevron-up slick-prev"></a>',
  //   nextArrow: '<i class="fa fa-chevron-down slick-next"></a>',
  //   responsive: [{
  //       breakpoint: 1024,
  //       settings: {
  //         slidesPerRow: 3
  //       }
  //     }, {
  //       breakpoint: 540,
  //       settings: {
  //         slidesPerRow: 3
  //       }
  //     }, {
  //       breakpoint: 420,
  //       settings: {
  //         slidesPerRow: 2
  //       }
  //     }]
  // });

  $(".contributors ul").slick({
    infinite: true,
    slidesToShow: 3,
    rows: 2,
    prevArrow: '<i class="fa fa-chevron-left slick-prev"></a>',
    nextArrow: '<i class="fa fa-chevron-right slick-next"></a>',
    responsive: [{
        breakpoint: 320,
        settings: {
          slidesToShow: 2
        }
      }]
  });

  function onSwipe(e, direction) {
    console.log(direction);
    if (direction === 'left') {
      playbookNav.find('.selected').next().find('a').click();
    } else if (direction === 'right') {
      playbookNav.find('.selected').prev().find('a').click();
    }
  }

  function getAdjacentPlays() {
    var current = $('.playbook-nav .selected'),
        first = $('.playbook-nav li').first(),
        last = $('.playbook-nav li').first();

    current.find('.content').html(content.html());

    // if (first.find('a').attr('href') == window.location.pathname) {
    //   first = null;
    // }

    preload(current.prev() || last);
    preload(current.next() || first);
  }
});

window.onbeforeunload = function(e) {
  $('#content').addClass('loading');
}

$(document).ajaxComplete(function () {
  $('#content').removeClass('loading');
});

function preload(item) {
  if (!item) {
    return;
  }

  var content = item.find('.content'),
      html = content.html(),
      href;

  if (html === undefined || html.length > 0) {
    return;
  }

  href = item.find('a').attr('href') + '?ajax';

  console.log('loading', href);

  content.load(href);
}