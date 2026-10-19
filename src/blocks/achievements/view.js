/* Counters: animate the number typed in the editor (e.g. "2,000+", "90%") when it scrolls into view. */
import { each, reduceMotion } from '../../shared/view';

function animate( el ) {
	const m = el.textContent.trim().match( /^([^\d]*)([\d,.]+)(.*)$/ );
	if ( ! m || reduceMotion() ) {
		return;
	}
	const [ , pre, num, post ] = m;
	const target = parseFloat( num.replace( /,/g, '' ) );
	const grouped = num.includes( ',' );
	const start = performance.now();
	const step = ( now ) => {
		const p = Math.min( ( now - start ) / 1400, 1 );
		const v = Math.floor( p * target );
		el.textContent = pre + ( grouped ? v.toLocaleString( 'en-US' ) : v ) + post;
		if ( p < 1 ) {
			requestAnimationFrame( step );
		} else {
			el.textContent = pre + num + post;
		}
	};
	requestAnimationFrame( step );
}

const io = new IntersectionObserver(
	( entries ) =>
		entries.forEach( ( e ) => {
			if ( e.isIntersecting ) {
				animate( e.target );
				io.unobserve( e.target );
			}
		} ),
	{ threshold: 0.4 }
);

each( '.wp-block-imet-achievements .count', ( c ) => io.observe( c ) );
