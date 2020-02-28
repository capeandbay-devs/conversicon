<?php

namespace CapeAndBay\Conversicon;

use Route;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use CapeAndBay\Conversicon\Http\Middleware\ValidateBasicHeader;

class ConversicaServiceProvider extends ServiceProvider
{
    const VERSION = '0.1.0';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
    public $routeFilePath = '/routes/conversica/routes.php';

    /**
     * Where custom routes can be written, and will be registered by Conversicon.
     *
     * @var string
     */
    public $customRoutesFilePath = '/routes/conversica/handwritten.php';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $_SERVER['CAPEANDBAY_CONVERSICA_VERSION'] = $this::VERSION;

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/conversica.php', 'conversica'
        );

        $this->setupRoutes($this->app->router);
        $this->setupCustomRoutes($this->app->router);
        $this->publishFiles();
        $this->checkLicenseCodeExists();
        $this->registerMiddleware($router);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ActivityLogger::class);

        $this->app->singleton(ActivityLogStatus::class);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists(base_path().$this->routeFilePath)) {
            $routeFilePathInUse = base_path().$this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }
    /**
     * Load custom routes file.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupCustomRoutes(Router $router)
    {
        // if the custom routes file is published, register its routes
        if (file_exists(base_path().$this->customRoutesFilePath)) {
            $this->loadRoutesFrom(base_path().$this->customRoutesFilePath);
        }
    }

    public function publishFiles()
    {
        $cnb_config_files = [__DIR__.'/config' => config_path()];
        $cnb_custom_routes_file = [__DIR__.$this->customRoutesFilePath => base_path($this->customRoutesFilePath)];

        $minimum = array_merge($cnb_config_files,$cnb_custom_routes_file);

        // register all possible publish commands and assign tags to each
        $this->publishes($cnb_config_files, 'config');
        $this->publishes($cnb_custom_routes_file, 'custom_routes');
        $this->publishes($minimum, 'minimum');
    }

    /**
     * Check to to see if a license code exists.
     * If it does not, throw a notification bubble.
     *
     * @return void
     */
    private function checkLicenseCodeExists()
    {

    }

    /**
     * Publish the package's middleware.
     *
     * @return void
     */
    protected function registerMiddleware(Router $router)
    {
        $router->pushMiddlewareToGroup('conversicon.basic', ValidateBasicHeader::class);
    }
}
