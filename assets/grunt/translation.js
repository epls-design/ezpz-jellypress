// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Makepot task
  // Generates a POT file to help translators translate the theme.
  // For most bespoke client builds this won't be necessary, but it's still nice - and compliant - to bundle this with the theme
  grunt.config('makepot', {
    target: {
      options: {
        type: 'wp-theme', // Type of project (wp-plugin or wp-theme).
        domainPath: '/languages', // Where to save the POT file.
        exclude: [
          'node_modules/**/*.*',
          'inc/tgm-plugin-activation.php',
          'inc/plugins.php',
        ],
        potFilename: 'jellypress.pot',
        potHeaders: {
          poedit: true,
          'x-poedit-keywordslist': true,
          'Report-Msgid-Bugs-To': '<%= pkg.bugs.url %>',
          'X-Poedit-Country': 'United Kingdom',
          'Last-Translator': '<%= pkg.author.name %> <<%= pkg.author.email %>>',
          'Language-Team': '<%= pkg.author.name %> <<%= pkg.author.email %>>'
        },
        mainFile: 'style.css', // Main project file.
        updateTimestamp: false, // Whether the POT-Creation-Date should be updated without other changes.
      }
    }
  });

  // Configure Addtextdomain task
  // Automatically appends textdomain to theme files
  grunt.config('addtextdomain', {
    options: {
      textdomain: '<%= opts.text_domain %>',
      updateDomains: true
    },
    php: {
      files: {
        src: [
          '*.php',
          '**/*.php',
          '!node_modules/*',
        ]
      }
    }
  });

};
