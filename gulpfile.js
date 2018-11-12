var basePaths = { // Paths for source and bundled parts of app
		src: 'src/', dest: 'assets/', npm: 'node_modules/', bower: 'bower_components/'
	},
	gulp = require( 'gulp' ), // Require plugins
	es = require( 'event-stream' ),
	gutil = require( 'gulp-util' ),
	bourbon = require( 'node-bourbon' ),
	path = require( 'relative-path' ),
	runSequence = require( 'run-sequence' ),
	del = require( 'del' ),
	plugins = require( 'gulp-load-plugins' )({ // Plugins - load gulp-* plugins without direct calls
		pattern: [ 'gulp-*', 'gulp.*' ], replaceString: /\bgulp[\-.]/
	}),
	// Env - call gulp --prod to go into production mode
	sassStyle = 'expanded', // SASS syntax
	sourceMap = true, // Wheter to build source maps
	isProduction = false, // Mode flag
	changeEvent = function( evt ) { // Log
		gutil.log( 'File', gutil.colors.cyan( evt.path.replace( new RegExp( '/.*(?=/' + basePaths.src +
																			')/' ), '' ) ), 'was', 		gutil.colors.magenta( evt.type ) );
	};

if ( true === gutil.env.prod ) {
	isProduction = true;
	sassStyle = 'compressed';
	sourceMap = false;
}

//js
gulp.task( 'build-js', function() {
	var vendorFiles = [
		basePaths.npm + 'imagesloaded/imagesloaded.pkgd.js'
	], appFiles = [ basePaths.src + 'js/*', basePaths.src + 'js/front/*' ]; //our own JS files

	return gulp.src( vendorFiles.concat( appFiles ) ) //join them
		.pipe( plugins.filter( '*.js' ) )//select only .js ones
		.pipe( plugins.concat( 'bundle.js' ) )//combine them into bundle.js
		.pipe( isProduction ? plugins.uglify() : gutil.noop() ) //minification
		.pipe( plugins.size() ) //print size for log
		.on( 'error', console.log ) //log
		.pipe( gulp.dest( basePaths.dest + 'js' ) ); //write results into file
});

//js
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

// Sass
gulp.task( 'build-css', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	//mdl = path('./node_modules/material-design-lite/src');
	//paths.push(mdl);

	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );

	var vendorFiles = gulp.src( [] ), // Components
		appFiles = gulp.src( basePaths.src + 'sass/front-main.scss' ) // Main file with @import-s
			.pipe( ! isProduction ? plugins.sourcemaps.init() : gutil.noop() )  // Process the
																				// original sources
																				// for sourcemap
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) ).pipe( ! isProduction ? plugins.sourcemaps.write() : gutil.noop() ) // Add the map
																					 // to modified
																					 // source
			.on( 'error', console.log ); // Log

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'bundle.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

gulp.task( 'build-editor-css', function() {

	var paths = require( 'node-bourbon' ).includePaths, vendorFiles = gulp.src( [] ),
		appFiles = gulp.src( basePaths.src + 'sass/editor-main.scss' ).
			pipe( ! isProduction ? plugins.sourcemaps.init() : gutil.noop() )  // Process the
																			   // original sources
																			   // for sourcemap
			.pipe( plugins.sass( {
				outputStyle: sassStyle, //SASS syntas
				includePaths: paths //add bourbon + mdl
			} ).on( 'error', plugins.sass.logError ) )//sass own error log
			.pipe( plugins.autoprefixer( { //autoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) ).pipe( ! isProduction ? plugins.sourcemaps.write() : gutil.noop() ) // Add the map
																					 // to modified
																					 // source
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
		appFiles = gulp.src( basePaths.src + 'sass/admin-main.scss' ).
			pipe( ! isProduction ? plugins.sourcemaps.init() : gutil.noop() )  // Process the
																			   // original sources
																			   // for sourcemap
			.pipe( plugins.sass( {
				outputStyle: sassStyle, //SASS syntas
				includePaths: paths //add bourbon + mdl
			} ).on( 'error', plugins.sass.logError ) )//sass own error log
			.pipe( plugins.autoprefixer( { //autoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) ).pipe( ! isProduction ? plugins.sourcemaps.write() : gutil.noop() ) // Add the map
																					 // to modified
																					 // source
			.on( 'error', console.log ); // Log

	return appFiles.pipe( plugins.concat( 'admin.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Revision
gulp.task( 'revision-clean', function() {
	// Clean folder https://github.com/gulpjs/gulp/blob/master/docs/recipes/delete-files-folder.md
	return del( [ basePaths.dest + 'rev/**/*' ] );
});

gulp.task( 'revision', function() {

	return gulp.src( [ basePaths.dest + 'css/*.css', basePaths.dest + 'js/*.js' ] ).
		pipe( plugins.rev() ).pipe( gulp.dest( basePaths.dest + 'rev' ) ).
		pipe( plugins.rev.manifest() ).pipe( gulp.dest( basePaths.dest + 'rev' ) ) // Write
																				   // manifest to
																				   // build dir
		.on( 'error', console.log ); // Log
});

// Builds
gulp.task( 'full-build', function( callback ) {
	runSequence( 'build-css', 'build-editor-css', 'build-admin-css', 'build-js', 'svg-opt', 'revision-clean', 'revision', callback );
});

gulp.task( 'full-build-css', function( callback ) {
	runSequence( 'build-css', 'build-editor-css', 'build-admin-css', 'revision-clean', 'revision', callback );
});

gulp.task( 'full-build-js', function( callback ) {
	runSequence( 'build-js', 'build-admin-js', 'revision-clean', 'revision', callback );
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

// Watchers
gulp.task( 'watch', function() {
	gulp.watch( [
		basePaths.src + 'sass/*.scss', basePaths.src + 'sass/**/*.scss'
	], [ 'full-build-css' ] ).on( 'change', function( evt ) {
		changeEvent( evt );
	});
	gulp.watch( [
		basePaths.src + 'js/*.js', basePaths.src + 'js/front/*.js', basePaths.src + 'js/admin/*.js'
	], [ 'full-build-js' ] ).on( 'change', function( evt ) {
		changeEvent( evt );
	});
});

// Default
gulp.task( 'default', [ 'full-build', 'watch' ] );