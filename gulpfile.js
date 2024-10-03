const fs           = require('fs');
const browserSync  = require('browser-sync').create();
const gulp         = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS     = require('gulp-clean-css');
const rename       = require('gulp-rename');
const sass         = require('gulp-dart-sass');
const sassLint     = require('gulp-sass-lint');
const readme       = require('gulp-readme-to-markdown');
const merge        = require('merge');


let config = {
  src: {
    scssPath: './src/scss',
    jsPath: './src/js'
  },
  dist: {
    cssPath: './static/css',
    jsPath: './static/js'
  },
  packagesPath: './node_modules',
  sync: false,
  syncTarget: 'http://localhost/wordpress/'
};

/* eslint-disable no-sync */
if (fs.existsSync('./gulp-config.json')) {
  const overrides = JSON.parse(fs.readFileSync('./gulp-config.json'));
  config = merge(config, overrides);
}
/* eslint-enable no-sync */


//
// Helper functions
//

// Base SCSS linting function
function lintSCSS(src) {
  return gulp.src(src)
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
}

// Base SCSS compile function
function buildCSS(src, dest) {
  dest = dest || config.dist.cssPath;

  return gulp.src(src)
    .pipe(sass({
      includePaths: [config.src.scssPath, config.packagesPath]
    })
      .on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      // Supported browsers added in package.json ("browserslist")
      cascade: false
    }))
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(gulp.dest(dest));
}

// BrowserSync reload function
function serverReload(done) {
  if (config.sync) {
    browserSync.reload();
  }
  done();
}

// BrowserSync serve function
function serverServe(done) {
  if (config.sync) {
    browserSync.init({
      proxy: {
        target: config.syncTarget
      }
    });
  }
  done();
}


//
// Installation of components/dependencies
//

// Copy sanitize-html script
gulp.task('move-components-sanitizehtml', (done) => {
  gulp.src([`${config.packagesPath}/sanitize-html/dist/sanitize-html.min.js`])
    .pipe(gulp.dest(config.dist.jsPath));
  done();
});

// Run all component-related tasks
gulp.task('components', gulp.parallel(
  'move-components-sanitizehtml'
));


//
// CSS
//

// Lint all plugin scss files
gulp.task('scss-lint-plugin', () => {
  return lintSCSS(`${config.src.scssPath}/*.scss`);
});

// Compile email editor WYSIWYGs stylesheet
gulp.task('scss-build-email-editor-styles', () => {
  return buildCSS(`${config.src.scssPath}/editor-email.scss`);
});

// All plugin css-related tasks
gulp.task('css', gulp.series('scss-lint-plugin', 'scss-build-email-editor-styles'));


//
// Documentation
//

// Generates a README.md from README.txt
gulp.task('readme', () => {
  return gulp.src('readme.txt')
    .pipe(readme({
      details: false,
      screenshot_ext: [] // eslint-disable-line camelcase
    }))
    .pipe(gulp.dest('.'));
});


//
// Rerun tasks when files change
//
gulp.task('watch', (done) => {
  serverServe(done);

  gulp.watch('./**/*.php', gulp.series(serverReload));
});


//
// Default task
//
gulp.task('default', gulp.series('components', 'css', 'readme'));
