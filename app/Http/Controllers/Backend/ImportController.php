<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    // Common Excel reader function
    private function readExcel($filename)
    {
        $path = public_path("import/" . $filename);

        if (!file_exists($path)) {
            return ['status' => false, 'message' => "$filename not found in public/import"];
        }

        $data = Excel::toArray([], $path);
        return ['status' => true, 'rows' => $data[0]];
    }

    // -----------------------------------------
    // 1) COURSE CATEGORIES IMPORT
    // -----------------------------------------
    public function importCourseCategories()
    {
        $data = $this->readExcel("tblcoursecategories.csv");
        if (!$data['status']) return $data;

        $rows = $data['rows'];
        $insert = [];

        array_shift($rows); // removes first row

        foreach ($rows as $r) {

            if (!isset($r[0])) continue;

            $insert[] = [
                'id'         => $r[0],
                'name'       => $r[2],
                'image'      => null,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($insert, 100) as $chunk) {
            DB::table('course_categories')->insertOrIgnore($chunk);
        }

        return "Course Categories Imported Successfully";
    }

    // -----------------------------------------
    // 2) COURSES IMPORT
    // -----------------------------------------
    public function importCourses()
    {
        $data = $this->readExcel("tblcourses.csv");
        if (!$data['status']) return $data;

        $rows = $data['rows'];
        $insert = [];

        array_shift($rows); // removes first row

        foreach ($rows as $r) {
            if (!isset($r[0])) continue;

            $insert[] = [
                'id'         => $r[0],
                'name'       => $r[1] ?? null,
                'image'      => null,
                'category_id'=> $r[2] ?? 0,
                'is_active'  => $r[3] ?? 1,
                'created_at' => (!empty($r[5]) && $r[5] != '0000-00-00 00:00:00') ? $r[5] : now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($insert, 100) as $chunk) {
            DB::table('courses')->insertOrIgnore($chunk);
        }

        return "Courses Imported Successfully";
    }

    // -----------------------------------------
    // 3) COURSE ENROLMENTS IMPORT
    // -----------------------------------------
    public function importCourseEnrolments()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0); // prevents execution timeout

        $data = $this->readExcel("tblusercourse.csv");
        if (!$data['status']) return $data;

        $rows = $data['rows'];
        array_shift($rows); // remove header row

        $insert = [];
        $chunkSize = 200; // best performance for 100k rows

        foreach ($rows as $index => $r) {
            // skip empty row
            if (!isset($r[0]) || $r[0] === null || trim($r[0]) === '') continue;

            $insert[] = [
                'id'         => $r[0],
                'user_id'    => $r[1],
                'course_id'  => $r[2] ?? null,
                'is_active'  => $r[4] ?? 0,
                'validity'   => (!empty($r[6]) && $r[6] != '0000-00-00 00:00:00') ? \Carbon\Carbon::parse($r[6])->addDays(365)->format('Y-m-d') : now()->addDays(365)->format('Y-m-d'),
                'created_at' => (!empty($r[6]) && $r[6] != '0000-00-00 00:00:00') ? $r[6] : now(),
                'updated_at' => now(),
            ];

            // Insert when chunk reaches limit
            if (count($insert) === $chunkSize) {
                DB::table('course_enrolments')->insertOrIgnore($insert);
                $insert = []; // reset array
            }
        }

        // Insert remaining rows
        if (!empty($insert)) {
            DB::table('course_enrolments')->insertOrIgnore($insert);
        }

        return "Course Enrolments Imported Successfully";
    }

    // -----------------------------------------
    // 4) COURSE MATERIALS IMPORT
    // -----------------------------------------
    public function importCourseMaterials()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $data = $this->readExcel("tblcoursematerials.csv");
        if (!$data['status']) return $data;

        $rows = $data['rows'];
        array_shift($rows); // remove header row

        $insert = [];
        $chunkSize = 100;

        foreach ($rows as $r) {

            if (!isset($r[0]) || trim($r[0]) === '') continue;

            $insert[] = [
                'id'          => $r[0],
                'course_id'   => $r[1],
                'title'       => null,
                'description' => null,
                'attachments' => null,
                'is_active'   => $r[3] ?? 0,
                'created_at'  => (!empty($r[5]) && $r[5] != '0000-00-00 00:00:00') ? $r[5] : now(),
                'updated_at'  => now(),
            ];

            // Insert when chunk is filled
            if (count($insert) === $chunkSize) {
                DB::table('course_materials')->insertOrIgnore($insert);
                $insert = []; // reset array
            }
        }

        // Insert any remaining rows less than chunk size
        if (!empty($insert)) {
            DB::table('course_materials')->insertOrIgnore($insert);
        }

        return "Course Materials Imported Successfully";
    }

    // -----------------------------------------
    // 5) USERS IMPORT
    // -----------------------------------------
    public function importUsers()
    {
        $data = $this->readExcel("tblusers.csv");
        if (!$data['status']) return $data;

        $rows = $data['rows'];
        $insert = [];

        array_shift($rows); // removes first row

        foreach ($rows as $r) {

            //dd($r);

            if (!isset($r[0])) continue;

            $insert[] = [
                'id'                 => $r[0],
                'role_id'            => 3,
                'company_id'         => null,
                'name'               => ucwords($r[4]),
                'email'              => $r[1] ?? null,
                'phone'              => $r[3] ?? null,
                'location'           => $r[5] ?? null,
                'is_active'          => 1,
                'email_verified_at'  => null,
                'password'           => null,
                'remember_token'     => null,
                'created_at'         => (!empty($r[15]) && $r[15] != '0000-00-00 00:00:00') ? $r[15] : now(),
                'updated_at'         => now(),
            ];
        }

        foreach (array_chunk($insert, 100) as $chunk) {
            DB::table('users')->insertOrIgnore($chunk);
        }

        return "Users Imported Successfully";
    }

    // -----------------------------------------
    // 6) COURSE MATERIALS IMAGE IMPORT
    // -----------------------------------------
    public function importCourseMaterialImages(Request $request)
    {
        $limit = 300;

        // Get order from URL, default = asc
        $order = strtolower($request->query('order', 'asc'));

        // Allow only asc or desc (security)
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }        

        // Total pending before process
        $totalPendingBefore = DB::table('tblcoursematerials')
            ->where('cm_status', 0)
            ->count();

        // Fetch 10 pending records
        $materials = DB::table('tblcoursematerials')
            ->where('cm_status', 0)
            ->orderBy('cm_id', $order)
            ->limit($limit)
            ->get();

        $processed = 0;
        $uploaded  = 0;
        $failed    = 0;

        foreach ($materials as $material) {

            $processed++;

            // External URL
            $externalUrl = "https://www.marinarch.in/uploads/" . trim($material->cm_file);

            // Call helper
            $uploadedPath = fetchUploadFromUrl($externalUrl);

            if ($uploadedPath !== false) {

                DB::table('tblcoursematerials')
                    ->where('cm_id', $material->cm_id)
                    ->update([
                        //'cm_file'            => $uploadedPath,
                        'cm_status'          => 1,
                        'cm_modified_date'   => now()
                    ]);

                //upload image id in course_materials
                DB::table('course_materials')
                    ->where('id', $material->cm_id)
                    ->update([
                        'attachments'  => $uploadedPath,
                        'updated_at'   => now(),
                    ]);                

                $uploaded++;

            } else {

                DB::table('tblcoursematerials')
                    ->where('cm_id', $material->cm_id)
                    ->update([
                        'cm_status'          => 2,
                        'cm_modified_date'  => now()
                    ]);

                $failed++;
            }
        }

        // Pending after process
        $pendingAfter = DB::table('tblcoursematerials')
            ->where('cm_status', 0)
            ->count();

        // Pending after process
        $junks = DB::table('tblcoursematerials')
            ->where('cm_status', 2)
            ->count();            

        return response()->json([
            //'total_pending_before' => $totalPendingBefore,
            'attempted'     => $processed,
            'success'       => $uploaded,
            'failed'        => $failed,
            'total_pending' => $pendingAfter,
            'total_junks'   => $junks,
        ]);
    }   

}
