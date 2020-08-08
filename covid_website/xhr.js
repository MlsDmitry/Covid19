	const getBtn = document.getElementById('get-btn');
	const getBtn = document.getElementById('post-btn');

	cosnt getData = () => {
		const xhr = new XMLHttpRequest();
	xhr.open('get','192.168.88.145:19958');
	};

	const sendData = () => {};

	getBtn.addEventListener('click',getData);
	postBtn.addEventListener('click',sendData);