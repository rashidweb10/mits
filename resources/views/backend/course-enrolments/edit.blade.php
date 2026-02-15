<form id="edit" action="{{ route('course-enrolments.update', $pageData->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">

        <!-- Student (User with role_id = 3) -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="user_id" class="form-label">Student <span class="text-danger">*</span></label>
                <select name="user_id" id="user_id" class="form-select select2" required>
                    <option value="">--Select Student--</option>
                    @php
                        $selectedUserId = old('user_id', $pageData->user_id);
                    @endphp
                    @foreach ($students as $index => $row)
                        <option value="{{ $row->id }}" @if($selectedUserId == $row->id) selected @endif>
                            {{ $row->name }} ({{ $row->email }})
                        </option>
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
                    @php
                        // Get the category ID - prefer course's category_id
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

        <!-- Validity -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="validity" class="form-label">Validity <span class="text-danger">*</span></label>
                <input value="{{ old('validity', $pageData->validity ? date('Y-m-d', strtotime($pageData->validity)) : '') }}" name="validity" type="date" class="form-control" required>
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
                        const selected = course.id == currentCourseId ? ' selected' : '';
                        options += '<option value="' + course.id + '"' + selected + '>' + course.name + '</option>';
                        if (course.id == currentCourseId) {
                            courseFound = true;
                        }
                    });
                }

                // If the current course is not in the filtered list, add it anyway (preserve selection)
                if (currentCourseId && !courseFound) {
                    options = '<option value="' + currentCourseId + '" selected>' + currentCourseName + '</option>' + options;
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

    // Trigger change on page load if category is already selected
    @if($pageData->course && $pageData->course->category_id)
        $('#category_id').trigger('change');
    @endif

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

