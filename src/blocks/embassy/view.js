import { each, marquee } from '../../shared/view';

/* Home page, alumni employer logos: each row glides sideways (only while it overflows). */
each( '.wp-block-imet-embassy.is-employers .emblem-row', ( row ) => marquee( row, [ ...row.querySelectorAll( '.emblem' ) ], { speed: 30 } ) );
