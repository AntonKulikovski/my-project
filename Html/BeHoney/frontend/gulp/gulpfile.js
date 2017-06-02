/*!
 * gulp
 * $ npm install gulp-ruby-sass gulp-autoprefixer gulp-minify-css gulp-jshint gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del --save-dev
 */

// Load plugins
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    spritesmith = require('gulp.spritesmith'),
    fileinclude = require('gulp-file-include'),
    del = require('del');

// Styles
gulp.task('styles', function() {
  return sass('src/styles/main.scss', { style: 'expanded' })
    .pipe(autoprefixer('last 5 version'))
    .pipe(gulp.dest('../web/css/'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(gulp.dest('../web/css/'))
    .pipe(notify({ message: 'Styles task complete' }));
});

// Scripts
gulp.task('scripts', function() {
  return gulp.src('src/scripts/*.js')
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('default'))
    .pipe(concat('front-end.js'))
    .pipe(gulp.dest('../web/js/'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify())
      .pipe(gulp.dest('../web/js/'))
    .pipe(notify({ message: 'Scripts task complete' }));
});

gulp.task('plugins', function() {
    return gulp.src('src/scripts/plugin/*.js')
        .pipe(gulp.dest('../web/js/plugin'))
        .pipe(notify({ message: 'Plugins task complete' }));
});

// Images
gulp.task('images', function() {
  return gulp.src('src/images/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true })))
    .pipe(gulp.dest('../web/images/'))
    .pipe(notify({ message: 'Images task complete' }));
});

// Html Pages
gulp.task('pages', function() {
    return gulp.src('src/*.html')
        .pipe(fileinclude({
            prefix: '@@',
            basepath: '@file'
        }))
        .pipe(gulp.dest('dist/'))
        .pipe(notify({ message: 'Pages task complete' }));
});

// Sprite Image
gulp.task('sprite', function() {
    var spriteData =
        gulp.src('src/sprite/*.*')
            .pipe(spritesmith({
                imgName: 'sprite.png',
                cssName: '_sprite.scss'
            }));

    spriteData.img.pipe(gulp.dest('../web/css/'));
    spriteData.css.pipe(gulp.dest('src/styles/libs'))
        .pipe(notify({ message: 'Sprite task complete' }));
});

// Fonts
gulp.task('fonts', function() {
    return gulp.src('src/fonts/**/*')
        .pipe(gulp.dest('../web/fonts/'))
        .pipe(notify({ message: 'Fonts task complete' }));
});

// Clean
gulp.task('clean', function() {
  return del(['../web/']);
});

// Default task
gulp.task('default', ['clean'], function() {
  gulp.start('styles', 'scripts', 'plugins', 'images', 'pages', 'fonts', 'sprite');
});

// Watch
gulp.task('watch', function() {

  // Watch .scss files
  gulp.watch('src/styles/**/*.scss', ['styles']);

  // Watch pages files
  gulp.watch('src/**/*.html', ['pages']);

  // Watch .js files
  gulp.watch('src/scripts/*.js', ['scripts']);
  gulp.watch('src/scripts/plugins/*.js', ['plugins']);

  // Watch image files
  gulp.watch('src/images/**/*', ['images']);
  gulp.watch('src/sprite/*', ['sprite']);

  // Watch fonts files
  gulp.watch('src/fonts/**/*', ['fonts']);

});
