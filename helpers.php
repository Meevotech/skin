<?php

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

if (! function_exists('mixpb')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    function mixpb($path, $manifestDirectory = 'themes/pillbox', $mixname = 'mix-manifest.json', $addSlash = true)
    {
        static $manifests = [];

        if (! Str::startsWith($path, '/') && $addSlash) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        $manifestPath = base_path($manifestDirectory.'/'.$mixname);

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
//            report(new Exception("Unable to locate Mix file: {$path}."));

            if (! app('config')->get('app.debug')) {
                return $path;
            }
        }

        return new HtmlString(($addSlash ? $manifestDirectory : '').$manifest[$path]);
    }
}

if (! function_exists('mixencore')) {
    function mixencore($path, $manifestDirectory = 'themes/pillbox/assets/build', $mixname = 'manifest.json', $addSlash = false)
    {
        // $path = str_replace('themes/pillbox/assets/build/', '', $path);
        static $manifests = [];

        if (! Str::startsWith($path, '/') && $addSlash) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        $manifestPath = base_path($manifestDirectory.'/'.$mixname);

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }


        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
//        report(new Exception("Unable to locate Mix file: {$path}."));

            if (! app('config')->get('app.debug')) {
                return $path;
            }
        }

        try {
            $manifest[$path];
        } catch (\Exception $e) {
//        dd($manifest, $path);
            return '';
        }
        return ($addSlash ? $manifestDirectory : '').$manifest[$path];
    }
}

if (! function_exists('encore_entry_script_tags')) {
    function encore_entry_script_tags(string $entryName, $runtime = true, $path = null)
    {
        if (!$path) {
            $path = themes_path('pillbox/assets/build/entrypoints.json');
        }
        $entryPointsFile = ($path);
        if (!file_exists($entryPointsFile)) {
            return;
        }
        $jsonResult = json_decode(file_get_contents($entryPointsFile), true);
        if (!array_key_exists('js', $jsonResult['entrypoints'][$entryName])) {
            return null;
        }
        $tags = array_map(function ($item) use ($runtime) {
            if (\Str::contains($item, 'runtime') && !$runtime) {
                return '';
            }
            return '<script src="' . $item . '"></script>';
        }, $jsonResult['entrypoints'][$entryName]['js']);
        return new HtmlString(implode(PHP_EOL, $tags));
    }
}

if (! function_exists('encore_entry_link_tags')) {
    function encore_entry_link_tags(string $entryName, $path = null)
    {
        if (!$path) {
            $path = themes_path('pillbox/assets/build/entrypoints.json');
        }
        $entryPointsFile = $path;
        if (!file_exists($entryPointsFile)) {
            return '';
        }
        $jsonResult = json_decode(file_get_contents($entryPointsFile), true);
        if (!array_key_exists('css', $jsonResult['entrypoints'][$entryName])) {
            return null;
        }
        $tags = array_map(function ($item) {
            return '<link rel="stylesheet" href="' . $item . '"/>';
        }, $jsonResult['entrypoints'][$entryName]['css']);
        return new HtmlString(implode('', $tags));
    }
}
