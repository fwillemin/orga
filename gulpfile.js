var gulp = require('gulp'),
        compass = require('gulp-compass'),
        browserSync = require('browser-sync').create(),
        useref = require('gulp-useref');

gulp.task('compass', function () {

    return gulp.src('assets/styles/scss/*.scss')
            .pipe(compass({
                config_file: './assets/styles/config.rb',
                css: './assets/styles/css',
                sass: './assets/styles/scss'
            }))
            .pipe(gulp.dest('assets/styles/css'));
});

gulp.task('default', ['compass'], function () {
    
});

gulp.task('watch', function () {

    browserSync.init({
        proxy: "localhost/organibat2/"
    });

    gulp.watch(['assets/styles/scss/*.scss'], ['compass']);
    gulp.watch(['application/**/*.php', 'assets/js/*.js', 'assets/css/*.css']).on('change', browserSync.reload);

});