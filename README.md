# Laravel CodeIgniter Database Integration

This package allows you to use the excellent Laravel database library
([illuminate/database](https://github.com/illuminate/database)) inside your
CodeIgniter applications.

Laravel normally uses [PDO](http://www.php.net/manual/en/intro.pdo.php) to
make database connections. CodeIgniter establishes its own connection to the database.

If you are only using the Laravel database components, then this will not be a problem -
you can simply disable the CodeIgniter database connection and use Laravel's instead.
However, if your application is using a mixture of CodeIgniter and Laravel database
libraries, this is the package for you.

This integration layer takes all requests made to the Laravel database library, converts
them to raw SQL, then passes them through to the underlying CodeIgniter database driver.
This means that you will not need to establish two separate connections to the database,
and it also means that CodeIgniter database profiling functions will continue to
work correctly.


## Usage

In your `composer.json` file:

```json
{
    "require": {
        "expressodev/laravel-codeigniter-db": "dev-master"
    }
}
```

In your application:

```php
// use our mock PDO class if PDO is not enabled on this server
if (!class_exists('PDO')) {
    class_alias('Illuminate\CodeIgniter\FakePDO', 'PDO');
}

// pass all Laravel database queries through to CodeIgniter
$ci = get_instance();
$resolver = new Illuminate\CodeIgniter\CodeIgniterConnectionResolver($ci);
Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
```

## License

[MIT License](https://github.com/expressodev/laravel-codeigniter-db/blob/master/LICENSE)
