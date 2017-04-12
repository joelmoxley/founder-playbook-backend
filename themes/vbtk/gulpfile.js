'use strict';


var proxy_url = 'jmox.dev'; // REPLACE THIS WITH YOUR LOCAL DEV URL

// PUT ALL FONT FILES USED HERE TO BE COPIED INTO BUILD DIRECTORY
var fonts = [
  './node_modules/font-awesome/fonts/*',
];

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    browserify = require('gulp-browserify'),
    browserSync = require('browser-sync'),
    cssnano = require('gulp-cssnano'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    jshint = require('gulp-jshint'),
    stylish = require('jshint-stylish'),
    uglify = require('gulp-uglify'),
    babel = require('gulp-babel'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat');

 
// COMPILE THEME SASS FILE
gulp.task('sass', function () {
  return gulp.src('./scss/style.scss')
    .pipe(sourcemaps.init()) // Start Sourcemaps
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(gulp.dest('./build/css/'))
    .pipe(cssnano())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./build/css/'));
});


// COPY ALL FONTS INTO FONT BUILD DIRECTORY
gulp.task('fonts', function () {
  return gulp.src(fonts)
    .pipe(gulp.dest('./build/fonts/'));
});


// MINIFY THEME JS FILE
gulp.task('script', function() {
  return gulp.src('./js/scripts.js')
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(sourcemaps.init())
    .pipe(browserify({
      // insertGlobals : true,
      debug : false
    }))
    .pipe(gulp.dest('./build/js'))
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./build/js'));
});

// BROWSERSYNC AND WATCH
gulp.task('watch', function () {
  var files = [
    './build/css/*.css', 
    './build/js/scripts.js',
    '**/*.php',
    '../../**/*.twig',
    '../../**/*.md',
  ];

  browserSync.init(files, { proxy: proxy_url });

  gulp.watch('./scss/**/*.scss', ['sass']);
  gulp.watch('./js/scripts.js', ['script']);
});

gulp.task('dev', ['fonts','script','sass','watch']);
gulp.task('production', ['fonts','script','sass']);