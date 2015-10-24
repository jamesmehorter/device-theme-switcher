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
				'assets/js/src/**/*.js'
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
		sass:   {
			all: {
				files: {
					'assets/css/device-theme-switcher-admin-styles.css': 'assets/css/sass/device-theme-switcher-admin-styles.scss'
				},
				options: {
					update: true,
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
				text_domain: 'device-theme-switcher',
				'package_name': 'device-theme-switcher',
				dest: 'languages/',
				keywords: [ //WordPress localisation functions
					'__',
					'_e',
					'_x',
					'_n',
					'_ex',
					'_nx',
					'esc_attr__',
					'esc_attr_e',
					'esc_attr_x',
					'esc_html__',
					'esc_html_e',
					'esc_html_x',
					'_nx_noop'
				]
			},
			files:{
				src:  [ '**/*.php', '!**/node_modules/**' ], //Parse all php files not in node_modules
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
			css: {
				files: ['assets/css/sass/*.scss'],
				tasks: ['sass'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/js/source/**/*.js', 'assets/js/vendor/**/*.js'],
				tasks: ['jshint', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		}
	});

	// Default task.
	grunt.registerTask('default', ['jshint', 'uglify', 'sass', 'po2mo', 'pot', 'checktextdomain']);

	grunt.util.linefeed = '\n';
};