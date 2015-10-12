module.exports = function(grunt) {

  // Load multiple grunt tasks using globbing patterns
  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    compress: {
      main: {
        options: {
          archive: '<%= pkg.name %>.<%= pkg.version %>.zip'
        },
        files: [
          {src: ['<%= pkg.name %>/**'], dest: 'build/'}, // includes files in path and its subdirs
        ]
      }
    }
  });

  grunt.registerTask( 'build', [ 'compress' ] );

};