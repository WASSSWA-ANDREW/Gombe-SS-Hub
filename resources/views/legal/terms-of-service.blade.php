@extends('layouts.admin')

@section('title', 'Terms of Service')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-contract"></i> Terms of Service
                    </h4>
                    <small>Last updated: {{ date('F d, Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="legal-content">
                        <h5>1. Acceptance of Terms</h5>
                        <p>By accessing and using the Gombe Secondary School Hub system, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

                        <h5>2. System Description</h5>
                        <p>The Gombe Secondary School Hub is a comprehensive school management system designed to facilitate:</p>
                        <ul>
                            <li>Student information management and academic record keeping</li>
                            <li>Staff administration and human resource management</li>
                            <li>Communication between school administration, staff, students, and parents</li>
                            <li>Report generation and data analysis</li>
                            <li>Administrative workflow automation</li>
                        </ul>

                        <h5>3. User Accounts and Access</h5>
                        <p><strong>Account Creation:</strong> User accounts are created and managed by authorized school administrators. Users are responsible for maintaining the confidentiality of their login credentials.</p>
                        <p><strong>Access Levels:</strong> The system provides different access levels based on user roles (administrators, teachers, students, parents). Users can only access information and features appropriate to their assigned role.</p>
                        <p><strong>Account Security:</strong> Users must immediately notify the school administration of any unauthorized use of their account or any other breach of security.</p>

                        <h5>4. Acceptable Use Policy</h5>
                        <p>Users agree to use the system only for legitimate educational and administrative purposes. Prohibited activities include:</p>
                        <ul>
                            <li>Attempting to gain unauthorized access to system resources or other user accounts</li>
                            <li>Uploading or transmitting malicious software, viruses, or harmful code</li>
                            <li>Using the system for commercial purposes without authorization</li>
                            <li>Harassing, threatening, or intimidating other users</li>
                            <li>Violating any applicable laws or regulations</li>
                            <li>Attempting to reverse engineer, decompile, or extract source code</li>
                        </ul>

                        <h5>5. Data Accuracy and Responsibility</h5>
                        <p>Users are responsible for ensuring the accuracy of information they input into the system. The school reserves the right to verify and correct information as necessary for administrative purposes.</p>

                        <h5>6. Intellectual Property Rights</h5>
                        <p>The Gombe Secondary School Hub system, including its design, functionality, and content, is protected by intellectual property laws. Users may not:</p>
                        <ul>
                            <li>Copy, modify, or distribute system components without authorization</li>
                            <li>Use school logos, trademarks, or branding without permission</li>
                            <li>Create derivative works based on the system</li>
                            <li>Remove or alter copyright notices or proprietary markings</li>
                        </ul>

                        <h5>7. Privacy and Data Protection</h5>
                        <p>The collection, use, and protection of personal information is governed by our Privacy Policy, which is incorporated into these terms by reference. Users consent to the collection and use of their information as described in the Privacy Policy.</p>

                        <h5>8. System Availability and Maintenance</h5>
                        <p>While we strive to maintain system availability, we do not guarantee uninterrupted access. The system may be temporarily unavailable due to:</p>
                        <ul>
                            <li>Scheduled maintenance and updates</li>
                            <li>Technical difficulties or system failures</li>
                            <li>Network connectivity issues</li>
                            <li>Security incidents requiring system shutdown</li>
                        </ul>

                        <h5>9. Limitation of Liability</h5>
                        <p>The school and system providers shall not be liable for any direct, indirect, incidental, special, or consequential damages resulting from:</p>
                        <ul>
                            <li>Use or inability to use the system</li>
                            <li>System downtime or technical failures</li>
                            <li>Data loss or corruption</li>
                            <li>Unauthorized access to user accounts</li>
                            <li>Errors or omissions in system content</li>
                        </ul>

                        <h5>10. User Conduct and Disciplinary Actions</h5>
                        <p>Violation of these terms may result in:</p>
                        <ul>
                            <li>Warning notices</li>
                            <li>Temporary suspension of system access</li>
                            <li>Permanent account termination</li>
                            <li>Referral to appropriate disciplinary authorities</li>
                            <li>Legal action if warranted</li>
                        </ul>

                        <h5>11. Modifications to Terms</h5>
                        <p>These terms may be updated periodically to reflect changes in system functionality, legal requirements, or school policies. Users will be notified of significant changes and continued use constitutes acceptance of modified terms.</p>

                        <h5>12. Termination</h5>
                        <p>User access may be terminated at any time by the school administration for violation of these terms, completion of educational program, or other administrative reasons. Upon termination, users must cease all use of the system.</p>

                        <h5>13. Governing Law</h5>
                        <p>These terms are governed by the laws of Nigeria and the educational regulations of Gombe State. Any disputes shall be resolved through appropriate legal channels within the jurisdiction.</p>

                        <h5>14. Contact Information</h5>
                        <p>Questions regarding these terms should be directed to:</p>
                        <div class="contact-info bg-light p-3 rounded">
                            <p><strong>Gombe Secondary School Hub</strong><br>
                            System Administrator<br>
                            Email: admin@gombeschoolhub.edu.ng<br>
                            Phone: +234 XXX XXX XXXX<br>
                            Address: Gombe State, Nigeria</p>
                        </div>

                        <div class="mt-4 p-3 bg-warning text-dark rounded">
                            <h6><i class="fas fa-exclamation-triangle"></i> Important Notice</h6>
                            <p class="mb-0">By using the Gombe Secondary School Hub system, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you must discontinue use of the system immediately.</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Effective Date: {{ date('F d, Y') }}</small>
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
.legal-content h5 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
}

.legal-content h5:first-child {
    margin-top: 0;
}

.legal-content ul {
    margin-bottom: 1.5rem;
}

.legal-content li {
    margin-bottom: 0.5rem;
}

.contact-info {
    border-left: 4px solid #3498db;
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