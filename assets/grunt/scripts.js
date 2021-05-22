// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {
  // Configure Concat task through config.merge as this task is used across multiple partials
  // Task to concatenate js files together separated by a line break
  grunt.config.merge({
    concat: {
      site: {
        options: {
          sourceMap: false,
          separator: ";\r\n",
        },
        src: [
          "<%= opts.build_dir %>/js/vendor/*.js",
          "<%= opts.build_dir %>/js/site/*.js",
          "template-parts/**/*.js",
        ],
        dest: "<%= opts.dist_dir %>/js/site.js",
      },
    },
  });

  // Configure Uglify task through config.merge as this task is used across multiple partials
  // Minifies javascript file(s)
  grunt.config.merge({
    uglify: {
      site: {
        options: {
          mangle: false,
          banner: "<%= opts.banner %>",
        },
        src: "<%= opts.dist_dir %>/js/site.js",
        dest: "<%= opts.dist_dir %>/js/site.min.js",
      },
    },
  });

  // Configure ESlint task through config.merge as this task is used across multiple partials
  // Validates Javascript files
  grunt.config.merge({
    eslint: {
      site: [
        "<%= opts.build_dir %>/js/site/**/*.js", // Validates javascript files in js/site only (doesn't validate vendor JS)
      ],
    },
  });

  // Configure Watch task through config.merge as this task is used across multiple partials
  // Watches javascript files for changes and then runs tasks accordingly
  grunt.config.merge({
    watch: {
      scripts: {
        options: {
          event: ["changed", "added", "deleted"],
        },
        files: ["<%= opts.build_dir %>/js/**/*.js", "template-parts/**/*.js"],
        tasks: ["newer:eslint:site", "concat", "uglify"],
      },
    },
  });
};
