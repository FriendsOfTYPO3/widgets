var path = require('path'),
  sass = require('node-sass');

module.exports = function (grunt) {
  var widgets = grunt.config.get('widgets'),
    scssFilePaths = [],
    sassTasks = {};

  for (let widget in grunt.config.get('widgets')) {
    if (widgets.hasOwnProperty(widget)) {
      widgets[widget].stylesheets.forEach(function (fileName) {
        var css = grunt.config.get('cssDirectory') + '/' + fileName + '.css',
            scss = grunt.config.get('sassDirectory') + '/' + fileName + '.scss';

        widgets[widget].scssFilePaths.push({
          src: scss,
          dest: css
        });
      });

      scssFilePaths = scssFilePaths.concat(widgets[widget].scssFilePaths);

      sassTasks[widget] = {
        options: {
          implementation: sass,
          outputStyle: 'nested',
          sourceMap: true
        },
        files: widgets[widget].scssFilePaths
      };
    }
  }

  sassTasks['build'] = {
    options: {
      implementation: sass,
      outputStyle: 'compressed',
      sourceMap: false
    },
    files: scssFilePaths
  };

  return sassTasks;
};
