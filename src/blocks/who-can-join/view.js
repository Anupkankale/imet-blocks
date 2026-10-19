import { each, accordion } from '../../shared/view';

each( '.wp-block-imet-who-can-join .join-acc', ( group ) => accordion( group, { openFirst: true } ) );
