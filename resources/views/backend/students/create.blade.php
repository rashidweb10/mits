@php 
    $name = request()->name ?? null;
    $email = request()->email ?? null;
    $phone = request()->phone ?? null;
    //dd($name, $email, $phone);
@endphp


<form id="create" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <!-- Name -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input value="{{ $name }}" name="name" type="text" class="form-control" minlength="3" maxlength="200" required>
            </div>
        </div>

        <!-- Email -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input value="{{ $email }}" name="email" type="email" class="form-control" required>
            </div>
        </div>

        <!-- Phone -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="phone" class="form-label">Phone</label>
                <input value="{{ $phone }}" name="phone" type="text" class="form-control" maxlength="20" placeholder="Enter phone number">
            </div>
        </div>

        <!-- Location -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="location" class="form-label">Location</label>
                <input value="" name="location" type="text" class="form-control" maxlength="200" placeholder="Enter location">
            </div>
        </div>

        <!-- Password -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input value="" name="password" type="password" class="form-control" minlength="8" required>
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

