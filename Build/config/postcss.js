var path = require('path'),
  sass = require('node-sass');

module.exports = function (grunt) {
  var widgets = grunt.config.get('widgets'),
    cssFilePaths = [],
    postCssTasks = {};

  for (let widget in grunt.config.get('widgets')) {
    if (widgets.hasOwnProperty(widget)) {
      widgets[widget].stylesheets.forEach(function (fileName) {
        var css = grunt.config.get('cssDirectory') + '/' + fileName + '.css';

        widgets[widget].cssFilePaths.push({
          src: css,
          dest: css
        });
      });

      cssFilePaths = cssFilePaths.concat(widgets[widget].cssFilePaths);

      postCssTasks[widget] = {
        options: {
          map: true,
          processors: [
            require('autoprefixer')({
              cascade: false,
              remove: false
            })
          ]
        },
        files: widgets[widget].cssFilePaths
      };
    }
  }

  postCssTasks['build'] = {
    options: {
      map: false,
      processors: [
        require('autoprefixer')({
          cascade: false,
          remove: false
        })
      ]
    },
    files: cssFilePaths
  };

  return postCssTasks;
};
