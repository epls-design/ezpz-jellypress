require("es6-promise").polyfill();

// Set up required modules
const { parallel, series, src, dest, watch } = require("gulp");
const rename = require("gulp-rename");
const banner = require("gulp-banner");
const sourcemaps = require("gulp-sourcemaps");
const pkg = require("./package.json");
const env = require("./env.json");
const browsersync = require("browser-sync").create();
const fs = require("fs");
const path = require("path");
const glob = require("glob");
const del = require("del");

var eslint = require("gulp-eslint");
var phplint = require("gulp-phplint");
var imagemin = require("gulp-imagemin");
var svgstore = require("gulp-svgstore");
var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var merge = require("merge-stream");
var replace = require("gulp-replace");
var changedInPlace = require("gulp-changed-in-place");
var sass = require("gulp-sass")(require("sass"));
var postcss = require("gulp-postcss");
var pxtorem = require("postcss-pxtorem");
var autoprefixer = require("autoprefixer");
var cssclean = require("gulp-clean-css");
var cssjanus = require("gulp-cssjanus");
var wpPot = require("gulp-wp-pot");

// Processors used by postcss
var sassProcessors = [
  autoprefixer(),
  pxtorem({
    rootValue: 16,
    unitPrecision: 2, // Decimal places
    propList: ["*"], // Apply to all elements
    replace: true, // False enables px fallback
    mediaQuery: false, // Do not apply within media queries (we use em instead)
    minPixelValue: 0,
  }),
];

// Sets options which are used later on in this file
const opts = {
  src_dir: "./src",
  dist_dir: "./dist",
  dev_url: env.DEV_URL,
  bannerText:
    "/* \n" +
    " *  Theme Name: <%= pkg.friendly_name %>\n" +
    " *  Theme URI: <%= pkg.homepage %>\n" +
    " *  Author: <%= pkg.author.name %> <<%= pkg.author.email %>>\n" +
    " *  Author URI: <%= pkg.author.url %>\n" +
    " *  Description: <%= pkg.description %>\n" +
    " *  Tags: <%= pkg.keywords %>\n" +
    " *  Version: <%= pkg.version %>\n" +
    " *  License: <%= pkg.license %>\n" +
    " *  Text Domain: <%= pkg.text_domain %> */ \n",
};

// Task to initialize browserSync client
function browsersyncInit(done) {
  browsersync.init({
    proxy: opts.dev_url,
    https: true,
  });
  done();
}

// Task to manually reload browserSync
function browsersyncReload(done) {
  browsersync.reload();
  done();
}

// Copy libraries from Node Modules so that they can be compiled into the project's codebase
function copyLibs() {
  var magnificJS = src(
    "node_modules/magnific-popup/dist/jquery.magnific-popup.min.js"
  )
    .pipe(rename("magnific-popup.min.js"))
    .pipe(dest("./lib/"));

  var magnificCSS = src("node_modules/magnific-popup/dist/magnific-popup.css")
    .pipe(rename("_magnific-popup.scss"))
    .pipe(dest("./template-parts/components/modal/"));

  var splideJS = src(
    "node_modules/@splidejs/splide/dist/js/splide.min.js"
  ).pipe(dest("./lib/"));

  //
  var splideCSS = src(
    "node_modules/@splidejs/splide/dist/css/splide-core.min.css"
  )
    .pipe(rename("_1_splide-core.scss"))
    .pipe(dest("./template-parts/components/slider/"));

  var accordionJS = src(
    "node_modules/a11y_accordions/assets/js/aria.accordion.min.js"
  ).pipe(dest("./lib/"));

  return merge(magnificJS, magnificCSS, splideJS, splideCSS, accordionJS);
}

// Tasks which watch for changes in specified files/dirs and run tasks based on filetypes edited
function watchTask(done) {
  watch(opts.src_dir + "/img/**", series(imagesClean, imagesMinify));
  watch(
    opts.src_dir + "/icons/**",
    series(iconsClean, iconsMinify, iconsSpritesheet)
  );

  watch(["**/*.php", "*.php", "!node_modules/**"], parallel(phpLint, makePot));
  watch(["**/*.html", "*.html", "!node_modules/**"], browsersyncReload);

  watch(opts.src_dir + "/**/!(__all).scss", sassTasks);

  watch(
    [opts.src_dir + "/js/**/*.js", "./template-parts/**/*.js"],
    series(javascriptLint, javascriptProcess)
  );
  watch("./gulpfile.js", series(gulpfileLint, buildScripts));
  done();
}

// Tasks which run when this file is edited (when watch is running)
function gulpfileLint() {
  return src("./gulpfile.js").pipe(eslint()).pipe(eslint.format());
}

// Tasks to process php
function phpLint(done) {
  return src(["**/*.php", "*.php", "!node_modules/**"])
    .pipe(changedInPlace())
    .pipe(phplint("", { skipPassedFiles: true }))
    .pipe(
      phplint.reporter(function (file) {
        var report = file.phplintReport || {};
        if (report.error) {
          console.error(
            report.message +
              " on line " +
              report.line +
              " of " +
              report.filename
          );
        }
      })
    )
    .pipe(browsersync.reload({ stream: true }));

  done();
}

// Clean Icons before processing
function iconsClean(done) {
  return del(opts.dist_dir + "/icons");
  done();
}

// Copy and minify icons
function iconsMinify() {
  return src(opts.src_dir + "/icons/**/*.svg")
    .pipe(imagemin({ includeTitleElement: false, preserveDescElement: false }))
    .pipe(dest(opts.dist_dir + "/icons"));
}

// Add all icons into a spritesheet
function iconsSpritesheet() {
  return src([opts.dist_dir + "/icons/**/*.svg", "!icons.svg"])
    .pipe(rename({ prefix: "icon-" }))
    .pipe(svgstore())
    .pipe(dest(opts.dist_dir + "/icons"))
    .pipe(browsersync.reload({ stream: true }));
}

// Clean images before processing
function imagesClean(done) {
  return del(opts.dist_dir + "/img");
  done();
}

// Optimise images into dist
function imagesMinify() {
  return src(opts.src_dir + "/img/**/*.{png,jpg,JPG,JPEG,jpeg,svg,gif}")
    .pipe(imagemin())
    .pipe(dest(opts.dist_dir + "/img"))
    .pipe(browsersync.reload({ stream: true }));
}

// eslint all first party JS
function javascriptLint(done) {
  return src([
    opts.src_dir + "/js/settings/**/*.js",
    opts.src_dir + "/js/site/**/*.js",
    "./template-parts/**/*.js",
  ])
    .pipe(eslint())
    .pipe(eslint.format());

  done();
}

// Tasks which process the core javascript files
function javascriptProcess() {
  return src([
    opts.src_dir + "/js/settings/**/*.js",
    "node_modules/jellyfish-ui/dist/js/jellyfish.min.js",
    opts.src_dir + "/js/vendor/**/*.js",
    opts.src_dir + "/js/site/**/*.js",
    "./template-parts/**/*.js",
  ])
    .pipe(sourcemaps.init())
    .pipe(concat("site.min.js"))
    .pipe(uglify({ mangle: true }))
    .pipe(
      banner(opts.bannerText, {
        pkg: pkg,
      })
    )
    .pipe(sourcemaps.write("."))
    .pipe(dest(opts.dist_dir + "/js"))
    .pipe(browsersync.reload({ stream: true }));
}

// Recursive task which traverses a directory and it's subdirectories to compile an array of all sass partials
const getSassDirPartials = function (dirPath, arrayOfFiles, relativeDir = "") {
  files = fs.readdirSync(dirPath);

  arrayOfFiles = arrayOfFiles || [];

  files.forEach(function (file) {
    if (fs.statSync(dirPath + "/" + file).isDirectory()) {
      arrayOfFiles = getSassDirPartials(
        dirPath + "/" + file,
        arrayOfFiles,
        path.join(relativeDir, file)
      );
    } else if (
      // Exclude the dynamically generated file
      file !== "__all.scss" &&
      // Only include _*.scss files
      path.basename(file).substring(0, 1) === "_" &&
      path.extname(file) === ".scss"
    ) {
      arrayOfFiles.push(path.join(relativeDir, file));
    }
  });
  return arrayOfFiles;
};

/**
 * Dynamically import SASS files into partials. Modified with the two refs below
 * @see https://nateeagle.com/2014/05/22/sass-directory-imports-with-gulp/
 * @see https://coderrocketfuel.com/article/recursively-list-all-the-files-in-a-directory-using-node-js
 */
function sassAutomaticImports(done) {
  // Array of directories where the __all files exist
  var srcFiles = "./**/__all.scss";
  glob(srcFiles, function (error, files) {
    files.forEach(function (allFile) {
      // Add a banner to warn users
      fs.writeFileSync(
        allFile,
        "// This file imports all other underscore-prefixed .scss files in this directory and sub-directories.\n" +
          "// It is automatically generated by gulp. Do not directly modify this file.\n\n"
      );

      var directory = path.dirname(allFile);
      try {
        let partials = getSassDirPartials(directory);

        // Append import statements for each partial
        partials.forEach(function (partial) {
          partial = partial.replace("_", "");
          partial = partial.replace(".scss", "");
          fs.appendFileSync(allFile, '@import "' + partial + '";\n');
        });
      } catch (error) {
        console.log(error);
      }
    });
  });

  done();
}

// Make translation file in /languages
function makePot() {
  return src(["**/*.php", "*.php", "!node_modules/**"])
    .pipe(
      wpPot({
        domain: pkg.text_domain,
        package: pkg.friendly_name,
        copyrightText:
          "Copyright (C) " +
          new Date().getFullYear() +
          " " +
          pkg.author.name +
          " <" +
          pkg.author.email +
          ">",
        lastTranslator: pkg.author.name + " <" + pkg.author.email + ">",
        team: pkg.author.name + " <" + pkg.author.email + ">",
        relativeTo: "./",
        headers: {
          "Project-Id-Version": pkg.friendly_name + " " + pkg.version,
        },
      })
    )
    .pipe(dest("./languages/en_GB.pot"));
}

// Process Theme Sass
function sassProcessSite() {
  return src(opts.src_dir + "/scss/compile/main.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
    .pipe(postcss(sassProcessors))
    .pipe(cssclean())
    .pipe(rename("style.css"))
    .pipe(
      banner(opts.bannerText, {
        pkg: pkg,
      })
    )
    .pipe(sourcemaps.write("."))
    .pipe(dest("./"))
    .pipe(browsersync.reload({ stream: true }))
    .pipe(cssjanus())
    .pipe(rename({ suffix: "-rtl" }))
    .pipe(dest("./"));
}

// Process Woocommerce Sass
function sassProcessWoo() {
  return src(opts.src_dir + "/scss/compile/woocommerce.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
    .pipe(postcss(sassProcessors))
    .pipe(cssclean())
    .pipe(rename("woocommerce.min.css"))
    .pipe(
      banner(opts.bannerText, {
        pkg: pkg,
      })
    )
    .pipe(sourcemaps.write("."))
    .pipe(dest(opts.dist_dir + "/css/"))
    .pipe(browsersync.reload({ stream: true }));
}

// Process Editor Sass
function sassProcessEditor() {
  return src(opts.src_dir + "/scss/compile/editor.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
    .pipe(postcss(sassProcessors))
    .pipe(cssclean())
    .pipe(rename("editor-style.min.css"))
    .pipe(
      banner(opts.bannerText, {
        pkg: pkg,
      })
    )
    .pipe(sourcemaps.write("."))
    .pipe(dest(opts.dist_dir + "/css/"))
    .pipe(browsersync.reload({ stream: true }));
}

// Process Admin Sass
function sassProcessAdmin() {
  return (
    src(opts.src_dir + "/scss/compile/admin-style.scss")
      .pipe(sourcemaps.init())
      .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
      .pipe(postcss(sassProcessors))
      // .pipe(cssclean())
      .pipe(rename("admin-style.css"))
      .pipe(
        banner(opts.bannerText, {
          pkg: pkg,
        })
      )
      .pipe(sourcemaps.write("."))
      .pipe(dest(opts.dist_dir + "/css/"))
      .pipe(browsersync.reload({ stream: true }))
  );
}

// Tasks for SASS compilation
const sassTasks = series(
  sassAutomaticImports,
  parallel(sassProcessSite, sassProcessWoo, sassProcessEditor, sassProcessAdmin)
);

// Tasks which run on $ gulp build
const buildScripts = series(
  copyLibs,
  parallel(
    phpLint,
    series(iconsClean, iconsMinify, iconsSpritesheet),
    series(imagesClean, imagesMinify),
    sassTasks,
    series(javascriptLint, javascriptProcess)
  ),
  makePot
);

// Tasks which run on $ gulp
const serverScripts = parallel(browsersyncInit, watchTask);

exports.reload = browsersyncReload;
exports.build = buildScripts;
exports.default = serverScripts;
exports.init = series(buildScripts, serverScripts);
