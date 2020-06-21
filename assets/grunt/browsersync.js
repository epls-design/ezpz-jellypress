// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Browsersync task
  // browserSync watches files defined in src, and on change reloads the browser
  grunt.config('browserSync', {
    bsFiles: {
      src: [
        '<%= gruntVariables.dist_dir %>/js/site.js',
        '<%= gruntVariables.dist_dir %>/img/*',
        '<%= gruntVariables.dist_dir %>/icons/*',
        '**/*.php',
        '**/*.html',
        'style.css',
        '<%= gruntVariables.dist_dir %>/css/woocommerce.min.css',
      ]
    },
    options: {
      watchTask: true,
      proxy: "<%= gruntVariables.dev_url %>"
    }
  });

};
