var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync').create();
var browserify = require('browserify');
var gutil = require('gulp-util');
var tap = require('gulp-tap');
var buffer = require('gulp-buffer');
var uglify = require('gulp-uglify');


// Config variables

var localUrl = 'localhost';

var stylesInput = './src/sass/**/*.scss';
var stylesOutput = './assets/css';
var scriptsInput = './src/js/*.js';
var scriptsOutput = './assets/js';

var simpleInput = './src/sass/theme.scss';
var simpleOutput = './assets/css';

var sassOptions = {
  errLogToConsole: true,
  includePaths: ['node_modules'],
  outputStyle: 'compressed'
};

var autoprefixerOptions = {
  browsers: ['last 2 versions', '> 5%']
};

/*
 * Tasks
 */

// Sass
gulp.task('sass', function () {
  return gulp
    .src(stylesInput)
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer(autoprefixerOptions))
    .pipe(sourcemaps.write('/maps'))
    .pipe(gulp.dest(stylesOutput))
    .pipe(browserSync.stream());
});

// Simple
gulp.task('simple', function () {
  return gulp
    .src(simpleInput)
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer(autoprefixerOptions))
    .pipe(sourcemaps.write('/maps'))
    .pipe(gulp.dest(simpleOutput))
    .pipe(browserSync.stream());
});

// JS
gulp.task('js', function () {
  return gulp.src(scriptsInput, {read: false})
    .pipe(tap(function (file) {
      gutil.log('bundling ' + file.path);
      file.contents = browserify(file.path, {debug: true}).bundle();
    }))
    .pipe(buffer())
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(uglify())
    .pipe(sourcemaps.write('/maps'))
    .pipe(gulp.dest(scriptsOutput))
    .pipe(browserSync.stream());
});

// Static Server + watching
gulp.task('serve', ['sass', 'js'], function() {
    if(gutil.env.url) {
      localUrl = gutil.env.url;
    }
    browserSync.init({
        proxy: localUrl,
        notify: false
    });

    gulp.watch(stylesInput, ['sass']);
    gulp.watch(scriptsInput, ['js']);
    gulp.watch('./**/*.php').on('change', browserSync.reload);
});

gulp.task('default', ['js', 'sass']);
