var gulp = require('gulp'),
	concat = require('gulp-concat'),
	minifyCSS = require('gulp-minify-css'),
	uglify = require('gulp-uglify');

gulp.task('js', function(){
	return gulp.src(["src/*.js"])
		.pipe(concat('script.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('js'));
});

gulp.task('css', function(){
	return gulp.src(["src/*.css"])
		.pipe(concat('style.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest('css'))
});

gulp.task('default', ['js', 'css']);
