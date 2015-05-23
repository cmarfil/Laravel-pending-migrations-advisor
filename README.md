# Laravel 5 pending migration advisor

Simple Laravel pending migrations advisor, display a warning popup when are pending migrations to run.

![alt tag](http://i60.tinypic.com/33l2t0o.jpg)

### For Laravel 4, please use the [L4 branch](https://github.com/cmarfil/Laravel-pending-migrations-advisor/tree/L4)!

## Installation
1. Begin by installing this package through Composer. Edit your project's composer.json file to require cmarfil/pending-migration-advisor.

	```php
	"require": {
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
Open `app/config/packages/cmarfil/pending-migration-advisor/config.php` to adjust package configuration.
If this file doesn't exist, run:

```console
php artisan config:publish cmarfil/pending-migration-advisor` to create the default configuration file.
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
