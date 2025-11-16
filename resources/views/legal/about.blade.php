@extends('layouts.admin')

@section('title', 'About Us')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle"></i> About Gombe Secondary School Hub
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Our Mission</h5>
                            <p class="lead">To provide a comprehensive, efficient, and user-friendly digital platform that enhances educational administration and supports the academic success of students in Gombe State secondary schools.</p>

                            <h5>About the System</h5>
                            <p>The Gombe Secondary School Hub is a state-of-the-art school management system designed specifically for secondary schools in Gombe State, Nigeria. Our platform streamlines administrative processes, improves communication, and provides valuable insights through comprehensive reporting and analytics.</p>

                            <h5>Key Features</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Student Information Management</li>
                                        <li><i class="fas fa-check text-success"></i> Staff Administration</li>
                                        <li><i class="fas fa-check text-success"></i> Academic Record Keeping</li>
                                        <li><i class="fas fa-check text-success"></i> Attendance Tracking</li>
                                        <li><i class="fas fa-check text-success"></i> Report Generation</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Multi-language Support</li>
                                        <li><i class="fas fa-check text-success"></i> Mobile-Responsive Design</li>
                                        <li><i class="fas fa-check text-success"></i> Secure Data Management</li>
                                        <li><i class="fas fa-check text-success"></i> User-Friendly Interface</li>
                                        <li><i class="fas fa-check text-success"></i> 24/7 Support System</li>
                                    </ul>
                                </div>
                            </div>

                            <h5>Our Vision</h5>
                            <p>To become the leading educational technology platform in Nigeria, empowering schools with innovative tools that enhance learning outcomes and administrative efficiency.</p>

                            <h5>Technology Stack</h5>
                            <p>Built with modern web technologies including Laravel PHP framework, MySQL database, and responsive Bootstrap frontend, ensuring reliability, security, and scalability.</p>

                            <h5>Support and Training</h5>
                            <p>We provide comprehensive training and ongoing support to ensure successful implementation and optimal use of the system. Our dedicated support team is available to assist with technical issues and user questions.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-school fa-4x text-primary mb-3"></i>
                                    <h6>Serving Schools Across Gombe State</h6>
                                    <p class="small text-muted">Empowering education through technology</p>
                                </div>
                            </div>

                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6><i class="fas fa-chart-line"></i> System Statistics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-primary">50+</h4>
                                            <small>Schools</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-success">10K+</h4>
                                            <small>Students</small>
                                        </div>
                                    </div>
                                    <div class="row text-center mt-2">
                                        <div class="col-6">
                                            <h4 class="text-info">500+</h4>
                                            <small>Staff</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-warning">99.9%</h4>
                                            <small>Uptime</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6><i class="fas fa-phone"></i> Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small">
                                        <strong>Address:</strong><br>
                                        Gombe State Ministry of Education<br>
                                        Gombe, Nigeria
                                    </p>
                                    <p class="small">
                                        <strong>Email:</strong><br>
                                        info@gombeschoolhub.edu.ng
                                    </p>
                                    <p class="small">
                                        <strong>Phone:</strong><br>
                                        +234 XXX XXX XXXX
                                    </p>
                                    <p class="small">
                                        <strong>Support Hours:</strong><br>
                                        Monday - Friday: 8:00 AM - 5:00 PM<br>
                                        Saturday: 9:00 AM - 2:00 PM
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <h5>Development Team</h5>
                            <p>The Gombe Secondary School Hub was developed by a dedicated team of software engineers, educators, and administrators working together to create a solution that meets the unique needs of Nigerian secondary schools.</p>
                            
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <i class="fas fa-code fa-2x text-primary mb-2"></i>
                                            <h6>Development Team</h6>
                                            <p class="small text-muted">Full-stack developers specializing in educational technology</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                                            <h6>Educational Consultants</h6>
                                            <p class="small text-muted">Experienced educators providing pedagogical guidance</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                            <h6>Security Team</h6>
                                            <p class="small text-muted">Cybersecurity experts ensuring data protection</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-primary text-white rounded">
                        <h6><i class="fas fa-heart"></i> Our Commitment</h6>
                        <p class="mb-0">We are committed to continuously improving our platform based on user feedback and evolving educational needs. Your success is our success, and we're here to support your educational journey every step of the way.</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">© {{ date('Y') }} Gombe Secondary School Hub. All rights reserved.</small>
                        <div>
                            <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="window.history.back()">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
h5 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
}

h5:first-child {
    margin-top: 0;
}

.list-unstyled li {
    padding: 0.25rem 0;
}

@media print {
    .card-header, .card-footer, .btn {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection