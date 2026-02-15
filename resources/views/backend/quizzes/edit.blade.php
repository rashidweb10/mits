<form id="edit" action="{{ route('quizzes.update', $pageData->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Category -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="category_id" class="form-label">Course Category</label>
                <select name="category_id" id="category_id" class="form-select select2">
                    <option value="">--Select Category--</option>
                    @php
                        // Get the category ID - use quiz's course category
                        $selectedCategoryId = old('category_id', $pageData->course->category_id ?? null);
                    @endphp
                    @foreach ($categoryList as $index => $row)
                        <option value="{{ $row->id }}" @if($selectedCategoryId == $row->id) selected @endif>{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Course -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                <select name="course_id" id="course_id" class="form-select select2" required>
                    <option value="">--Select Course--</option>
                    @php
                        $selectedCourseId = old('course_id', $pageData->course_id);
                    @endphp
                    @foreach ($courseList as $index => $row)
                        @if($selectedCourseId == $row->id)
                        <option value="{{ $row->id }}" selected>{{ $row->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Title -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input value="{{ old('title', $pageData->title) }}" name="title" type="text" class="form-control" minlength="3" maxlength="200" required>
            </div>
        </div>
        
        <!-- Total Marks -->
        <div class="col-sm-6">
            <div class="form-group mb-2">
                <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                <input value="{{ old('total_marks', $pageData->total_marks) }}" name="total_marks" type="number" class="form-control" min="1" required>
            </div>
        </div>
        
        <!-- Pass Marks -->
        <div class="col-sm-6">
            <div class="form-group mb-2">
                <label for="pass_marks" class="form-label">Pass Marks <span class="text-danger">*</span></label>
                <input value="{{ old('pass_marks', $pageData->pass_marks) }}" name="pass_marks" type="number" class="form-control" min="1" required>
            </div>
        </div>

        <!-- Duration -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                <input value="{{ old('duration', $pageData->duration ?? 60) }}" name="duration" type="number" class="form-control" min="1" max="300" required>
            </div>
        </div>

        <!-- Is Active (dropdown) -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" @if(old('is_active', $pageData->is_active) == 1) selected @endif>Active</option>
                    <option value="0" @if(old('is_active', $pageData->is_active) == 0) selected @endif>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-sm-12">
            <div class="text-center mt-1">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    initValidate('#edit'); // Initializes validation for the form
    initSelect2('.select2');

    // Store the initially selected course ID and name
    const initialCourseId = $('#course_id').val();
    const initialCourseName = $('#course_id option:selected').text();

    // When category changes, fetch courses for that category via AJAX
    $('#category_id').on('change', function () {
        const categoryId = $(this).val();
        const $courseSelect = $('#course_id');
        const currentCourseId = $courseSelect.val() || initialCourseId;
        const currentCourseName = $courseSelect.find('option:selected').text() || initialCourseName;

        // Show a temporary loading option
        $courseSelect.html('<option value="">Loading...</option>');

        $.ajax({
            url: '{{ route('courses.by-category') }}',
            method: 'GET',
            data: {
                category_id: categoryId
            },
            success: function (response) {
                // Reset options
                let options = '<option value="">--Select Course--</option>';
                let courseFound = false;

                if (Array.isArray(response) && response.length) {
                    response.forEach(function (course) {
                        options += '<option value="' + course.id + '">' + course.name + '</option>';
                    });
                }

                $courseSelect.html(options).trigger('change.select2');
            },
            error: function () {
                // On error, preserve the current selection
                let options = '<option value="">--Select Course--</option>';
                if (currentCourseId) {
                    options = '<option value="' + currentCourseId + '" selected>' + currentCourseName + '</option>';
                }
                $courseSelect.html(options).trigger('change.select2');
            }
        });
    });

    $("#edit").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, callbackEditForm);
    });

    const callbackEditForm = function(response) {
        setTimeout(function() {
            location.reload(); // Reload the page after a successful form submission
        }, 1500);
    }
});
</script>
