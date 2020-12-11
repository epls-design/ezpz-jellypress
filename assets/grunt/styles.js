// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

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
          src: '<%= opts.build_dir %>/scss/compile/main.scss',
          dest: '<%= opts.dist_dir %>/css/style.css'
        },
        {
          src: '<%= opts.build_dir %>/scss/compile/woocommerce.scss',
          dest: '<%= opts.dist_dir %>/css/woocommerce.css'
        },
        {
          src: '<%= opts.build_dir %>/scss/compile/editor.scss',
          dest: '<%= opts.dist_dir %>/css/editor-style.css'
        },
        {
          src: '<%= opts.build_dir %>/scss/compile/admin-style.scss',
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
        // TODO: Add https://github.com/luisrudge/postcss-flexbugs-fixes
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

  grunt.config('import_sass_from_dirs', {
    // This is an arbitrary name for this sub-task
    src: {
      files: {
          // Put an _all.scss file in any directory inside our scss files, and
          // this task will write @import statements for every other _*.scss
          // file in that directory. Then simply @import your _all.scss file to
          // import the contents of the directory.
          src: ['<%= opts.build_dir %>/scss/**/_all.scss','template-parts/**/_all.scss']
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
          banner: '/*!\n' +
          ' *  Theme Name: <%= pkg.friendly_name %>\n' +
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
        files: ['<%= opts.build_dir %>/scss/**/*.scss', 'template-parts/**/*.scss'],
        tasks: ['import_sass_from_dirs', 'sass', 'postcss', 'usebanner:stylesmain', 'usebanner:stylesothers', 'cssjanus']
      }
    }
  });

};
