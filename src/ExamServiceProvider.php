<?php

namespace Cnwyt\Exams;

use Cnwyt\Exams\Models\ExamsFacade;
use Illuminate\Auth\Events\Logout;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

class ExamServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([
            __DIR__ . '/config/exams.php' => config_path('exams.php'),
        ]);
 
        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ( ! class_exists('CreateExamTables')) {
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/database/migrations/create_exams_tables.php' => database_path('migrations/'.$timestamp.'_create_exams_tables.php'),
            ], 'migrations');
        }

        $this->registerExams();
        
    }

    public function registerExams(){
        $this->app->singleton('exams', function($app){
            return new ExamsFacade();
        });
    }

}
