<form id="edit" action="{{ route('courses.update', $pageData->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">

        <!-- Category -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select select2" required>
                    <option value="">--Select--</option>
                    @foreach ($categoryList as $index => $row)
                        <option value="{{ $row->id }}" @if(old('category_id', $pageData->category_id) == $row->id) selected @endif>{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Name -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="name" class="form-label">Name</label>
                <input value="{{ old('name', $pageData->name) }}" name="name" type="text" class="form-control" minlength="3" maxlength="200" required>
            </div>
        </div>

        <!-- Image -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                    </div>
                    <div class="form-control file-amount">{{ __('Choose File') }}</div>
                    <input type="hidden" name="image" class="selected-files" value="{{ $pageData->image }}" required>
                </div>
                <div class="file-preview box sm"></div>
            </div>
        </div>

        <!-- Brochure -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="brochure" class="form-label">Brochure</label>
                <div class="input-group" data-toggle="aizuploader" data-type="document" data-multiple="false">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                    </div>
                    <div class="form-control file-amount">{{ __('Choose File') }}</div>
                    <input type="hidden" name="brochure" class="selected-files" value="{{ $pageData->brochure }}">
                </div>
                <div class="file-preview box sm"></div>
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
    initTextEditor();
    AIZ.uploader.previewGenerate();
    initSelect2('.select2');

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

