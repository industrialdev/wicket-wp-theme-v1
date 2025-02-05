const gulp = require('gulp');
const sass = require('gulp-dart-sass');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const imagemin = require('gulp-imagemin');
const insert = require('gulp-insert');
const gulpIgnore = require('gulp-ignore');
const postcss = require('gulp-postcss');
const tailwindcss = require('tailwindcss');
const browserSync = require('browser-sync').create();
const webpack = require('webpack-stream');
const fs = require('fs');
const path = require('path');

const wicketPaths = {
  src: '.',
  assets: '/assets',
  theme: '.',
  basePlugin: '../../plugins/wicket-wp-base-plugin',
  baseStyle: 'wicket',
  script: 'wicket',
  adminScripts: '/assets/scripts/wp-admin/*.js',
};

const sassFiles = [
  `${wicketPaths.src}${wicketPaths.assets}/styles/${wicketPaths.baseStyle}.scss`,
  `${wicketPaths.src}${wicketPaths.assets}/styles/admin.scss`,
];

const jsFiles = [
  `${wicketPaths.src}${wicketPaths.assets}/scripts/*.js`,
];

const adminJsFiles = [
  `${wicketPaths.src}${wicketPaths.assets}/scripts/wp-admin/*.js`,
];

// BrowserSync configuration
browserSync.init({
  proxy: 'https://localhost',
  reloadDebounce: 2000,
});

// Error notifications
function onError(err) {
  notify.onError({
    title: 'Gulp Task Error',
    message: 'Error: <%= error.message %>',
  })(err);
  this.emit('end');
}

// Extract JSON theme settings into SCSS and CSS variables
function parseJsonTheme(obj, extract = '', scssVariables = {}, cssVariables = {}) {
  const mainPrefix = 'wp-';

  if (extract === 'color' && obj.settings?.color?.palette) {
    obj.settings.color.palette.forEach(function (item) {
      const slug = item.slug.replace(/"/g, '').toLowerCase();
      scssVariables[`$${mainPrefix}${extract}-${slug}`] = item.color;
      cssVariables[`--color-${slug}`] = item.color;
    });
  }

  if (extract === 'border-radius' && obj.settings?.custom?.['border-radius']) {
    Object.keys(obj.settings.custom['border-radius']).forEach(function (key) {
      scssVariables[`$${mainPrefix}border-radius-${key}`] = obj.settings.custom['border-radius'][key];
      cssVariables[`--border-radius-${key}`] = obj.settings.custom['border-radius'][key];
    });
  }

  if (extract === 'box-shadow' && obj.settings?.custom?.['box-shadow']) {
    Object.keys(obj.settings.custom['box-shadow']).forEach(function (key) {
      scssVariables[`$${mainPrefix}box-shadow-${key}`] = obj.settings.custom['box-shadow'][key];
      cssVariables[`--box-shadow-${key}`] = obj.settings.custom['box-shadow'][key];
    });
  }

  if (extract === 'layout' && obj.settings?.custom?.layout) {
    Object.keys(obj.settings.custom.layout).forEach(function (key) {
      scssVariables[`$${mainPrefix}layout-${key}`] = obj.settings.custom.layout[key];
      cssVariables[`--layout-${key}`] = obj.settings.custom.layout[key];
    });
  }

  if (extract === 'letter-spacing' && obj.settings?.custom?.['letter-spacing']) {
    Object.keys(obj.settings.custom['letter-spacing']).forEach(function (key) {
      scssVariables[`$${mainPrefix}letter-spacing-${key}`] = obj.settings.custom['letter-spacing'][key];
      cssVariables[`--letter-spacing-${key}`] = obj.settings.custom['letter-spacing'][key];
    });
  }

  if (extract === 'line-height' && obj.settings?.custom?.['line-height']) {
    Object.keys(obj.settings.custom['line-height']).forEach(function (key) {
      scssVariables[`$${mainPrefix}line-height-${key}`] = `${obj.settings.custom['line-height'][key]}rem`;
      cssVariables[`--line-height-${key}`] = `${obj.settings.custom['line-height'][key]}rem`;
    });
  }

  if (extract === 'font-sizes' && obj.settings?.typography?.fontSizes) {
    obj.settings.typography.fontSizes.forEach(function (item) {
      const slug = item.slug.replace(/"/g, '').toLowerCase();
      scssVariables[`$${mainPrefix}font-size-${slug}`] = item.size;
      cssVariables[`--font-size-${slug}`] = item.size;
    });
  }

  if (extract === 'spacing-sizes' && obj.settings?.spacing?.spacingSizes) {
    obj.settings.spacing.spacingSizes.forEach(function (item) {
      const slug = item.slug.replace(/"/g, '').toLowerCase();
      scssVariables[`$${mainPrefix}spacing-${slug}`] = item.size;
      cssVariables[`--spacing-${slug}`] = item.size;
    });

    scssVariables[`$${mainPrefix}spacing-none`] = '0rem';
    cssVariables[`--spacing-none`] = '0rem';
  }

  return { scssVariables, cssVariables };
}


// Generate SCSS variables from theme.json
function themeJsonToSCSS() {
  return new Promise(function (resolve, reject) {
    const themeJsonPath = path.join(wicketPaths.src, 'theme.json');
    const themeScssPath = path.join(wicketPaths.src, 'assets/styles/variables/_theme-json.scss');

    // If _theme-json.scss doesn't exist, create it with empty content. Create the directory structure if it doesn't exist too.
    if (!fs.existsSync(path.dirname(themeScssPath))) {
      fs.mkdirSync(path.dirname(themeScssPath), { recursive: true });
    }

    if (!fs.existsSync(themeScssPath)) {
      fs.writeFileSync(themeScssPath, '');
    }

    fs.readFile(themeJsonPath, 'utf8', function (err, data) {
      if (err) {
        reject(err);
        return;
      }

      const themeJson = JSON.parse(data);
      let scssVariables = {};
      let cssVariables = {};

      let result = parseJsonTheme(themeJson, 'color', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'border-radius', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'box-shadow', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'layout', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'letter-spacing', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'line-height', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'font-sizes', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      result = parseJsonTheme(themeJson, 'spacing-sizes', scssVariables, cssVariables);
      scssVariables = result.scssVariables;
      cssVariables = result.cssVariables;

      const scssContent = Object.keys(scssVariables)
        .map(function (key) { return `${key}: ${scssVariables[key]};`; })
        .join('\n');

      const cssContent = `:root {\n${Object.keys(cssVariables)
        .map(function (key) { return `  ${key}: ${cssVariables[key]};`; })
        .join('\n')}\n}`;

      const combinedContent = `${scssContent}\n\n${cssContent}`;

      fs.writeFile(themeScssPath, combinedContent, 'utf8', function (err) {
        if (err) {
          reject(err);
          return;
        }
        resolve();
      });
    });
  });
}

// Compile and minify CSS
function sassTask() {
  return gulp
    .src(sassFiles)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(sass({ outputStyle: 'expanded', includePaths: ['node_modules'] }).on('error', sass.logError))
    .pipe(postcss([tailwindcss]))
    .pipe(autoprefixer({ overrideBrowserslist: ['last 2 versions'], cascade: false }))
    .pipe(sourcemaps.write('../../maps'))
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/styles/`))
    .pipe(browserSync.stream());
}

function minSass() {
  return gulp
    .src([
      `${wicketPaths.src}${wicketPaths.assets}/styles/${wicketPaths.baseStyle}.css`,
      `${wicketPaths.src}${wicketPaths.assets}/styles/admin.css`,
    ])
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(autoprefixer({ overrideBrowserslist: ['last 2 versions'], cascade: false }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('../../maps'))
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/styles/min`))
    .pipe(browserSync.stream());
}

function minScripts() {
  return gulp
    .src(`${wicketPaths.src}${wicketPaths.assets}/scripts/${wicketPaths.script}.js`)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(webpack({}))
    .pipe(rename({ basename: wicketPaths.script, suffix: '.min' }))
    .pipe(uglify())
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/scripts/min/`));
}

function minScriptsAdmin() {
  return gulp
    .src(adminJsFiles)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(concat(`${wicketPaths.script}-wp-admin.js`))
    .pipe(insert.wrap('(function($){\n\n', '\n\n})(jQuery);'))
    .pipe(rename(`${wicketPaths.script}-wp-admin.min.js`))
    .pipe(uglify())
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/scripts/min`));
}

function imagesTask() {
  return gulp
    .src(`${wicketPaths.src}${wicketPaths.assets}/images/*`)
    .pipe(imagemin())
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/images`));
}

function iconsTask() {
  return gulp
    .src(`${wicketPaths.src}${wicketPaths.assets}/icons/*`)
    .pipe(imagemin())
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/icons`));
}

function fontsTask() {
  return gulp
    .src(`${wicketPaths.src}${wicketPaths.assets}/fonts/**/*`)
    .pipe(gulp.dest(`${wicketPaths.src}${wicketPaths.assets}/fonts`));
}

function watchTask() {
  const scssTailwindLocations = [
    `${wicketPaths.src}${wicketPaths.assets}/styles/**/*.scss`,
    `${wicketPaths.src}/**/*.php`,
    `${wicketPaths.basePlugin}/**/*.php`,
    `${wicketPaths.src}/**/*.js`,
  ];

  const jsLocations = [
    `${wicketPaths.src}${wicketPaths.assets}/scripts/*.js`,
    `${wicketPaths.src}${wicketPaths.assets}/scripts/wp-admin/*.js`,
    `${wicketPaths.src}${wicketPaths.assets}/scripts/components/*.js`,
  ];

  const themeJsonPath = `${wicketPaths.src}/theme.json`;

  gulp.watch(jsLocations, gulp.series(minScripts, minScriptsAdmin)).on('change', browserSync.reload);
  gulp.watch(scssTailwindLocations, gulp.series(sassTask, minSass)).on('change', browserSync.reload);
  gulp.watch(themeJsonPath, gulp.series(themeJsonToSCSS, sassTask, minSass)).on('change', browserSync.reload);
}

module.exports = {
  sass: gulp.series(themeJsonToSCSS, sassTask, minSass),
  scripts: gulp.series(minScripts, minScriptsAdmin),
  images: gulp.series(imagesTask, iconsTask),
  watch: gulp.series(watchTask),
  jsonvars: gulp.series(themeJsonToSCSS),
  default: gulp.series(
    themeJsonToSCSS,
    sassTask,
    minSass,
    minScripts,
    minScriptsAdmin,
    fontsTask,
    watchTask
  ),
  serve: gulp.parallel(watchTask),
  build: gulp.series(
    themeJsonToSCSS,
    sassTask,
    minSass,
    minScripts,
    minScriptsAdmin,
    imagesTask,
    iconsTask,
    fontsTask
  ),
};
