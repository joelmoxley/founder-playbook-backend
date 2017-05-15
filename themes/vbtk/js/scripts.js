window.jQuery = window.$ = require('jquery');


require('jquery.event.swipe')(window.jQuery);

$(document).ready(function () {
  var container  = $('#container'),
      loadFileExts = function () {
        $('.play-content li .filename').attr('target', 'blank').each(function () {
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

  if (window.location.pathname !== '/' && window.location.pathname !== '/index') {
    $('.footer-about, .join-community, #footer hr').hide();
  }

	$('nav select').on('change', function () {
		window.location = $(this).find('option:selected').val();
	});

  $('nav select').on('click', function (e) {
    e.preventDefault();
  });

  loadFileExts();

  $(container).on('click', 'a', function (e) {
    var href = $(this).attr('href');

    if (href.charAt(0) === '#') {
      e.preventDefault();
      $(href).addClass('target-active');
      return;
    }

    if (/^https?:/.test(href) || href.indexOf('.') !== -1) {
      return;
    }

    e.preventDefault();
    history.pushState({}, $(this).text(), href);

    container.load(href + '?ajax', function () {
      loadFileExts();
    });

  });

  $(document.body).on('swipe', function () {
    alert("swipe");
  });
});