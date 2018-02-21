<?php

namespace Runsite\CMF\Console\Commands\Setup;

use Illuminate\Console\Command;
use Artisan;
use DB;
use Runsite\CMF\Models\Dynamic\Language;

use Runsite\CMF\Console\Commands\Setup\Verifications\FilesAccess;

use Runsite\CMF\Console\Commands\Setup\Steps\ClearApp;
use Runsite\CMF\Console\Commands\Setup\Steps\ArtisanMigrate;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateApplications;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateDevelopersGroup;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateRootModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateFirstLanguage;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateRootNode;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateSettingsNode;
use Runsite\CMF\Console\Commands\Setup\Steps\GivePermissions;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateSectionModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateAdminSectionModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreatePageModel;
use Runsite\CMF\Console\Commands\Setup\Steps\CreateDependencies;
use Runsite\CMF\Console\Commands\Setup\Steps\PublishVendor;
use Runsite\CMF\Console\Commands\Setup\Steps\StoragePreparation;

use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Models\User\User;

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
        CreateDevelopersGroup::class,
        CreateRootModel::class,
        CreateFirstLanguage::class,
        CreateRootNode::class,
        GivePermissions::class,
        CreateSectionModel::class,
        CreateAdminSectionModel::class,
        CreatePageModel::class,
        CreateDependencies::class,
        CreateSettingsNode::class,
        PublishVendor::class,
        StoragePreparation::class,
    ];

    protected $options = [
      'app_locale' => [
        'locale' => null,
        'display_name' => null,
      ],

      'fallback_locale' => [
        'locale' => null,
        'display_name' => null,
      ],
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
            $option = $this->menu('Database "'.$this->database.'" is not empty. Do you want to remove all tables in this database?', [
                'Cancel installation',
                'Yes, I want to remove all tables in this database and continue installation',
            ])->open();

            if ($option == 1) {
                $this->dropAllTablesInDb();
            }
            else
            {
              $this->comment('Installation canceled');
              return false;
            }
          }
          else 
          {
            $this->dropAllTablesInDb();
          }
        }


        $this->options['app_locale']['locale'] = $this->ask('Enter main app locale');
        $this->options['app_locale']['display_name'] = $this->ask('Enter main app locale display name');

        $this->options['fallback_locale']['locale'] = $this->ask('Enter fallback locale');

        if($this->options['fallback_locale']['locale'] != $this->options['app_locale']['locale'])
        {
          $this->options['fallback_locale']['display_name'] = $this->ask('Enter fallback locale display name');
        }
        else
        {
          $this->options['fallback_locale']['display_name'] = $this->options['app_locale']['locale'];
        }
        
        


        $this->comment('Installation...');

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
          $class->handle($this->options);
        }

        $bar->setMessage('Installation complete!');
        $bar->finish();

        $this->clearCLI();

        $this->comment('=================================');
        $this->comment('=== Create the developer user ===');
        $this->comment('=================================');

        $data['name'] = $this->ask('What is your name?');
        $data['email'] = $this->ask('Enter your email');
        $data['password'] = $this->secret('Enter new password');
        $data['password_confirmation'] = $this->secret('Enter new password again');

        while($data['password_confirmation'] != $data['password'])
        {
          $this->error('Passwords do not match.');
          $user['password_confirmation'] = $this->secret('Enter new password again');
        }

        $data['password'] = bcrypt($data['password']);

        $group = Group::first();
        $user = User::create($data);

        $user->assignGroup($group);

        $this->comment('Thank you for trying!');
        $this->comment('We really need support in this project. You can create a fork and develop the project together with us, giving us the pull-requests.');

        // $this->comment('');
        // $this->comment('Thank you for trying!');
        // $this->comment('We really need support in this project. You can create a fork and develop the project together with us, giving us the pull-requests.');

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

    private function clearCLI()
    {
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          system('cls');
      } else {
          system('clear');
      }
    }

}
