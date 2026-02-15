@extends('frontend.layouts.app')

@section('meta.title', 'Certificate: ' . ($certificate->certificate_no ?? 'Certificate'))
@section('meta.description', 'View your course completion certificate')

@section('content')

<style>
   
</style>
<div class="container py-5">
    <div class="row justify-content-center" style="margin-top:120px;">
        <div class="col-lg-10">
            <div class="cirtificate_main">
               
                <div class="" style="border: 9px double #83d0f4;">
                    <div class="certificate-container bg-white p-5 rounded-3 shadow-sm border">
                    <div class="text-center" style="text-align:center; margin-bottom:30px;">
                                <img src="{{ asset('assets/backend/img/logo.png') }}" 
                                     alt="Institution Logo" 
                                     class="certificate-logo" 
                                     style="max-height: 80px;">
                            </div>
                            
                            <div class="text-center mb-4">
                            <h2 class="text-uppercase fw-bold mb-2" style="color:#00a0e3; font-size:22px;">Certificate of Completion</h2>
                            <p class="text-muted">This certificate is proudly presented to</p>
                        </div>

                        <div class="text-center mb-4">
                            <h1 class="" style="color:#00a0e3; font-size:48px; font-weight:bold">
                                {{ ucwords($certificate->user->name) ?? 'Student Name' }}
                            </h1>
                        </div>

                        <div class="text-center mb-3">
                            <p class="lead">
                                For successfully completing the course:
                            </p>
                            <h3 style="color:#00a0e3; font-size:18px; font-weight:700 !important">
                                {{ $certificate->course->name ?? 'Course Name' }}
                            </h3>
                        </div>

                        <div class="text-center mb-3" style="padding: 10px 30px;
    border: 2px solid #2098d1;
    border-radius: 50px;
    margin-top: 15px;
    width: 210px;
    margin-left: auto;
    margin-right: auto;">
                            <div class="lead" style="margin-bottom:0px;">
                                 <span style="    font-size: 16px;
    color: #555;
    font-weight: 500;">Quiz Score:</span> 
                                <span class="fw-bold" style="    font-size: 24px;
    font-weight: 700;
    color: #2098d1;">
                                    {{ $quizAttempt->obtained_marks ?? 0 }}/{{ $quizAttempt->total_marks ?? 0 }}
                                </span>
</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 text-center">
                                <p class="mb-0"><strong>Certificate Number:</strong></p>
                                <p class="fw-bold">{{ $certificate->certificate_no ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 text-center">
                                <p class="mb-0"><strong>Date Issued:</strong></p>
                                <p class="fw-bold">
                                    @if($certificate->issued_at)
                                        {{ is_string($certificate->issued_at) ? date('F j, Y', strtotime($certificate->issued_at)) : $certificate->issued_at->format('F j, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            
                            <p class="text-muted">
                                This certificate is awarded for demonstrating excellence in learning and commitment to personal growth.
                            </p>
                        </div>
                    </div>
                                     
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button onclick="printCertificate()" class="btn btn-primary me-md-2">
                            <i class="fas fa-print me-2"></i>Print Certificate
                        </button>
                        <a href="{{ route('auth.enrolled-courses') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Courses
                        </a>
                    </div>  
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .certificate-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #dee2e6;
        position: relative;
    }
    
    .certificate-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(0,0,0,0.05) 35px, rgba(0,0,0,0.05) 70px);
        opacity: 0.3;
        z-index: 0;
    }
    
    .certificate-container > * {
        position: relative;
        z-index: 1;
    }
    
    @media print {

        /* Hide everything */
        body * {
            visibility: hidden;
        }

        /* Show only certificate */
        .certificate-container,
        .certificate-container * {
            visibility: visible;
        }

        /* Position certificate properly */
        .certificate-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Remove watermark / overlay */
        .certificate-container::before {
            display: none !important;
        }

        /* Page settings */
        @page {
            size: A4;
            margin: 20mm;
        }
    }

</style>
@endsection

@section('scripts')
<script>
function printCertificate() {
    const cert = document.querySelector('.certificate-container');

    if (!cert) {
        alert('Certificate section not found');
        return;
    }

    // Save original page
    const originalHTML = document.body.innerHTML;

    // Clone certificate HTML
    document.body.innerHTML = `
        <html>
            <head>
                <title>Print Certificate</title>
                <style>
                    body {
                        margin: 0;
                        padding: 20mm;
                        font-family: Arial, sans-serif;
                        background: white;
                    }
                </style>
            </head>
            <body>
                ${cert.outerHTML}
            </body>
        </html>
    `;

    window.print();

    // Restore page
    document.body.innerHTML = originalHTML;
    location.reload(); // ensure JS & styles reload correctly
}
</script>
@endsection