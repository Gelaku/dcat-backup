<?php

use Dcat\Admin\Backup\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('auth/backup', Controllers\BackupController::class.'@index')->name('dcat-admin.backup.index');//数据库表单列表
Route::get('auth/backup/restore', Controllers\BackupController::class.'@restore')->name('dcat-admin.backup.restore');//还原备份列表
Route::get('auth/backup/download', Controllers\BackupController::class.'@download')->name('dcat-admin.backup.download');//下载备份文件
