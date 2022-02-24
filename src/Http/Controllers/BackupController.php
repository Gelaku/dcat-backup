<?php

namespace Dcat\Admin\Backup\Http\Controllers;

use Dcat\Admin\Backup\BackupServiceProvider;
use Dcat\Admin\Backup\Grid\BackupBack;
use Dcat\Admin\Backup\Grid\BackupDelete;
use Dcat\Admin\Backup\Grid\BackupOptimize;
use Dcat\Admin\Backup\Grid\BackupRecover;
use Dcat\Admin\Backup\Grid\BackupRepair;
use Carbon\Carbon;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Gelaku\LaravelBackup\Backup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BackupController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title(BackupServiceProvider::trans('backup.backup_title'))
            ->description(trans('admin.list'))
            ->body(Grid::make(null, function (Grid $grid) {
                $backup = new Backup();
                $list = $backup->dataList();
                $grid->model()->setData($list);
                $grid->setKeyName('name');
                $grid->number();
                $grid->column('name',BackupServiceProvider::trans('backup.backup.name'));
                $grid->column('rows',BackupServiceProvider::trans('backup.backup.rows'));
                $grid->column('data_length',BackupServiceProvider::trans('backup.backup.data_length'))->display(function ($size){
                    return format_byte($size);
                });
                $grid->column('engine',BackupServiceProvider::trans('backup.backup.engine'));
                $grid->column('collation',BackupServiceProvider::trans('backup.backup.collation'));
                $grid->column('comment',BackupServiceProvider::trans('backup.backup.comment'));
                $grid->column('create_time',BackupServiceProvider::trans('backup.backup.create_time'));

                $grid->disableActions();
                $grid->disableCreateButton();
                $grid->disablePagination();
                $grid->disableBatchDelete();
                $grid->disableFilterButton();
                $grid->tools(function (Grid\Tools $tools){
                    $tools->append(new BackupBack('<button class="btn btn-primary disable-outline">'.BackupServiceProvider::trans('backup.backup_btn').'</button>'));
                    $tools->append(new BackupOptimize('<button class="btn btn-success disable-outline">'.BackupServiceProvider::trans('backup.optimize_btn').'</button>'));
                    $tools->append(new BackupRepair('<button class="btn btn-warning disable-outline">'.BackupServiceProvider::trans('backup.repair_btn').'</button>'));
                });
            }));
    }
    /**
     * 数据库备份列表
     */
    public function restore(Content $content){
        return $content
            ->title(BackupServiceProvider::trans('backup.revert_title'))
            ->description(trans('admin.list'))
            ->body(Grid::make(null, function (Grid $grid) {
//            dd($grid->model()->toArray());
                $backup = new Backup();
                $list = $backup->fileList();
                krsort($list);
                $grid->model()->setData(array_values($list));
                $grid->setKeyName('name');
                $grid->number();
                $grid->column('name',BackupServiceProvider::trans('backup.revert.name'));//
                $grid->column('size',BackupServiceProvider::trans('backup.revert.size'))->display(function ($size){
                    return format_byte($size);
                });
                $grid->column('created_at',BackupServiceProvider::trans('backup.revert.created_at'))->display(function (){
                    return Carbon::createFromTimestamp($this->time)->toDateTimeString();
                });

                $grid->actions(function (Grid\Displayers\Actions $actions){
//                    $actions->append(new BackupRecover('<button class="btn-sm btn-custom" title="">'.BackupServiceProvider::trans('backup.recover_btn').'</button>&nbsp;'));

//                $actions->append(new BackupDownload('<button class="btn-sm btn-primary" title="">下载</button>&nbsp;'));
//                    $actions->append('<button class="btn-sm btn-primary" onclick="window.location.href=\''.(admin_route('dcat-admin.backup.download',['time'=>$actions->row->time])).'\'">'.BackupServiceProvider::trans('backup.download_btn').'</button>&nbsp;');
//                    $actions->append(new BackupDelete('<button class="btn-sm btn-danger" title="">'.trans('admin.delete').'</button>&nbsp;'));
                    $actions->append(new BackupRecover('<i class="fa  fa-clock-o grid-action-icon" title="'.BackupServiceProvider::trans('backup.recover_btn').'"></i>&nbsp;&nbsp;'));
                    $actions->append('<i class="fa fa-download grid-action-icon" onclick="window.location.href=\''.(admin_route('dcat-admin.backup.download',['time'=>$actions->row->time])).'\'" title="'.BackupServiceProvider::trans('backup.download_btn').'"></i>&nbsp;&nbsp;');
                    $actions->append(new BackupDelete('<i class="feather icon-trash grid-action-icon" title="'.trans('admin.delete').'"></i>&nbsp;'));
                });
                $grid->disableToolbar();
                $grid->disablePagination();
                $grid->disableEditButton();
                $grid->disableViewButton();
                $grid->disableDeleteButton();

            }));

    }

    /**
     * 备份文件下载
     * @param  Request  $request
     * @return array|mixed|string
     * @throws \Exception
     */
    public function download(Request $request){
        $time = $request->input('time');
        $backup = new Backup();
        return $backup->downloadFile($time);
    }
}