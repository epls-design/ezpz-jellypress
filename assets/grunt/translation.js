// Important to use `grunt` as an argument in the function
module.exports = function (grunt) {

  // Configure Makepot task
  // Generates a POT file to help translators translate the theme.
  // For most bespoke client builds this won't be necessary, but it's still nice - and compliant - to bundle this with the theme
  grunt.config('makepot', {
    target: {
      options: {
        type: 'wp-themes', // Type of project (wp-plugin or wp-theme).
        domainPath: '/languages', // Where to save the POT file.
        mainFile: 'style.css', // Main project file.
        updateTimestamp: false // Whether the POT-Creation-Date should be updated without other changes.
      }
    }
  });

};
