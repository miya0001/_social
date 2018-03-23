( function() {
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
