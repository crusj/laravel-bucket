<?php
/**
 * author crusj
 * date   2019/10/24 2:10 下午
 */


namespace Crusj\Bucket\Command;


use Illuminate\Console\Command;

class RegisterAllService extends Command
{
    protected $signature = 'bucket:rsa';
    protected $description = 'register all services to Services/Common';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $className = $this->getAllClassNames();
        foreach ($className as $item) {
            RegisterService::addMethodToDoc($item);
        }
    }

    private function getAllClassNames(): array
    {
        $path = app_path('Services');
        $files = array();
        if ($head = opendir($path)) {
            while ($file = readdir($head) !== false) {
                if ($file != ".." && $file != ".") {
                    $className = explode('.', $file);
                    if ($className[0] != 'ServiceFactory') {
                        $files[] = $className[0];
                    }
                }
            }
        }
        closedir($head);
        return $files;
    }

}
