// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Clean task through config.merge as this task is used across multiple partials
  // Removes all existing icons from dist directory
  grunt.config.merge({
    clean: {
      icons: ['<%= opts.dist_dir %>/icons/*']
    }
  });

  // Configure Imagemin task through config.merge as this task is used across multiple partials
  // Minifies all images from <build_dir>/img/ and outputs to <dist_dir>/img/
  grunt.config.merge({
    imagemin: {
      icons: {
        files: [{
          expand: true,
          cwd: '<%= opts.build_dir %>/icons/',
          src: ['**/*.svg'],
          dest: '<%= opts.dist_dir %>/icons/'
        }]
      }
    }
  });

  // Configure SVGstore task
  // Put all of our minified svg icons into a sprite sheet
  grunt.config('svgstore', {
    options: {
      prefix: 'icon-', // This will prefix each ID
      svg: { // will add and override the the default xmlns="http://www.w3.org/2000/svg" attribute to the resulting SVG
        viewBox: '0 0 100 100',
        xmlns: 'http://www.w3.org/2000/svg'
      }
    },
    default: {
      files: {
        '<%= opts.dist_dir %>/icons/icons.svg': ['<%= opts.dist_dir %>/icons/*.svg']
      }
    }
  });

  // Configure Watch task through config.merge as this task is used across multiple partials
  // Watches icon files for changes and then runs tasks accordingly
  grunt.config.merge({
    watch: {
      icons: {
        options: {
          event: ['changed', 'added', 'deleted']
        },
        files: ['<%= opts.build_dir %>/icons/*.svg'],
        tasks: ['clean:icons', 'imagemin:icons', 'svgstore']
      }
    }
  });

};
