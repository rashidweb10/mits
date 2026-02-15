@extends('frontend.layouts.profile')

@section('meta.title', $course->name)
@section('meta.description', "Syllabus and materials for " . $course->name)

@php $pageTitle = $course->name; @endphp

@section('profile-content')
<div class="container">
    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold robot_slab mb-1">{{ $course->name }}</h3>
                <div class="text-muted small">
                    Category: {{ $course->category->name ?? 'N/A' }}
                </div>
            </div>
            <a href="{{ route('auth.enrolled-courses') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        @if($course->materials->count())

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary btn-sm" id="toggle-accordion">
                Expand All
            </button>
        </div>

        <div class="accordion accordion-flush" id="courseMaterialsAccordion">
            @foreach($course->materials as $i => $material)
            @php
                $attachments = array_filter(explode(',', $material->attachments ?? ''));
            @endphp

            <div class="accordion-item mb-3 border rounded-3">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold"
                        data-bs-toggle="collapse"
                        data-bs-target="#lesson{{ $i }}">
                        📘 Lesson {{ $i+1 }}: {{ $material->title }}
                    </button>
                </h2>

                <div id="lesson{{ $i }}" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        @if($material->description)
                        <div class="mb-3 fw-light">
                            {!! $material->description !!}
                        </div>
                        @endif

                        <!-- ATTACHMENTS -->
                        @if($attachments)
                        <div class="row g-3">
                            @foreach($attachments as $k => $id)
                            @php
                                $url  = uploaded_asset($id);
                                $name = uploaded_asset_name($id);
                                $type = uploaded_asset_type($id);
                            @endphp

                            <div class="col-md-3 col-6">
                                @if($type === 'image')
                                <!-- IMAGE → MODAL -->
                                <div class="card attachment-card h-100 text-center open-image"
                                    data-url="{{ $url }}"
                                    data-name="{{ $name }}">
                                    <div class="card-body">
                                        <img src="{{ $url }}"
                                             class="img-fluid mb-2 rounded"
                                             style="height:100px;object-fit:contain;">
                                        <div class="small fw-semibold text-truncate">{{ $name }}</div>
                                    </div>
                                </div>
                                @else
                                <!-- DOCUMENT → MODAL -->
                                <div class="card attachment-card h-100 text-center open-document"
                                    data-url="{{ $url }}"
                                    data-name="{{ $name }}">
                                    <div class="card-body">
                                        <img src="{{ asset('assets/frontend/img/doc.png') }}"
                                             class="img-fluid mb-2 rounded"
                                             style="height:100px;object-fit:contain;">
                                        <div class="small fw-semibold text-truncate">{{ $name }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- VIDEO -->
                        @if($material->youtube_url)
                        <div class="mt-4">
                            <button class="btn btn-outline-danger btn-sm open-video"
                                data-video="{{ $material->youtube_url }}">
                                ▶ Watch Video Lesson
                            </button>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Course materials will be available soon.</p>
        </div>
        @endif

    </div>
</div>

<!-- IMAGE MODAL -->
<div class="modal fade" id="imageModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imageModalImg" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<!-- VIDEO MODAL -->
<div class="modal fade" id="videoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video Lesson</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="videoFrame"
                        width="100%"
                        height="500"
                        allow="autoplay; encrypted-media"
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>

<!-- DOCUMENT MODAL -->
<div class="modal fade" id="documentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalTitle"></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- PDF.js Viewer -->
                <div id="pdfViewer" style="display: none; padding: 20px; background: #111;">
                    <div id="pdfCanvasContainer"></div>
                </div>
                <!-- Fallback to iframe for other document types -->
                <iframe id="documentFrame"
                        width="100%"
                        height="600"
                        frameborder="0"
                        style="background: #fff; display: none;">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
// PDF.js configuration
pdfjsLib.GlobalWorkerOptions.workerSrc =
  'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

$(function () {

    // Expand / Collapse All
    $('#toggle-accordion').click(function () {
        let open = $('.accordion-collapse.show').length;
        $('.accordion-collapse').toggleClass('show', !open);
        $('.accordion-button').toggleClass('collapsed', open);
        $(this).text(open ? 'Expand All' : 'Collapse All');
    });

    // IMAGE MODAL
    $('.open-image').click(function () {
        $('#imageModalTitle').text($(this).data('name'));
        $('#imageModalImg').attr('src', $(this).data('url'));
        new bootstrap.Modal('#imageModal').show();
    });

    // VIDEO MODAL
    $('.open-video').click(function () {
        let video = $(this).data('video');
        $('#videoFrame').attr('src', video + '?autoplay=1');
        new bootstrap.Modal('#videoModal').show();
    });

    // STOP VIDEO ON CLOSE
    $('#videoModal').on('hidden.bs.modal', function () {
        $('#videoFrame').attr('src', '');
    });

    // DOCUMENT MODAL
    $('.open-document').click(function () {
        const fileName = $(this).data('name');
        const fileUrl = $(this).data('url');
        const extension = fileUrl.split('?')[0].split('.').pop().toLowerCase();
        
        $('#documentModalTitle').text(fileName);
        
        if(extension == 'pdf'){
            // Show PDF.js viewer and hide iframe
            $('#documentFrame').hide();
            $('#pdfViewer').show();
            
            // Render PDF
            renderPDF(fileUrl);
            
            new bootstrap.Modal('#documentModal').show(); 
            return true;           
        } else {
            // Show iframe for other document types
            // $('#pdfViewer').hide();
            // $('#documentFrame').show();
            
            // const viewerUrl = `${fileUrl}#toolbar=0&navpanes=0&scrollbar=0`;
            // $('#documentFrame').attr('src', viewerUrl);
            // new bootstrap.Modal('#documentModal').show(); 
            // return true;
            alert(`Document Not Supported: ${extension}`);
        }
    });

    // CLEAR DOCUMENT VIEWER ON CLOSE
    $('#documentModal').on('hidden.bs.modal', function () {
        $('#documentFrame').attr('src', '');
        $('#pdfCanvasContainer').empty();
        $('#pdfViewer').hide();
        $('#documentFrame').show();
    });

    // PDF rendering function
    function renderPDF(url) {
        const container = document.getElementById('pdfCanvasContainer');
        container.innerHTML = '';
        
        pdfjsLib.getDocument(url).promise.then(pdf => {
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                pdf.getPage(pageNum).then(page => {
                    const scale = 1.4;
                    const viewport = page.getViewport({ scale });

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    container.appendChild(canvas);

                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    });
                });
            }
        });
    }

    // // Enhanced protection for PDF viewer
    // $('#pdfViewer').on('contextmenu', function(e) {
    //     e.preventDefault();
    // });
    
    // $('#pdfViewer').on('copy cut paste selectstart', function(e) {
    //     e.preventDefault();
    // });
    
    // $(document).on('keydown', function(e) {
    //     if ($('#pdfViewer').is(':visible')) {
    //         if (
    //             (e.ctrlKey && ['p','s','c','u','x','v'].includes(e.key.toLowerCase())) ||
    //             e.key === 'PrintScreen'
    //         ) {
    //             e.preventDefault();
    //         }
    //     }
    // });

});
</script>

<style>
.attachment-card {
    cursor: pointer;
    transition: all .2s ease;
}
.attachment-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,.12);
}

#pdfCanvasContainer {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

#pdfCanvasContainer canvas {
    max-width: 100%;
    box-shadow: 0 0 20px rgba(0,0,0,.6);
    background: #fff;
}
</style>
@endsection