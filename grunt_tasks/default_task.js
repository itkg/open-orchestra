module.exports = function(grunt) {
  grunt.registerTask(
    'default',
    [
      'clean:built',
      'clean:symlinks',
      'commands:assets_install',
      'symlink',
      'css',
      'javascript',
      'javascriptProd',
      'commands:assetic_dump'
    ]
  );
};
