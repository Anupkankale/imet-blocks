/* Enquiry form: the flag and the phone placeholder follow the selected country code
   (for forms built with #phoneCode, #phoneInput and .h-flag, e.g. the Contact Form 7 form). */
import { each, marquee } from '../../shared/view';

const FLAGS = { '+971': 'ae', '+91': 'in', '+1': 'us', '+44': 'gb', '+65': 'sg' };

each( '.wp-block-imet-hero', ( hero ) => {
	const code = hero.querySelector( '#phoneCode' );
	const phone = hero.querySelector( '#phoneInput' );
	const flag = hero.querySelector( '.h-flag' );
	if ( ! code ) {
		return;
	}
	code.addEventListener( 'change', () => {
		// The option value may be the label, e.g. "United Arab Emirates (+971)" (Contact Form 7).
		const dial = ( code.value.match( /\+\d+/ ) || [ '' ] )[ 0 ];
		if ( phone && dial ) {
			phone.placeholder = '(' + dial + ') Phone Number';
		}
		if ( flag && FLAGS[ dial ] ) {
			// Same folder as the current flag image (flag-ae.png, flag-in.png…).
			flag.src = flag.src.replace( /flag-[a-z]{2}(\.\w+)(\?.*)?$/, 'flag-' + FLAGS[ dial ] + '$1' );
		}
	} );
} );

/* Accreditation logos: a marquee on phones (CSS makes the row scroll there; on wider screens it fits). */
each( '.wp-block-imet-hero .ac-logos', ( row ) => marquee( row, [ ...row.querySelectorAll( '.ac-logo' ) ], { speed: 30 } ) );
