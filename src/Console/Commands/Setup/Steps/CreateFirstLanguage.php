<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Dynamic\Language;

class CreateFirstLanguage
{
    public $message = 'Creating first language';

    public function handle()
    {
        Language::create(['locale'=>'en', 'display_name'=>'English', 'is_active'=>true]);
        Language::create(['locale'=>'uk', 'display_name'=>'Українська', 'is_active'=>true]);
        Language::create(['locale'=>'pl', 'display_name'=>'Polski', 'is_active'=>true]);
        Language::create(['locale'=>'ru', 'display_name'=>'Русский', 'is_active'=>true]);
    }
}