// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Copy task through config.merge as this task may be used across multiple partials
  // Copies required libraries from node_modules
  grunt.config.merge({
    copy: {
      npm: {
        files: [
          {
          expand: true,
          cwd: 'node_modules/hamburgers/_sass/hamburgers',
          src: ['**/*'],
          dest: '<%= opts.build_dir %>/scss/jellyfish/05-vendor/hamburgers'
        },
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
      ],
      }
    }
  });

};
