$(function() {
	$('.primary-record').click(function (e) {
		e.preventDefault();

		$(this).parent().find('.secondary-record').toggleClass('hidden-xs-down');
	});
});
