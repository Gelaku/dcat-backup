<?php
/**
 * 数据库表单优化
 */
namespace Dcat\Admin\Backup\Grid;

use Dcat\Admin\Grid\BatchAction;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Models\HasPermissions;
use Gelaku\LaravelBackup\Backup;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BackupOptimize extends BatchAction
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
        $tables = collect($this->getKey())->filter()->toArray();
        if (empty($tables)){
            return $this->response()->error('No data selected!');
        }
        // 数据库表单
        $backup = new Backup();
        if (!$backup->optimize($tables)){
            return $this->response()->error('admin.update_failed');
        }

        return $this->response()
                    ->success(trans('admin.update_succeeded'))
                    ->refresh();
    }

    /**
     * @return string|void
     */
    public function confirm()
    {
//         return 'Confirm?';
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
        return [];
    }
}
