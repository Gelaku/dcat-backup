<?php
/**
 * 数据库备份
 */
namespace Dcat\Admin\Backup\Grid;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\BatchAction;
use Dcat\Admin\Traits\HasPermissions;
use Gelaku\LaravelBackup\Backup;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BackupBack extends BatchAction
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
        foreach ($tables as $table) {
            $backup->setFile()->backup($table);
        }

        return $this->response()
            ->success(trans('admin.save_succeeded'))
            ->refresh();
    }

    /**
	 * @return string|array|void
	 */
	public function confirm()
	{
//		 return ['Confirm?', 'contents'];
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
