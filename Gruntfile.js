
'use strict';

module.exports = function(grunt)
{

    grunt.initConfig({});

    // PHP Code Linter
    grunt.loadNpmTasks('grunt-phplint');
    grunt.config('phplint', {
        options: {
            phpArgs: {
                '-lf': null
            }
        },
        src: [
            'src/*.php',
            'src/Storages/*.php'
        ],
        tests: 'tests/*.php'
    });

    // PHP Coding Standards Fixer
	grunt.loadNpmTasks('grunt-php-cs-fixer');
    grunt.config('phpcsfixer', {
        src: {
            dir: 'src/'
        },
        tests: {
            dir: 'tests/'
        }
	});

    // Testing with PHPUnit
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.config('phpunit', {
        tests: {
            dir: 'tests/'
        },
        options: {
            bootstrap: 'tests/bootstrap.php',
            colors: true,
            stopOnError: true,
            stopOnFailure: true
        }
    });

    // Watch src/ and test/ files and run tasks automatically
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.config('watch', {
        src: {
            files: [
                'src/*.php',
                'src/Storages/*.php',
                'tests/*.php'
            ],
            tasks: ['php']
        }
    });

    // Notify errors and warnings
    grunt.loadNpmTasks('grunt-notify');

    // Task declarations
    grunt.registerTask('default', ['php', 'watch']);
    grunt.registerTask('php', [
        'phplint',
        'phpcsfixer',
        'phpunit'
    ]);
    grunt.registerTask('test', ['phpunit']);

};
