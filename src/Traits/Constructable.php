<?php

namespace Runsite\CMF\Traits;

trait Constructable 
{
	public function __construct($table, $dates=null)
	{
		parent::__construct($table, $dates);
	}
}