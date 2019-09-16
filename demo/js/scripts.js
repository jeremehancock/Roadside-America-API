$(document).ready(function() {
	$(".scale1").fitText();
	$(".scale2").fitText(3, { maxFontSize: '100px' });
	$(".scale3").fitText(3, { maxFontSize: '25px' });
});