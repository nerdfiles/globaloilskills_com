module.exports = function(grunt) {

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

    coffee: {
      "coffee_to_js": {
        options: {
          bare: true,
          sourceMap: true
        },
        expand: true,
        flatten: false,
        //cwd: "../dist",
        src: ["../js/**/*.coffee"],
        dest: '../js',
        ext: ".js"
      }
    },

		// chech our JS
		jshint: {
			options: {
				"bitwise": true,
				"browser": true,
				"curly": true,
				"eqeqeq": true,
				"eqnull": true,
				"esnext": true,
				"immed": true,
				"jquery": true,
				"latedef": true,
				"newcap": true,
				"noarg": true,
				"node": true,
				"strict": false,
				"trailing": true,
				"undef": true,
				"globals": {
					"jQuery": true,
					"alert": true,
					"Framework7": true,
					"Dom7": true
				}
			},
      coffee: [
				'../js/scripts.coffee'
      ],
			all: [
				'gruntfile.js',
				'../js/scripts.js'
			]
		},

		// concat and minify our JS
		uglify: {
			dist: {
				files: {
					'../js/scripts.min.js': [
						'../js/scripts.js'
					]
				}
			}
		},

		// compile your sass
		sass: {
			dev: {
				options: {
					style: 'expanded'
				},
				src: ['../scss/style.scss'],
				dest: '../style.css'
			},
			prod: {
				options: {
					style: 'compressed'
				},
				src: ['../scss/style.scss'],
				dest: '../style.css'
			},
			editorstyles: {
				options: {
					style: 'expanded'
				},
				src: ['../scss/wp-editor-style.scss'],
				dest: '../css/wp-editor-style.css'
			}
		},

		// watch for changes
		watch: {
			scss: {
				files: ['../scss/**/*.scss'],
				tasks: [
					'sass:dev',
					'sass:editorstyles',
					'notify:scss'
				]
			},
			js: {
				files: [
          '<%= jshint.coffee %>'
				],
				tasks: [
          'coffee',
					'jshint:all',
					'uglify',
					'notify:js'
				]
			}
		},

    cordovacli: {
        options: {
            path: 'myHybridAppFolder'
        },
        cordova: {
            options: {
                command: ['create','platform','plugin','build'],
                platforms: ['ios','android'],
                plugins: ['device','dialogs'],
                path: 'myHybridAppFolder',
                id: 'io.cordova.hellocordova',
                name: 'HelloCordova'
            }
        },
        create: {
            options: {
                command: 'create',
                id: 'com.myHybridApp',
                name: 'myHybridApp'
            }
        },
        add_platforms: {
            options: {
                command: 'platform',
                action: 'add',
                platforms: ['ios', 'android']
            }
        },
        add_plugins: {
            options: {
                command: 'plugin',
                action: 'add',
                plugins: [
                    'battery-status',
                    'camera',
                    'console',
                    'contacts',
                    'device',
                    'device-motion',
                    'device-orientation',
                    'dialogs',
                    'file',
                    'geolocation',
                    'globalization',
                    'inappbrowser',
                    'media',
                    'media-capture',
                    'network-information',
                    'splashscreen',
                    'vibration'
                ]
            }
        },
        build_ios: {
            options: {
                command: 'build',
                platforms: ['ios']
            }
        },
        build_android: {
            options: {
                command: 'build',
                platforms: ['android']
            }
        },
        emulate_android: {
            options: {
                command: 'emulate',
                platforms: ['android'],
                args: ['--target','Nexus5']
            }
        }
    },

		// check your php
		phpcs: {
			application: {
				dir: '../*.php'
			},
			options: {
				bin: '/usr/bin/phpcs'
			}
		},

		// notify cross-OS
		notify: {
			scss: {
				options: {
					title: 'Grunt, grunt!',
					message: 'SCSS is all gravy'
				}
			},
			js: {
				options: {
					title: 'Grunt, grunt!',
					message: 'JS is all good'
				}
			},
			dist: {
				options: {
					title: 'Grunt, grunt!',
					message: 'Theme ready for production'
				}
			}
		},

		clean: {
			dist: {
				src: ['../dist'],
				options: {
					force: true
				}
			}
		},

    exec: {
      test: "npm test"
    },

		copyto: {
			dist: {
				files: [
					{cwd: '../', src: ['**/*'], dest: '../dist/'}
				],
				options: {
					ignore: [
						'../dist{,/**/*}',
						'../doc{,/**/*}',
						'../grunt{,/**/*}',
						'../scss{,/**/*}'
					]
				}
			}
		}
	});

	// Load NPM's via matchdep
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);


  //grunt.registerTask "test", ["exec:test"]
  grunt.registerTask("lint", [
    "exec:jshint",
    "exec:csslint"
  ]);

	// Development task
	grunt.registerTask('default', [
		'jshint:all',
		'uglify',
		'sass:dev',
		'sass:editorstyles'
	]);

	// Production task
	grunt.registerTask('dist', function() {
		grunt.task.run([
			'jshint:all',
			'uglify',
			'sass:prod',
			'sass:editorstyles',
			'clean:dist',
			'copyto:dist',
			'notify:dist'
		]);
	});

  grunt.registerTask('compile', [
    'coffee',
    'jshint:all',
    'uglify',
    'clean:dist',
    'copyto:dist',
    'notify:dist'
  ]);
};
