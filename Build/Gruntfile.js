/*global module, require */

module.exports = function (grunt) {
  'use strict';

  const fs = require('fs'),
    path = require('path'),
    url = require('url'),
    extDirectory = path.normalize('../../'),
    projectDirectory = path.join(extDirectory, 'widgets');
  var cssFilePaths = [];

  require('jit-grunt')(grunt, {
    sasslint: 'grunt-sass-lint',
  });

  var config = {
    pkg: grunt.file.readJSON('package.json'),
    projectDirectory: projectDirectory,
    extDirectory: extDirectory,
    cssDirectory: path.join(projectDirectory, 'Resources/Public/Css'),
    sassDirectory: path.join(projectDirectory, 'Resources/Private/Sass'),
    widgets: {
      'extendedListWidget': {
        stylesheets: ['extendedListWidget'],
        scssFilePaths: [],
        cssFilePaths: []
      },
      'reportsWidget': {
        stylesheets: ['reportsWidget'],
        scssFilePaths: [],
        cssFilePaths: []
      },
    },
    scssFilePaths: [],
  };

  for (let widget in config.widgets) {
    config.widgets[widget].stylesheets.forEach(function (fileName) {
      var css = config.cssDirectory + '/' + fileName + '.css';

      config.widgets[widget].cssFilePaths.push({
        src: css,
        dest: css
      });
    });

    cssFilePaths = cssFilePaths.concat(config.widgets[widget].cssFilePaths);
  }

  grunt.initConfig(config);

  // Autoload all config files
  fs.readdirSync('./config/').forEach(function (file) {
    var moduleConfig = require('./config/' + file);

    if (typeof moduleConfig === 'function') {
      grunt.config.set(path.parse(file).name, moduleConfig(grunt));
    } else {
      grunt.config.set(path.parse(file).name, moduleConfig);
    }
  });

  grunt.registerTask('lint', 'sasslint:dev');
  for (let widget in config.widgets) {
    grunt.registerTask(widget, ['sass:' + widget, 'postcss:' + widget, 'watch:' + widget, 'cssmin:build']);
  }
  grunt.registerTask('build', ['sasslint:build', 'sass:build', 'postcss:build', 'cssmin:build']);
};
