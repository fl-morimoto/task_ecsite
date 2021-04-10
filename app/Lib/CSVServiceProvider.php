<?php
namespace App\Lib;

use Illuminate\Support\ServiceProvider;

class CSVServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('csv', function()
		{
			return new CSV;
		});
	}
}


