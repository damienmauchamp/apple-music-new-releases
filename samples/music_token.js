// listen for MusicKit Loaded callback
// document.addEventListener('musickitloaded', () => {
// MusicKit global is now defined
fetch('./developer_token.php').then(response => response.json()).then(res => {
	/***
		  Configure our MusicKit instance with the signed token from server, returns a configured MusicKit Instance
		  https://developer.apple.com/documentation/musickitjs/musickit/musickitinstance
	 ***/
	const music = MusicKit.configure({
		developerToken: res.token,
		app: {
			name: 'AppleMusicUpdate',
			build: '1978.4.1'
		}
	});

	// // setup click handlers
	// document.getElementById('add-to-q-btn').addEventListener('click', () => {
	// 	const idInput = document.getElementById('id-input');
	// 	const typeInput = document.getElementById('type-input');
	//
	// 	/***
	// 		  Add an item to the playback queue
	// 		  https://developer.apple.com/documentation/musickitjs/musickit/musickitinstance/2992716-setqueue
	// 	 ***/
	// 	music.setQueue({
	// 		[typeInput.value]: idInput.value
	// 	});
	//
	// 	idInput.value = '';
	// 	typeInput.value = '';
	// });
	//
	// document.getElementById('play-btn').addEventListener('click', () => {
	// 	/***
	// 		  Resume or start playback of media item
	// 		  https://developer.apple.com/documentation/musickitjs/musickit/musickitinstance/2992709-play
	// 	 ***/
	// 	music.play();
	// });
	//
	// document.getElementById('pause-btn').addEventListener('click', () => {
	// 	/***
	// 		  Pause playback of media item
	// 		  https://developer.apple.com/documentation/musickitjs/musickit/musickitinstance/2992708-pause
	// 	 ***/
	// 	music.pause();
	// });

	document.getElementById('login-btn').addEventListener('click', () => {
		/***
			  Returns a promise which resolves with a music-user-token when a user successfully authenticates and authorizes
			  https://developer.apple.com/documentation/musickitjs/musickit/musickitinstance/2992701-authorize
		 ***/
		music.authorize().then(musicUserToken => {
			saveToken(musicUserToken, new Date().toLocaleString('us-US')).then(res => res.json()).then(json => {
				alert(json.message);
			}).catch(err => {
				console.error(err);
			})
		});
	});

	// expose our instance globally for testing
	window.music = music;
});
// });

let saveToken = (musicUserToken, expiracy) => {
	return fetch('', {
		method: "post",
		body: JSON.stringify({token: musicUserToken}),
	});
}