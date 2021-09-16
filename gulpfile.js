var basePaths = { // Paths for source and bundled parts of app
		src: 'src/', dest: 'assets/', npm: 'node_modules/'
	},
	gulp = require( 'gulp' ), // Require plugins
	es = require( 'event-stream' ),
	zip = require('gulp-zip');
	gutil = require( 'gulp-util' ),
	bourbon = require( 'node-bourbon' ),
	path = require( 'relative-path' ),
	runSequence = require( 'run-sequence' ),
	plugins = require( 'gulp-load-plugins' )({ // Plugins - load gulp-* plugins without direct calls
		pattern: [ 'gulp-*', 'gulp.*' ], replaceString: /\bgulp[\-.]/
	}),
	concat = require('gulp-concat'),
	jsImport = require('gulp-js-import'),
	// Env - call gulp --prod to go into production mode
	sassStyle = 'expanded', // SASS syntax
	sourceMap = false, // Wheter to build source maps
	isProduction = false, // Mode flag
	changeEvent = function( evt ) { // Log
		gutil.log( 'File', gutil.colors.cyan( evt.path.replace( new RegExp( '/.*(?=/' + basePaths.src + ')/' ), '' ) ), 'was', gutil.colors.magenta( evt.type ) );
	};

if ( true === gutil.env.prod ) {
	isProduction = true;
	sassStyle = 'compressed';
	sourceMap = false;
}

const run     = require('gulp-run');
const wpPot   = require('gulp-wp-pot');
const po2json = require('gulp-po2json');

//js
gulp.task( 'build-js', function() {
	var vendorFiles = [
		//basePaths.npm + 'imagesloaded/imagesloaded.pkgd.js'
	], appFiles = [ basePaths.src + 'js/front/*' ]; //our own JS files

	return gulp.src( vendorFiles.concat( appFiles ) ) //join them
		.pipe( plugins.filter( '*.js' ) )//select only .js ones
		.pipe( plugins.concat( 'scripts.js' ) )//combine them into bundle.js
		.pipe( isProduction ? plugins.uglify() : gutil.noop() ) //minification
		.pipe( plugins.size() ) //print size for log
		.on( 'error', console.log ) //log
		.pipe( gulp.dest( basePaths.dest + 'js' ) ); //write results into file
});

//admin.js
gulp.task( 'build-admin-js', function() {
	var vendorFiles = [
		// basePaths.npm + 'imagesloaded/imagesloaded.pkgd.js'
	], appFiles = [ basePaths.src + 'js/admin/*' ]; //our own JS files

	return gulp.src( vendorFiles.concat( appFiles ) ) //join them
		.pipe( plugins.filter( '*.js' ) )//select only .js ones
		.pipe( plugins.concat( 'admin.js' ) )//combine them into bundle.js
		.pipe( isProduction ? plugins.uglify() : gutil.noop() ) //minification
		.pipe( plugins.size() ) //print size for log
		.on( 'error', console.log ) //log
		.pipe( gulp.dest( basePaths.dest + 'js' ) ); //write results into file
});

//js
gulp.task( 'build-blocks-js', function() {
	var vendorFiles = [
		//basePaths.npm + 'imagesloaded/imagesloaded.pkgd.js'
	], appFiles = [ basePaths.src + 'js/blocks/*' ]; //our own JS files

	return gulp.src( vendorFiles.concat( appFiles ) ) //join them
		.pipe( plugins.filter( '*.js' ) )//select only .js ones
		.pipe( plugins.concat( 'blocks.js' ) )//combine them into bundle.js
		.pipe( isProduction ? plugins.uglify() : gutil.noop() ) //minification
		.pipe( plugins.size() ) //print size for log
		.on( 'error', console.log ) //log
		.pipe( gulp.dest( basePaths.dest + 'js' ) ); //write results into file
});

/*
gulp.task('build-blocks-js', function() {
	return gulp.src('src/js/blocks/blocks.js')
		.pipe(concat('blocks.js'))
		.pipe(jsImport({
			hideConsole: true,
			importStack: true
		}))
		.pipe(gulp.dest('assets/js/'));
});
*/

// Sass
gulp.task( 'build-css', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var vendorFiles = gulp.src('.', {allowEmpty: true}),//gulp.src( [] ), // Components
		appFiles = gulp.src( basePaths.src + 'sass/front-main.scss' ) // Main file with @import-s
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); // Log

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'style.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Sass
gulp.task( 'build-gutenberg-css', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var vendorFiles = gulp.src('.', {allowEmpty: true}),//gulp.src( [] ), // Components
		appFiles = gulp.src( basePaths.src + 'sass/gutenberg.scss' ) // Main file with @import-s
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); // Log

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'gutenberg.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Blocks
gulp.task( 'build-blocks-css', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var vendorFiles = gulp.src('.', {allowEmpty: true}),//gulp.src( [] ), // Components
		appFiles = gulp.src( basePaths.src + 'sass/wp-blocks.scss' ) // Main file with @import-s
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); // Log

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'blocks.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

gulp.task( 'build-editor-css', function() {

	var paths = require( 'node-bourbon' ).includePaths, vendorFiles = gulp.src('.', {allowEmpty: true});
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var appFiles = gulp.src( basePaths.src + 'sass/editor-main.scss' )
			.pipe( plugins.sass( {
				outputStyle: sassStyle, //SASS syntas
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths //add bourbon + mdl
			} ).on( 'error', plugins.sass.logError ) )//sass own error log
			.pipe( plugins.autoprefixer( { //autoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); //log

	return es.concat( appFiles, vendorFiles ) //combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'editor.css' ) ) //combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) //minification on production
		.pipe( plugins.size() ) //display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) //write file
		.on( 'error', console.log ); //log
});

gulp.task( 'build-admin-css', function() {

	var paths = require( 'node-bourbon' ).includePaths,
		appFiles = gulp.src( basePaths.src + 'sass/admin-main.scss' )
			.pipe( plugins.sass( {
				outputStyle: sassStyle, //SASS syntas
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths //add bourbon + mdl
			} ).on( 'error', plugins.sass.logError ) )//sass own error log
			.pipe( plugins.autoprefixer( { //autoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); // Log

	return appFiles.pipe( plugins.concat( 'admin.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Svg - combine and clear svg assets
gulp.task( 'svg-opt', function() {

	var icons = gulp.src( [ basePaths.src + 'svg/icon-*.svg' ] ).pipe( plugins.svgmin( {
			plugins: [
				{
					removeTitle: true,
					removeDesc: { removeAny: true },
					removeEditorsNSData: true,
					removeComments: true
				}
			]
		} ) ) // Minification
		.pipe( plugins.cheerio( {
			run: function( $ ) { //remove fill from icons
				$( '[fill]' ).removeAttr( 'fill' );
				$( '[fill-rule]' ).removeAttr( 'fill-rule' );
			}, parserOptions: { xmlMode: true }
		} ) ), pics = gulp.src( [ basePaths.src + 'svg/pic-*.svg' ] ).pipe( plugins.svgmin( {
		plugins: [
			{
				removeTitle: true,
				removeDesc: { removeAny: true },
				removeEditorsNSData: true,
				removeComments: true
			}
		]
	} ) ); // Minification

	// Combine for inline usage
	return es.concat( icons, pics ).pipe( plugins.svgstore( { inlineSvg: true } ) )
		.pipe( gulp.dest( basePaths.dest + 'svg' ) );
});

gulp.task( 'translate', function () {
	return gulp.src( ['**/*.php'] )
		.pipe( wpPot( {
			domain: 'knd',
			package: 'knd'
		} ))
		//.pipe(gulp.dest('file.pot'));
		.pipe( gulp.dest( './lang/knd.pot' ) );
});

gulp.task('po2json', function () {
	return gulp.src(['lang/ru_RU.po'])
		.pipe(po2json( {
			pretty: true,
			format: "jed",
			fuzzy: true
		} ))
		.pipe( gulp.dest('lang/') );
});

// Builds
gulp.task( 'full-build-css',
	gulp.series( 'build-css', 'build-blocks-css', 'build-gutenberg-css', 'build-editor-css', 'build-admin-css' )
);

gulp.task( 'full-build-js',
	gulp.series( 'build-js', 'build-admin-js', 'build-blocks-js' )
);

gulp.task( 'full-build',
	gulp.series( 'full-build-css', 'full-build-js', 'svg-opt' )
);

// Watchers
gulp.task( 'watch', () => {
	gulp.watch(
		[ basePaths.src + 'js/**/*.js' ],
		gulp.series( [ 'full-build-js' ] )
	);
	gulp.watch(
		[ basePaths.src + 'sass/*.scss', basePaths.src + 'sass/**/*.scss' ],
		gulp.series([ 'full-build-css' ] )
	);
});

// Default
gulp.task( 'default', gulp.series( 'full-build', 'watch' ) );

// Archive
gulp.task('zip', function(){

	const distFiles = [
		'**',
		'!src/**',
		'!node_modules/**',
		'!.gitignore',
		'!gulpfile.js',
		'!LICENSE.txt',
		'!README.md',
		'!CHANGELOG.md',
		'!package.json',
		'!package-lock.json',
		'!theme_test.json'
	];

	return gulp.src( distFiles, { base: '../' } )
		.pipe( zip( 'kandinsky.zip' ) )
		.pipe( gulp.dest( './' ) )
});

// gulp.task('zip', function(){
// 	return run('npm run archive').exec();
// });
