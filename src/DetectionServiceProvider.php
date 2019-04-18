<?php namespace JosKoomen\Detect;

use JosKoomen\Api\AbstractApiServiceProvider;
use Illuminate\Support\ServiceProvider;

class DetectionServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    protected $package_name = 'joskoomen';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfigs();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind any implementations.
        $this->app->bind('joskoomen_detection', DetectionFacade::class);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [AbstractApiServiceProvider::class];
    }

    private function handleConfigs()
    {
        $configPath = __DIR__ . '/../config/joskoomen-detection.php';

        $this->publishes([
            $configPath => config_path('joskoomen-detection.php')
        ], $this->package_name);

        $this->mergeConfigFrom($configPath, $this->package_name);
    }
}
