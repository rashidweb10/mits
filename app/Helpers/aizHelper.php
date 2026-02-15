<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

// if (!function_exists('uploaded_asset')) {
//     function uploaded_asset($id)
//     {
//         $asset = Cache::rememberForever('uploaded_asset_'.$id , function() use ($id) {
//             return \App\Models\Upload::find($id);
//         });

//         if ($asset != null) {
//             return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
//         }
//         return static_asset('assets/frontend/img/placeholder.jpg');
//     }
// }
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id, $thumb = null)
    {
        $asset = Cache::rememberForever('uploaded_asset_' . $id, function () use ($id) {
            return \App\Models\Upload::find($id);
        });

        // Fallback if asset not found
        if ($asset === null) {
            return static_asset('assets/frontend/img/default.png');
        }
        
        // Thumbnail Image
        if ($thumb !== null && $asset->type === 'image') {
            //var_dump(1);exit;
            $originalPath = $asset->file_name;

            // Thumbnail sizes mapping
            $sizes = [
                0 => [150, 150],
                1 => [300, 300],
                2 => [600, 400],
            ];

            if (isset($sizes[$thumb])) {
                
                [$w, $h] = $sizes[$thumb];
                $pathInfo = pathinfo($originalPath);

                $filename = $pathInfo['filename'];
                $thumbName = $filename . '.' . $pathInfo['extension'];
                $thumbRelativePath = 'storage/thumbs/' . "{$w}x{$h}/" . $thumbName;

                // If thumbnail exists, return it
                // if (file_exists(public_path($thumbRelativePath))) {
                    
                //     return my_asset($thumbRelativePath);
                // }
                // If thumbnail exists, return it
                $remoteUrl = rtrim(config('custom.assets_url'), '/') . '/' . ltrim($thumbRelativePath, '/');
                $cacheKey = 'remote_thumb_exists_' . md5($thumbRelativePath);

                $exists = Cache::remember($cacheKey, 86400, function () use ($remoteUrl) {
                    $headers = @get_headers($remoteUrl);
                    return $headers && strpos($headers[0], '200') !== false;
                });

                if ($exists) {
                    return $remoteUrl;
                }

            }
        }
        
        // Return original or external
        return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        // return app('url')->asset('public/' . $path, $secure);

        if(env('ENVIRONMENT') == "Production"){
            return app('url')->asset('public/' . $path, $secure);
        } else {
            return app('url')->asset($path, $secure);
        }

    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}