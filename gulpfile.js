var gulp = require('gulp'),
        livereload = require('gulp-livereload'),
        compass = require('gulp-compass'),
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

    livereload.listen();

    gulp.watch(['assets/styles/scss/*.scss'], ['compass']);
    gulp.watch(['application/**/*.php', 'assets/js/*.js', 'assets/css/*.css', '!application/logs/*.php'], function(){ gulp.src(['application/**/*.php', 'assets/js/*.js', 'assets/css/*.css', '!application/logs/*.php']).pipe(livereload())});

});