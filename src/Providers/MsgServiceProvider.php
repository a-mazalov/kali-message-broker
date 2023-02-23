<?php

namespace Kali\MessageBroker\Providers;

use Illuminate\Support\ServiceProvider;

class MsgServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap application service.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../../config/messages.php' => config_path('messages.php'),
		]);
	}
}