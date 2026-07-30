/**
 * Consent Pilot — front-end consent logic.
 *
 * Reads and writes the consent cookie, toggles the banner and preferences
 * panel, and unblocks scripts tagged with a data-cp-category attribute once
 * the matching category has been granted.
 */
( function () {
	'use strict';

	var cfg = window.consentPilot || {};
	var banner = document.getElementById( 'consent-pilot' );

	if ( ! banner ) {
		return;
	}

	var prefsPanel = banner.querySelector( '.cp-prefs' );
	var saveBtn = banner.querySelector( '[data-cp-action="save"]' );

	/**
	 * Read a cookie by name.
	 *
	 * @param {string} name Cookie name.
	 * @return {string|null} Value or null.
	 */
	function readCookie( name ) {
		var match = document.cookie.match( '(?:^|; )' + name.replace( /([.$?*|{}()[\]\\/+^])/g, '\\$1' ) + '=([^;]*)' );
		return match ? decodeURIComponent( match[ 1 ] ) : null;
	}

	/**
	 * Write a cookie.
	 *
	 * @param {string} name  Cookie name.
	 * @param {string} value Cookie value.
	 * @param {number} days  Expiry in days.
	 */
	function writeCookie( name, value, days ) {
		var expires = '';
		if ( days ) {
			var date = new Date();
			date.setTime( date.getTime() + days * 24 * 60 * 60 * 1000 );
			expires = '; expires=' + date.toUTCString();
		}
		var secure = 'https:' === window.location.protocol ? '; Secure' : '';
		document.cookie = name + '=' + encodeURIComponent( value ) + expires + '; path=/; SameSite=Lax' + secure;
	}

	/**
	 * Parse the stored consent into an object of category => bool.
	 *
	 * @return {Object|null}
	 */
	function getStoredConsent() {
		var raw = readCookie( cfg.cookieName );
		if ( ! raw ) {
			return null;
		}
		try {
			return JSON.parse( raw );
		} catch ( e ) {
			return null;
		}
	}

	/**
	 * Persist a consent object and act on it.
	 *
	 * @param {Object} consent Category => bool map.
	 */
	function saveConsent( consent ) {
		consent.necessary = true;
		writeCookie( cfg.cookieName, JSON.stringify( consent ), cfg.expiryDays );
		applyConsent( consent );
		hideBanner();
		logConsent( consent );
		document.dispatchEvent(
			new CustomEvent( 'consentPilot:updated', { detail: consent } )
		);
	}

	/**
	 * Unblock any scripts whose category has been granted.
	 *
	 * @param {Object} consent Category => bool map.
	 */
	function applyConsent( consent ) {
		if ( ! cfg.blockScripts ) {
			return;
		}

		var blocked = document.querySelectorAll( 'script[type="text/plain"][data-cp-category]' );
		blocked.forEach( function ( node ) {
			var category = node.getAttribute( 'data-cp-category' );
			if ( ! consent[ category ] ) {
				return;
			}

			var script = document.createElement( 'script' );
			// Copy attributes across, swapping the placeholder type for real JS.
			for ( var i = 0; i < node.attributes.length; i++ ) {
				var attr = node.attributes[ i ];
				if ( 'type' === attr.name || 'data-cp-category' === attr.name ) {
					continue;
				}
				script.setAttribute( attr.name, attr.value );
			}
			script.type = 'text/javascript';
			if ( ! node.src ) {
				script.textContent = node.textContent;
			}
			node.parentNode.replaceChild( script, node );
		} );
	}

	/**
	 * Send the anonymised consent record to the server, if enabled.
	 *
	 * @param {Object} consent Category => bool map.
	 */
	function logConsent( consent ) {
		if ( ! cfg.logConsent || ! cfg.ajaxUrl ) {
			return;
		}

		var granted = ( cfg.categories || [] ).filter( function ( c ) {
			return !! consent[ c ];
		} );

		var body = new URLSearchParams();
		body.append( 'action', 'consent_pilot_log' );
		body.append( 'nonce', cfg.nonce );
		body.append( 'categories', granted.join( ',' ) );

		if ( window.fetch ) {
			window.fetch( cfg.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString(),
			} );
		}
	}

	/** Show the banner. */
	function showBanner() {
		banner.hidden = false;
	}

	/** Hide the banner and collapse the preferences panel. */
	function hideBanner() {
		banner.hidden = true;
		if ( prefsPanel ) {
			prefsPanel.hidden = true;
		}
		if ( saveBtn ) {
			saveBtn.hidden = true;
		}
	}

	/** Toggle the preferences panel open. */
	function openPrefs() {
		if ( prefsPanel ) {
			prefsPanel.hidden = false;
		}
		if ( saveBtn ) {
			saveBtn.hidden = false;
		}
		showBanner();
	}

	/**
	 * Build a consent map with every optional category set to a value.
	 *
	 * @param {boolean} value Grant or deny.
	 * @return {Object}
	 */
	function allCategories( value ) {
		var consent = { necessary: true };
		( cfg.categories || [] ).forEach( function ( c ) {
			if ( 'necessary' !== c ) {
				consent[ c ] = value;
			}
		} );
		return consent;
	}

	/**
	 * Read the current state of the preference checkboxes.
	 *
	 * @return {Object}
	 */
	function readPrefs() {
		var consent = { necessary: true };
		var inputs = banner.querySelectorAll( '.cp-pref-input' );
		inputs.forEach( function ( input ) {
			consent[ input.getAttribute( 'data-category' ) ] = input.checked;
		} );
		return consent;
	}

	/**
	 * Reflect a stored consent map back onto the checkboxes.
	 *
	 * @param {Object} consent Category => bool map.
	 */
	function syncPrefs( consent ) {
		var inputs = banner.querySelectorAll( '.cp-pref-input' );
		inputs.forEach( function ( input ) {
			var cat = input.getAttribute( 'data-category' );
			if ( input.disabled ) {
				return;
			}
			input.checked = !! consent[ cat ];
		} );
	}

	// Wire up the action buttons and reopen links.
	document.addEventListener( 'click', function ( event ) {
		var trigger = event.target.closest( '[data-cp-action]' );
		if ( ! trigger ) {
			return;
		}

		var action = trigger.getAttribute( 'data-cp-action' );

		if ( 'reopen' === action ) {
			event.preventDefault();
			var current = getStoredConsent();
			if ( current ) {
				syncPrefs( current );
			}
			openPrefs();
			return;
		}

		if ( ! banner.contains( trigger ) ) {
			return;
		}

		switch ( action ) {
			case 'accept':
				saveConsent( allCategories( true ) );
				break;
			case 'reject':
				saveConsent( allCategories( false ) );
				break;
			case 'settings':
				openPrefs();
				break;
			case 'save':
				saveConsent( readPrefs() );
				break;
		}
	} );

	// On load: apply an existing decision, or show the banner.
	var stored = getStoredConsent();
	if ( stored ) {
		applyConsent( stored );
	} else {
		showBanner();
	}
} )();
