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
const del = require("del");
const gulp = require("gulp");
const babel = require("gulp-babel");

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
var sassGlob = require("gulp-sass-glob");
var postcss = require("gulp-postcss");
var pxtorem = require("postcss-pxtorem");
var autoprefixer = require("autoprefixer");
var cssjanus = require("gulp-cssjanus");
var wpPot = require("gulp-wp-pot");
var cssnano = require("cssnano");

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
  cssnano(),
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
    .pipe(dest(opts.src_dir + "/scss/vendors/"));

  var splideJS = src(
    "node_modules/@splidejs/splide/dist/js/splide.min.js"
  ).pipe(dest("./lib/"));

  //
  var splideCSS = src(
    "node_modules/@splidejs/splide/dist/css/splide-core.min.css"
  )
    .pipe(rename("_splide-core.scss"))
    .pipe(dest(opts.src_dir + "/scss/vendors/"));

  var accordionJS = src(
    "node_modules/a11y_accordions/assets/js/aria.accordion.min.js"
  ).pipe(dest("./template-parts/blocks/accordion"));

  var photoswipeJS = src(
    "node_modules/photoswipe/dist/photoswipe.esm.min.js"
  ).pipe(dest("./lib/"));

  var photoswipeLightboxJS = src(
    "node_modules/photoswipe/dist/photoswipe-lightbox.esm.min.js"
  ).pipe(dest("./lib/"));

  var photoswipeCSS = src("node_modules/photoswipe/dist/photoswipe.css")
    .pipe(rename("_photoswipe.scss"))
    .pipe(dest(opts.src_dir + "/scss/vendors/"));

  return merge(
    magnificJS,
    magnificCSS,
    splideJS,
    splideCSS,
    accordionJS,
    photoswipeJS,
    photoswipeLightboxJS,
    photoswipeCSS
  );
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

  watch(opts.src_dir + "/**/*.scss", sassTasks);

  // Watch for changes to acf-json
  watch(opts.src_dir + "/acf-json/*.json", moveBlockJson);

  watch(
    // ignore the editor-block-filters.js file as we transpile this separately
    [
      opts.src_dir + "/js/**/*.js",
      "!" + opts.src_dir + "/js/editor-block-filters.js",
    ],
    series(javascriptLint, javascriptProcess)
  );

  watch(opts.src_dir + "/js/editor-block-filters.js", compileGutenberg);

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
  return src([opts.src_dir + "/js/**/*.*"])
    .pipe(eslint())
    .pipe(eslint.format());

  done();
}

function compileGutenberg(done) {
  return src(opts.src_dir + "/js/editor-block-filters.js")
    .pipe(
      babel({
        presets: ["@babel/env", "@babel/preset-react"],
      })
    )
    .pipe(uglify({ mangle: true }))
    .pipe(dest(opts.dist_dir));

  done();
}

function moveBlockJson(done) {
  /** Get All files within the acf-json folder
   * Loop through each file, and look for the property location.value
   * If location.param == 'block' then search in value for the text after the character '/'
   * Save this value as a variable and then check if a folder exists in template-parts/blocks
   * with the same name. If it does, move the file to that folder.
   */
  // Get all files in acf-json
  files = fs.readdirSync(opts.src_dir + "/acf-json");
  let blocksFolder = "template-parts/blocks";

  files.forEach(function (stream, file) {
    // Get the absolute path to the acf json file
    // Get relative path to the acf json file
    file = opts.src_dir + "/acf-json/" + stream;
    if (stream != ".DS_Store") {
      // Convert the file into a json object
      var jsonContent = JSON.parse(fs.readFileSync(file));

      // If jsonContent.title contains 'Blocks > ' then we know it's a block
      let acfTitle = jsonContent.title;
      if (acfTitle.includes("Block > ")) {
        let blockName = acfTitle.split("Block > ")[1];
        // Remove 'and' from the block name
        blockName = blockName.replace("and", "");
        // Replace whitespace with hyphens and make lowercase
        blockName = blockName.replace(/\s+/g, "-").toLowerCase();
        // Check if the block folder exists
        if (fs.existsSync(blocksFolder + "/" + blockName)) {
          // Move the file to the block folder
          fs.renameSync(file, blocksFolder + "/" + blockName + "/" + stream);
        }
      }
    }
    done();
  });
}

// Tasks which process the core javascript files
function javascriptProcess() {
  return (
    src([
      opts.src_dir + "/js/01-breakpoints.js",
      opts.src_dir + "/js/*.*",
      "!" + opts.src_dir + "/js/editor-block-filters.js", // IGNORE GUTENBERG AS WE TRANSPILE THIS SEPARATELY
      "node_modules/jellyfish-ui/dist/js/jellyfish.min.js",
    ])
      // .pipe(sourcemaps.init())
      .pipe(concat("site.min.js"))
      .pipe(uglify({ mangle: true }))
      .pipe(
        banner(opts.bannerText, {
          pkg: pkg,
        })
      )
      //.pipe(sourcemaps.write("."))
      .pipe(dest(opts.dist_dir))
      .pipe(browsersync.reload({ stream: true }))
  );
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
  return (
    src(opts.src_dir + "/scss/main.scss")
      //.pipe(sourcemaps.init())
      .pipe(sassGlob())
      .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
      .pipe(postcss(sassProcessors))
      .pipe(rename("style.css"))
      .pipe(
        banner(opts.bannerText, {
          pkg: pkg,
        })
      )
      // .pipe(sourcemaps.write("."))
      .pipe(dest("./"))
      .pipe(browsersync.reload({ stream: true }))
      .pipe(cssjanus())
      .pipe(rename({ suffix: "-rtl" }))
      .pipe(dest("./"))
  );
}

// Process Woocommerce Sass
function sassProcessWoo() {
  return (
    src(opts.src_dir + "/scss/woocommerce.scss")
      //.pipe(sourcemaps.init())
      .pipe(sassGlob())
      .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
      .pipe(postcss(sassProcessors))
      .pipe(rename("woocommerce.min.css"))
      .pipe(
        banner(opts.bannerText, {
          pkg: pkg,
        })
      )
      // .pipe(sourcemaps.write("."))
      .pipe(dest(opts.dist_dir))
      .pipe(browsersync.reload({ stream: true }))
  );
}

// Process Editor Sass
function sassProcessEditor() {
  return (
    src(opts.src_dir + "/scss/editor.scss")
      //.pipe(sourcemaps.init())
      .pipe(sassGlob())
      .pipe(sass({ includePaths: ["node_modules"] }).on("error", sass.logError))
      .pipe(postcss(sassProcessors))
      .pipe(rename("editor-style.min.css"))
      .pipe(
        banner(opts.bannerText, {
          pkg: pkg,
        })
      )
      //.pipe(sourcemaps.write("."))
      .pipe(dest(opts.dist_dir))
      .pipe(browsersync.reload({ stream: true }))
  );
}

// Tasks for SASS compilation
const sassTasks = parallel(sassProcessSite, sassProcessWoo, sassProcessEditor);

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

exports.watch = watchTask;
exports.reload = browsersyncReload;
exports.build = buildScripts;
exports.default = serverScripts;
exports.init = series(buildScripts, serverScripts);
