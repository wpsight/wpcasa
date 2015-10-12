module.exports = function(grunt) {

  // Load multiple grunt tasks using globbing patterns
  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    wp_deploy: {
      deploy: { 
        options: {
          plugin_slug: 'wpcasa',
          svn_user: 'wpsight',  
          build_dir: '<%= pkg.name %>', //relative path to your build directory
          assets_dir: 'wp-assets' //relative path to your assets directory (optional).
        },
      }
    },
  });

};