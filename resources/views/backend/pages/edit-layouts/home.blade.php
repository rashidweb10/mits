
@php
$banner_title = $pageData->meta->where('meta_key', 'banner_title')->first()->meta_value ?? '';
$banner_description = $pageData->meta->where('meta_key', 'banner_description')->first()->meta_value ?? '';
$banner_images = $pageData->meta->where('meta_key', 'banner_images')->first()->meta_value ?? '';

$popup_title = $pageData->meta->where('meta_key', 'popup_title')->first()->meta_value ?? '';
$popup_description = $pageData->meta->where('meta_key', 'popup_description')->first()->meta_value ?? '';
$popup_image = $pageData->meta->where('meta_key', 'popup_image')->first()->meta_value ?? '';

$about_title = $pageData->meta->where('meta_key', 'about_title')->first()->meta_value ?? '';
$about_description = $pageData->meta->where('meta_key', 'about_description')->first()->meta_value ?? '';
$about_image = $pageData->meta->where('meta_key', 'about_image')->first()->meta_value ?? '';


$about_school_title = $pageData->meta->where('meta_key', 'about_school_title')->first()->meta_value ?? '';
$about_school_description = $pageData->meta->where('meta_key', 'about_school_description')->first()->meta_value ?? '';

$testimonial_images = $pageData->meta->where('meta_key', 'testimonial_images')->first()->meta_value ?? '';

$home_milestones = json_decode($pageData->meta->where('meta_key', 'home_milestones')->first()->meta_value ?? '[]', true);

$achievement_title = $pageData->meta->where('meta_key', 'achievement_title')->first()->meta_value ?? '';
$achievement_description = $pageData->meta->where('meta_key', 'achievement_description')->first()->meta_value ?? '';
$achievement_image = $pageData->meta->where('meta_key', 'achievement_image')->first()->meta_value ?? '';

$video = $pageData->meta->where('meta_key', 'video')->first()->meta_value ?? '';

$home_quicklinks = json_decode($pageData->meta->where('meta_key', 'home_quicklinks')->first()->meta_value ?? '[]', true);

@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Banner Section</h4>
    </div>     
    <div class="col-md-6 form-group">
        <label for="name" class="form-label">Title <span class="text-danger">*</span></label>
        <input class="form-control" value="{{$banner_title}}" name="meta[banner_title]" type="text" required>
    </div> 
    <div class="col-md-6">
        <label for="name" class="form-label">Banner <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="video" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{$banner_images}}" type="hidden" name="meta[banner_images]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>   
    <div class="col-md-12 form-group mb-2">
        <label for="content" class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[banner_description]" class="form-control text-editor" rows="4" required>{{$banner_description}}</textarea>
    </div>     
</div> 

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Popup Section</h4>
    </div>        
    <div class="col-md-6 form-group mb-2">
        <label for="name" class="form-label">Title<span class="text-danger">*</span></label>
        <input class="form-control" value="{{$popup_title}}" name="meta[popup_title]" type="text" required>
    </div>   
    <div class="col-md-6">
        <label for="name" class="form-label">Image<span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{$popup_image}}" type="hidden" name="meta[popup_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>     
    <div class="col-md-12 form-group mb-2">
        <label for="content" class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[popup_description]" class="form-control text-editor" rows="4" required>{{$popup_description}}</textarea>
    </div>     
</div> 

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">About Section</h4>
    </div>        
    <div class="col-md-6 form-group mb-2">
        <label for="name" class="form-label">Title<span class="text-danger">*</span></label>
        <input class="form-control" value="{{$about_title}}" name="meta[about_title]" type="text" required>
    </div>   
    <div class="col-md-6">
        <label for="name" class="form-label">Image<span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{$about_image}}" type="hidden" name="meta[about_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>     
    <div class="col-md-12 form-group mb-2">
        <label for="content" class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[about_description]" class="form-control text-editor" rows="4" required>{{$about_description}}</textarea>
    </div>     
</div> 

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">About Section 2</h4>
    </div>        
    <div class="col-md-12 form-group mb-2">
        <label for="name" class="form-label">Title<span class="text-danger">*</span></label>
        <input class="form-control" value="{{$about_school_title}}" name="meta[about_school_title]" type="text" required>
    </div>        
    <div class="col-md-12 form-group mb-2">
        <label for="content" class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[about_school_description]" class="form-control text-editor" rows="4" required>{{$about_school_description}}</textarea>
    </div>     
</div>

{{-- <div class="row d-none">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Testimonials Section</h4>
    </div>      
    <div class="col-md-12">
        <label for="name" class="form-label">Images <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{$testimonial_images}}" type="hidden" name="meta[testimonial_images]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>    
</div> --}}

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Milestones</h4>
    </div>    
    <div class="{{$pageData->layout}}-target">
        @if(isset($home_milestones['itration']) && is_array($home_milestones['itration']))
            @foreach($home_milestones['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-12">
                        <label for="name" class="form-label">Milestones <span class="text-danger">*</span></label>
                        <input value="{{ $index }}" name="meta[home_milestones][itration][]" type="hidden" required>
                    </div> 
                    <div class="col-md">
                        <div class="form-group mb-2">
                            <input value="{{ $home_milestones['title'][$index] ?? '' }}" 
                                name="meta[home_milestones][title][]" 
                                type="text" 
                                class="form-control" 
                                minlength="1" 
                                maxlength="200" 
                                placeholder="E.g +1000" 
                                required>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group mb-2">
                            <input value="{{ $home_milestones['description'][$index] ?? '' }}" 
                                name="meta[home_milestones][description][]" 
                                type="text" 
                                class="form-control" 
                                minlength="3" 
                                maxlength="200" 
                                placeholder="E.g Years in Education" 
                                required>
                        </div>
                    </div>                   
                    <div class="col-md-auto">
                        <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <button
        type="button"
        class="mt-1 btn btn-soft-success btn-icon w-100"
        data-toggle="add-more"
        data-content='
            <div class="row remove-parent">
                <div class="col-md-12">
                    <label for="name" class="form-label">Milestones <span class="text-danger">*</span></label>
                    <input value="data" name="meta[home_milestones][itration][]" type="hidden" required>
                </div> 
                <div class="col-md">
                    <div class="form-group mb-2">
                        <input value="" name="meta[home_milestones][title][]" type="text" class="form-control" minlength="1" maxlength="200" placeholder="E.g +1000" required>
                    </div>
                </div> 
                <div class="col-md">
                    <div class="form-group mb-2">
                        <input value="" name="meta[home_milestones][description][]" type="text" class="form-control" minlength="3" maxlength="200" placeholder="E.g Years in Education" required>
                    </div>
                </div>              
                <div class="col-md-auto">
                    <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>   
        '
        data-target=".{{$pageData->layout}}-target">
        <i class="ti ti-plus"></i>
        <span class="ml-2">Add More</span>
    </button>     
</div> 

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Achievement Section</h4>
    </div>        
    <div class="col-md-6 form-group mb-2">
        <label for="name" class="form-label">Title<span class="text-danger">*</span></label>
        <input class="form-control" value="{{$achievement_title}}" name="meta[achievement_title]" type="text" required>
    </div>   
    <div class="col-md-6">
        <label for="name" class="form-label">Image <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{$achievement_image}}" type="hidden" name="meta[achievement_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>     
    <div class="col-md-12 form-group mb-2">
        <label for="content" class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[achievement_description]" class="form-control text-editor" rows="4" required>{{$achievement_description}}</textarea>
    </div>     
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Video Section</h4>
    </div>     
    <div class="col-md-12">
        <label for="name" class="form-label">Youtube Video <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <input class="form-control" value="{{$video}}" name="meta[video]" type="text" required>
        </div>
    </div>    
</div> 

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Quick links</h4>
    </div>    
    <div class="quicklinks-target">
        @if(isset($home_quicklinks['itration']) && is_array($home_quicklinks['itration']))
            @foreach($home_quicklinks['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-12">
                        <label for="name" class="form-label">Quick links <span class="text-danger">*</span></label>
                        <input value="{{ $index }}" name="meta[home_quicklinks][itration][]" type="hidden" required>
                    </div> 
                    <div class="col-md">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" 
                                    name="meta[home_quicklinks][icon][]" 
                                    class="selected-files" 
                                    value="{{ $home_quicklinks['icon'][$index] ?? '' }}" 
                                    required>
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group mb-2">
                            <input value="{{ $home_quicklinks['title'][$index] ?? '' }}" 
                                name="meta[home_quicklinks][title][]" 
                                type="text" 
                                class="form-control" 
                                minlength="3" 
                                maxlength="200" 
                                placeholder="Enter Title" 
                                required>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group mb-2">
                            <input value="{{ $home_quicklinks['url'][$index] ?? '' }}" 
                                name="meta[home_quicklinks][url][]" 
                                type="text" 
                                class="form-control" 
                                minlength="1" 
                                maxlength="200" 
                                placeholder="Enter URL" 
                                required>
                        </div>
                    </div>                                       
                    <div class="col-md-auto">
                        <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <button
        type="button"
        class="mt-1 btn btn-soft-success btn-icon w-100"
        data-toggle="add-more"
        data-content='
            <div class="row remove-parent">
                <div class="col-md-12">
                    <label for="name" class="form-label">Quick links <span class="text-danger">*</span></label>
                    <input value="data" name="meta[home_quicklinks][itration][]" type="hidden" required>
                </div> 
                <div class="col-md">
                    <div class="form-group mb-2">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                            </div>
                            <div class="form-control file-amount">{{ __('Choose File') }}</div>
                            <input type="hidden" name="meta[home_quicklinks][icon][]" class="selected-files" required>
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>
                </div> 
                <div class="col-md">
                    <div class="form-group mb-2">
                        <input value="" name="meta[home_quicklinks][title][]" type="text" class="form-control" minlength="3" maxlength="200" placeholder="Enter Title" required>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group mb-2">
                        <input value="" name="meta[home_quicklinks][url][]" type="text" class="form-control" minlength="1" maxlength="200" placeholder="Enter URL" required>
                    </div>
                </div>                              
                <div class="col-md-auto">
                    <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>   
        '
        data-target=".quicklinks-target">
        <i class="ti ti-plus"></i>
        <span class="ml-2">Add More</span>
    </button>     
</div>  