<?php

namespace App\Providers;

use App\Http\ViewComposers\ProfileComposer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * 在容器中注册绑定.
     *
     * @return void
     */
    public function boot()
    {
        //基于类的composers...
        view()->composers([
            ProfileComposer::class  =>  [
                'web.test.*',
                'web.category.*',
                'web.article.*',
                'web.users.*',
            ],
        ]);

        /*view()->composer(
            'web.users.*', UsersComposer::class
        );*/

        /*view()->composer('dashboard', function ($view) {
            //
        });*/

        /*View::share('key', 'value');*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
