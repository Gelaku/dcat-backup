<?php
/**
 * 数据库备份删除
 */
namespace Dcat\Admin\Backup\Grid;

use Dcat\Admin\Grid\BatchAction;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Models\HasPermissions;
use Gelaku\LaravelBackup\Backup;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BackupDelete extends RowAction
{
    /**
     * @return string
     */
    protected $title = 'Title';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        // 数据库表单
        $backup = new Backup();
        $time = $request->input('time');
        if (!$backup->delFile($time)){
            return $this->response()->error(trans('admin.delete_failed'));
        }

        return $this->response()
                    ->success(trans('admin.delete_succeeded'))
                    ->refresh();
    }

    /**
     * @return string|void
     */
    public function confirm()
    {
         return trans('admin.delete_confirm');
    }

    /**
     * @param Model|Authenticatable|HasPermissions|null $user
     *
     * @return bool
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * @return array
     */
    protected function parameters()
    {
        return $this->row->toArray();
    }
}
