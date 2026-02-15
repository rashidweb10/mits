<?php

use App\Models\Company;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use App\Models\TinyMCEKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;

if (!function_exists('truncate_text')) {
    /**
     * Truncate a string to a specified length and append a suffix if needed.
     *
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    function truncateText($text, int $length = 15, string $suffix = '...'): string
    {
        if ($text === null || empty($text)) {
            return '';
        }    
        return \Illuminate\Support\Str::limit($text, $length, $suffix);
    }
}

if (!function_exists('convertToSlug')) {
    /**
     * Convert text to a slug format.
     *
     * @param  string  $text
     * @return string
     */
    function convertToSlug(string $text): string
    {
        if (empty($text)) {
            return '';
        }
        
        return \Illuminate\Support\Str::slug($text);
    }
}


// if (!function_exists('getCompanyList')) {
//     function getCompanyList()
//     {
//         return auth()->user()->company_id
//             ? Company::where('id', auth()->user()->company_id)->get()
//             : Company::all();
//     }
// }
if (!function_exists('getCompanyList')) {
    function getCompanyList()
    {
        $companies = auth()->user()?->company_id
            ? Company::where('id', auth()->user()->company_id)->get()
            : Company::all();

        // Add a custom display_name field (only for this helper)
        return $companies->map(function ($company) {
            $company->name = $company->name . ($company->website ? ' - ' . $company->website . '' : '');
            return $company;
        });
    }
}

if (! function_exists('formatDate')) {
    /**
     * Format date to dd/mm/yyyy.
     *
     * @param  string  $date
     * @return string
     */
    function formatDate($date)
    {
        // Check if the date is not null or empty
        if ($date) {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        }
        return null; // Return null if no date is provided
    }
}

if (! function_exists('formatDatetime')) {
    /**
     * Format date and time to dd/mm/yyyy h:i A (AM/PM).
     *
     * @param  string  $date
     * @return string|null
     */
    function formatDatetime($date)
    {
        // Check if the date is not null or empty
        if ($date) {
            return \Carbon\Carbon::parse($date)->format('d/m/Y h:i A');
        }
        return null; // Return null if no date is provided
    }
}


if (! function_exists('jsonDecodeAndPrint')) {
    /**
     * Decode a JSON string and return its values as a string.
     *
     * @param  string  $json
     * @param  string  $separator  The separator between items when printing (default is a comma)
     * @return string
     */
    function jsonDecodeAndPrint($json, $separator = ', ')
    {
        // Decode the JSON string into an array
        $decoded = json_decode($json, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return "";  // Return error message if decoding fails
        }

        // Return the values as a string with the given separator
        return implode($separator, $decoded);
    }
}

if (!function_exists('currentUser')) {
    /**
     * Get the currently authenticated user.
     *
     * @return \App\Models\User|null
     */
    function currentUser()
    {
        return \App\Models\User::find(Auth::id());
    }
}

if (!function_exists('getYears')) {
    /**
     * Get an array of years from the specified start year to the end year.
     *
     * @param int $start The start year (default is 2020).
     * @param int $end The end year (default is 2050).
     * @return array An array containing the years from start to end (inclusive).
     */
    function getYears(int $start = 2020, int $end = 2050): array
    {
        // Ensure the start year is less than or equal to the end year
        if ($start > $end) {
            throw new InvalidArgumentException("Start year cannot be greater than end year.");
        }

        return range($start, $end);
    }
}

if (!function_exists('central_asset')) {
    function central_asset($path)
    {
        $baseUrl = rtrim(config('custom.assets_url', env('ASSETS_URL', env('APP_URL'))), '/');

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($path);
            $path = $parsedUrl['path'] ?? '';
        }

        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('generateHtmlTableFromCsv_old')) {
    function generateHtmlTableFromCsv_old($csvFilePath) {
        $relativePath = str_replace(url('/storage'), 'storage', $csvFilePath);
        $csvFilePath = public_path($relativePath);

        if (!file_exists($csvFilePath) || !is_readable($csvFilePath)) {
            return '<p>Error: File not found or unreadable.</p>';
        }

        $file = fopen($csvFilePath, 'r');
        $headers = fgetcsv($file);
        if (!$headers) {
            //return '<p>Error: Empty CSV file.</p>';
            return '<p>Data Not Found</p>';
        }

        $html = '<table class="table table-bordered table-hover">';
        $html .= '<thead class="thead-dark"><tr>';

        foreach ($headers as $index => $header) {
            $className = 'col_' . ($index + 1);
            $html .= "<th scope='col' class='$className text-left'>" . htmlspecialchars($header) . "</th>";
        }

        $html .= '</tr></thead><tbody>';

        while (($row = fgetcsv($file)) !== false) {
            $html .= '<tr>';
            foreach ($row as $index => $cell) {
                $className = 'col_' . ($index + 1);

                if (filter_var($cell, FILTER_VALIDATE_URL) || preg_match('/^(\/?storage\/)/', $cell)) {
                    $ext = strtolower(pathinfo(parse_url($cell, PHP_URL_PATH), PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];

                    if (in_array($ext, $imageExtensions)) {
                        //$cell = "<img src='" . htmlspecialchars($cell) . "' alt='Image' style='max-width: 100px; height: auto;' />";
                        $cell = "<a href='" . htmlspecialchars($cell) . "' target='_blank'><img src='" . htmlspecialchars($cell) . "' alt='Image' style='max-width: 100px; height: auto;' /></a>";

                    } else {
                        $cell = "<a href='" . htmlspecialchars($cell) . "' target='_blank' class='result_vm_btn'>View</a>";
                    }
                } else {
                    $cell = htmlspecialchars($cell);
                    $cell = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $cell);
                    $cell = nl2br($cell);
                }

                $html .= "<td class='$className text-left'>$cell</td>";
            }
            $html .= '</tr>';
        }

        fclose($file);
        $html .= '</tbody></table>';

        return $html;
    }
}

if (!function_exists('generateHtmlTableFromCsv')) {
    function generateHtmlTableFromCsv($csvFilePath, $id = null, $class = []) {
        // Handle external URLs
        if (filter_var($csvFilePath, FILTER_VALIDATE_URL)) {
            // Try to fetch the remote CSV
            try {
                $fileContent = @file_get_contents($csvFilePath);
                if (!$fileContent) {
                    return '<p>Error: Could not read remote file.</p>';
                }

                $file = fopen('php://temp', 'r+');
                fwrite($file, $fileContent);
                rewind($file);
            } catch (\Exception $e) {
                return '<p>Error: ' . $e->getMessage() . '</p>';
            }
        } else {
            // Handle local file
            $relativePath = str_replace(url('/storage'), 'storage', $csvFilePath);
            $csvFilePath = public_path($relativePath);

            if (!file_exists($csvFilePath) || !is_readable($csvFilePath)) {
                return '<p>Error: File not found or unreadable.</p>';
            }

            $file = fopen($csvFilePath, 'r');
        }

        // Parse CSV
        $headers = fgetcsv($file);
        if (!$headers) return '<p>Data Not Found</p>';

        $html = '<table id="'.$id.'" class="table '.implode(" ", $class).' table-bordered table-hover">';
        $html .= '<thead class="thead-dark"><tr>';

        foreach ($headers as $index => $header) {
            $className = 'col_' . ($index + 1);
            $html .= "<th scope='col' class='$className text-left'>" . htmlspecialchars($header) . "</th>";
        }

        $html .= '</tr></thead><tbody>';

        while (($row = fgetcsv($file)) !== false) {
            $html .= '<tr>';
            foreach ($row as $index => $cell) {
                $className = 'col_' . ($index + 1);

                if (filter_var($cell, FILTER_VALIDATE_URL) || preg_match('/^(\/?storage\/)/', $cell)) {
                    $ext = strtolower(pathinfo(parse_url($cell, PHP_URL_PATH), PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];

                    if (in_array($ext, $imageExtensions)) {
                        $cell = "<a href='" . config('custom.assets_url').'/'. htmlspecialchars($cell) . "' target='_blank'><img class='img-thumbnail' src='" . config('custom.assets_url').'/'. htmlspecialchars($cell) . "' alt='Image' style='max-width: 100px; height: auto;' /></a>";
                    } else {
                        $cell = "<a href='" . config('custom.assets_url').'/'. htmlspecialchars($cell) . "' target='_blank' class='result_vm_btn'>View</a>";
                    }
                } else {
                    $cell = htmlspecialchars($cell);
                    $cell = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $cell);
                    $cell = nl2br($cell);
                }

                $html .= "<td class='$className text-left'>$cell</td>";
            }
            $html .= '</tr>';
        }

        fclose($file);
        $html .= '</tbody></table>';

        return $html;
    }
}


if (!function_exists('uploaded_asset_name')) {
    function uploaded_asset_name($id) {

        $asset = Cache::rememberForever('uploaded_asset_name_'.$id , function() use ($id) {
            return \App\Models\Upload::find($id);
        });

        $filename = 'Unknown';

        if ($asset != null) {
            $filename = $asset->file_original_name;
        }
                
        // Extract filename without extension
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace underscores and hyphens with spaces
        $formattedName = str_replace(['_', '-'], ' ', $nameWithoutExt);
        
        // Convert multiple spaces to a single space and trim excess spaces
        $formattedName = preg_replace('/\s+/', ' ', trim($formattedName));
    
        // Capitalize each word
        return ucwords($formattedName);
    }
}

if (!function_exists('uploaded_asset_type')) {
    function uploaded_asset_type($id) {

        $asset = Cache::rememberForever('uploaded_asset_type_'.$id , function() use ($id) {
            return \App\Models\Upload::find($id);
        });

        $filename = 'Unknown';

        if ($asset != null) {
            $filename = $asset->type;
        }
    
        // Capitalize each word
        return $filename;
    }
}

if (!function_exists('get_setting')) {
    function get_setting($metaKey, $default = null) {
        //return \Illuminate\Support\Facades\Cache::rememberForever("setting_" . config('custom.school_id') . "_{$metaKey}", function () use ($metaKey, $default) {
            $company = \App\Models\Company::with('meta')->where('id', config('custom.school_id'))->first();

            if (!$company) {
                return $default;
            }

            // First, check if the column exists in the companies table
            if (isset($company->$metaKey)) {
                return $company->$metaKey;
            }

            // Otherwise, check the meta table
            return $company->meta->where('meta_key', $metaKey)->first()->meta_value ?? $default;
        //});
    }
}

// if (!function_exists('makeImageThumbnail')) {
//     function makeImageThumbnail($relativePath, $width = 150, $height = 150, $quality = 80) {
        
//         try {

//             $publicPath = public_path($relativePath);

//             if (!file_exists($publicPath)) {
//                 return null;
//             }

//             $pathInfo = pathinfo($relativePath);
//             $originalFileName = $pathInfo['filename'] . '.' . $pathInfo['extension'];
//             $thumbDir = "storage/thumbs/{$width}x{$height}";
//             $thumbRelativePath = "$thumbDir/{$originalFileName}";
//             $thumbPublicPath = public_path($thumbRelativePath);

//             // Create thumbnail only if it doesn't exist
//             if (!file_exists($thumbPublicPath)) {
//                 $manager = new ImageManager(new Driver());

//                 // Ensure thumbnail directory exists
//                 if (!file_exists(public_path($thumbDir))) {
//                     mkdir(public_path($thumbDir), 0777, true);
//                 }

//                 // Resize + crop using cover (like object-fit: cover)
//                 $manager->read($publicPath)
//                     ->cover($width, $height)
//                     ->save($thumbPublicPath, $quality);
//             }

//             return asset($thumbRelativePath);

//         } catch (\Throwable $e) {
//             return null;
//         }
//     }
// }

if (!function_exists('makeImageThumbnail')) {
    function makeImageThumbnail($relativePath, $width = 150, $height = 150, $quality = 80) {
        try {
            $publicPath = public_path($relativePath);

            if (!file_exists($publicPath)) {
                return null;
            }

            $pathInfo = pathinfo($relativePath);
            $originalFileName = $pathInfo['basename']; // abc.jpg

            // Thumbnail directory (e.g., storage/thumbs/150x150/)
            $thumbDir = "storage/thumbs/{$width}x{$height}";
            $thumbRelativePath = "{$thumbDir}/{$originalFileName}";
            $thumbPublicPath = public_path($thumbRelativePath);

            // Create the directory if it doesn't exist
            if (!file_exists(public_path($thumbDir))) {
                mkdir(public_path($thumbDir), 0777, true);
            }

            // Only create thumbnail if it doesn't exist
            if (!file_exists($thumbPublicPath)) {
                $manager = new ImageManager(new Driver());

                $manager->read($publicPath)
                    ->cover($width, $height)
                    ->save($thumbPublicPath, $quality);
            }

            return asset($thumbRelativePath);

        } catch (\Throwable $e) {
            return null;
        }
    }
}

/*START - EDU PRINTS HELPER FUNCTIONS*/
if (!function_exists('get_edusprint_token')) {
    function get_edusprint_token() {
        try {
            $client = new Client();

            $response = $client->post('https://1nh.edusprint.in/api/EduSprint/GetTokenByCredential', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'UserName' => '1nhwebsiteapi',
                    'Password' => 'W@bsiteAPIP@sSW0rd_1NH',
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return trim($result['ResponseData']) ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('get_school_export_data')) {
    function get_school_export_data()
    {
        try {
            // Try to get from cache first
            return Cache::remember('school_export_data', now()->addDays(7), function () {
                $client = new Client();

                $response = $client->post('https://1nh.edusprint.in/api/EduSprint/GetSchoolExportAPIDataJSON', [
                    'headers' => [
                        'AuthToken'    => get_edusprint_token(), // 🔑 reuse the token helper we made earlier
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'RequestCriteria' => 'basic',
                    ],
                ]);

                $result = json_decode($response->getBody(), true);

                return $result['ResponseData'] ?? false;
            });
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('create_student_enquiry')) {
    function create_student_enquiry(array $studentData) {
        try {
            $client = new Client();

            $response = $client->post('https://1nh.edusprint.in/api/EduSprint/CreateStudentEnquiry', [
                'headers' => [
                    'AuthToken'    => get_edusprint_token(), // 🔑 fetch token dynamically
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'RequestCriteria' => 'insert',
                    'RequestJsonData' => json_encode([$studentData]),
                ],
            ]);

            $result = json_decode(json_decode($response->getBody(), true),true);
            if($result['Success'] == false){
                return $result['ResponseData'];
            }else{
                return $result['ResponseData'][0];
            }
        } catch (\Exception $e) {
            return false; // or $e->getMessage() for debugging
        }
    }
}
/*END - EDU PRINTS HELPER FUNCTIONS*/

/*Start - tiny MCE Helper*/
if (!function_exists('getTinyMCEApiKey')) {
    function getTinyMCEApiKey(): string
    {
        $now = Carbon::now();

        // Reset keys at start of new month
        TinyMCEKey::where('month', '!=', $now->month)
            ->orWhere('year', '!=', $now->year)
            ->update([
                'count' => 0,
                'month' => $now->month,
                'year'  => $now->year
            ]);

        // Get first available key under 1000 requests
        $key = TinyMCEKey::where('count', '<', 1000)->orderBy('id')->first();

        if (!$key) {
            return 'NO_KEY_AVAILABLE';
        }

        // Increment usage
        $key->increment('count');

        return $key->api_key;
    }
}

if (!function_exists('text_limit')) {
    function text_limit($text, $limit = 15)
    {
        return \Illuminate\Support\Str::limit($text, $limit);
    }
}

/*End - tiny MCE Helper*/

if (!function_exists('fetchUploadFromUrl')) {

    /**
     * Fetch file from external URL, store & insert into uploads table
     *
     * @param string $url
     * @param string $schoolId
     * @return string|false
     */
    // function fetchUploadFromUrl(string $url, string $schoolId = 'media-old')
    // {
    //     try {

    //         if (!filter_var($url, FILTER_VALIDATE_URL)) {
    //             return false;
    //         }

    //         $type = [
    //             "jpg" => "image",
    //             "jpeg" => "image",
    //             "png" => "image",
    //             "svg" => "image",
    //             "webp" => "image",
    //             "gif" => "image",
    //             "mp4" => "video",
    //             "mpeg" => "video",
    //             "webm" => "video",
    //             "mov" => "video",
    //             "avi" => "video",
    //             "mp3" => "audio",
    //             "wav" => "audio",
    //             "pdf" => "document",
    //             "doc" => "document",
    //             "docx" => "document",
    //             "xls" => "document",
    //             "xlsx" => "document",
    //             "csv" => "document",
    //         ];

    //         // Fetch file
    //         $response = Http::timeout(20)->get($url);

    //         if (!$response->successful()) {
    //             return false;
    //         }

    //         $body = $response->body();

    //         if ($body === null || strlen($body) === 0) {
    //             return false;
    //         }           

    //         // Detect extension
    //         $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

    //         if (!$extension || !isset($type[$extension])) {
    //             return false;
    //         }

    //         // File name
    //         $originalName = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
    //         $fileName     = Str::random(20) . '.' . $extension;

    //         // Path (same structure as your upload fn)
    //         $path = 'uploads/'.$schoolId.'/'.date('Y').'/'.date('m').'/'.$fileName;

    //         // Store file
    //         Storage::disk('public')->put($path, $response->body());

    //         // Save DB record (EXACT LIKE YOUR FUNCTION)
    //         $upload = new Upload;
    //         $upload->file_original_name = $originalName;
    //         $upload->extension          = $extension;
    //         $upload->file_name          = 'storage/'.$path;
    //         $upload->user_id            = Auth::check() ? Auth::id() : null;
    //         $upload->type               = $type[$extension];
    //         $upload->file_size          = strlen($response->body());
    //         $upload->save();

    //         return $upload->id;
    //         //return $upload->file_name;

    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }

    function fetchUploadFromUrl(string $url, string $schoolId = 'media-old')
    {
        try {

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            $allowedMimeMap = [
                'image/jpeg' => ['jpg', 'jpeg'],
                'image/png'  => ['png'],
                'image/webp' => ['webp'],
                'image/gif'  => ['gif'],
                'application/pdf' => ['pdf'],
            ];

            $response = Http::timeout(20)
                ->withHeaders(['Accept' => '*/*'])
                ->get($url);

            if (!$response->successful()) {
                return false;
            }

            // 🚫 Block HTML explicitly
            $contentType = strtolower($response->header('Content-Type', ''));

            if (
                str_contains($contentType, 'text/html') ||
                str_contains($contentType, 'text/plain')
            ) {
                return false;
            }

            $body = $response->body();
            if (!$body || strlen($body) < 100) {
                return false;
            }

            // 🧪 Real MIME detection
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $realMime = $finfo->buffer($body);

            if (!isset($allowedMimeMap[$realMime])) {
                return false;
            }

            // Extension from REAL MIME (not URL)
            $extension = $allowedMimeMap[$realMime][0];

            // 🖼️ Extra check for images
            if (str_starts_with($realMime, 'image/')) {
                if (@getimagesizefromstring($body) === false) {
                    return false;
                }
            }

            $fileName = Str::random(20).'.'.$extension;
            $path = 'uploads/'.$schoolId.'/'.date('Y').'/'.date('m').'/'.$fileName;

            Storage::disk('public')->put($path, $body);

            $upload = new Upload;
            $upload->file_original_name = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
            $upload->extension = $extension;
            $upload->file_name = 'storage/'.$path;
            $upload->type = str_starts_with($realMime, 'image/') ? 'image' : 'document';
            $upload->file_size = strlen($body);
            $upload->user_id = Auth::id();
            $upload->save();

            return $upload->id;

        } catch (\Throwable $e) {
            return false;
        }
    }

}