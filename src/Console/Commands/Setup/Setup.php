<?php

namespace Runsite\CMF\Console\Commands\Setup;

use Illuminate\Console\Command;
use Artisan;
use DB;

use Runsite\CMF\Console\Commands\Setup\Verifications\FilesAccess;

use Runsite\CMF\Console\Commands\Setup\Steps\ClearApp;
use Runsite\CMF\Console\Commands\Setup\Steps\ArtisanMigrate;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateApplications;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateDeveloperUser;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateRootModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateFirstLanguage;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateRootNode;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateSettingsNode;
use Runsite\CMF\Console\Commands\Setup\Steps\GivePermissions;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateSectionModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateAdminSectionModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateDependencies;

class Setup extends Command
{

    protected $signature = 'runsite:setup';
    protected $description = 'Setup Runsite';

    protected $database = '';

    protected $steps = [
        FilesAccess::class,

        ClearApp::class,
        ArtisanMigrate::class,
        CreateApplications::class,
        CreateDeveloperUser::class,
        CreateRootModel::class,
        CreateFirstLanguage::class,
        CreateRootNode::class,
        GivePermissions::class,
        CreateSectionModel::class,
        CreateAdminSectionModel::class,
        CreateDependencies::class,
        CreateSettingsNode::class,
    ];

    public function __construct()
    {
        parent::__construct();

        $this->database = env('DB_DATABASE');
    }

    public function handle()
    {
        $this->comment('RunsiteCMF Setup');

        if($this->tablesExists())
        {
          if(config('app.env') != 'testing')
          {
            if ($this->confirm('Database "'.$this->database.'" is not empty. Do you want to remove all tables in this database?')) {
                $this->dropAllTablesInDb();
            }
            else
            {
              $this->comment('Instalation canceled');
              return false;
            }
          }
          else 
          {
            $this->dropAllTablesInDb();
          }
        }
        


        $this->comment('Instalation...');

        $bar = $this->output->createProgressBar(count($this->steps));
        $bar->setFormatDefinition('runsite', '  %current%/%max% [%bar%] %percent:3s%% -- %message%');
        $bar->setFormat('runsite');
        $bar->setMessage('Preparing');
        $bar->start();

        foreach($this->steps as $class)
        {
          $class = new $class;
          $bar->setMessage($class->message);
          $bar->advance();
          $class->handle();
        }

        $bar->setMessage('Instalation complete!');
        $bar->finish();

        $this->comment('');
        $this->comment('Thank you for trying!');
        $this->comment('We really need support in this project. You can create a fork and develop the project together with us, giving us the pull-requests.');

    }

    public function isEmail($email)
    {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function strongPassword($password)
    {
      return strlen($password) >= 6;
    }

    public function tablesExists()
    {
      $colname = 'Tables_in_' . $this->database;
      $tables = DB::select('SHOW TABLES');

      return count($tables);
    }

    public function dropAllTablesInDb()
    {
      $colname = 'Tables_in_' . $this->database;
      $tables = DB::select('SHOW TABLES');
      $droplist = [];

      foreach($tables as $table) {
          $droplist[] = $table->$colname;
      }

      $droplist = implode(',', $droplist);
      if($droplist)
      {
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("DROP TABLE $droplist");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();
      }
    }

}
