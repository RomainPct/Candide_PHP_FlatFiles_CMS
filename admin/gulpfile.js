// Requis
var gulpfile = require('gulp');

// Include plugins
var plugins = require('gulp-load-plugins')(); // tous les plugins de package.json

// Variables de chemins
var source = 'src/'; // dossier de travail
var destination = 'assets/'; // dossier à livrer
const jsSource = 'src/js/',
    jsDestination = 'assets/scripts/'

gulpfile.task('jsMinifier', function(done) {
    gulpfile.src(jsSource+"*.js") // path to your files
        .pipe(plugins.concat("main.js"))
        .pipe(plugins.minify({
            ext:{
                src:'-debug.js',
                min:'.min.js'
            }
        }))
        .pipe(gulpfile.dest(jsDestination));
    done()
});

gulpfile.task('jsLint', function(done) {
    gulpfile.src(jsSource+"/**/*.js") // path to your files
        .pipe(plugins.jshint())
        .pipe(plugins.jshint.reporter()); // Dump results
    done()
})

gulpfile.task('sass', function() {
    return gulpfile.src(source + 'scss/main.scss')
        .pipe(plugins.sass().on('error', plugins.sass.logError))
        .pipe(plugins.autoprefixer())
        .pipe(gulpfile.dest(destination + 'styles/'))
});

gulpfile.task('cssMinfier', function() {
    return gulpfile.src(destination + 'styles/main.css')
        .pipe(plugins.csso())
        .pipe(plugins.rename({
            'suffix': '.min'
        }))
        .pipe(gulpfile.dest(destination + 'styles/'))
});

gulpfile.task('js', gulpfile.series('jsMinifier'))
gulpfile.task('css', gulpfile.series('sass', 'cssMinfier'))
gulpfile.task('watch', function() {
    gulpfile.watch('./src/js/*.js', gulpfile.series('js'))
    gulpfile.watch('./src/scss/**/*.scss', gulpfile.series('css'))
});

gulpfile.task('default', gulpfile.series('js','css','watch'));