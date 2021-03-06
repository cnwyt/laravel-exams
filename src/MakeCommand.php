<?php

namespace Cnwyt\Exams;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class MakeCommand extends Command
{
    use DetectsApplicationNamespace;

    public function __construct(){
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:install
                    {--views : Only scaffold the posts views}
                    {--force : Overwrite existing files by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[laravel-exams]Scaffold basic views, models and routes';

    protected $stubDir = __DIR__ . '/console/stubs';

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'examPaperList.stub.php'   => 'examPaperList.blade.php',
        'examPaperDetail.stub.php' => 'examPaperDetail.blade.php',
    ];

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $controllers = [
        'ExamController.php'   => 'Exams/ExamController.php',
    ];

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $routes = [
        'routes.stub.php'   => 'routes.stub.php',
    ];

 	/**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->fire();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->line(' => Start install exams: ');
        $this->createDirectories();

        $this->info(' => Start to generate [views] ...');
        $this->exportViews();

        $this->info(' => Start to generate [controllers] ...');
        $this->exportControllers();
        
        $this->info(' => Start to generate [routes] ...');
        $this->exportRoutes();

        $this->exportMigration();

        $this->info('=> :) Laravel-Exams scaffolding generated successfully.');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        // 创建Exams子目录
        if (! is_dir(app_path('Http/Controllers/Exams'))) {
            mkdir(app_path('Http/Controllers/Exams'), 0755, true);
        }

        if (! is_dir(resource_path('views/layouts'))) {
            mkdir(resource_path('views/layouts'), 0755, true);
        }

        if (! is_dir(resource_path('views/exams'))) {
            mkdir(resource_path('views/exams'), 0755, true);
        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            $destinationFilePath = resource_path('views/exams/' . $value);
            if (file_exists($destinationFilePath) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy($this->stubDir . '/views/'.$key, $destinationFilePath);
        }
    }

    protected function exportControllers()
    {
        foreach ($this->controllers as $key => $value) {
            $destinationFilePath = \app_path('Http/Controllers/' . $value );
            if (file_exists($destinationFilePath) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] controller already exists. Do you want to replace it?")) {
                    continue;
                }
            }
            copy($this->stubDir . '/controllers/'.$key, $destinationFilePath);
                        
            $this->info(' => Start to generate [ExamController] ...');
//            file_put_contents(
//                $destinationFilePath,
//                $this->compileControllerStub($key)
//            );

        }
    }

    /**
     * 将路由配置追加到 routes/web.php 文件内容末尾
     */
    protected function exportRoutes()
    {
        foreach ($this->routes as $key => $value) {

            $webRouter  = file_get_contents(base_path('routes/web.php'));
            $postRouter = file_get_contents($this->stubDir . '/' . $value);
            if( stripos($webRouter, $postRouter) !== false ){
                if (! $this->confirm("The [{$value}] routes already exists. Do you want to continue?")) {
                    continue;
                }
            }

            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents($this->stubDir . '/' . $value),
                FILE_APPEND
            );
        }
    }

    /**
     * Compiles the HomeController stub.
     *
     * @return string
     */
    protected function compileControllerStub($fileName = '')
    {
//        return str_replace('{{namespace}}', $this->getAppNamespace(),
//            file_get_contents($this->stubDir . '/controllers/' . $fileName)
//        );
    }

    protected function exportMigration()
    {
        $this->info(' => Start to generate [migration] ...');

        $destFile = database_path('migrations/2018_09_15_204025_create_exam_tables.php');
        if (file_exists($destFile)) {
            if (! $this->confirm("The file [2018_09_15_204025_create_exam_tables] already exists. Do you want to replace it?")) {
                $this->info(' => Start to generate [migration] ... cancel');
                return;
            }
        }
        $res =  copy(
            __DIR__.'/database/migrations/create_exam_tables.php',
            $destFile
        );
        if ($res) {
            $this->info('=> Start to generate [migration] ... ok');
        } else {
            $this->info('=> Start to generate [migration] ... fail');
        }
    }
}
