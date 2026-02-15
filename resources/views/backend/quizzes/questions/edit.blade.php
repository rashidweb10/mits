<form id="edit" action="{{ route('quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Question -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                <textarea name="question" class="form-control" rows="3" required placeholder="Enter your question">{{ old('question', $question->question) }}</textarea>
            </div>
        </div>
        
        <!-- Marks -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="marks" class="form-label">Marks <span class="text-danger">*</span></label>
                <input value="{{ old('marks', $question->marks) }}" name="marks" type="number" class="form-control" min="1" required>
            </div>
        </div>
        
        <!-- Options -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="options" class="form-label">Options <span class="text-danger">*</span></label>
                <div id="options-container">
                    @foreach ($question->options as $index => $option)
                    <div class="option-row mb-2 d-flex align-items-center gap-2">
                        <input type="radio" name="correct_option" value="{{ $index }}" class="correct-option-radio" {{ $option->is_correct ? 'checked' : '' }}>
                        <input type="text" name="options[{{ $index }}][option_text]" class="form-control flex-grow-1" value="{{ old('options.'.$index.'.option_text', $option->option_text) }}" required placeholder="Option {{ $index + 1 }}">
                        <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">
                        <button type="button" class="btn btn-danger btn-sm remove-option" {{ $question->options->count() <= 2 ? 'disabled' : '' }}>
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary btn-sm mt-2" id="add-option">
                    <i class="ti ti-plus"></i> Add Option
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-sm-12">
            <div class="text-center mt-1">
                <button type="submit" class="btn btn-primary">Update Question</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    initValidate('#edit');
    
    let optionCount = {{ $question->options->count() }};

    // Add new option
    $('#add-option').click(function() {
        optionCount++;
        const html = `
            <div class="option-row mb-2 d-flex align-items-center gap-2">
                <input type="radio" name="correct_option" value="${optionCount - 1}" class="correct-option-radio">
                <input type="text" name="options[${optionCount - 1}][option_text]" class="form-control flex-grow-1" required placeholder="Option ${optionCount}">
                <button type="button" class="btn btn-danger btn-sm remove-option">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        `;
        $('#options-container').append(html);
        
        // Update remove button states
        updateRemoveButtonStates();
    });

    // Remove option
    $('#options-container').on('click', '.remove-option', function() {
        if ($('.option-row').length > 2) {
            $(this).parent().remove();
            updateRemoveButtonStates();
        }
    });

    // Update remove button states
    function updateRemoveButtonStates() {
        $('.remove-option').prop('disabled', $('.option-row').length <= 2);
    }

    $("#edit").submit(function(e) {
        e.preventDefault();
        
        // Validate that a correct option is selected
        const correctOptionSelected = $("input[name='correct_option']:checked").length > 0;
        if (!correctOptionSelected) {
            toastr.error('Please select the correct option');
            return;
        }
        
        // Convert radio button value to options is_correct flag
        const correctIndex = parseInt($("input[name='correct_option']:checked").val());
        const options = [];
        
        $(".option-row").each(function(index) {
            const optionText = $(this).find("input[name^='options']").val();
            const optionId = $(this).find("input[name*='id']").val();
            options.push({
                id: optionId,
                option_text: optionText,
                is_correct: index === correctIndex ? 1 : 0
            });
        });

        // Remove any existing options input and add the serialized options
        $("input[name='options']").remove();
        const optionsInput = $("<input type='hidden' name='options'>").val(JSON.stringify(options));
        $(this).append(optionsInput);

        // Use the existing ajaxSubmit function to handle the form submission
        var form = $(this);
        ajaxSubmit(e, form, callbackEditForm);
    });

    const callbackEditForm = function(response) {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }
});
</script>
