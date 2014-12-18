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
					'assets/js/device-theme-switcher-admin-scripts.min.js': ['assets/js/source/device-theme-switcher-admin-scripts.js']
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
		po2mo: {
			files: {
				src: 'languages/*.po',
				expand: true
			}
		},
		pot: {
			options:{
				text_domain: 'device-theme-switcher', //Your text domain. Produces my-text-domain.pot
				dest: 'languages/', //directory to place the pot file
				keywords: [ //WordPress localisation functions
					'__:1',
					'_e:1',
					'_x:1,2c',
					'esc_html__:1',
					'esc_html_e:1',
					'esc_html_x:1,2c',
					'esc_attr__:1',
					'esc_attr_e:1',
					'esc_attr_x:1,2c',
					'_ex:1,2c',
					'_n:1,2',
					'_nx:1,2,4c',
					'_n_noop:1,2',
					'_nx_noop:1,2,3c'
				]
			},
			files:{
				src:  [ '**/*.php', '!**/node_modules/**' ], //Parse all php files
				expand: true
			}
		},
		checktextdomain: {
			options:{
				text_domain: 'device-theme-switcher',
				correct_domain: true, //Will correct missing/variable domains
				keywords: [ //WordPress localisation functions
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src:  [ '**/*.php', '!**/node_modules/**' ], //All php files except those within node_modules
				expand: true
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
	grunt.registerTask('default', ['jshint', 'uglify', 'compass', 'po2mo', 'pot', 'checktextdomain']);

	grunt.util.linefeed = '\n';
};