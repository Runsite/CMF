$('.ripple').on('click', function (e) {
	var $div = $('<div/>'),
		btnOffset = $(this).offset(),
		xPos = e.pageX - btnOffset.left,
		yPos = e.pageY - btnOffset.top;
	$div.addClass('ripple-effect');
	var $ripple = $('.ripple-effect');
	$ripple.css('height', $(this).height());
	$ripple.css('width', $(this).height());
	$div.css({
		top: yPos - $ripple.height() / 2,
		left: xPos - $ripple.width() / 2,
		background: $(this).data('ripple-color')
	}).appendTo($(this));
	window.setTimeout(function () {
		$div.remove();
	}, 1500);
});