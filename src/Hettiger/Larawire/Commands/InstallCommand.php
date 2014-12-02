<?php namespace Hettiger\Larawire\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'larawire:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Merge Laravel with ProcessWire';

	/**
	 * Create a new command instance.
	 *
	 * @return InstallCommand
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->publishProcessWireCore();
        $this->moveLaravelsIndexFile();
        $this->publishProcessWirePublicFiles();
        $this->publishSite();
        $this->createAssetsDir();
        $this->showInstructions();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

    /**
     * Copy a directory recursively and optionally use a extension whitelist
     *
     * @param string $source
     * @param string $dest
     * @param array $allowedExtensions
     * @return bool|null
     */
    protected function recursiveCopy($source, $dest, $allowedExtensions = [])
    {
        // Check for symlinks
        if ( is_link($source) )
        {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            // Filter files not matching the extension whitelist
            if ( ! empty($allowedExtensions) and ! in_array(pathinfo($source, PATHINFO_EXTENSION), $allowedExtensions) )
            {
                return null;
            }

            return copy($source, $dest);
        }

        // Make destination directory
        if ( ! is_dir($dest) )
        {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);

        while ( false !== $entry = $dir->read() )
        {
            // Skip pointers
            if ( $entry == '.' || $entry == '..' )
            {
                continue;
            }

            // Deep copy directories
            $this->recursiveCopy("{$source}/{$entry}", "{$dest}/{$entry}", $allowedExtensions);
        }

        // Clean up
        $dir->close();

        return true;
    }

    /**
     * Check if a directory exists and error, info + abort if it does
     *
     * @param string $dir
     * @return void
     */
    protected function abortOnExistingDir($dir)
    {
        if ( is_dir($dir) )
        {
            $this->error("Path \"{$dir}\" does already exist. Aborting ...");
            $this->info("Remove/Rename this directory and try again.");
            die();
        }
    }

    /**
     * Publish ProcessWires core to the public folder.
     *
     * @return void
     */
    protected function publishProcessWireCore()
    {
        $this->info('Publishing ProcessWire\'s Core Files to "./wire".');

        $dest = public_path() . '/wire';
        $this->abortOnExistingDir($dest);

        $this->recursiveCopy(__DIR__ . '/../../../../vendor/ryancramerdesign/processwire/wire', $dest);
    }

    /**
     * Move laravels public/index.php file to public/laravel.php
     *
     * @return void
     */
    protected function moveLaravelsIndexFile()
    {
        $this->info('Moving "public/index.php" to "public/laravel.php".');

        $source = public_path() . '/index.php';
        $dest = public_path() . '/laravel.php';

        if ( is_file($dest) )
        {
            $this->error("File \"{$dest}\" does already exist. Aborting ...");
            $this->info("If you've previously tried running this installer, rename \"{$dest}\" to \"{$source}\" and try again.");
            die();
        }

        rename($source, $dest);
    }

    /**
     * Publish ProcessWires starter files to the public directory
     *
     * @return void
     */
    protected function publishProcessWirePublicFiles()
    {
        rename(public_path() . '/.htaccess', public_path() . '/old.htaccess');

        $source = __DIR__ . '/../../../../public';
        $dest = public_path();
        $diff = array_diff(scandir($source), scandir($dest));

        $this->info("Publishing contents of \"{$source}\" (ProcessWire starter files) to \"{$dest}\".");

        if ( (count($diff) + 2) !== count(scandir($source)) )
        {
            $this->error("This action is about to overwrite files in \"{$dest}\" with the contents of \"{$source}\". Aborting ...");
            $this->info("Please delete/rename the matching files in \"{$dest}\" and try again. ");
            die();
        }

        $this->recursiveCopy($source, $dest);
    }

    /**
     * Publish a ProcessWire blank site to ./site
     *
     * @return void
     */
    protected function publishSite()
    {
        $source = $source = __DIR__ . '/../../../../site';
        $dest = public_path() . '/../site';

        $this->info('Publishing a ProcessWire blank site to "./site". (This might take a while ...)');

        $this->abortOnExistingDir($dest);
        $this->recursiveCopy($source, $dest);
    }

    /**
     * Create a directory for ProcessWire Uploads in public/assets
     */
    protected function createAssetsDir()
    {
        $this->info('Creating a directory for ProcessWire Uploads in "public/assets".');

        $dest = public_path() . '/assets/files';
        $this->abortOnExistingDir($dest);

        if ( ! is_dir(public_path() . '/assets') )
        {
            mkdir(public_path() . '/assets');
        }

        mkdir($dest);
    }

    /**
     * Show instructions after completing the installation process
     */
    protected function showInstructions()
    {
        $this->info(PHP_EOL . 'Success!');
        $this->info('We renamed the old laravel "public/.htaccess" to "public/old.htaccess". You may remove that file now.');
        $this->info('Visit ' . url('/') . ' in your browser and follow the instructions to complete the ProcessWire installation.');
    }

}
