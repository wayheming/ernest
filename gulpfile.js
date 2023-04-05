const gulp = require( 'gulp' );
const sass = require( 'gulp-sass' );
const rename = require( 'gulp-rename' );
const uglify = require( 'gulp-uglify' );
const wpPot = require( 'gulp-wp-pot' );
const babel = require('gulp-babel');

gulp.task( 'pot', function() {
	return gulp.src( './src/**/*.php' )
	.pipe( wpPot( {
		domain: 'ernest',
		package: 'Ernest',
	} ) )
	.pipe( gulp.dest( './assets/languages/ernest.pot' ) );
} );

gulp.task( 'sass-admin', function() {
	return gulp.src( './assets/scss/admin/admin.scss' )
	.pipe( sass({outputStyle: 'compressed'}) )
	.pipe( rename( 'styles.min.css' ) )
	.pipe( gulp.dest( './assets/css/admin' ) );
} );

gulp.task( 'js-admin', function() {
	return gulp.src( ['./assets/js/admin/*.js', '!./assets/js/admin/*.min.js' ] )
	.pipe( uglify() )
	.pipe(rename(function (path) {
		path.extname = '.min.js';
	}))
	.pipe( gulp.dest( './assets/js/admin/' ) );
} );

gulp.task( 'js-gutenberg', function() {
	return gulp.src( ['./assets/js/gutenberg/*.js', '!./assets/js/gutenberg/*.min.js' ] )
	.pipe(babel({
		plugins: ['transform-react-jsx']
	}))
	.pipe(rename(function (path) {
		path.extname = '.min.js';
	}))
	.pipe( gulp.dest( './assets/js/gutenberg/' ) );
} );

gulp.task( 'watch', function() {
	gulp.watch( './assets/scss/admin/*.scss', gulp.series( 'sass-admin' ) );
	gulp.watch( ['./assets/js/admin/*.js', '!./assets/js/admin/*.min.js' ], gulp.series( 'js-admin' ) );
	gulp.watch( ['./assets/js/gutenberg/*.js', '!./assets/js/gutenberg/*.min.js' ], gulp.series( 'js-gutenberg' ) );
} );

gulp.task( 'default', gulp.series( 'sass-admin', 'js-admin', 'js-gutenberg', 'watch' ) );

gulp.task( 'build', gulp.series( 'sass-admin', 'js-admin', 'pot' ) );