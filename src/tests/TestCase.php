<?php 

namespace Runsite\CMF\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Runsite\CMF\Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
