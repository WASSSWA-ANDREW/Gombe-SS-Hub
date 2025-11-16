@extends('layouts.admin')

@section('title', 'Privacy Policy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Privacy Policy
                    </h4>
                    <small>Last updated: {{ date('F d, Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="legal-content">
                        <h5>1. Information We Collect</h5>
                        <p>The Gombe Secondary School Hub collects and processes the following types of information:</p>
                        <ul>
                            <li><strong>Student Information:</strong> Names, contact details, academic records, attendance data, and other educational information necessary for school administration.</li>
                            <li><strong>Staff Information:</strong> Employee details, qualifications, employment records, and professional information required for human resource management.</li>
                            <li><strong>System Usage Data:</strong> Login information, system access logs, and usage patterns for security and system improvement purposes.</li>
                            <li><strong>Communication Data:</strong> Messages, feedback, and support requests submitted through the system.</li>
                        </ul>

                        <h5>2. How We Use Your Information</h5>
                        <p>We use the collected information for the following purposes:</p>
                        <ul>
                            <li>School administration and management</li>
                            <li>Academic record keeping and reporting</li>
                            <li>Communication with students, parents, and staff</li>
                            <li>System security and maintenance</li>
                            <li>Compliance with educational regulations</li>
                            <li>Improving our services and user experience</li>
                        </ul>

                        <h5>3. Information Sharing and Disclosure</h5>
                        <p>We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:</p>
                        <ul>
                            <li>With your explicit consent</li>
                            <li>To comply with legal obligations or court orders</li>
                            <li>To protect the rights, property, or safety of the school, students, or staff</li>
                            <li>With educational authorities as required by law</li>
                            <li>With trusted service providers who assist in system operations (under strict confidentiality agreements)</li>
                        </ul>

                        <h5>4. Data Security</h5>
                        <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:</p>
                        <ul>
                            <li>Secure data encryption</li>
                            <li>Regular security assessments</li>
                            <li>Access controls and user authentication</li>
                            <li>Regular data backups</li>
                            <li>Staff training on data protection</li>
                        </ul>

                        <h5>5. Data Retention</h5>
                        <p>We retain personal information for as long as necessary to fulfill the purposes outlined in this policy, comply with legal obligations, resolve disputes, and enforce our agreements. Student academic records are typically retained according to educational regulations and institutional policies.</p>

                        <h5>6. Your Rights</h5>
                        <p>You have the following rights regarding your personal information:</p>
                        <ul>
                            <li><strong>Access:</strong> Request access to your personal information</li>
                            <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                            <li><strong>Deletion:</strong> Request deletion of your personal information (subject to legal requirements)</li>
                            <li><strong>Portability:</strong> Request transfer of your information to another system</li>
                            <li><strong>Objection:</strong> Object to certain processing of your information</li>
                        </ul>

                        <h5>7. Cookies and Tracking Technologies</h5>
                        <p>Our system uses cookies and similar technologies to enhance user experience, maintain session security, and analyze system usage. You can control cookie settings through your browser preferences.</p>

                        <h5>8. Children's Privacy</h5>
                        <p>We are committed to protecting the privacy of students under 18 years of age. We collect and process student information only as necessary for educational purposes and in compliance with applicable laws regarding children's privacy.</p>

                        <h5>9. Changes to This Policy</h5>
                        <p>We may update this privacy policy from time to time. We will notify users of any material changes through the system or other appropriate means. Continued use of the system after changes constitutes acceptance of the updated policy.</p>

                        <h5>10. Contact Information</h5>
                        <p>If you have questions, concerns, or requests regarding this privacy policy or your personal information, please contact us:</p>
                        <div class="contact-info bg-light p-3 rounded">
                            <p><strong>Gombe Secondary School Hub</strong><br>
                            Data Protection Officer<br>
                            Email: privacy@gombeschoolhub.edu.ng<br>
                            Phone: +234 XXX XXX XXXX<br>
                            Address: Gombe State, Nigeria</p>
                        </div>

                        <div class="mt-4 p-3 bg-info text-white rounded">
                            <h6><i class="fas fa-info-circle"></i> Important Notice</h6>
                            <p class="mb-0">This privacy policy is part of our commitment to protecting your personal information. By using the Gombe Secondary School Hub system, you acknowledge that you have read, understood, and agree to this privacy policy.</p>
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