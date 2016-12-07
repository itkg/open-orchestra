module.exports = function(grunt) {
  grunt.registerTask(
    'javascript',
    'Main project task to generate javascripts',
    [
      'commands:routing_dump',
      'commands:translation_dump',
      'babel:config',
      'babel:es6',
      'browserify:config',
      'browserify',
      'template:compile',
      'jst',
      'navigation:compile',
      'concat:lib_js',
      'concat:orchestra_js',
      'concat:media_js',
      'concat:all_js'
    ]
  );
};
