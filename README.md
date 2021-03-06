# Laravel 5 pending migration advisor

Simple Laravel pending migrations advisor, display a warning popup when are pending migrations to run.

![alt tag](http://i61.tinypic.com/zo9h7q.png)

### For Laravel 4, please use the [L4 branch](https://github.com/cmarfil/Laravel-pending-migrations-advisor/tree/L4)!

## Installation
1. Begin by installing this package through Composer. Edit your project's composer.json file to require-dev cmarfil/pending-migration-advisor.

	```php
	"require-dev": {
		"cmarfil/pending-migration-advisor": ">=1.1"
	}
	```

2. Add `'Cmarfil\PendingMigrationAdvisor\PendingMigrationAdvisorServiceProvider'` to `providers` in `app/config/app.php`.

  ```php
  'providers' => array(
    // ...
    'Cmarfil\PendingMigrationAdvisor\PendingMigrationAdvisorServiceProvider',
  ),
  ```

## Configuration
Open `app/config/pending-migration-advisor.php` to adjust package configuration.
If this file doesn't exist, run:

```console
php artisan vendor:publish --provider="Cmarfil\PendingMigrationAdvisor\PendingMigrationAdvisorServiceProvider"
```

```php
return array(
    /**
     * The migrations path
     */
    'migrations_path' =>  base_path().'/database/migrations',
    /**
     * Turn off the advisor
     */
    'enabled' =>  true,
);
```

#### Migrations path
Specify the migrations path ( normally database/migrations )

#### Enabled
Enable or disable the advisor


## Finally

#### Contributing
Feel free to create a fork and submit a pull request if you would like to contribute.

#### Bug reports
Raise an issue on GitHub if you notice something broken.

#### Credits
Html popup injection based on: https://github.com/barryvdh/laravel-debugbar
