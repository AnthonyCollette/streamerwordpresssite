
(function($) {
	$(document).ready(function() {
		var codeEditor = CodeMirror.fromTextArea(document.getElementById('tccj-content'), {
			lineNumbers: true,
			viewportMargin: Infinity,
			mode: 'javascript'
		});
	});
})(jQuery);
