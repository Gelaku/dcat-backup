<?php

namespace Dcat\Admin\Backup;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class BackupServiceProvider extends ServiceProvider
{
    protected $menu = [
        [
            'title' => 'Database Backup',
            'uri'   => 'auth/backup',
            'icon'=>'fa-database',
        ],
        [
            'title' => 'Database Recover',
            'uri'   => 'auth/backup/restore',
            'icon'=>'fa-clock-o',
        ],
    ];

}
