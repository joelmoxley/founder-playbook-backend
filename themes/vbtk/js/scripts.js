window.jQuery = window.$ = require('jquery');

$(document).ready(function () {
  var container  = $('#container');

	$('nav select').on('change', function () {
		window.location = $(this).find('option:selected').val();
	});

  $('nav select').on('click', function (e) {
    e.preventDefault();
  });

  $('.play-content li a').attr('target', 'blank').each(function () {
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

  $(container).on('click', 'a', function (e) {
    var href = $(this).attr('href');

    if (/^https?:/.test(href)) {
      return;
    }

    e.preventDefault();
    history.pushState({}, $(this).text(), href);

    container.load(href + '?ajax');
  });
});