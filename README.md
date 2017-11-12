# wxappr.com

Wxappr.com is a flexible, clear and fast forum which actually come from Phosphorum.
You can adapt it to your own needs or improve it if you want.

## Get Started

### Requirements

To run this application on your machine, you need at least:

* [Curl][1] extension
* [Openssl][2] extension
* Internationalization ([intl][3]) extension
* Mbstring ([mbstring][4]) extension
* [GD][21] extension
* [Composer][5]
* PHP >= 5.5
* [Apache][6] Web Server with [mod_rewrite][7] enabled or [Nginx][8] Web Server
* Latest stable [Phalcon Framework release][9] extension enabled

### Installation

#### 1. Getting project

Install composer in a common location or in your project:

```sh
$ curl -s http://getcomposer.org/installer | php
```

Create the `composer.json` file as follows:

```json
{
    "require": {
        "phalcon/forum": "~3.0"
    }
}
```

Run the composer installer:

```sh
$ php composer.phar install
```

Another way to get this project by using `composer create-project`:

```sh
$ composer create-project phalcon/forum
```

#### 2. Creating database

Then you'll need to create the database and initialize schema:

```sh
$ echo 'CREATE DATABASE forum CHARSET=utf8 COLLATE=utf8_unicode_ci' | mysql -u root
$ cat schemas/wxappr.sql | mysql -u root wxappr
```

#### 3. Set up project

Copy environment config:

```sh
$ cp .env.example .env
```

Copy application config:

```sh
$ cp app/config/config.example.php app/config/config.php
```

You can override application configuration by creating development configuration:

```sh
$ cp app/config/development.example.php app/config/development.php
```

#### Directory Permissions

After installing this software, you may need to configure some permissions.
Directories within the `app/cache` and the `app/logs` directory should be writable by your web server.

#### OAuth

This application uses [Github as authentication system][18], you need a client id and secret id
to be set up in the configuration (`app/config/config.php`).

## License

Wxappr is open-sourced software licensed under the [New BSD License].

[1]: http://php.net/manual/en/book.curl.php
[2]: http://php.net/manual/en/book.openssl.php
[3]: http://php.net/manual/en/book.intl.php
[4]: http://php.net/manual/en/book.mbstring.php
[5]: https://getcomposer.org/
[6]: http://httpd.apache.org/
[7]: http://httpd.apache.org/docs/current/mod/mod_rewrite.html
[8]: http://nginx.org/
[9]: https://github.com/phalcon/cphalcon/releases
[10]: http://kr.github.io/beanstalkd/
[11]: http://codeception.com
[12]: http://goo.gl/yLJLZg
[13]: http://codeception.com/docs/reference/Commands
[14]: https://github.com/phalcon/forum/blob/master/docs/LICENSE.md
[15]: https://forum.phalconphp.com/
[16]: https://forum.zephir-lang.com/
[17]: http://supervisord.org/
[18]: https://developer.github.com/v3/oauth/
[19]: https://github.com/phalcon/forum/branches
[20]: https://github.com/phalcon/forum/tags
[21]: http://php.net/manual/en/book.image.php
