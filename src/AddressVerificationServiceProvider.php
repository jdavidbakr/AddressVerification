<?php 

namespace jdavidbakr\AddressVerification;

use Illuminate\Support\ServiceProvider;

class AddressVerificationServiceProvider extends ServiceProvider {
	/**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
	        __DIR__.'/../config/address-verification.php' => config_path('address-verification.php')
	    ], 'config');

	    $this->app->singleton('address-verification', function($app) {
	    	return new \jdavidbakr\AddressVerification\AddressVerificationService;
	    });
	}
}

?>