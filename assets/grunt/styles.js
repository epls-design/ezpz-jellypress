// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // TODO: Auto add css banner to replace _theme.scss

  // Configure Sass Directory Import task
  // Watches directories for scss file name _all and imports all partials into that file
  grunt.config('sass_directory_import', {
    options: {
      quiet: true,
    },
    files: {
      // The file pattern to add @imports to.
      src: ['<%= opts.build_dir %>/scss/**/_all.scss'] // Need to add back in globalVars
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
      options: {
        banner: '/*! Test */'
      },
      files: [{
          src: '<%= opts.build_dir %>/scss/compile.scss',
          dest: '<%= opts.dist_dir %>/css/style.css'
        },
        {
          src: '<%= opts.build_dir %>/scss/woocommerce.scss',
          dest: '<%= opts.dist_dir %>/css/woocommerce.css'
        },
        {
          src: '<%= opts.build_dir %>/scss/editor.scss',
          dest: '<%= opts.dist_dir %>/css/editor-style.css'
        },
        {
          src: '<%= opts.build_dir %>/scss/admin-style.scss',
          dest: '<%= opts.dist_dir %>/css/admin-style.css'
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
          src: '<%= opts.dist_dir %>/css/style.css',
          dest: 'style.css'
        },
        {
          src: '<%= opts.dist_dir %>/css/woocommerce.css',
          dest: '<%= opts.dist_dir %>/css/woocommerce.min.css'
        },
        {
          src: '<%= opts.dist_dir %>/css/editor-style.css',
          dest: '<%= opts.dist_dir %>/css/editor-style.min.css'
        },
        {
          src: '<%= opts.dist_dir %>/css/admin-style.css',
          dest: '<%= opts.dist_dir %>/css/admin-style.min.css'
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

  // Configure Usebanner task through config.merge as this task is used across multiple partials
  // Appends a banner to the top of the outputted CSS file(s)
  grunt.config.merge({
    usebanner: {
      stylesmain: {
        options: {
          position: 'top',
          banner: '/*! Theme Name: <%= pkg.name %>\n' +
          ' *  Theme URI: <%= pkg.homepage %>\n' +
          ' *  Author: <%= pkg.author.name %> <<%= pkg.author.email %>>\n' +
          ' *  Author URI: <%= pkg.author.url %>\n' +
          ' *  Description: <%= pkg.description %>\n' +
          ' *  Tags: <%= pkg.keywords %>\n' +
          ' *  Version: <%= pkg.version %>\n' +
          ' *  License: <%= pkg.license %>\n' +
          ' *  Text Domain: <%= opts.text_domain %> */ \n',
          linebreak: true
        },
        files: {
          src: ['style.css']
        }
      },
      stylesothers: {
        options: {
          position: 'top',
          banner: '<%= opts.banner %>',
          linebreak: true
        },
        files: {
          src: ['<%= opts.dist_dir %>/css/*.css']
        }
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
        files: ['<%= opts.build_dir %>/scss/**/*.scss'],
        tasks: ['sass_directory_import', 'sass', 'postcss', 'usebanner:stylesmain', 'usebanner:stylesothers', 'cssjanus']
      }
    }
  });

};
