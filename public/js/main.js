String.prototype.ucfirst = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
};

$(function() {
	// Open order details
	$('.primary-record').click(function (e) {
		e.preventDefault();

		$(this).parent().find('.secondary-record').toggleClass('hidden-xs-up');
	});

	// Open action modal
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

		actionBtn
			.addClass('btn-' + actionClass)
			.attr('data-status', action);
	});

	// Close an action modal
	$('.close').click(function (e) {
		e.preventDefault();

		$(this).closest('.alert').addClass('hidden-xs-up');
	});

	// Aplly order action
	$('.apply-action').click(function (e) {
		e.preventDefault();

		var order = $(this).closest('.order'),
			badge = order.find('.badge'),
			orderId = order.data('order-id'),
			status = $(this).attr('data-status');

		$.ajax({
			method: 'POST',
			url: '/status.php',
			data: {
				'order_id': orderId,
				'status': status
			}
		}).done(function (data) {
			if (data.status == 'success') {
				order.find('.alert').remove();
				order.find('.actions').remove();

				badge
					.removeClass('badge-warning')
					.addClass('badge-' + (status == 'complete' ? 'success' : 'danger'))
					.text(status == 'complete' ? 'completed' : 'cancelled');

				order.find('.secondary-record').toggleClass('hidden-xs-up')
			} else {
				alert(data.message);
			}
		});
	});
});
