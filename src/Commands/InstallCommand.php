<?php

namespace Pvtl\VoyagerSliders\Commands;

use Pvtl\VoyagerSliders\Providers\VoyagerSlidersServiceProvider;
use TCG\Voyager\Traits\Seedable;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__ . '/../../database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'voyager-sliders:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Voyager Sliders package';

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd() . '/composer.phar')) {
            return '"' . PHP_BINARY . '" ' . getcwd() . '/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Publishing Sliders assets, database, and config files');
        $this->call('vendor:publish', ['--provider' => VoyagerSlidersServiceProvider::class]);

        $this->info('Dumping the autoloaded files and reloading all new files');
        $composer = $this->findComposer();
        $process = new Process($composer . ' dump-autoload');
        $process->setTimeout(null); // Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->mustRun();

        $this->info('Migrating the database tables into your application');
        $this->call('migrate');

        $this->info('Seeding data into the database');
        $this->seed('SlidersDatabaseSeeder');

        $this->info('Successfully installed Voyager Sliders! Enjoy');
    }
}
