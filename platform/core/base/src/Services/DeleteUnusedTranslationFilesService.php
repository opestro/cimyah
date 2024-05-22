<?php

namespace Botble\Base\Services;

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\File;

class DeleteUnusedTranslationFilesService
{
    public function handle(): void
    {
        if (! defined('THEME_MODULE_SCREEN_NAME') && File::isDirectory(lang_path('vendor/themes'))) {
            File::deleteDirectory(lang_path('vendor/themes'));
        }

        if (File::isDirectory(lang_path('vendor/themes'))) {
            foreach (BaseHelper::scanFolder(lang_path('vendor/themes')) as $theme) {
                if (! File::isDirectory(theme_path($theme))) {
                    File::deleteDirectory(lang_path('vendor/themes/' . $theme));
                }
            }
        }

        if (File::isDirectory(lang_path('vendor/packages'))) {
            foreach (File::directories(lang_path('vendor/packages')) as $package) {
                if (! File::isDirectory(package_path(File::basename($package)))) {
                    File::deleteDirectory($package);
                }
            }
        }

        if (File::isDirectory(lang_path('vendor/plugins'))) {
            foreach (File::directories(lang_path('vendor/plugins')) as $plugin) {
                if (! File::isDirectory(plugin_path(File::basename($plugin)))) {
                    File::deleteDirectory($plugin);
                }
            }
        }
    }
}
