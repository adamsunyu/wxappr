<?php

namespace Phosphorum\Utils;

use Phalcon\Config;
use Dropbox\Client;
use Phalcon\DI\Injectable;
use League\Flysystem\Filesystem;
use League\Flysystem\Dropbox\DropboxAdapter;

/**
 * Backup
 *
 * Backups the default database to Dropbox (only MySQL/Unix)
 * @property \Phalcon\Config config
 */
class Backup extends Injectable
{
    public function generate()
    {
        if (PHP_SAPI != 'cli') {
            throw new \Exception("This script only can be used in CLI");
        }

        $config = $this->config->get('database');

        system(sprintf(
            '/usr/bin/mysqldump -u %s -h %s -p%s -r /tmp/wxappr.sql %s',
            $config->username,
            $config->host,
            $config->password,
            $config->dbname
        ));
        system('bzip2 -f /tmp/wxappr.sql');

        $sourcePath = '/tmp/wxappr.sql.bz2';
        if (!file_exists($sourcePath)) {
            throw new \Exception("Backup could not be created");
        }

        $date = date('Y-m-d');
        $toPath = BASE_DIR.'data/backup/wxappr-'.$date.'.sql.bz2';
        rename($sourcePath, $toPath);

        @unlink($sourcePath);
    }
}
