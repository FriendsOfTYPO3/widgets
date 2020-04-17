/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

module.exports = function (grunt) {

  const sass = require('node-sass');

  /**
   * Grunt stylefmt task
   */
  grunt.registerMultiTask('formatsass', 'Grunt task for stylefmt', function () {
    var options = this.options(),
      done = this.async(),
      stylefmt = require('stylefmt'),
      scss = require('postcss-scss'),
      files = this.filesSrc.filter(function (file) {
        return grunt.file.isFile(file);
      }),
      counter = 0;
    this.files.forEach(function (file) {
      file.src.filter(function (filepath) {
        var content = grunt.file.read(filepath);
        var settings = {
          from: filepath,
          syntax: scss
        };
        stylefmt.process(content, settings).then(function (result) {
          grunt.file.write(file.dest, result.css);
          grunt.log.success('Source file "' + filepath + '" was processed.');
          counter++;
          if (counter >= files.length) done(true);
        });
      });
    });
  });

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    paths: {
      sources: 'Sources/',
      sass: '../Resources/Private/Sass/',
      css: '../Resources/Public/Css/',
    },
    stylelint: {
      options: {
        configFile: '.stylelintrc',
      },
      sass: ['<%= paths.sass %>**/*.scss']
    },
    formatsass: {
      sass: {
        files: [{
          expand: true,
          cwd: '<%= paths.sass %>',
          src: ['**/*.scss'],
          dest: '<%= paths.sass %>'
        }]
      }
    },
    sass: {
      options: {
        implementation: sass,
        outputStyle: 'expanded',
        precision: 8,
      },
      sass: {
        files: {
          "<%= paths.css %>extendedListWidget.css": "<%= paths.sass %>extendedListWidget.scss",
          "<%= paths.css %>reportsWidget.css": "<%= paths.sass %>reportsWidget.scss"
        }
      },
    },
    postcss: {
      options: {
        map: false,
        processors: [
          require('autoprefixer')(),
          require('postcss-clean')({
            rebase: false,
            level: {
              1: {
                specialComments: 0
              }
            }
          }),
        ]
      },
      extendedListWidget: {
        src: '<%= paths.css %>/*.css'
      },
    },
    watch: {
      options: {
        livereload: true
      },
      sass: {
        files: '<%= paths.sass %>**/*.scss',
        tasks: 'css'
      },
    },
    newer: {
      options: {
        cache: './.cache/grunt-newer/'
      }
    },
  });

  // Register tasks
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-exec');
  grunt.loadNpmTasks('grunt-stylelint');
  grunt.loadNpmTasks('grunt-newer');

  /**
   * grunt default task
   *
   * call "$ grunt"
   *
   * this will trigger the CSS build
   */
  grunt.registerTask('default', ['css']);

  /**
   * grunt lint
   *
   * call "$ grunt lint"
   *
   * this task does the following things:
   * - stylelint
   */
  grunt.registerTask('lint', ['stylelint']);

  /**
   * grunt format
   *
   * call "$ grunt format"
   *
   * this task does the following things:
   * - formatsass
   * - lint
   */
  grunt.registerTask('format', ['formatsass', 'stylelint']);

  /**
   * grunt css task
   *
   * call "$ grunt css"
   *
   * this task does the following things:
   * - formatsass
   * - sass
   * - postcss
   */
  grunt.registerTask('css', ['formatsass', 'newer:sass', 'newer:postcss']);

  /**
   * grunt clear-build task
   *
   * call "$ grunt clear-build"
   *
   * Removes all build-related assets, e.g. cache and built files
   */
  grunt.registerTask('clear-build', function () {
    grunt.option('force');
    grunt.file.delete('.cache');
  });

  /**
   * grunt build task
   *
   * call "$ grunt build"
   *
   * this task does the following things:
   * - execute update task
   * - compile sass files
   */
  grunt.registerTask('build', ['clear-build', 'format', 'css']);
};
