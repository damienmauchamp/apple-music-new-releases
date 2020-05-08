$(function () {

	// theme match on html tag
	//var prefersColorScheme = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	//var theme = $('body').className();
	//var bodyThemeClass = $('body')[0].className.replace(/\s*is\-music\-theme\s*/, '');
	$('html').css('background', $('body').css('background-color'));

	//$(.maClasse).parents('li').css(propriété, valeur);
	// :root, :root .light : --light
	// :root .dark : --dark
	// :root .night : --night
	// prefers-color-scheme: dark :
	//	- :root:not(.light):not(.night) : night
	//	- :root:not(.light):not(.night) .variant-dark : dark

	// matchMedia('(prefers-color-scheme: dark)').matches

	// $('html').css('background', 'var(--dark-bg-color)')


	/*
	$('body').bind('touchend', function (e) {
		e.preventDefault();
		// Add your code here.
		$(this).click();
		// This line still calls the standard click event, in case the user needs to interact with the element that is being clicked on, but still avoids zooming in cases of double clicking.
	});*/

	const addArtist = $(".add-artists");
	// const addArtistSubmit = $(".add-artists-submit");
	const addArtistSubmit = $(".add-artists-label-after");
	const artistDataId = "data-am-artist-id";
	const mailAlert = $("#mail-alert");
	var flag = false;

	function template(data) {
		if ($(data.html).length === 0)
			return data.text;
		return $(data.html);
	}

	mailAlert.on("change", function() {
		var state = $(this).prop("checked");
		$.ajax({
			url: "./ajax/update.php",
			method: "POST",
			data: {
				f: 5,
				notif: state
			},
			success(response) {
				if (!response)
					$(this).prop("checked", !state);
			}
		});
	});

	addArtist.select2({
		ajax: {
			url: "./ajax/search.php",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				// parse the results into the format expected by Select2.
				// since we are using custom formatting functions we do not need to
				// alter the remote JSON data
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 1,
		placeholder: 'Ajouter un artiste',
		allowClear: true,
		// data: data,
		templateResult: template,
		templateSelection: template
	});


	addArtistSubmit.on("click", function () {
		$.ajax({
			url: "./ajax/addArtists.php",
			method: "GET",
			dataType: 'json',
			data: {
				artists: addArtist.val()
			},
			complete: function () {
				addArtist.val('').trigger('change');
			}
		});
	});

	/**
	 * update
	 * @param id
	 * @param f
	 */
	function update(id, f) {
		//console.log("f", f, "id", id);
		var c = $("#artist-" + id).find(".section-body");
		$.ajax({
			url: "./ajax/update.php",
			// dataType: 'json',
			method: "POST",
			data: {
				f: f,
				id: id
			},
			success: function (data) {
				// console.log("success", data);
				var html = "";
				if (parseInt(f) === 1 && data["albumCount"] > 0) {
					$(data["albums"]).each(function (key, val) {
						html += val;
					})
				} else {
					html = "Vous êtes à jour.";
				}
				c.empty().append(html);
			}
		});
	}


	// $(document)
	//	 .on('touchstart click', '.maj-link', function (e) {
	// alert(e.type + " maj " + $(this).attr(artistDataId));

	/*
	$(".maj-link").on("click", function () {
		if (!flag) {
			flag = true;
			setTimeout(function () {
				flag = false;
			}, 100);

			var id = $(this).attr(artistDataId);
			update(id, 1);
		}
	}).bind('touchend', function (e) {
		e.preventDefault();
		$(this).click();
	});*/
	// .on('touchstart click', '.suppr-link', function (e) {
	// alert(e.type + " suppr " + $(this).attr(artistDataId));

	$(".suppr-link").on("click", function () {
		if (!flag) {
			flag = true;
			setTimeout(function () {
				flag = false;
			}, 100);
			var id = $(this).attr(artistDataId);

			/** @todo: opacity reduction, loader, hide after returning */
			$("#artist-" + id).addClass("--invisible");
			update(id, 2);
		}
	}).bind('touchend', function (e) {
		e.preventDefault();
		$(this).click();
	});
	// .on('touchstart click', '.rm-artist', function (e) {

	$(".rm-artist").on("click", function () {
		var id = $(this).attr("data-artist-id");
		$.ajax({
			url: "./ajax/removeArtist.php",
			method: "GET",
			dataType: 'json',
			data: {
				artist: id
			},
			success: function () {
				$("#artist-" + id).hide();
			}, error: function (e) {
				console.log(e);
			}
		});
	}).bind('touchend', function (e) {
		e.preventDefault();
		$(this).click();
	});


	$('#nav-icon').click(function () {
		$(this).toggleClass('open');
		var mobilemenu = $('#mobile-menu');
		mobilemenu.toggle();
		mobilemenu.toggleClass('menu-open');
	});

	// $(".album").on("touchstart click", function () {
	//	 alert(e.type + " album " + $(this).attr("id"));
	// });

	//$(".maj-link").on("touchstart click", function (e) {
	// $(".suppr-link").on("touchstart click", function (e) {
	// $(".rm-artist").on("click", function (e) {


	/* CONTEXT MENU */
	// Trigger action when the contexmenu is about to be shown
	$(document).bind("contextmenu", function (e) {

		var $album;
		if ($(this).hasClass('album')) {
			$album = $(this);
		} else if (e.target.closest('.album')) {
			$album = $(e.target.closest('.album'));
		} else if ($(this).hasClass('song')) {
			$album = $(this);
		} else if (e.target.closest('.song')) {
			$album = $(e.target.closest('.song'));
		} else {
			return true;
		}
		
		// Avoid the real one
		e.preventDefault();

		$(".custom-menu li[data-action='open-itunes'] a").attr('href', $album.data('itunes-link'));
		$(".custom-menu li[data-action='open-browser'] a").attr('href', $album.data('link'));

		// Show contextmenu
		$(".custom-menu").finish().toggle(100).
		
		// In the right position (the mouse)
		css({
			top: e.pageY + "px",
			left: e.pageX + "px"
		});
	});

	// If the document is clicked somewhere
	$(document).on("mousedown", function (e) {
		
		// If the clicked element is not the menu
		if (!$(e.target).parents(".custom-menu").length > 0) {
			$(".custom-menu").hide(100);
			$(".custom-menu li a").attr('href', '#');
		} else {
			switch (e.which) {
				case 1: // left
					return true;
					break;
				case 2: // middle
				case 3: // right
				default:
					$(".custom-menu").hide(100);
					$(".custom-menu li a").attr('href', '#');
					return true;
			}
		}
	});

	// If the menu element is clicked
	$(document).on('click', '.custom-menu li', function(e){		
		// Hide it AFTER the action was triggered
		$(".custom-menu").hide(100);
	});


	// notifications
	$(document).on('click', '#notification-button-submit', function(e) {
		$(this).closest('.notification').fadeOut('fast', function() {
			$(this).closest('.notification-mask').fadeOut('fast', function() {
				$(this).remove();
			});
		});
	});

	// theme selector
	$(document).change('.theme-input', function(e){
		change_theme($(e.target).val());
	});

	$(document).on('click', '#settings', function() {
		$('.notification-mask').remove();
		// console.log('test');
		open_theme_settings();
	})

	// Notifications
	// initNotifications();
});

var display_notification = function(options) {
	 $('body').append('<div class="notification-mask" style="display:none"><div class="notification"><div class="notification-top"><div class="notification-title">'+options.title+'</div><div class="notification-body">'+options.body+'</div></div><div class="notification-bottom"><!--div class="notification-button">Annuler</div--><div class="notification-button" id="notification-button-submit">OK</div></div></div></div>');
	 $('.notification-mask').fadeIn(50);
}

var open_theme_settings = function() {

	var theme = getCookie('theme') || 'variant';

	display_notification({
		title: `Changement de thème`,
		body: `
<h3>Forcé :</h3>
<input type="radio" class="theme-input" name="theme" id="light" value="light"/>
<label for="light">Clair</label>
<input type="radio" class="theme-input" name="theme" id="dark" value="dark"/>
<label for="dark">Sombre</label>
<input type="radio" class="theme-input" name="theme" id="night" value="night"/>
<label for="night">Nuit</label>

<h3>Variant :</h3>
<input type="radio" class="theme-input" name="theme" id="variant" value="variant"/>
<label for="variant">Clair / Nuit</label>
<input type="radio" class="theme-input" name="theme" id="variant-dark" value="variant-dark"/>
<label for="variant-dark">Clair / Sombre</label>
		`
	});

	$('.theme-input#'+theme).prop('checked', true);
}

var change_theme = function(theme) {
	var themes = ['light', 'dark', 'night', 'variant-dark'];
	themes.forEach(t => {
		$('body').removeClass(t);
	});
	if (themes.includes(theme)) {
		setCookie('theme', theme, 99999);
		//document.cookie = "theme=" + theme;
		$('body').addClass(theme);
	} else {
		setCookie('theme', 'variant', 99999);
		//document.cookie = "theme=variant; expires=Fri, 31 Dec 9999 23:59:59 GMT;";
	}
	/*
transition: background-color .5s ease .7s;
	*/
	// changing the html tag's background color to match the body's
	$('html').css('background', $('body').css('background-color'));

}

var getNewReleases = function (scrapped) {

	function getArtists() {
		return $.ajax({
			url: "./ajax/update.php",
			method: "POST",
			dataType: 'json',
			data: {f: 3}
		});
	}

	$.when(getArtists()).done(function (str) {
		var artists = JSON.parse(str);
		var count = artists.length;
		const spinner = $("#loading-spinner");
		spinner.show();
		$(artists).each(function (i, artist) {
			$.ajax({
				url: "./ajax/update.php",
				method: "POST",
				data: {
					f: 4,
					scrapped: scrapped || false,
					artist: artist
				}, success: function (data) {
					// $("#new-albums").append(data);
					// $(data).insertBefore(spinner);
					$(data).insertBefore(spinner, $("#new-albums"));
					spinner.show();
				}, complete: function () {
					if (!--count) {
						spinner.hide();
					}
				}
			});
		});
	});

};

//////////////////////////////
// COOKIES

function setCookie(cname, cvalue, exdays) {
	exdays = exdays || 99999;
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

//////////////////////////////
// ARRAY

//
Array.prototype.getMaxDate = function() {
	return new Date(Math.max.apply(null, this.map(e => {
		return new Date(e);
	})));
}

Array.prototype.uniqueKey = function(key) {
	return this.filter((value, index, self) => self.map(x => x[key]).indexOf(value[key]) == index);
}

///////////////////////////////
// NEW RELEASES CHECK

//
function getLatestAddedDate(section_element) {
	section_element = section_element || 'section#weekly-releases';
	var dates = $(`${section_element} a.album`).map((i, e) => $(e).data('added')).toArray();
	return dates.getMaxDate();
}

function getRecentlyAdded(cookie_latest_date, section_element) {
	section_element = section_element || 'section#weekly-releases';
	var $releases = $(`${section_element} a.album`).filter((i, e) => new Date($(e).data('added')) > new Date(cookie_latest_date));
	return $releases.map((i, e) => {

		var album = $($(e).get(0)).find('h3.album-title').text().trim();
		var type = 'album';

		if (/\- Single$/.test(album)) {
			type = 'single';
		} else if (/\- EP$/.test(album)) {
			type = 'EP';
		} else {
			// type = 'album';
		}

		return {
			link: $(e).data('link'),
			id: $(e).attr('id'),
			am_id: $(e).data('am-album-id'),
			type: type,
			album: album,
			artist: $($(e).get(0)).find('h4.album-subtitle').text().trim(),
			artwork: $($(e).get(0)).find('picture img').attr('src'),
		};
	}).toArray().uniqueKey('id');
}
//checkNewAddedReleases
function checkNewAddedReleases(section_element) {
	section_element = section_element || 'section#weekly-releases';
	var cookie = `${section_element}_latest_added`;
	var latest_date = getLatestAddedDate(section_element);
	var cookie_latest_date = getCookie(cookie);

	if (cookie_latest_date && new Date(cookie_latest_date) < latest_date) {
		// getting new releases
		var newReleases = getRecentlyAdded(cookie_latest_date, section_element);

		// send notifications
		newReleases.forEach((r) => {
				var notification_title = `New ${r.type} by ${r.artist}`;
				var notification_options = {
					lang: 'EN', //'FR',
					icon: "./favicon.png",
					tag:  `amu-${r.id}`,
					body: r.album,	
					image: r.artwork,
					data: {
						link: r.link
					},
					link: r.link
				};
				sendNotification(notification_title, notification_options);
				// console.log('sendNotification', notification_title, notification_options)
		});
	}
	setCookie(cookie, latest_date);
}

function reinitCheckNewAddedReleases(section_element) {
	section_element = section_element || 'section#weekly-releases';
	var cookie = `${section_element}_latest_added`;
	setCookie(cookie, '2020-05-06 01:37:06');
	// console.log('reinit');
}

////////////////////////////////
// NOTIFICATIONS

var enable_notifications = true;

function notificationRequestPermission() {
	if (window.Notification && Notification.permission !== "granted") {
		Notification.requestPermission(function (status) {
			if (Notification.permission !== status) {
				Notification.permission = status;
			}
		});
	}
}

function sendNotification(title, options) {
	// https://developer.mozilla.org/fr/docs/Web/API/notification/Notification

	// if granted
	if (window.Notification && Notification.permission === "granted") {
		// send
		var notification = new Notification(title, options);
		notification.onclick = function(event) {
			event.preventDefault(); // no focus
			window.open(this.data.link, '_blank');
		}
	}
	// if not granted yet
	else if (window.Notification && Notification.permission !== "denied") {
		Notification.requestPermission(function (status) {
			// If the user said okay
			if (status === "granted") {
				// send
				var notification = new Notification(title, options);
				notification.onclick = function(event) {
					event.preventDefault(); // no focus
					window.open(this.data.link, '_blank');
				}
			} else {
				// not granted
				console.warn('notifications not granted (2)');
			}
	  });
	}
	// not granted
	else {
		// not granted
		console.warn('notifications not granted (1)');
	}
}

$(function() {
	if (enable_notifications) {
		// reinitCheckNewAddedReleases();
		checkNewAddedReleases();
	}
});


////////////////////


// reinitCheckNewAddedReleases();
// checkNewAddedReleases();
// auto-refresh
// on AR : get lastAdded, compare to previous cookie
// if newer : notifications for every new releases (or group ?)
// set latest "last added" as the cookie


// Notifications
// function 

// function initNotifications() {
// 	if (window.Notification && Notification.permission !== "granted") {
// 		Notification.requestPermission(function (status) {
// 			if (Notification.permission !== status) {
// 				Notification.permission = status;
// 			}
// 		});
// 	}
// }