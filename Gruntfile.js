/*globals require */

// Gruntfile.js
module.exports = function(grunt) {

	// Load all grunt tasks matching the `grunt-*` pattern
	require('load-grunt-tasks')(grunt);

	// Project configuration
	grunt.initConfig({
		pkg:    grunt.file.readJSON('package.json'),
		jshint: {
			all: [
				'Gruntfile.js',
				'assets/js/src/**/*.js',
				'assets/js/test/**/*.js'
			],
			options: {
				curly:   true,
				eqeqeq:  true,
				immed:   true,
				latedef: true,
				newcap:  true,
				noarg:   true,
				sub:     true,
				undef:   true,
				boss:    true,
				eqnull:  true,
				globals: {
					exports: true,
					module:  false
				}
			}
		},
		uglify: {
			all: {
				files: {
					'assets/js/device-theme-switcher.min.js': ['assets/js/source/device-theme-switcher.js']
				},
				options: {
					banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
						' * <%= pkg.homepage %>\n' +
						' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
						' * Licensed GPLv2+' +
						' */\n',
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		compass: {
			dist: {
				options: {
					environment             : 'production',
					sassDir                 : 'assets/css/sass',
					cssDir                  : 'assets/css',
					imagesDir               : "assets/images/src/",
					generatedImagesDir      : "assets/images/sprites/",
					httpGeneratedImagesPath : "../../assets/images/sprites/"
				},
				development: {
					outputStyle: 'expanded'
				},
				production: {
					outputStyle: 'compressed'
				}
			}
		},
		watch:  {
			sass: {
				files: ['assets/css/sass/*.scss'],
				tasks: ['compass'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/js/source/**/*.js', 'assets/js/vendor/**/*.js'],
				tasks: ['concat', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		}
	});

	// Default task.
	grunt.registerTask('default', ['jshint', 'uglify', 'compass']);

	grunt.util.linefeed = '\n';
};