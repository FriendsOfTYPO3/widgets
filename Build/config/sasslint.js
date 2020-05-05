module.exports = function (grunt) {
  return {
    build: {
      options: {
        configFile: '../.sass-lint.yml',
        formatter:
          'junit',
        outputFile:
          '../log/sass-lint.xml'
      },
      files: [
        {src: grunt.config.get('sassDirectory') + '/**/*.scss'}
      ]
    }
  }
};
