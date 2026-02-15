@php 

$student_id = request()->get('student_id');
$email = request()->get('email');
$category = request()->get('category');

@endphp


<form id="create" action="{{ route('course-enrolments.store') }}" method="POST">
    @csrf
    <div class="row">

        <!-- Student (User with role_id = 3) -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="user_id" class="form-label">Student <span class="text-danger">*</span></label>
                <select name="user_id" id="user_id" class="form-select select2" required>
                    <option value="">--Select Student--</option>
                    @foreach ($students as $index => $row)
                        <option value="{{ $row->id }}" @if($student_id == $row->id || $email == $row->email) selected @endif>{{ $row->name }} ({{ $row->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Category -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="category_id" class="form-label">Course Category <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" class="form-select select2" required>
                    <option value="">--Select Category--</option>
                    @foreach ($categoryList as $index => $row)
                        <option value="{{ $row->id }}" @if($category == $row->name) selected @endif>{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Courses (Checkboxes) -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label class="form-label">Courses <span class="text-danger">*</span></label>
                <div class="form-check mb-2" id="select-all-container" style="display: none;">
                    <input class="form-check-input" type="checkbox" id="select-all-courses">
                    <label class="form-check-label" for="select-all-courses">Select All</label>
                </div>
                <div id="courses-container">
                    <!-- Courses will be loaded here via AJAX -->
                </div>
            </div>
        </div>

        <!-- Validity -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="validity" class="form-label">Validity <span class="text-danger">*</span></label>
                <input value="" name="validity" type="date" class="form-control" required>
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

    setTimeout(function () {
        // Auto trigger when coming from URL
        if ($('#category_id').val() && $('#user_id').val()) {
            $('#category_id').trigger('change');
        }   
    }, 1000);

    // When category or student changes, fetch courses via AJAX
    $('#category_id, #user_id').on('change', function () {
        const categoryId = $('#category_id').val();
        const userId = $('#user_id').val();
        const $coursesContainer = $('#courses-container');
        const $selectAllContainer = $('#select-all-container');

        // Check if both category and student are selected
        if(categoryId == '' || userId == '') {
            $coursesContainer.html('Please select both category and student first.');
            $selectAllContainer.hide();
            return false;
        }

        // Show a temporary loading message
        $coursesContainer.html('<p>Loading...</p>');
        $selectAllContainer.hide();

        $.ajax({
            url: '{{ route('courses.by-category') }}',
            method: 'GET',
            data: {
                category_id: categoryId,
                user_id: userId
            },
            success: function (response) {
                // Reset options
                let checkboxes = '';

                if (Array.isArray(response) && response.length) {
                    response.forEach(function (course) {
                        const isDisabled = course.is_enrolled ? 'disabled' : '';
                        const disabledText = course.is_enrolled ? ' (Already Enrolled)' : '';
                        checkboxes += '<div class="form-check">' +
                            '<input class="form-check-input course-checkbox" type="checkbox" name="course_ids[]" value="' + course.id + '" id="course_' + course.id + '" ' + isDisabled + '>' +
                            '<label class="form-check-label" for="course_' + course.id + '">' + course.name + disabledText + '</label>' +
                            '</div>';
                    });
                    $selectAllContainer.show();
                } else {
                    checkboxes = '<p>No courses found for this category.</p>';
                    $selectAllContainer.hide();
                }

                $coursesContainer.html(checkboxes);
            },
            error: function () {
                // On error, just reset to default option
                $coursesContainer.html('<p>Error loading courses.</p>');
                $selectAllContainer.hide();
            }
        });
    });

    // Select All functionality
    $('#select-all-courses').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.course-checkbox:not(:disabled)').prop('checked', isChecked);
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

