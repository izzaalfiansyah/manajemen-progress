import axios from 'axios';
import useSWRV from 'swrv';

export const http = axios.create({
	baseURL: (window as any).apiURL || 'http://localhost:8000',
	headers: {
		'Content-Type': 'application/json',
		Accept: 'application/json',
		Authorization: localStorage.getItem('app_key') as string,
	},
});

export const createResource = useSWRV;

export const notif = (
	message: string,
	type: 'danger' | 'success' | 'primary' | 'warning' = 'primary',
) => {
	const div = document.createElement('div');
	div.innerHTML = `
	<div class="toast show position-fixed toast-top-right">
    <div class="toast-header">
      <strong class="mr-auto">Informasi!</strong>
      <button
        type="button"
        onclick="this.parentNode.parentNode.parentNode.remove()"
        class="btn btn-sm"
        style="position: absolute; right: 5px"
      >
        X
      </button>
    </div>
    <div class="toast-body bg-${type} text-white">
      ${message}
    </div>
  </div>
  `;
	document.body.appendChild(div);

	setTimeout(() => {
		div?.remove();
	}, 3000);
};

export const auth = {
	id: localStorage.getItem('id'),
	role: localStorage.getItem('role'),
};

export const formatDate = (timestamp: string, t = false) => {
	const dates = new Date(timestamp);

	let date = dates.getDate().toString();
	date += ' ';
	date += [
		'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember',
	][dates.getMonth()];
	date += ' ';
	date += dates.getFullYear();

	if (true) {
		date += ' ';
		date += dates.getHours();
		date += ':';
		date += dates.getMinutes() < 10 ? '0' + dates.getMinutes() : dates.getMinutes();
	}

	return date;
};
