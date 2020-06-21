// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Sass Directory Import task
  // Watches directories for scss file name _all and imports all partials into that file
  grunt.config('sass_directory_import', {
    options: {
      quiet: true,
    },
    files: {
      // The file pattern to add @imports to.
      src: ['<%= gruntVariables.build_dir %>/scss/**/_all.scss'] // Need to add back in globalVars
    }
  });

  // Configure Sass task
  // Task to run sass on defined scss file(s)
  grunt.config('sass', {
    options: {
      implementation: require('node-sass'), // Use Node-sass as implementation option, rather than dart-sass
      sourceMap: true
    },
    dist: {
      files: [{
          src: '<%= gruntVariables.build_dir %>/scss/compile.scss',
          dest: '<%= gruntVariables.dist_dir %>/css/style.css'
        },
        {
          src: '<%= gruntVariables.build_dir %>/scss/woocommerce.scss',
          dest: '<%= gruntVariables.dist_dir %>/css/woocommerce.css'
        },
        {
          src: '<%= gruntVariables.build_dir %>/scss/editor.scss',
          dest: '<%= gruntVariables.dist_dir %>/css/editor-style.css'
        },
        {
          src: '<%= gruntVariables.build_dir %>/scss/admin-style.scss',
          dest: '<%= gruntVariables.dist_dir %>/css/admin-style.css'
        }
      ]
    }
  });

  // Configure Postcss task
  // Task to run postcss on files in dist/css/ and apply additional processors
  grunt.config('postcss', {
    options: {
      map: false,
      processors: [
        require('autoprefixer')(),
        require('postcss-pxtorem')({
          rootValue: 16,
          unitPrecision: 2, // Decimal places
          propList: ['*'], // Apply to all elements
          replace: true, // False enables px fallback
          mediaQuery: false, // Do not apply within media queries (we use em instead)
          minPixelValue: 0
        }),
        require('cssnano')() // minify the result
      ]
    },
    dist: {
      files: [{
          src: '<%= gruntVariables.dist_dir %>/css/style.css',
          dest: 'style.css'
        },
        {
          src: '<%= gruntVariables.dist_dir %>/css/woocommerce.css',
          dest: '<%= gruntVariables.dist_dir %>/css/woocommerce.min.css'
        },
        {
          src: '<%= gruntVariables.dist_dir %>/css/editor-style.css',
          dest: '<%= gruntVariables.dist_dir %>/css/editor-style.min.css'
        },
        {
          src: '<%= gruntVariables.dist_dir %>/css/admin-style.css',
          dest: '<%= gruntVariables.dist_dir %>/css/admin-style.min.css'
        }
      ]
    }
  });

  // Configure Cssjanus task
  // Create style-rtl.css for Wordpress Accessibility
  grunt.config('cssjanus', {
    dist: {
      files: {
        'style-rtl.css': 'style.css',
      }
    }
  });

  // Configure Watch task through config.merge as this task is used across multiple partials
  // Watches scss files for changes and then runs tasks accordingly
  grunt.config.merge({
    watch: {
      styles: {
        options: {
          event: ['changed', 'added', 'deleted']
        },
        files: ['<%= gruntVariables.build_dir %>/scss/**/*.scss'],
        tasks: ['sass_directory_import', 'sass', 'postcss', 'cssjanus']
      }
    }
  });

};
