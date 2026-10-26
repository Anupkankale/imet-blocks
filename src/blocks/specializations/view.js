/* Program cards scroll like a marquee (shared helper: see src/shared/view.js). */
import { each, marquee } from '../../shared/view';

each( '.wp-block-imet-specializations:not(.is-style-home) .spec-grid', ( grid ) => marquee( grid, [ ...grid.querySelectorAll( '.spec-card' ) ], { speed: 40 } ) );
