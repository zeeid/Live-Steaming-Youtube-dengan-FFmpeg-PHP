const form = document.getElementById('livestream-form');
const statusDiv = document.getElementById('status');

form.addEventListener('submit', function(event) {
	event.preventDefault();

	// disable form
	form.classList.add('disabled');
	form.querySelectorAll('input, select, button').forEach(function(el) {
		el.disabled = true;
	});

	// show status
	statusDiv.textContent = 'Memulai Live Streaming...';

	// get form data
	const formData = new FormData(form);

	// send form data to server
	fetch('livestream.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(function(response) {
		if (response.status === 'success') {
			statusDiv.textContent = 'Live Streaming Berhasil!';
		} else {
			statusDiv.textContent = 'Live Streaming Gagal: ' + response.message;
		}
	})
	.catch(function(error) {
		statusDiv.textContent = 'Terjadi Kesalahan: ' + error.message;
	})
	.finally(function() {
		// enable form
		form.classList.remove('disabled');
		form.querySelectorAll('input, select, button').forEach(function(el) {
			el.disabled = false;
		});
	});
});
