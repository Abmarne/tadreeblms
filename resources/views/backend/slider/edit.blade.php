@extends('backend.layouts.app')

@section('title', __('labels.backend.hero_slider.title').' | '.app_name())

@push('after-styles')
<style>
    .form-control-label { line-height: 35px; }
    .remove { float: right; color: red; font-size: 20px; cursor: pointer; }
    .error { color: red; }
</style>

<link rel="stylesheet" href="{{ asset('plugins/jqueryui-datetimepicker/jquery.datetimepicker.css') }}">
@endpush

@section('content')

<form method="POST"
      action="{{ route('admin.sliders.update', $slide->id) }}"
      enctype="multipart/form-data"
      class="form-horizontal"
      id="slider-create">

    @csrf
    @method('PATCH')

    <div class="alert alert-danger d-none" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <div class="error-list"></div>
    </div>

    <div class="d-flex justify-content-between align-items-center pb-3">
        <h4>@lang('labels.backend.hero_slider.edit')</h4>
        <a href="{{ route('admin.sliders.index') }}" class="add-btn">
            @lang('labels.backend.hero_slider.view')
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            {{-- NAME --}}
            <div class="row form-group">
                <label class="col-md-2 form-control-label">
                    @lang('labels.backend.hero_slider.fields.name')
                </label>
                <div class="col-md-10">
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $slide->name) }}"
                           placeholder="@lang('labels.backend.hero_slider.fields.name')"
                           maxlength="191"
                           autofocus>
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="row form-group">
                <label class="col-md-2 form-control-label">
                    @lang('labels.backend.hero_slider.fields.bg_image')
                </label>

                <div class="col-md-8">
                    <div class="custom-file-upload-wrapper">
                        <input type="file"
                               name="image"
                               id="customFileInput"
                               class="custom-file-input"
                               accept="image/*">
                        <label for="customFileInput" class="custom-file-label">
                            <i class="fa fa-upload mr-1"></i> Choose a file
                        </label>
                    </div>

                    <p class="help-text mb-0 font-italic mt-4">
                        {!! __('labels.backend.hero_slider.note') !!}
                    </p>

                    <input type="hidden" name="old_image" value="{{ $slide->bg_image }}">
                </div>

                <div class="col-md-2">
                    <img src="{{ asset('storage/uploads/'.$slide->bg_image) }}" height="50">
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">

                    <a href="{{ route('admin.sliders.index') }}"
                       class="btn btn-secondary">
                        @lang('buttons.general.cancel')
                    </a>

                    <button type="submit" class="add-btn">
                        @lang('buttons.general.crud.update')
                    </button>

                </div>
            </div>

        </div>
    </div>

</form>
@endsection

@push('after-scripts')
<script src="{{ asset('plugins/jqueryui-datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('js/slider-form.js') }}"></script>

<script>
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function(e) {
        const label = input.nextElementSibling;
        const fileName = e.target.files.length
            ? e.target.files[0].name
            : 'Choose a file';
        label.innerHTML = '<i class="fa fa-upload mr-1"></i> ' + fileName;
    });
});
</script>
@endpush
