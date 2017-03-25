String.prototype.ucfirst = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
};

$(function() {
	$('.primary-record').click(function (e) {
		e.preventDefault();

		$(this).parent().find('.secondary-record').toggleClass('hidden-xs-up');
	});

	$('.order-action').click(function (e) {
		e.preventDefault();

		var action = $(this).data('action'),
			order = $(this).closest('.order'),
			alert = order.find('.alert'),
			actionBtn = order.find('.apply-action'),
			actionClass = (
				action == 'cancel'
					? 'danger'
					: 'success'
			);

		$('.alert')
			.addClass('hidden-xs-up')
			.removeClass('alert-success')
			.removeClass('alert-danger');

		alert
			.removeClass('hidden-xs-up')
			.addClass('alert-' + actionClass);
		alert.find('.action-name').text(
			action.ucfirst()
		);
		alert.find('.action-name').text(action.ucfirst());
		alert.find('.action-name-btn').text('Proceed ' + action.ucfirst());

		$('.apply-action')
			.removeClass('btn-danger')
			.removeClass('btn-success');

		actionBtn.addClass('btn-' + actionClass);
	});

	$('.close').click(function (e) {
		e.preventDefault();

		$(this).closest('.alert').addClass('hidden-xs-up');
	});
});
