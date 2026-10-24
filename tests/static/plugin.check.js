/* Static checks: no WordPress, browser or build needed. Run with `npm run test:static`. */
const { test } = require( 'node:test' );
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { execFileSync } = require( 'node:child_process' );

const root = path.resolve( __dirname, '../..' );
const read = ( p ) => fs.readFileSync( path.join( root, p ), 'utf8' );
const blocks = fs.readdirSync( path.join( root, 'src/blocks' ) ).filter( ( d ) => fs.statSync( path.join( root, 'src/blocks', d ) ).isDirectory() );
const patterns = fs.readdirSync( path.join( root, 'patterns' ) ).filter( ( f ) => f.endsWith( '.php' ) );

test( 'every block has the files and metadata AGENTS.md requires', () => {
	assert.ok( blocks.length > 0 );
	for ( const name of blocks ) {
		const dir = `src/blocks/${ name }`;
		for ( const file of [ 'block.json', 'index.js', 'default.html', 'style.scss' ] ) {
			assert.ok( fs.existsSync( path.join( root, dir, file ) ), `${ dir }/${ file } is missing` );
		}
		const meta = JSON.parse( read( `${ dir }/block.json` ) );
		assert.equal( meta.name, `imet/${ name }`, `${ dir }: block name must match the folder` );
		assert.equal( meta.category, 'imet', `${ dir }: category` );
		assert.ok( meta.imet && meta.imet.tagName && meta.imet.className, `${ dir }: "imet" key needs tagName and className` );
	}
} );

test( 'build/ contains every block (build is committed)', () => {
	const manifest = read( 'build/blocks-manifest.php' );
	for ( const name of blocks ) {
		assert.ok( manifest.includes( `'imet/${ name }'` ), `build manifest is missing imet/${ name }: run npm run build` );
		assert.ok( fs.existsSync( path.join( root, `build/blocks/${ name }/block.json` ) ), `build/blocks/${ name } is missing: run npm run build` );
		const src = JSON.parse( read( `src/blocks/${ name }/block.json` ) );
		const built = JSON.parse( read( `build/blocks/${ name }/block.json` ) );
		assert.deepEqual( built.imet, src.imet, `build/blocks/${ name } is out of date: run npm run build` );
	}
} );

test( 'plugin version is the same in all four places', () => {
	const header = read( 'imet-blocks.php' );
	const versions = {
		header: header.match( /^\s*\*\s*Version:\s*(\S+)/m )?.[ 1 ],
		constant: header.match( /IMET_BLOCKS_VERSION',\s*'([^']+)'/ )?.[ 1 ],
		package: JSON.parse( read( 'package.json' ) ).version,
		readme: read( 'readme.txt' ).match( /^Stable tag:\s*(\S+)/m )?.[ 1 ],
	};
	assert.equal( new Set( Object.values( versions ) ).size, 1, `version mismatch: ${ JSON.stringify( versions ) }` );
} );

test( 'image tokens point at files in assets/images and the media import list', () => {
	const media = read( 'includes/media.php' );
	const sources = [ ...patterns.map( ( f ) => `patterns/${ f }` ), ...blocks.map( ( b ) => `src/blocks/${ b }/default.html` ) ];
	for ( const file of sources ) {
		for ( const [ , , img ] of read( file ).matchAll( /\{\{(ID|URL):([^}]+)\}\}/g ) ) {
			assert.ok( fs.existsSync( path.join( root, 'assets/images', img ) ), `${ file }: assets/images/${ img } does not exist` );
			assert.ok( media.includes( `'${ img }'` ), `${ file }: ${ img } is not in imet_blocks_media_files()` );
		}
	}
} );

test( 'in-page links in each pattern match an id in that pattern', () => {
	for ( const file of patterns ) {
		const html = read( `patterns/${ file }` );
		const ids = new Set( [ ...html.matchAll( /\bid="([^"]+)"/g ) ].map( ( m ) => m[ 1 ] ) );
		for ( const [ , target ] of html.matchAll( /href="#([^"]+)"/g ) ) {
			assert.ok( ids.has( target ), `patterns/${ file }: link #${ target } has no matching id` );
		}
	}
} );

test( 'every PHP file passes php -l', ( t ) => {
	let php;
	try {
		php = execFileSync( 'php', [ '-v' ] ) && 'php';
	} catch {
		return t.skip( 'php is not installed' );
	}
	const files = [ 'imet-blocks.php' ];
	for ( const dir of [ 'includes', 'templates', 'patterns', 'build' ] ) {
		const walk = ( d ) => fs.readdirSync( path.join( root, d ), { withFileTypes: true } ).forEach( ( e ) => {
			const rel = `${ d }/${ e.name }`;
			if ( e.isDirectory() ) {
				walk( rel );
			} else if ( e.name.endsWith( '.php' ) ) {
				files.push( rel );
			}
		} );
		walk( dir );
	}
	for ( const f of files ) {
		execFileSync( php, [ '-l', path.join( root, f ) ], { stdio: 'pipe' } );
	}
} );

// Known problems (see the UX audit). They are reported as "todo" and will start failing the run once fixed,
// so remove `todo` when you fix them.
test( 'no placeholder href="#" links in default content', { todo: 'dead See More / View More buttons' }, () => {
	const sources = [ ...patterns.map( ( f ) => `patterns/${ f }` ), ...blocks.map( ( b ) => `src/blocks/${ b }/default.html` ) ];
	const bad = sources.filter( ( f ) => /href="#"/.test( read( f ) ) );
	assert.deepEqual( bad, [] );
} );
