'use strict'

const gulp          = require('gulp');
const css          = require('gulp-clean-css');
const sourcemaps    = require('gulp-sourcemaps');
const connect       = require('gulp-connect');
const htmlmin       = require('gulp-htmlmin');
const uglify        = require('gulp-uglify-es').default;

const paths = {
  html: '*.html',
  css: 'css/**/*.css',
  script: 'js/**/*.js',
  sw: 'service-worker.js',
  images: 'images/**',
  vendor: 'libs/**/*.js',
  vendor_css: 'libs/**/*.css',
  manifest: 'site.webmanifest'
};

const imagemin = require('gulp-imagemin');
 
const imagesGulp = function() {
  return gulp.src(paths.images)
    .pipe(imagemin([
      imagemin.gifsicle({interlaced: true}),
      imagemin.mozjpeg({quality:75,progressive: true}),
      imagemin.optipng({optimizationLevel: 5}),
      imagemin.svgo({
          plugins: [
              {removeViewBox: true},
              {cleanupIDs: false}
          ]
      })
    ]))
    .pipe(gulp.dest('public/images'));
};

const gulpCss = function() {
  return gulp.src(paths.css)
    .pipe(sourcemaps.init())
    .pipe(css())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('public/css'));
};

const jsGulp = function() {
  return gulp.src(paths.script, {
      ignore: [paths.sw, paths.vendor]
    })
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('public/js'));
};

const copyManifest = function() {
  return gulp.src(paths.manifest)
      .pipe(gulp.dest('./public/'))
};

const swGulp = function() {
  return gulp.src(paths.sw)
    .pipe(uglify())
    .pipe(gulp.dest('./public'));
};

const vendorGulp = function() {
  return gulp.src(paths.vendor)
    .pipe(uglify())
    .pipe(gulp.dest('./public/libs'))
};

const htmlGulp = function() {
  return gulp.src(paths.html)
    .pipe(htmlmin({
      collapseWhitespace: true,
      removeComments: true
    }))
    .pipe(gulp.dest('./public/'));
};

const watchJS = function() {
  return gulp.watch(paths.script, gulp.series(jsGulp, reload));
};

const watchCSS = function() {
  return gulp.watch(paths.css, gulp.series(gulpCss, reload));
};

const watchHTML = function() {
  return gulp.watch(paths.html, gulp.series(htmlGulp, reload));
};

const server = function() {
  return connect.server({livereload: true});
};

const reload = function() {
  return gulp.src(paths.html).pipe(connect.reload());
};

const watch = gulp.parallel(
  watchCSS, watchJS, watchHTML
);


exports.build = gulp.parallel(jsGulp, gulpCss,imagesGulp, htmlGulp, swGulp, vendorGulp, copyManifest);

exports.watch = gulp.parallel(server, watch);
