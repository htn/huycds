<?php

namespace Huycds\Backends\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {

            // @backend($slug)
            $bladeCompiler->directive('backend', function ($slug) {
                return "<?php if(Backend::exists({$slug}) && Backend::isEnabled({$slug})): ?>";
            });
            $bladeCompiler->directive('endbackend', function () {
                return '<?php endif; ?>';
            });

        });
    }
}
