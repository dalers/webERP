/* Expand and collapse descendants in the indented BOM report. */

(function () {
	function updateRowVisibility(Rows) {
		var CollapsedLevels = [];

		Rows.forEach(function (Row) {
			var Level = parseInt(Row.dataset.level, 10);

			while (CollapsedLevels.length > 0 && CollapsedLevels[CollapsedLevels.length - 1] >= Level) {
				CollapsedLevels.pop();
			}

			var IsHidden = CollapsedLevels.length > 0;
			Row.hidden = IsHidden;

			if (Row.dataset.collapsed === 'true') {
				CollapsedLevels.push(Level);
			}
		});
	}

	function toggleRow(Event) {
		var Button = Event.target.closest('.bom-toggle');
		if (!Button) {
			return;
		}

		var Row = Button.closest('tr');
		var Rows = Array.from(document.querySelectorAll('#IndentedBOM tbody tr[data-level]'));
		var IsCollapsed = Row.dataset.collapsed === 'true';

		Row.dataset.collapsed = IsCollapsed ? 'false' : 'true';
		Button.setAttribute('aria-expanded', IsCollapsed ? 'true' : 'false');
		Button.setAttribute('title', IsCollapsed ? Button.dataset.collapseLabel : Button.dataset.expandLabel);
		Button.textContent = IsCollapsed ? '-' : '+';
		updateRowVisibility(Rows);
	}

	document.addEventListener('click', toggleRow);
})();
