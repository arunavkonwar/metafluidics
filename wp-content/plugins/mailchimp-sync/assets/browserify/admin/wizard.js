'use strict';

var logger = require('./logger.js');
var User = require('./user.js');
var started = false,
	running = false,
	done = false,
	userCount = 0,
	usersProcessed = 0,
	progress = m.prop(0),
	batch = m.prop([]),
	settingsForm,
	unsavedChanges = m.prop(false);

function controller() {
	settingsForm = document.getElementById('settings-form');

	settingsForm.addEventListener('change', function() {
		unsavedChanges(true);
		m.redraw();
	});
}

function askToStart() {
	var sure = confirm( "Are you sure you want to start synchronising all of your users? This can take a while if you have many users, please don't close your browser window." );
	if( sure ) {
		start();
	}
}

function start() {
	started = true;
	running = true;

	fetchTotalUserCount()
		.then(prepareBatch)
		.then(subscribeFromBatch);

}

function resume() {
	running = true;
	subscribeFromBatch();
	m.redraw();
}

function pause() {
	running = false;
	m.redraw();
}

function finish() {
	done = true;
	logger.log("Done");
}

function fetchTotalUserCount() {
	var deferred = m.deferred();

	var data = { action : 'mcs_wizard', mcs_action: 'get_user_count' };
	m.request({ method: "GET", url: ajaxurl, data: data }).then(function(data) {
		logger.log("Found " + data + " users.");
		userCount = data;
		deferred.resolve();
	});

	return deferred.promise;
}

function prepareBatch() {

	var deferred = m.deferred();

	m.request( {
		method: "GET",
		url: ajaxurl,
		data: {
			action: 'mcs_wizard',
			mcs_action: 'get_users',
			offset: usersProcessed,
			limit: 100
		},
		type: User
	}).then( function( users ) {
		logger.log("Fetched " + users.length + " users.");

		// finish if we didn't get any users
		if( users.length == 0 ) {
			finish();
		}

		// otherwise, fill batch and move on.
		batch( users );
		deferred.resolve();
		m.redraw();
	}, function( error ) {
		logger.log( "Error fetching users. Error: " + error );
		deferred.reject();
	});

	return deferred.promise;
}

function subscribeFromBatch() {

	if( ! running || done ) {
		return;
	}

	// do we have users left in this batch>
	if( batch().length === 0 ) {
		return prepareBatch().then(subscribeFromBatch);
	}

	// Get next user
	var user = batch().shift();

	// Add line to log
	logger.log("Updating <strong> #" + user.id() + " " + user.username() + " &lt;" + user.email() + "&gt;</strong>" );

	// Perform subscribe request
	var data = {
		action: "mcs_wizard",
		mcs_action: "subscribe_users",
		user_id: user.id()
	};

	m.request({
		method: "GET",
		data: data,
		url: ajaxurl
	}).then(function( response ) {
		usersProcessed++;
		response.success ? logger.success( response.message ) : logger.error( response.message );
	}, logger.error).then(updateProgress).then(subscribeFromBatch);
}

// calculate new progress & update progress bar.
function updateProgress() {
	var newProgress = Math.round( usersProcessed / userCount * 100 );
	progress( newProgress );
	if( newProgress >= 100 ) {
		finish();
	}
}

/**
 * View
 *
 * @returns {*}
 */
function view() {

	// Wizard isn't running, show button to start it
	if( ! started ) {
		return m('p', [
			m('input', { type: 'button', class: 'button', value: 'Synchronise All', onclick: askToStart, disabled: unsavedChanges() } ),
			unsavedChanges() ? m('span.help', ' — Please save your changes first.') : ''
		]);
	} else

	// Show progress
	return [
		done ? '' : m("p",
				m("input", {
					type: 'button',
					class: 'button',
					value: ( running ? "Pause" : "Resume" ),
					onclick: ( running  ? pause : resume )
				})
		),
		m('div.progress-bar', [
			m( "div.value", {
				style: "width: "+ progress() +"%"
			}),
			m( "div.text", ( progress() == 100  ? "Done!" : "Working: " + progress() + "%" ))
		]),

		logger.render()
	];
}




module.exports = {
	'controller': controller,
	'view': view
};