<?php

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Artisan;

class ArtisanMigrate {

  public $message = 'Runing migrations';

  public function handle()
  {
    return Artisan::call('migrate');
  }
}
