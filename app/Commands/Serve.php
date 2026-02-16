<?php

namespace App\Commands;

use CodeIgniter\Commands\Server\Serve as CoreServe;

class Serve extends CoreServe
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'serve';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Launches the CodeIgniter PHP-Development Server with Tailwind/Esbuild watchers.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'serve';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--php'  => 'The PHP Binary [default: "PHP_BINARY"]',
        '--host' => 'The HTTP Host [default: "localhost"]',
        '--port' => 'The HTTP Host Port [default: "8080"]',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        // Start npm run dev in background (Windows)
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            \CodeIgniter\CLI\CLI::write('Starting Tailwind & Esbuild watchers in background...', 'yellow');
            pclose(popen('start /B npm run dev', 'r'));
        } else {
            // For Linux/Mac (just in case, though user is on Windows)
            \CodeIgniter\CLI\CLI::write('Starting Tailwind & Esbuild watchers in background...', 'yellow');
            exec('npm run dev > /dev/null 2>&1 &');
        }

        // Run the original serve command
        parent::run($params);
    }
}
