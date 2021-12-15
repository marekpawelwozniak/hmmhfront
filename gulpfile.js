// Fetch required plugins
const gulp = require('gulp');
const { src, dest, watch, series, parallel } = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const replace = require('gulp-replace');
const terser = require('gulp-terser');
const sass = require('gulp-sass')(require('sass'));

const postcss = require('gulp-postcss');
const cssnano = require('cssnano');

// All paths
const paths = {
    styles: {
        src: ['./sass/**/*.scss'],
        dest: './css/index.css',
    }
};


function compileStyles() {
    return src(paths.styles.src)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([cssnano]))
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(paths.styles.dest));
}

// Watch for file modification at specific paths and run respective tasks accordingly
function watcher() {
    watch(paths.styles.src, parallel(compileStyles));
}

exports.compileStyles = compileStyles;

exports.watcher = watcher;
exports.default = series(
    parallel(compileStyles),
    watcher
);