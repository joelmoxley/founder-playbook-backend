window.jQuery = window.$ = require('jquery');

$(document).ready(function () {
	$('nav select').on('change', function () {
		window.location = $(this).find('option:selected').val();
	});
});