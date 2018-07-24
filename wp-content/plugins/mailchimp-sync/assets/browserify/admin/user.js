'use strict';

/**
 * User Model
 *
 * @param data
 * @constructor
 */
var User = function( data ) {
	this.id = m.prop( data.ID );
	this.username = m.prop( data.user_login );
	this.email = m.prop( data.user_email );
};

module.exports = User;