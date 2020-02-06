<?php namespace Meevo\Skin;

use Backend\Models\BrandSetting;
use Backend\Widgets\Form;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function register()
    {
        \Event::listen('system.console.mirror.extendPaths', function ($paths) {
            $paths->wildcards = array_merge($paths->wildcards, [
                'plugins/*/*/res/assets',
                'plugins/*/*/res/assets/*/*/assets',
            ]);
        });

        \Event::listen('backend.form.extendFields', function (Form $widget) {
            if ($widget->model instanceof BrandSetting) {
                $widget->removeField('primary_color');
                $widget->removeField('secondary_color');
                $widget->removeField('accent_color');
            }
        });
    }
}
