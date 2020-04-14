module.exports = function (grunt) {

    // Sets up global variables which will be used throughout the Gruntfile
    var globalConfig = {
        build_dir: 'build',
        dist_dir: 'assets',
        dev_url: 'https://jellypress.local',
    };

    // Use Node-sass as implementation option, rather than dart-sass
    const sass = require('node-sass');

    /**
     * BEGIN PROJECT CONFIG
     */

    grunt.initConfig({

        // Import variables
        globalConfig: globalConfig,

        // Task to remove old images and icons from the dist directory
        clean: {
            images: ['<%= globalConfig.dist_dir %>/img/*'],
            icons:  ['<%= globalConfig.dist_dir %>/icons/*']
        },

        copy: {
            // Task to copy required scripts from node_modules
            npm: {
                files: [
                  {
                    expand: true,
                    cwd: 'node_modules/hamburgers/_sass/hamburgers',
                    src: ['**/*'],
                    dest: '<%= globalConfig.build_dir %>/scss/jellyfish/vendor/hamburgers'
                  }
                ],
            }
        },

        // Validates javascript files in js/site only (doesn't validate vendor JS)
        jshint: {
          files: [
              '<%= globalConfig.build_dir %>/js/site/**/*.js'
          ]
          },

        // Validates php files
        phplint: {
          all: {
            src : ['*.php', '**/*.php', '!node_modules/**/*.php'], // Ignore node_modules
          }
        },

        // Task to run sass on defined scss file(s)
        sass: {
            options: {
              implementation: sass,
              sourceMap: true
            },
            dist: {
                files: [
                  {
                      src: '<%= globalConfig.build_dir %>/scss/compile.scss',
                      dest: '<%= globalConfig.dist_dir %>/css/style.css'
                  },
                  {
                    src: '<%= globalConfig.build_dir %>/scss/woocommerce.scss',
                    dest: '<%= globalConfig.dist_dir %>/css/woocommerce.css'
                },
                  {
                      src: '<%= globalConfig.build_dir %>/scss/editor.scss',
                      dest: '<%= globalConfig.dist_dir %>/css/editor-style.css'
                  },
                  {
                    src: '<%= globalConfig.build_dir %>/scss/admin-style.scss',
                    dest: '<%= globalConfig.dist_dir %>/css/admin-style.css'
                }
              ]
            }
        },

        // Task to run postcss on files in <dist_dir>/css/ and apply additional processors
        postcss: {
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
              files: [
                {
                  src: '<%= globalConfig.dist_dir %>/css/style.css',
                  dest: 'style.css'
                },
                {
                  src: '<%= globalConfig.dist_dir %>/css/woocommerce.css',
                  dest: 'woocommerce.css'
                },
                {
                  src: '<%= globalConfig.dist_dir %>/css/editor-style.css',
                  dest: 'editor-style.css'
                },
                {
                  src: '<%= globalConfig.dist_dir %>/css/admin-style.css',
                  dest: 'admin-style.css'
                }
              ]
            }
        },

        // Tasks to compress and optimize images and icons
        imagemin: {
          // Runs on images saved in <build_dir>/img/ and outputs to <dist_dir>/img/
            images: {
                files: [{
                    expand: true,
                    cwd: '<%= globalConfig.build_dir %>/img/',
                    src: ['**/*.{png,jpg,JPG,JPEG,jpeg,svg,gif}'],
                    dest: '<%= globalConfig.dist_dir %>/img/'
                }]
            },
            // Runs on icons saved in <build_dir>/icons/ and outputs to <dist_dir>/icons/
            icons: {
                files: [{
                    expand: true,
                    cwd: '<%= globalConfig.build_dir %>/icons/',
                    src: ['**/*.svg'],
                    dest: '<%= globalConfig.dist_dir %>/icons/'
                }]
            }
        },

        // Put all of our minified svg icons into a sprite sheet
        svgstore: {
            options: {
                prefix: 'icon-', // This will prefix each ID
                svg: { // will add and override the the default xmlns="http://www.w3.org/2000/svg" attribute to the resulting SVG
                    viewBox : '0 0 100 100',
                    xmlns: 'http://www.w3.org/2000/svg'
                }
            },
            default: {
                files: {
                    '<%= globalConfig.dist_dir %>/icons/icons.svg': ['<%= globalConfig.dist_dir %>/icons/*.svg']
                }
            },
        },

        // Task to concatenate js files together separated by a line break
        concat: {
          options: {
                sourceMap: false,
                separator: ';\n'
          },

          site: {
            src: ['<%= globalConfig.build_dir %>/js/vendor/*.js', '<%= globalConfig.build_dir %>/js/site/*.js'],
            dest: '<%= globalConfig.dist_dir %>/js/site.js',
          },
        },

        // Task to minify javascript file(s)
        uglify: {
          options: {
            mangle: false
          },
          site: {
            files: {
                '<%= globalConfig.dist_dir %>/js/site.min.js': ['<%= globalConfig.dist_dir %>/js/site.js']
            }
          }
        },

        // Watches directories for scss file name _all and imports all partials into that file
        sass_directory_import: {
          files: {
            // The file pattern to add @imports to.
            // The name of the file is arbitrary - I like "all".
            src: ['<%= globalConfig.build_dir %>/scss/**/_all.scss']
          },
        },

        // Generates a POT file to help translators translate the theme. For most builds this won't be necessary, but it's still nice - and compliant - to bundle this with the theme
        makepot: {
          target: {
              options: {
                  type: 'wp-theme',                 // Type of project (wp-plugin or wp-theme).
                  domainPath: '/languages',         // Where to save the POT file.
                  mainFile: 'style.css'            // Main project file.
              }
          }
         },

        // Task which watches files in the working directory for changes, and runs certain tasks on detection
        watch: {
            // rerun $ build when the Gruntfile is edited
            gruntfile: {
                files: ['Gruntfile.js'],
                tasks: ['build'],
                options: {
                    event: ['changed', 'added', 'deleted']
                }
            },
            phplint: {
              options: {
                event: ['changed', 'added', 'deleted']
              },
              files: ['*.php', '**/*.php', '!node_modules/**/*.php'], // Ignore node_modules
              tasks: [ 'phplint' ]
            },
            // run 'sass_directory_import', 'sass' and 'postcss' tasks when any scss file is edited
            sass: {
                options: {
                  event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/scss/**/*.scss'],
                tasks: [ 'sass_directory_import', 'sass', 'postcss']
            },
            // Concats and uglifies javascript files on change
            concat_js: {
                options: {
                  event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/js/**/*.js'],
                tasks: ['jshint', 'concat', 'uglify']
            },
            // Cleans and minifies images when changes detected
            images: {
                options: {
                    event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/img/**/*.{png,jpg,JPG,JPEG,jpeg,svg,gif}'],
                tasks: ['clean:images', 'imagemin:images']
            },
            // Cleans, minifies and creates spritesheet of icons when changes detected
            spritesheet: {
                options: {
                    event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/icons/*.svg'],
                tasks: ['clean:icons', 'imagemin:icons', 'svgstore']
            }
        },
        // browserSync watches files defined in src, and on change reloads the browser
        browserSync: {
          bsFiles: {
              src: [
                  '<%= globalConfig.dist_dir %>/js/site.js',
                  '<%= globalConfig.dist_dir %>/img/*',
                  '<%= globalConfig.dist_dir %>/icons/*',
                  '**/*.php',
                  '**/*.html',
                  'style.css',
              ]
          },
          options: {
            watchTask: true,
            proxy: "<%= globalConfig.dev_url %>"
        }
      },


    });

    // Initialise tasks from npm
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-phplint');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-svgstore');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-sass-directory-import');
    grunt.loadNpmTasks('grunt-wp-i18n');

    grunt.registerTask('default', ['browserSync', 'watch']);
    grunt.registerTask('init', ['copy:npm', 'phplint', 'jshint', 'concat', 'uglify', 'sass_directory_import', 'sass', 'postcss', 'clean', 'imagemin', 'svgstore', 'makepot', 'browserSync', 'watch']);
    grunt.registerTask('build', ['copy:npm', 'phplint', 'jshint', 'concat', 'uglify', 'sass_directory_import', 'sass', 'postcss', 'clean', 'imagemin', 'svgstore', 'makepot' ]);

};


// TODO: Put together some basic docs - refer to Jellyfish repo, with a few tweaks
