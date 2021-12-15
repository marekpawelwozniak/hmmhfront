var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));

gulp.task( 'sass', function() {
    return gulp.src('sass/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('css'));
})

gulp.task('watch', function() {
    gulp.watch('./sass/*.scss', ['sass']);  // If a file changes, re-run 'sass'
    //gulp.watch('./sass/*.scss', gulp.series('styles'));  // If a file changes, re-run 'sass'
});