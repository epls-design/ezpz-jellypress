module.exports = function (grunt) {

    // Set up global variables
    var globalConfig = {
        build_dir: 'build',
        dist_dir: 'assets',
        dev_url: 'http://jellypress.local',
    };

    // Project configuration
    grunt.initConfig({

        globalConfig: globalConfig,

        // Remove old images and icons
        clean: {
            images: ['<%= globalConfig.dist_dir %>/img/*'],
            icons:  ['<%= globalConfig.dist_dir %>/icons/*']
        },

        copy: {
            // Copy scripts from node_modules
            npm: {
                files: [
                    // NOTE: Add any site-specific files here
                ],
            }
        },

        // Define which sass files should be compiled
        sass: {
            options: {
                sourceMap: true
            },
            dist: {
                files: [
                  {
                      src: '<%= globalConfig.build_dir %>/scss/compile.scss',
                      dest: '<%= globalConfig.dist_dir %>/css/style.css'
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

        // Run postcss on files in dist_dir/css/ to apply autoprefix and minify
        postcss: {
            options: {
                map: true,
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

        // Compress images in build_dir/img/, output to dist_dir/img
        imagemin: {
            images: {
                files: [{
                    expand: true,
                    cwd: '<%= globalConfig.build_dir %>/img/',
                    src: ['**/*.{png,jpg,JPG,JPEG,jpeg,svg,gif}'],
                    dest: '<%= globalConfig.dist_dir %>/img/'
                }]
            },
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
                svg: { // will add and overide the the default xmlns="http://www.w3.org/2000/svg" attribute to the resulting SVG
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

        watch: {

            // rerun $ grunt when the Gruntfile is edited
            gruntfile: {
                files: ['Gruntfile.js'],
                tasks: ['build'],
                options: {
                    event: ['changed', 'added', 'deleted']
                }

            },

            // run 'sass' and 'postcss' tasks when any scss file is edited
            sass: {
                options: {
                  event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/scss/**/*.scss'],
                tasks: [ 'sass', 'postcss']
            },

            concat_js: {
                options: {
                  event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/js/**/*.js'],
                tasks: ['concat', 'uglify']
            },

            images: {
                options: {
                    event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/img/**/*.{png,jpg,JPG,JPEG,jpeg,svg,gif}'],
                tasks: ['clean:images', 'imagemin:images']
            },

            spritesheet: {
                options: {
                    event: ['changed', 'added', 'deleted']
                },
                files: ['<%= globalConfig.build_dir %>/icons/*.svg'],
                tasks: ['clean:icons', 'imagemin:icons', 'svgstore']
            }
        },
        browserSync: {
          bsFiles: {
              src: [
                  '<%= globalConfig.dist_dir %>/css/style.min.css',
                  '<%= globalConfig.dist_dir %>/js/site.js',
                  '<%= globalConfig.dist_dir %>/img/*',
                  '*.php',
                  '*.html'
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
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-svgstore');
    grunt.loadNpmTasks('grunt-browser-sync');

    grunt.registerTask('default', ['browserSync', 'watch']);
    grunt.registerTask('build', ['copy:npm', 'concat', 'uglify', 'sass', 'postcss', 'clean', 'imagemin', 'svgstore', 'watch']);

};
