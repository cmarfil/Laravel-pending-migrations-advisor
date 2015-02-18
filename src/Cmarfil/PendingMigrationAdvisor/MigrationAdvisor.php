<?php

namespace Cmarfil\PendingMigrationAdvisor;

class MigrationAdvisor {

    /**
     * The migrations path.
     * Configured by the developer (see config/config.php for default).
     *
     * @var string
     */
    protected $migrations_path = '';

    /**
     * The migration table.
     * Configured by the developer (see config/database.php for default).
     *
     * @var string
     */
    protected $migration_table = '';

    /**
     * Enable or disable the advisor
     * Configured by the developer (see config/config.php for default).
     *
     * @var string
     */
    protected $enabled = true;


    /**
     * Construction method to read package configuration.
     *
     * @return void
     */
    public function __construct()
    {
        $this->migrations_path = \Config::get('pending-migration-advisor::migrations_path');
        $this->migration_table = \Config::get('database.migrations');
		$this->enabled = \Config::get('pending-migration-advisor::enabled');
    }


	/**
	 * Get pending migrations
	 *
	 * @return array
	 */
	public function getPendingMigrations()
	{
		$migrationFiles = $this->getMigrationFiles();
		$ranMigrations = $this->getRanMigrations();

		//Compare migration files with db ran migrations
		$pendingMigrations = array_diff($migrationFiles, $ranMigrations);

		return $pendingMigrations;
	}

	/**
	 * Get all of the migration files in a config path.
	 *
	 * @return array
	 */
	public function getMigrationFiles()
	{
		$files = \File::glob($this->migrations_path.'/*_*.php');

		// Once we have the array of files in the directory we will just remove the
		// extension and take the basename of the file which is all we need when
		// finding the migrations that haven't been run against the databases.
		if ($files === false) return array();

		$files = array_map(function($file)
		{
			return str_replace('.php', '', basename($file));

		}, $files);

		// Once we have all of the formatted file names we will sort them and since
		// they all start with a timestamp this should give us the migrations in
		// the order they were actually created by the application developers.
		sort($files);

		return $files;
	}

	/**
	 * Get the ran migrations from db.
	 *
	 * @return array
	 */
	public function getRanMigrations()
	{
		return \DB::table('migrations')->lists('migration');
	}

    /**
     * Modify the response and inject the migration advisor
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
	 * Based on https://github.com/barryvdh/laravel-debugbar/tree/1.8
     */
    public function modifyResponse($request, $response)
    {
        $app = app();
        if ($this->enabled === false || $app->runningInConsole() || ($response->headers->has('Content-Type') && strpos($response->headers->get('Content-Type'), 'html') === false || $request->getRequestFormat() !== 'html')){
            return $response;
        }

		try {
			$pendingMigrations = $this->getPendingMigrations();
		} catch (\Exception $e) {
			\Log::error('Inject migration advisor error: ' . $e->getMessage());
		}

		if(!empty($pendingMigrations)){
			try {
				$this->injectMigrationAdvisor($response, $pendingMigrations);
			} catch (\Exception $e) {
				\Log::error('Inject migration advisor error: ' . $e->getMessage());
			}
		}

		return $response;
    }


    /**
     * Injects the web migration advisor into the given Response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response A Response instance
     * Based on https://github.com/barryvdh/laravel-debugbar/tree/1.8
     */
	public function injectMigrationAdvisor($response, $pendingMigrations)
    {
        $content = $response->getContent();

		//Render advisor html
        $renderedAdvisor = $this->renderAdvisor($pendingMigrations);

        $pos = strripos($content, '</body>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $renderedAdvisor . substr($content, $pos);
        } else {
            $content = $content . $renderedAdvisor;
        }

        $response->setContent($content);
    }

	/**
	 * Get the ran migrations from db.
	 *
	 * @return array
	 */
	public function renderAdvisor($pendingMigrations)
	{
		$mainCont = "<div onclick=\"document.getElementById('pending_migration_advisor').style.display = 'none';\" id='pending_migration_advisor' style='margin:0px; padding:0px; border-radius:5px; max-height:250px; overflow:hidden; overflow-y:auto; background-color:#fff; border:1px solid #ccc; position:fixed; z-index:9999; top:8px; right:12px; box-shadow: 1px 1px 10px #ccc;'>";
		$headBar = "<div style='cursor:pointer; margin:0px; padding:5px 20px; text-align:center; background-color:#f4645f; color:#fff;'>".count($pendingMigrations)." pending migrations</div>";

		//Do list
		$list = '<ul style="margin:0px; padding:10px; list-style:none; color:#f4645f;">';
		foreach($pendingMigrations as $v){
			$list .= "<li style='padding-bottom:10px;'>".$v."</li>";
		}

		return $mainCont . $headBar . $list . '</ul></div>';
	}


}