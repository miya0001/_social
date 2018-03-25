( function() {
	var load_css = function() {

		var me = '/_social/js/script.min.js';
		var css = '/_social/css/style.min.css';

		var scripts = document.querySelectorAll( 'script' );
		scripts.forEach( function( item ) {
			var src = item.getAttribute( 'src' );
			if ( src ) {
				if ( 0 < src.indexOf( me ) ) {
					css = src.replace( me, css );
				}
			}
		} );

		var link = document.createElement( 'link' );
		link.setAttribute( 'rel', 'stylesheet' );
		link.setAttribute( 'type', 'text/css' );
		link.setAttribute( 'media', 'all' );
		link.setAttribute( 'href', css );
		document.head.appendChild( link );
	}

	if ( document.querySelector( '.underscore-social' ) ) {
		load_css();
	}

	var twitter = document.querySelector( '.underscore-social .twitter' );
	var facebook = document.querySelector( '.underscore-social .facebook' );

	twitter.addEventListener( 'click', function( e ) {
		e.preventDefault();
		window.open( this.href, 'twitter',
			'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1' );
	} );

	facebook.addEventListener( 'click', function( e ) {
		e.preventDefault();
		window.open( this.href, 'facebook',
			'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes' );
	} );
} )();
