<form id="create" action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Category -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="category_id" class="form-label">Course Category</label>
                <select name="category_id" id="category_id" class="form-select select2">
                    <option value="">--Select Category--</option>
                    @foreach ($categoryList as $index => $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
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
                    @foreach($courseList as $index => $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Title -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input value="" name="title" type="text" class="form-control" minlength="3" maxlength="200" required>
            </div>
        </div>
        
        <!-- Total Marks -->
        <div class="col-sm-6">
            <div class="form-group mb-2">
                <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                <input value="" name="total_marks" type="number" class="form-control" min="1" required>
            </div>
        </div>
        
        <!-- Pass Marks -->
        <div class="col-sm-6">
            <div class="form-group mb-2">
                <label for="pass_marks" class="form-label">Pass Marks <span class="text-danger">*</span></label>
                <input value="" name="pass_marks" type="number" class="form-control" min="1" required>
            </div>
        </div>

        <!-- Duration -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                <input value="" name="duration" type="number" class="form-control" min="1" max="300" required>
            </div>
        </div>

        <!-- Is Active (dropdown) -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-sm-12">
            <div class="text-center mt-1">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    initValidate('#create'); // Initializes validation for the form
    initSelect2('.select2');

    // When category changes, fetch courses for that category via AJAX
    $('#category_id').on('change', function () {
        const categoryId = $(this).val();
        const $courseSelect = $('#course_id');
        const currentCourseId = $courseSelect.val();

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

                if (Array.isArray(response) && response.length) {
                    response.forEach(function (course) {
                        options += '<option value="' + course.id + '">' + course.name + '</option>';
                    });
                }

                $courseSelect.html(options).trigger('change.select2');
            },
            error: function () {
                // On error, just reset to default option
                $courseSelect.html('<option value="">--Select Course--</option>').trigger('change.select2');
            }
        });
    });

    $("#create").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, callbackCreateForm);
    });

    const callbackCreateForm = function(response) {
        setTimeout(function() {
            location.reload(); // Reload the page after a successful form submission
        }, 1500);
    }
});
</script>
