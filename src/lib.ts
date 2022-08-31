export const notif = (message: string) => {
	const div = document.createElement('div');
	div.innerHTML = `
	<div class="toast show position-fixed toast-top-right">
    <div class="toast-header">
      <strong class="mr-auto">Information!</strong>
      <button
        type="button"
        onclick="this.parentNode.parentNode.parentNode.remove()"
        class="btn btn-sm"
        style="position: absolute; right: 5px"
      >
        X
      </button>
    </div>
    <div class="toast-body bg-info text-white">
      ${message}
    </div>
  </div>
  `;
	document.body.appendChild(div);

	setTimeout(() => {
		div?.remove();
	}, 3000);
};
