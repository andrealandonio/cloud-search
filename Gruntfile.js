module.exports = function (grunt) {
	'use strict';

    /**
     * LOAD TASKS
     */

    require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );
	
    /**
     * CONFIGURATION
     */
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jshint: {
            dist: [
                'js/*.js'
            ],
            options: {
                "-W041": false,
                curly: true,
                eqeqeq: false,
                immed: true,
                latedef: false,
                newcap: true,
                noarg: true,
                sub: true,
                undef: false,
                boss: true,
                eqnull: false,
                browser: true,
                jquery: true,
                globals: {
                    require: true,
                    define: true,
                    requirejs: true,
                    describe: true,
                    expect: true,
                    it: true
                }
            }
        },
        clean: {
            options: {
                force: true
            },
            dist: [
                'css/dist/cloud-search.min.css',
                'css/dist/cloud-search-suggest.min.css',
                'css/dist/images/',
                'js/dist/cloud-search.min.js',
                'js/dist/cloud-search-admin-help.min.js',
                'js/dist/cloud-search-admin-manage.min.js',
                'js/dist/cloud-search-admin-settings.min.js',
                'js/dist/cloud-search-locale-en-US.min.js',
                'js/dist/cloud-search-locale-it-IT.min.js',
                'js/dist/cloud-search-suggest.min.js',
            ],
			deploy: [
				'deploy'
			]
        },
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'css/images/',
                        src: ['**'],
                        dest: 'css/dist/images/'
                    }
                ]
            },
			deploy: {
				files: [{
					expand: true,
					src: [
						'**',
						'!**/assets/**',
						'!**/bin/**',
						'!**/deploy/**',
						'!**/sass/**',
						'!**/node_modules/**',
						'!**/tests/**',
						'!config.rb',
						'!Gruntfile.js',
						'!package.json',
						'!phpunit.xml',
						'!README.md'
					],
					dest: 'deploy/'
				}],
			}
        },
        cssmin: {
            dist: {
                files: [
                    {
                        src: 'css/cloud-search.css',
                        dest: 'css/dist/cloud-search.min.css'
                    },
                    {
                        src: 'css/cloud-search-suggest.css',
                        dest: 'css/dist/cloud-search-suggest.min.css'
                    }
                ]
            }
        },
		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				},
				options: {
					screenshot_url: 'https://ps.w.org/cloud-search/trunk/{screenshot}.jpg',
				}
			}
		},
		wp_deploy: {
			deploy: {
				options: {
					plugin_slug: 'cloud-search',
					build_dir: 'deploy',
					assets_dir: 'assets'
				},
			}
		},
        uglify: {
            options: {
                preserveComments: false
            },
            dist: {
                files: [
                    {
                        src: 'js/cloud-search.js',
                        dest: 'js/dist/cloud-search.min.js'
                    },
                    {
                        src: 'js/cloud-search-admin-help.js',
                        dest: 'js/dist/cloud-search-admin-help.min.js'
                    },
                    {
                        src: 'js/cloud-search-admin-manage.js',
                        dest: 'js/dist/cloud-search-admin-manage.min.js'
                    },
                    {
                        src: 'js/cloud-search-admin-settings.js',
                        dest: 'js/dist/cloud-search-admin-settings.min.js'
                    },
                    {
                        src: 'js/cloud-search-locale-en-US.js',
                        dest: 'js/dist/cloud-search-locale-en-US.min.js'
                    },
                    {
                        src: 'js/cloud-search-locale-it-IT.js',
                        dest: 'js/dist/cloud-search-locale-it-IT.min.js'
                    },
                    {
                        src: 'js/cloud-search-suggest.js',
                        dest: 'js/dist/cloud-search-suggest.min.js'
                    }
                ]
            }
        }
    });

    /**
     * BUILD FILES
     */
    grunt.registerTask('build', [
        'check',
        'clean:dist',
        'copy:dist',
        'uglify',
        'cssmin'
    ]);

    /**
     * CHECK SCRIPTS FILES
     */
    grunt.registerTask('check', [
        'jshint'
    ]);
	
    /**
     * BUILD GIT README FILE AND MAYBE SOME SASS LATER?
     */
	grunt.registerTask( 'default', [
		'wp_readme_to_markdown' 
	]);
	
    /**
     * DEPLOY TO WORDPRESS
     */
	grunt.registerTask('deploy', [
        'check',
        'clean:dist',
        'copy:dist',
        'uglify',
        'cssmin',
		'copy:deploy', 
		'wp_deploy:deploy',
		'clean:deploy'
	]);

};
