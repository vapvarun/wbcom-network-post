module.exports = function(grunt) {
	'use strict';

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Clean build and release directories
		clean: {
			build: ['build'],
			release: ['release']
		},

		// Copy files to build directory
		copy: {
			build: {
				files: [
					{
						expand: true,
						src: [
							'**',
							'!node_modules/**',
							'!build/**',
							'!release/**',
							'!.git/**',
							'!.gitignore',
							'!package.json',
							'!package-lock.json',
							'!Gruntfile.js',
							'!.DS_Store',
							'!**/.DS_Store',
							'!.vscode/**',
							'!.idea/**',
							'!*.log',
							'!.editorconfig',
							'!.eslintrc.js',
							'!.prettierrc',
							'!composer.json',
							'!composer.lock',
							'!phpcs.xml',
							'!phpunit.xml',
							'!tests/**',
							'!bin/**'
						],
						dest: 'build/wbcom-network-post/'
					}
				]
			}
		},

		// Compress build directory into zip file
		compress: {
			release: {
				options: {
					archive: 'release/wbcom-network-post-v<%= pkg.version %>.zip',
					mode: 'zip'
				},
				files: [
					{
						expand: true,
						cwd: 'build/',
						src: ['**/*'],
						dest: '/'
					}
				]
			}
		}
	});

	// Load tasks
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-compress');

	// Register tasks
	grunt.registerTask('build', [
		'clean:build',
		'copy:build'
	]);

	grunt.registerTask('zip', [
		'build',
		'clean:release',
		'compress:release',
		'clean:build'
	]);

	grunt.registerTask('default', ['zip']);
};
