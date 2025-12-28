@extends('backend.layouts.app')

@section('title', __('labels.backend.certificates.title') . ' | ' . app_name())

@section('content')

<div class="pb-3 userheading">
    <h4 class=""> <span>  @lang('labels.backend.certificates.title')</span></h4>
</div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table id="myTable" class="table custom-teacher-table table-striped ">
                            <thead>
                                <tr>
                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>@lang('labels.backend.certificates.fields.course_name')</th>
                                    <th>@lang('labels.backend.certificates.fields.Download-Link')</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-scripts')
<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.certificates.get") }}', 
        select: { info: false }, // 👈 hides the “0 rows selected” message
        iDisplayLength: 10,

        language: {
            @if(app()->getLocale() == 'ar')
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
            @else
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/en-GB.json'
            @endif
        },

        columns: [
            { data: "DT_RowIndex", name: 'DT_RowIndex', searchable: false, orderable: false },
            { data: "title", name: 'title' },
            { data: "link", name: 'link' },
        ]
    });
});
</script>
@endpush


