<?php
/**
 * 下载数据库备份文件
 */
namespace Dcat\Admin\Backup\Grid;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Models\HasPermissions;
use Gelaku\LaravelBackup\Backup;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BackupDownload extends RowAction
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
        $file = $backup->getFile('time', $time);
        $url = str_replace([public_path(),'\\'],['','/'],$file[0]);dd(url($url));
        return $this->response()
            ->download(url($url));
    }

    /**
     * @return string|void
     */
    public function confirm()
    {
         return 'Confirm?';
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
