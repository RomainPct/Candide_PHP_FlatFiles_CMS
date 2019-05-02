// Requis
var gulpfile = require('gulp');

// Include plugins
var plugins = require('gulp-load-plugins')(); // tous les plugins de package.json

// Variables de chemins
var source = 'src/'; // dossier de travail
var destination = 'assets/'; // dossier à livrer

gulpfile.task('css',function () {
    return gulpfile.src(source+'scss/main.scss')
        .pipe(plugins.sass().on('error', plugins.sass.logError))
        .pipe(plugins.autoprefixer())
        .pipe(gulpfile.dest(destination+'styles/'))
});

gulpfile.task('minify',function () {
    return gulpfile.src(destination+'styles/main.css')
        .pipe(plugins.csso())
        .pipe(plugins.rename({
            'suffix':'.min'
        }))
        .pipe(gulpfile.dest(destination+'styles/'))
});

gulpfile.task('build', gulpfile.series('css','minify'));

gulpfile.task('watch',function () {
   gulpfile.watch('./src/scss/**/*.scss',gulpfile.series('build'))
});

gulpfile.task('default', gulpfile.series('watch'));