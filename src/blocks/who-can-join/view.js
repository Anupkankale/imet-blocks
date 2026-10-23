import { each, accordion } from '../../shared/view';

// The default style opens the first item; "Boxed" (e.g. a curriculum) starts with every item closed.
each( '.wp-block-imet-who-can-join .join-acc', ( group ) =>
	accordion( group, { openFirst: ! group.closest( '.is-style-boxed' ) } )
);
