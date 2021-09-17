// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Copy task through config.merge as this task may be used across multiple partials
  // Copies required libraries from node_modules
  grunt.config.merge({
    copy: {
      npm: {
        files: [
          {
            src: ['node_modules/a11y_accordions/assets/js/aria.accordion.min.js'],
            dest: 'lib/aria.accordion.min.js'
          },
          {
            src: ['node_modules/@splidejs/splide/dist/js/splide.min.js'],
            dest: 'lib/splide.min.js'
          },
          {
            src: ['node_modules/@splidejs/splide/dist/css/splide-core.min.css'],
            dest: 'template-parts/components/slider/_1_splide-core.scss'
          },
          {
            src: ['node_modules/magnific-popup/dist/jquery.magnific-popup.min.js'],
            dest: 'lib/magnific-popup.min.js'
          },
          {
            src: ['node_modules/magnific-popup/dist/magnific-popup.css'],
            dest: 'template-parts/components/modal/_magnific-popup.scss'
          },
          {
            src: ['node_modules/zurb-twentytwenty/scss/twentytwenty-no-compass.scss'],
            dest: 'template-parts/blocks/image-compare/_twentytwenty-lib.scss'
          },
        ],
      }
    }
  });

  // Configure Concat task through config.merge as this task is used across multiple partials
  // Task to concatenate related lib files together to prevent enqueuing multiple js files for the same functionality
  grunt.config.merge({
    concat: {
      charts: {
        options: {
          sourceMap: false,
          separator: ';\r\n'
        },
        src: ['node_modules/chart.js/dist/Chart.bundle.min.js', 'node_modules/chartjs-plugin-deferred/dist/chartjs-plugin-deferred.min.js'],
        dest: 'lib/charts.min.js',
      },
      twentytwenty: {
        options: {
          sourceMap: false,
          separator: ';\r\n'
        },
        src: ['node_modules/zurb-twentytwenty/js/jquery.event.move.js', 'node_modules/zurb-twentytwenty/js/jquery.twentytwenty.js'],
        dest: 'lib/twentytwenty.min.js',
      },
    }
  });

  // Configure Uglify task through config.merge as this task is used across multiple partials
  // Minifies javascript file(s)
  grunt.config.merge({
    uglify: {
      twentytwenty: {
        options: {
          mangle: false,
        },
        src: 'lib/twentytwenty.min.js',
        dest: 'lib/twentytwenty.min.js',
      }
    }
  });

};
