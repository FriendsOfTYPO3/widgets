var path = require('path'),
  sass = require('node-sass');

module.exports = function (grunt) {
  var widgets = grunt.config.get('widgets'),
    watchTask = {};

  for (let widget in grunt.config.get('widgets')) {
    if (widgets.hasOwnProperty(widget)) {
      watchTask[widget] = {
        files: grunt.config.get('sassDirectory') + '/**/*.scss',
        tasks: ['sass:' + widget, 'postcss:' + widget, 'bsReload:css'],
        options: {
          spawn: false,
          event: 'changed'
        }
      };
    }
  }

  return watchTask;
};
