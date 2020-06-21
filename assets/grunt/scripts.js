// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Concat task
  // Task to concatenate js files together separated by a line break
  grunt.config('concat', {
    options: {
      sourceMap: false,
      separator: ';\n'
    },
    dist: {
      src: ['<%= gruntVariables.build_dir %>/js/vendor/*.js', '<%= gruntVariables.build_dir %>/js/site/*.js'],
      dest: '<%= gruntVariables.dist_dir %>/js/site.js',
    }
  });

  // Configure Uglify task
  // Minifies javascript file(s)
  grunt.config('uglify', {
    options: {
      mangle: false
    },
    dist: {
      files: {
        '<%= gruntVariables.dist_dir %>/js/site.min.js': ['<%= gruntVariables.dist_dir %>/js/site.js']
      }
    }
  });

  // Configure ESlint task through config.merge as this task is used across multiple partials
  // Validates Javascript files
  grunt.config.merge({
    eslint: {
      site: [
        '<%= gruntVariables.build_dir %>/js/site/**/*.js' // Validates javascript files in js/site only (doesn't validate vendor JS)
      ],
    }
  });

  // Configure Watch task through config.merge as this task is used across multiple partials
  // Watches javascript files for changes and then runs tasks accordingly
  grunt.config.merge({
    watch: {
      scripts: {
        options: {
          event: ['changed', 'added', 'deleted']
        },
        files: ['<%= gruntVariables.build_dir %>/js/**/*.js'],
        tasks: ['newer:eslint:site', 'concat', 'uglify']
      }
    }
  });

};
