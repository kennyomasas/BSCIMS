<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT certificate_name FROM certificate_types";
$result = $conn->query($sql);

$certificates = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row['certificate_name'];
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Template Editor - Barangay Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .template-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
        }
        
        .template-card:hover {
            transform: translateY(-2px);
        }
        
        .certificate-preview {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 800px;
        }
        
        .editable {
            transition: all 0.3s ease;
            border-radius: 4px;
            position: relative;
        }
        
        .editable:hover {
            background-color: #f8f9fa !important;
        }
        
        .editable[contenteditable="true"] {
            background-color: #fff3cd !important;
            border: 2px dashed #007bff !important;
            padding: 4px 8px !important;
            cursor: text !important;
            outline: none;
        }
        
        .editable[contenteditable="true"]:focus {
            background-color: #e7f3ff !important;
            border: 2px solid #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .template-selector {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .edit-mode-bar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .btn-edit-mode {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            color: white;
        }
        
        .btn-save-template {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
        }
        
        .btn-reset-template {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            border: none;
            color: white;
        }
        
        .template-info {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 1rem;
            border-radius: 0 8px 8px 0;
        }
        
        .certificate-actions {
            position: sticky;
            top: 20px;
            z-index: 100;
        }
        
        .dynamic-field {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            color: #1976d2;
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
        
        .custom-toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border: none;
        }
        
        @media print {
            .no-print, .template-selector, .certificate-actions, .edit-mode-bar {
                display: none !important;
            }
            
            .certificate-preview {
                box-shadow: none;
                padding: 0;
            }
            
            .editable {
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
            }
        }
        
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .template-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 1rem;
        }

        .header-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-group .btn {
    border-color: rgba(255,255,255,0.3);
    color: white;
}

.btn-group .btn:hover {
    background-color: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
    color: white;
}

.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
}

.btn-outline-success {
    border-color: #28a745;
    color: #28a745;
}

.btn-outline-success:hover {
    background-color: #28a745;
    color: white;
}

.btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

.vr {
    width: 1px;
    height: 2rem;
    background-color: currentColor;
    opacity: 0.25;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-wrap: wrap;
        gap: 0.5rem !important;
    }
    
    .btn-group {
        flex-wrap: wrap;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
    </style>
</head>
<body>
   <!-- Header -->
<div class="header-bar">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-certificate fa-2x me-3"></i>
                <div>
                    <h3 class="mb-0">Certificate Template Editor</h3>
                    <p class="mb-0 opacity-75">Customize and manage all certificate templates</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Logo Upload Buttons -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="uploadBarangayLogo()" title="Upload Barangay Logo">
                        <i class="fas fa-image me-1"></i> Barangay Logo
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="uploadCityLogo()" title="Upload City Logo">
                        <i class="fas fa-image me-1"></i> City Logo
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="resetLogos()" title="Reset All Logos">
                        <i class="fas fa-trash me-1"></i> Reset
                    </button>
                </div>
                
                <!-- Separator -->
                <div class="vr opacity-50"></div>
                
                <!-- Original Template Management Buttons -->
                <button class="btn btn-light btn-sm" onclick="exportTemplates()" title="Export Templates">
                    <i class="fas fa-download me-1"></i> Export Templates
                </button>
                <button class="btn btn-light btn-sm" onclick="importTemplates()" title="Import Templates">
                    <i class="fas fa-upload me-1"></i> Import Templates
                </button>
            </div>
        </div>
        
        <!-- Logo Preview Status Bar (Optional - shows current logo status) -->
        <div class="mt-2 d-none" id="logoStatusBar">
            <div class="d-flex align-items-center justify-content-end gap-3">
                <small class="text-muted d-flex align-items-center">
                    <i class="fas fa-info-circle me-1"></i>
                    <span id="barangayLogoStatus">Barangay Logo: Not uploaded</span>
                </small>
                <small class="text-muted d-flex align-items-center">
                    <span id="cityLogoStatus">City Logo: Not uploaded</span>
                </small>
            </div>
        </div>
    </div>
</div>

    

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Left Panel - Template Selection -->
            <div class="col-md-4">
                <!-- Template Selector -->
                <div class="template-selector mb-4">
    <h5 class="mb-3">
        <i class="fas fa-list me-2"></i>Select Certificate Type
    </h5>
    
    <select id="certificateTypeSelect" class="form-select mb-3" onchange="loadTemplate()">
        <option value="">Choose a certificate type...</option>
        <?php foreach ($certificates as $certificate): ?>
            <option value="<?php echo htmlspecialchars($certificate); ?>">
                <?php echo htmlspecialchars($certificate); ?>
            </option>
        <?php endforeach; ?>
    </select>

                    
                    <div id="templateInfo" class="template-info d-none">
                        <h6><i class="fas fa-info-circle me-2"></i>Template Information</h6>
                        <p class="mb-1"><strong>Type:</strong> <span id="currentTemplateType">-</span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="templateStatus" class="status-badge">-</span></p>
                        <p class="mb-0"><strong>Last Modified:</strong> <span id="lastModified">-</span></p>
                    </div>
                </div>

                <!-- Template Statistics -->
                <div class="template-stats mb-4">
                    <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Template Statistics</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-0" id="totalTemplates">0</div>
                            <small>Custom Templates</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0" id="defaultTemplates">8</div>
                            <small>Default Templates</small>
                        </div>
                    </div>
                </div>

                <!-- Sample Data Panel -->
                <div class="template-card p-3">
                    <h6 class="mb-3"><i class="fas fa-user me-2"></i>Sample Data for Preview</h6>
                    <div class="mb-3">
                        <label class="form-label">Resident Name</label>
                        <input type="text" id="sampleName" class="form-control" value="Juan Dela Cruz" onchange="updatePreview()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose</label>
                        <input type="text" id="samplePurpose" class="form-control" value="Employment purposes" onchange="updatePreview()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Signatory</label>
                        <input type="text" id="sampleSignatory" class="form-control" value="Hon. Maria Santos" onchange="updatePreview()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="sampleDate" class="form-control" onchange="updatePreview()">
                    </div>
                </div>
            </div>

            <!-- Right Panel - Certificate Preview and Editor -->
            <div class="col-md-8">
                <!-- Certificate Actions -->
                <div class="certificate-actions mb-4">
                    <div class="template-card p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <button id="editModeBtn" class="btn btn-edit-mode" onclick="toggleEditMode()">
                                    <i class="fas fa-edit me-1"></i> Edit Template
                                </button>
                                <button id="saveModeBtn" class="btn btn-save-template d-none" onclick="saveTemplate()">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                                <button id="cancelModeBtn" class="btn btn-secondary d-none" onclick="cancelEdit()">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="previewPrint()">
                                    <i class="fas fa-print me-1"></i> Print Preview
                                </button>
                                <button class="btn btn-reset-template btn-sm" onclick="resetTemplate()">
                                    <i class="fas fa-undo me-1"></i> Reset to Default
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Mode Instructions -->
                <div id="editInstructions" class="edit-mode-bar d-none">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit me-3"></i>
                        <div>
                            <strong>Edit Mode Active:</strong> Click on any text to edit it. Changes will be saved permanently for this certificate type.
                            <br>
                            <small>Dynamic fields (highlighted in blue) will be automatically replaced with actual data when certificates are generated.</small>
                        </div>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div class="loading-spinner" id="loadingSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading template...</p>
                </div>

                <!-- Certificate Preview -->
                <div id="certificateContainer" class="d-none">
                    <div class="certificate-preview" id="certificatePreview">
                        <!-- Certificate content will be loaded here -->
                    </div>
                </div>

                <!-- No Template Selected Message -->
                <div id="noTemplateMessage" class="text-center py-5">
                    <i class="fas fa-certificate fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Select a Certificate Type</h4>
                    <p class="text-muted">Choose a certificate type from the dropdown to start editing its template.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container">
        <div id="successToast" class="toast custom-toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="successToastBody">
                Template saved successfully!
            </div>
        </div>
        
        <div id="errorToast" class="toast custom-toast" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="errorToastBody">
                An error occurred!
            </div>
        </div>
    </div>

    <!-- Hidden file input for template import -->
    <input type="file" id="templateFileInput" accept=".json" style="display: none;" onchange="handleTemplateImport(event)">


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let certificateTemplates = {};
        let currentCertificateType = '';
        let isEditMode = false;
        let originalTemplate = '';

        // Certificate types and their default content
        const certificateTypes = {
            'Certificate of Residency': 'THIS IS TO FURTHER CERTIFY that the above-named person is a bonafide resident of this barangay and has been residing here for a considerable period of time.',
            'Certificate of Indigency': 'THIS IS TO FURTHER CERTIFY that the above-named person belongs to the indigent family of this barangay and is in need of financial assistance.',
            'Barangay Clearance': 'THIS IS TO FURTHER CERTIFY that as far as this Office is concerned as of this date, the above named person has no criminal record or pending charge for any violation or infraction of rules or regulations, ordinances and laws.',
            'Barangay Certification': 'THIS CERTIFICATION is issued to attest to the good moral character and standing of the above-named person in this community.',
            'Business Permit': 'THIS IS TO CERTIFY that the above-named business establishment has complied with all barangay requirements and is hereby permitted to operate within this jurisdiction.',
          
        };

        // Initialize the application
        document.addEventListener("DOMContentLoaded", function() {
            loadSavedTemplates();
            initializeSampleData();
            updateTemplateStats();
        });

        // Load saved templates from memory storage
        function loadSavedTemplates() {
            
            try {
                const saved = localStorage.getItem('certificateTemplates');
                if (saved) {
                    certificateTemplates = JSON.parse(saved);
                }
            } catch (error) {
                console.error('Error loading saved templates:', error);
                certificateTemplates = {};
            }
        }

        // Save templates to memory storage
        function saveTemplatesToStorage() {
            try {
                localStorage.setItem('certificateTemplates', JSON.stringify(certificateTemplates));
                updateTemplateStats();
            } catch (error) {
                console.error('Error saving templates:', error);
                showToast('Error saving templates!', 'error');
            }
        }

        // Initialize sample data with current date
        function initializeSampleData() {
            const today = new Date();
            document.getElementById('sampleDate').value = today.toISOString().split('T')[0];
        }

        // Get template key for certificate type
        function getTemplateKey(certificateType) {
            return certificateType.replace(/\s+/g, '_').toLowerCase();
        }

        

        // Load template when certificate type is selected
        function loadTemplate() {
            const selectedType = document.getElementById('certificateTypeSelect').value;
            
            if (!selectedType) {
                showNoTemplateMessage();
                return;
            }

            currentCertificateType = selectedType;
            showLoadingSpinner();
            
            setTimeout(() => {
                populateCertificate();
                updateTemplateInfo();
                hideLoadingSpinner();
                showCertificateContainer();
            }, 500);
        }

        // Show loading spinner
        function showLoadingSpinner() {
            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('certificateContainer').classList.add('d-none');
            document.getElementById('noTemplateMessage').style.display = 'none';
        }

        // Hide loading spinner
        function hideLoadingSpinner() {
            document.getElementById('loadingSpinner').style.display = 'none';
        }

        // Show certificate container
        function showCertificateContainer() {
            document.getElementById('certificateContainer').classList.remove('d-none');
            document.getElementById('noTemplateMessage').style.display = 'none';
        }

        // Show no template message
        function showNoTemplateMessage() {
            document.getElementById('noTemplateMessage').style.display = 'block';
            document.getElementById('certificateContainer').classList.add('d-none');
            document.getElementById('templateInfo').classList.add('d-none');
        }

        // Populate certificate with template or default content
        function populateCertificate() {
            const templateKey = getTemplateKey(currentCertificateType);
            const savedTemplate = certificateTemplates[templateKey];
            
            let certificateContent;
            
            if (savedTemplate) {
                certificateContent = applySavedTemplate(savedTemplate);
            } else {
                certificateContent = getDefaultTemplate();
            }
            
            document.getElementById('certificatePreview').innerHTML = certificateContent;
        }

        // Apply saved template with current sample data
        function applySavedTemplate(template) {
            let content = template.content;
            
            const sampleData = getSampleData();
            const replacements = {
                '{{RESIDENT_NAME}}': sampleData.name,
                '{{CERTIFICATE_TYPE}}': currentCertificateType,
                '{{PURPOSE}}': sampleData.purpose,
                '{{REQUEST_DATE}}': sampleData.date,
                '{{SIGNATORY}}': sampleData.signatory,
                '{{CERTIFICATE_CONTENT}}': certificateTypes[currentCertificateType] || 'THIS CERTIFICATION is issued for whatever legal purpose it may serve.'
            };
            
            Object.keys(replacements).forEach(placeholder => {
                const regex = new RegExp(placeholder, 'g');
                content = content.replace(regex, `<span class="dynamic-field">${replacements[placeholder]}</span>`);
            });
            
            return content;
        }

        // Get sample data from form inputs
        function getSampleData() {
            return {
                name: document.getElementById('sampleName').value || 'Juan Dela Cruz',
                purpose: document.getElementById('samplePurpose').value || 'Employment purposes',
                signatory: document.getElementById('sampleSignatory').value || 'Hon. Maria Santos',
                date: formatDate(document.getElementById('sampleDate').value) || formatDate(new Date().toISOString().split('T')[0])
            };
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        // Get default template with proper logo implementation
function getDefaultTemplate() {
    const sampleData = getSampleData();
    
    return `
        <!-- Certificate Header -->
        <div class="text-center mb-4">
            <div class="row align-items-center">
                <div class="col-3">
                    <div class="logo-container" style="display: flex; justify-content: center; align-items: center; height: 120px;">
                        <div class="logo-placeholder" style="width: 95px; height: 95px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 50%; background-color: #f8f9fa;">
                            <span style="font-size: 12px; color: #6c757d; text-align: center;">Barangay<br>Logo</span>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <h6 class="mb-0 editable" contenteditable="false" data-field="header_republic">Republic of the Philippines</h6>
                    <p class="mb-0 editable" contenteditable="false" data-field="header_province">Province of Bukidnon</p>
                    <p class="mb-0 editable" contenteditable="false" data-field="header_city">City of Valencia</p>
                    <h5 class="mb-0 editable" contenteditable="false" data-field="header_barangay">Barangay San Carlos</h5>
                    <p class="mb-0 editable" contenteditable="false" data-field="header_decoration">~ o ~ o ~ O ~ o ~ o ~</p>
                    <h6 class="mb-0 editable" contenteditable="false" data-field="header_office">OFFICE OF THE PUNONG BARANGAY</h6>
                    <br>
                    <h1 class="mt-2 editable" contenteditable="false" data-field="certificate_title">${currentCertificateType.toUpperCase()}</h1>
                </div>
                <div class="col-3">
                    <div class="logo-container" style="display: flex; justify-content: center; align-items: center; height: 120px;">
                        <div class="logo-placeholder" style="width: 100px; height: 100px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 50%; background-color: #f8f9fa;">
                            <span style="font-size: 12px; color: #6c757d; text-align: center;">City<br>Logo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <br><br>
        
        <!-- Certificate Body -->
        <div class="mb-4">
            <p class="mb-2 editable" contenteditable="false" data-field="greeting">TO WHOM IT MAY CONCERN:</p>
            <br>
            <p class="mb-2">
                <span class="ms-5"></span><span class="editable" contenteditable="false" data-field="certify_intro">THIS IS TO CERTIFY that</span> 
                <span class="fw-bold dynamic-field">${sampleData.name}</span> 
                <span class="editable" contenteditable="false" data-field="certify_middle">has requested a</span> <span class="fw-bold dynamic-field">${currentCertificateType}</span> <span class="editable" contenteditable="false" data-field="certify_end">from this Office.</span>
            </p>
            <br>
            <p class="mb-2">
                <span class="ms-5"></span><span class="editable" contenteditable="false" data-field="certificate_content">${certificateTypes[currentCertificateType] || 'THIS CERTIFICATION is issued for whatever legal purpose it may serve.'}</span>
            </p>
            <br>
            <p class="mb-2">
                <span class="ms-5"></span><span class="editable" contenteditable="false" data-field="purpose_intro">THIS CERTIFICATION is being issued upon the request of the above named-person for:</span> 
                <span class="fw-bold dynamic-field">${sampleData.purpose}</span>
            </p>
            <br>
            <p class="mb-2">
                <span class="editable" contenteditable="false" data-field="issue_intro">Issued this</span> <span class="fw-bold dynamic-field">${sampleData.date}</span> 
                <span class="editable" contenteditable="false" data-field="issue_location">at the office of the Punong Barangay, San Carlos, City of Valencia, Bukidnon.</span>
            </p>
        </div>
        
        <!-- Certificate Footer/Signatures -->
        <br><br><br><br><br>
        
        <div class="row mt-5">
            <div class="col-6">
                <p class="mb-0 text-center fw-bold dynamic-field">${sampleData.signatory}</p>
                <p class="text-center editable" contenteditable="false" data-field="signatory_title">Sangguniang Barangay Member</p>
            </div>
            <div class="col-6">
                <p class="mb-0 text-center editable" contenteditable="false" data-field="punong_name">Hon. Mariza T. Labe</p>
                <p class="text-center editable" contenteditable="false" data-field="punong_title">Punong Barangay</p>
            </div>
        </div>
    `;
}

// Updated apply saved template function to ensure logos are preserved
function applySavedTemplate(template) {
    let content = template.content;
    
    // Ensure logos are present in saved templates - add if missing
    if (!content.includes('logo-container') || !content.includes('logo-placeholder')) {
        content = addLogosToTemplate(content);
    }
    
    const sampleData = getSampleData();
    const replacements = {
        '{{RESIDENT_NAME}}': sampleData.name,
        '{{CERTIFICATE_TYPE}}': currentCertificateType,
        '{{PURPOSE}}': sampleData.purpose,
        '{{REQUEST_DATE}}': sampleData.date,
        '{{SIGNATORY}}': sampleData.signatory,
        '{{CERTIFICATE_CONTENT}}': certificateTypes[currentCertificateType] || 'THIS CERTIFICATION is issued for whatever legal purpose it may serve.'
    };
    
    Object.keys(replacements).forEach(placeholder => {
        const regex = new RegExp(placeholder, 'g');
        content = content.replace(regex, `<span class="dynamic-field">${replacements[placeholder]}</span>`);
    });
    
    return content;
}

// Function to add logos to existing templates that don't have them
function addLogosToTemplate(content) {
    // Check if template already has logo containers
    if (content.includes('logo-container')) {
        return content;
    }
    
    // Find the header section and add logos
    const headerPattern = /<div class="text-center mb-4">\s*<div class="row align-items-center">/;
    const headerMatch = content.match(headerPattern);
    
    if (headerMatch) {
        // Replace the header section with one that includes logos
        const logoHeader = `
            <div class="text-center mb-4">
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="logo-container" style="display: flex; justify-content: center; align-items: center; height: 120px;">
                            <div class="logo-placeholder" style="width: 95px; height: 95px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 50%; background-color: #f8f9fa;">
                                <span style="font-size: 12px; color: #6c757d; text-align: center;">Barangay<br>Logo</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">`;
        
        // Find where the middle column ends and add the city logo column
        const middleColumnEnd = content.indexOf('</div>\n                    </div>\n                </div>');
        if (middleColumnEnd !== -1) {
            const beforeMiddleEnd = content.substring(0, middleColumnEnd);
            const afterMiddleEnd = content.substring(middleColumnEnd);
            
            const cityLogoColumn = `</div>
                    <div class="col-3">
                        <div class="logo-container" style="display: flex; justify-content: center; align-items: center; height: 120px;">
                            <div class="logo-placeholder" style="width: 100px; height: 100px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 50%; background-color: #f8f9fa;">
                                <span style="font-size: 12px; color: #6c757d; text-align: center;">City<br>Logo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            
            return beforeMiddleEnd + cityLogoColumn + afterMiddleEnd.substring(afterMiddleEnd.indexOf('</div>') + 6);
        }
    }
    
    return content;
}

// Function to upload and set barangay logo
function uploadBarangayLogo() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    
    input.onchange = function(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showToast('File size must be less than 2MB!', 'error');
                return;
            }
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showToast('Please select a valid image file!', 'error');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const logoContainers = document.querySelectorAll('.logo-placeholder');
                if (logoContainers.length > 0) {
                    // Replace first logo (Barangay logo)
                    logoContainers[0].innerHTML = `<img src="${e.target.result}" alt="Barangay Logo" style="width: 95px; height: 95px; object-fit: contain; border-radius: 50%;">`;
                    
                    // Update preview
                    updateLogoPreview('barangay', e.target.result);
                    
                    // Save logo to storage for persistence
                    try {
                        localStorage.setItem('barangayLogo', e.target.result);
                        showToast('Barangay logo uploaded successfully!', 'success');
                    } catch (error) {
                        console.error('Error saving logo:', error);
                        showToast('Logo uploaded but could not be saved for future use.', 'error');
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    };
    
    input.click();
}

// Function to upload and set city logo
function uploadCityLogo() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    
    input.onchange = function(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showToast('File size must be less than 2MB!', 'error');
                return;
            }
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showToast('Please select a valid image file!', 'error');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const logoContainers = document.querySelectorAll('.logo-placeholder');
                if (logoContainers.length > 1) {
                    // Replace second logo (City logo)
                    logoContainers[1].innerHTML = `<img src="${e.target.result}" alt="City Logo" style="width: 100px; height: 100px; object-fit: contain; border-radius: 50%;">`;
                    
                    // Update preview
                    updateLogoPreview('city', e.target.result);
                    
                    // Save logo to storage for persistence
                    try {
                        localStorage.setItem('cityLogo', e.target.result);
                        showToast('City logo uploaded successfully!', 'success');
                    } catch (error) {
                        console.error('Error saving logo:', error);
                        showToast('Logo uploaded but could not be saved for future use.', 'error');
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    };
    
    input.click();
}

// Function to load saved logos
function loadSavedLogos() {
    try {
        const barangayLogo = localStorage.getItem('barangayLogo');
        const cityLogo = localStorage.getItem('cityLogo');
        
        const logoContainers = document.querySelectorAll('.logo-placeholder');
        
        if (barangayLogo && logoContainers.length > 0) {
            logoContainers[0].innerHTML = `<img src="${barangayLogo}" alt="Barangay Logo" style="width: 95px; height: 95px; object-fit: contain; border-radius: 50%;">`;
        }
        
        if (cityLogo && logoContainers.length > 1) {
            logoContainers[1].innerHTML = `<img src="${cityLogo}" alt="City Logo" style="width: 100px; height: 100px; object-fit: contain; border-radius: 50%;">`;
        }
    } catch (error) {
        console.error('Error loading saved logos:', error);
    }
}

// Update the populateCertificate function to load logos after template is populated
function populateCertificate() {
    const templateKey = getTemplateKey(currentCertificateType);
    const savedTemplate = certificateTemplates[templateKey];
    
    let certificateContent;
    
    if (savedTemplate) {
        certificateContent = applySavedTemplate(savedTemplate);
    } else {
        certificateContent = getDefaultTemplate();
    }
    
    document.getElementById('certificatePreview').innerHTML = certificateContent;
    
    // Load saved logos after template is populated
    setTimeout(() => {
        loadSavedLogos();
    }, 100);
}

// Add logo management functions to the template upgrade process
function upgradeExistingTemplates() {
    let upgraded = false;
    
    Object.keys(certificateTemplates).forEach(templateKey => {
        const template = certificateTemplates[templateKey];
        if (!template.content.includes('logo-container')) {
            template.content = addLogosToTemplate(template.content);
            template.lastModified = new Date().toISOString();
            upgraded = true;
        }
    });
    
    if (upgraded) {
        saveTemplatesToStorage();
        showToast('Existing templates have been upgraded with logo support!', 'success');
    }
}

// Function to reset logos
function resetLogos() {
    if (confirm('Are you sure you want to remove all uploaded logos?')) {
        try {
            localStorage.removeItem('barangayLogo');
            localStorage.removeItem('cityLogo');
            
            // Reset logo containers to placeholders
            const logoContainers = document.querySelectorAll('.logo-placeholder');
            if (logoContainers.length > 0) {
                logoContainers[0].innerHTML = '<span style="font-size: 12px; color: #6c757d; text-align: center;">Barangay<br>Logo</span>';
            }
            if (logoContainers.length > 1) {
                logoContainers[1].innerHTML = '<span style="font-size: 12px; color: #6c757d; text-align: center;">City<br>Logo</span>';
            }
            
            showToast('All logos have been reset to placeholders.', 'success');
        } catch (error) {
            console.error('Error resetting logos:', error);
            showToast('Error resetting logos!', 'error');
        }
    }
}

// Add HTML for logo upload buttons - Add this to your HTML file
const logoUploadButtonsHTML = `
<!-- Logo Management Panel -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-image me-2"></i>Logo Management
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <button type="button" class="btn btn-outline-primary w-100" onclick="uploadBarangayLogo()">
                    <i class="fas fa-upload me-2"></i>Upload Barangay Logo
                </button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-outline-success w-100" onclick="uploadCityLogo()">
                    <i class="fas fa-upload me-2"></i>Upload City Logo
                </button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-outline-danger w-100" onclick="resetLogos()">
                    <i class="fas fa-trash me-2"></i>Reset Logos
                </button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Supported formats: JPG, PNG, GIF. Recommended size: 100x100px for best quality.
                </small>
            </div>
        </div>
        <!-- Logo Preview Section -->
        <div class="row mt-3" id="logoPreviewSection">
            <div class="col-md-6">
                <div class="text-center">
                    <p class="small fw-bold mb-2">Barangay Logo Preview</p>
                    <div id="barangayLogoPreview" class="border rounded p-2" style="height: 80px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        <span class="text-muted small">No logo uploaded</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <p class="small fw-bold mb-2">City Logo Preview</p>
                    <div id="cityLogoPreview" class="border rounded p-2" style="height: 80px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        <span class="text-muted small">No logo uploaded</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`;

// Function to initialize logo preview section
function initializeLogoPreview() {
    try {
        const barangayLogo = localStorage.getItem('barangayLogo');
        const cityLogo = localStorage.getItem('cityLogo');
        
        const barangayPreview = document.getElementById('barangayLogoPreview');
        const cityPreview = document.getElementById('cityLogoPreview');
        
        if (barangayLogo && barangayPreview) {
            barangayPreview.innerHTML = `<img src="${barangayLogo}" alt="Barangay Logo" style="max-width: 70px; max-height: 70px; object-fit: contain;">`;
        }
        
        if (cityLogo && cityPreview) {
            cityPreview.innerHTML = `<img src="${cityLogo}" alt="City Logo" style="max-width: 70px; max-height: 70px; object-fit: contain;">`;
        }
    } catch (error) {
        console.error('Error initializing logo preview:', error);
    }
}

// Update logo preview when logos are uploaded
function updateLogoPreview(logoType, logoData) {
    const previewElement = document.getElementById(`${logoType}LogoPreview`);
    if (previewElement) {
        previewElement.innerHTML = `<img src="${logoData}" alt="${logoType} Logo" style="max-width: 70px; max-height: 70px; object-fit: contain;">`;
    }
}

// Update the loadSavedTemplates function to upgrade existing templates
function loadSavedTemplates() {
    try {
        const saved = localStorage.getItem('certificateTemplates');
        if (saved) {
            certificateTemplates = JSON.parse(saved);
            // Upgrade existing templates to include logos
            upgradeExistingTemplates();
        }
    } catch (error) {
        console.error('Error loading saved templates:', error);
        certificateTemplates = {};
    }
}

// Update the initialize function to include logo preview
document.addEventListener("DOMContentLoaded", function() {
    loadSavedTemplates();
    initializeSampleData();
    updateTemplateStats();
    // Initialize logo preview after DOM is loaded
    setTimeout(initializeLogoPreview, 100);
});

// Enhanced reset logos function with preview updates
function resetLogos() {
    if (confirm('Are you sure you want to remove all uploaded logos?')) {
        try {
            localStorage.removeItem('barangayLogo');
            localStorage.removeItem('cityLogo');
            
            // Reset logo containers to placeholders
            const logoContainers = document.querySelectorAll('.logo-placeholder');
            if (logoContainers.length > 0) {
                logoContainers[0].innerHTML = '<span style="font-size: 12px; color: #6c757d; text-align: center;">Barangay<br>Logo</span>';
            }
            if (logoContainers.length > 1) {
                logoContainers[1].innerHTML = '<span style="font-size: 12px; color: #6c757d; text-align: center;">City<br>Logo</span>';
            }
            
            // Reset preview sections
            const barangayPreview = document.getElementById('barangayLogoPreview');
            const cityPreview = document.getElementById('cityLogoPreview');
            
            if (barangayPreview) {
                barangayPreview.innerHTML = '<span class="text-muted small">No logo uploaded</span>';
            }
            if (cityPreview) {
                cityPreview.innerHTML = '<span class="text-muted small">No logo uploaded</span>';
            }
            
            showToast('All logos have been reset to placeholders.', 'success');
        } catch (error) {
            console.error('Error resetting logos:', error);
            showToast('Error resetting logos!', 'error');
        }
    }
}

        // Update template info panel
        function updateTemplateInfo() {
            const templateKey = getTemplateKey(currentCertificateType);
            const hasCustomTemplate = certificateTemplates[templateKey];
            
            document.getElementById('templateInfo').classList.remove('d-none');
            document.getElementById('currentTemplateType').textContent = currentCertificateType;
            
            if (hasCustomTemplate) {
                document.getElementById('templateStatus').textContent = 'Custom';
                document.getElementById('templateStatus').className = 'status-badge bg-success';
                document.getElementById('lastModified').textContent = new Date(hasCustomTemplate.lastModified).toLocaleString();
            } else {
                document.getElementById('templateStatus').textContent = 'Default';
                document.getElementById('templateStatus').className = 'status-badge bg-secondary';
                document.getElementById('lastModified').textContent = 'Never modified';
            }
        }

        // Update preview when sample data changes
        function updatePreview() {
            if (currentCertificateType) {
                populateCertificate();
            }
        }

        // Toggle edit mode
        function toggleEditMode() {
            if (!currentCertificateType) {
                showToast('Please select a certificate type first!', 'error');
                return;
            }

            isEditMode = true;
            originalTemplate = document.getElementById('certificatePreview').innerHTML;
            
            const editables = document.querySelectorAll('.editable');
            editables.forEach(el => {
                el.setAttribute('contenteditable', 'true');
                el.addEventListener('input', handleEdit);
                el.addEventListener('paste', handlePaste);
            });
            
            // Update UI
            document.getElementById('editModeBtn').classList.add('d-none');
            document.getElementById('saveModeBtn').classList.remove('d-none');
            document.getElementById('cancelModeBtn').classList.remove('d-none');
            document.getElementById('editInstructions').classList.remove('d-none');
            
            showToast('Edit mode activated! Click on any text to edit it.', 'success');
        }

        // Handle edit events
        function handleEdit(event) {
            // Auto-save functionality could be added here
        }

        // Handle paste events to clean HTML
        function handlePaste(event) {
            event.preventDefault();
            const text = (event.originalEvent || event).clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
        }

        // Save template
        function saveTemplate() {
            if (!currentCertificateType || !isEditMode) return;
            
            const templateKey = getTemplateKey(currentCertificateType);
            let templateContent = document.getElementById('certificatePreview').innerHTML;
            
            // Replace dynamic content with placeholders
            const sampleData = getSampleData();
            const replacements = {
                [sampleData.name]: '{{RESIDENT_NAME}}',
                [currentCertificateType]: '{{CERTIFICATE_TYPE}}',
                [sampleData.purpose]: '{{PURPOSE}}',
                [sampleData.date]: '{{REQUEST_DATE}}',
                [sampleData.signatory]: '{{SIGNATORY}}'
            };
            
            Object.keys(replacements).forEach(text => {
                const regex = new RegExp(`<span class="dynamic-field">${escapeRegExp(text)}</span>`, 'g');
                templateContent = templateContent.replace(regex, replacements[text]);
            });
            
            // Save template
            certificateTemplates[templateKey] = {
                content: templateContent,
                lastModified: new Date().toISOString(),
                certificateType: currentCertificateType
            };
            
            saveTemplatesToStorage();
            exitEditMode();
            updateTemplateInfo();
            
            showToast(`Template for "${currentCertificateType}" saved successfully!`, 'success');
        }

        // Cancel edit mode
        function cancelEdit() {
            if (originalTemplate) {
                document.getElementById('certificatePreview').innerHTML = originalTemplate;
            }
            exitEditMode();
            showToast('Changes cancelled.', 'error');
        }

       // Exit edit mode
        function exitEditMode() {
            isEditMode = false;
            originalTemplate = '';
            
            const editables = document.querySelectorAll('.editable');
            editables.forEach(el => {
                el.setAttribute('contenteditable', 'false');
                el.removeEventListener('input', handleEdit);
                el.removeEventListener('paste', handlePaste);
            });
            
            // Update UI
            document.getElementById('editModeBtn').classList.remove('d-none');
            document.getElementById('saveModeBtn').classList.add('d-none');
            document.getElementById('cancelModeBtn').classList.add('d-none');
            document.getElementById('editInstructions').classList.add('d-none');
        }

        // Reset template to default
        function resetTemplate() {
            if (!currentCertificateType) {
                showToast('Please select a certificate type first!', 'error');
                return;
            }

            if (confirm(`Are you sure you want to reset "${currentCertificateType}" template to default? This will remove all customizations.`)) {
                const templateKey = getTemplateKey(currentCertificateType);
                delete certificateTemplates[templateKey];
                saveTemplatesToStorage();
                populateCertificate();
                updateTemplateInfo();
                showToast(`Template for "${currentCertificateType}" reset to default.`, 'success');
            }
        }

        // Print preview
        function previewPrint() {
            if (!currentCertificateType) {
                showToast('Please select a certificate type first!', 'error');
                return;
            }

            // Exit edit mode if active
            if (isEditMode) {
                exitEditMode();
            }

            // Open print preview
            window.print();
        }

        // Export templates
        function exportTemplates() {
            try {
                const dataStr = JSON.stringify(certificateTemplates, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                
                const exportFileDefaultName = `certificate-templates-${new Date().toISOString().split('T')[0]}.json`;
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
                
                showToast('Templates exported successfully!', 'success');
            } catch (error) {
                console.error('Export error:', error);
                showToast('Error exporting templates!', 'error');
            }
        }

        // Import templates
        function importTemplates() {
            document.getElementById('templateFileInput').click();
        }

        // Handle template import
        function handleTemplateImport(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.type !== 'application/json') {
                showToast('Please select a valid JSON file!', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const importedTemplates = JSON.parse(e.target.result);
                    
                    // Validate imported data
                    if (typeof importedTemplates !== 'object') {
                        throw new Error('Invalid template format');
                    }

                    if (confirm('This will replace all existing custom templates. Are you sure you want to continue?')) {
                        certificateTemplates = importedTemplates;
                        saveTemplatesToStorage();
                        updateTemplateStats();
                        
                        // Refresh current template if loaded
                        if (currentCertificateType) {
                            populateCertificate();
                            updateTemplateInfo();
                        }
                        
                        showToast('Templates imported successfully!', 'success');
                    }
                } catch (error) {
                    console.error('Import error:', error);
                    showToast('Error importing templates! Please check the file format.', 'error');
                }
            };
            
            reader.readAsText(file);
            event.target.value = ''; // Reset file input
        }

        // Update template statistics
        function updateTemplateStats() {
            const customCount = Object.keys(certificateTemplates).length;
            document.getElementById('totalTemplates').textContent = customCount;
        }

        // Escape regular expression special characters
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastElement = document.getElementById(type === 'success' ? 'successToast' : 'errorToast');
            const toastBody = document.getElementById(type === 'success' ? 'successToastBody' : 'errorToastBody');
            
            toastBody.textContent = message;
            
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        // Prevent accidental page leaving when in edit mode
        window.addEventListener('beforeunload', function(e) {
            if (isEditMode) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });

        // Auto-save draft functionality (optional enhancement)
        function autosaveTemplate() {
            if (isEditMode && currentCertificateType) {
                const templateKey = getTemplateKey(currentCertificateType);
                const draftKey = `draft_${templateKey}`;
                const currentContent = document.getElementById('certificatePreview').innerHTML;
                
                try {
                    localStorage.setItem(draftKey, JSON.stringify({
                        content: currentContent,
                        timestamp: new Date().toISOString()
                    }));
                } catch (error) {
                    console.error('Auto-save error:', error);
                }
            }
        }

        // Load draft if available
        function loadDraft() {
            if (currentCertificateType) {
                const templateKey = getTemplateKey(currentCertificateType);
                const draftKey = `draft_${templateKey}`;
                
                try {
                    const draft = localStorage.getItem(draftKey);
                    if (draft) {
                        const draftData = JSON.parse(draft);
                        const draftAge = new Date() - new Date(draftData.timestamp);
                        
                        // Show draft if it's less than 1 hour old
                        if (draftAge < 3600000) {
                            if (confirm('A recent draft was found for this template. Would you like to load it?')) {
                                document.getElementById('certificatePreview').innerHTML = draftData.content;
                                toggleEditMode();
                            }
                        }
                        
                        // Clean up old drafts
                        localStorage.removeItem(draftKey);
                    }
                } catch (error) {
                    console.error('Draft loading error:', error);
                }
            }
        }

        // Auto-save every 30 seconds when in edit mode
        setInterval(autosaveTemplate, 30000);

        // Additional utility functions for enhanced functionality
        function validateTemplate(content) {
            // Basic validation to ensure template has required elements
            const requiredFields = ['RESIDENT_NAME', 'CERTIFICATE_TYPE', 'PURPOSE'];
            const missingFields = requiredFields.filter(field => !content.includes(`{{${field}}}`));
            
            if (missingFields.length > 0) {
                showToast(`Warning: Template is missing required fields: ${missingFields.join(', ')}`, 'error');
                return false;
            }
            
            return true;
        }

        function generateTemplatePreview(templateType) {
            // Generate a quick preview without loading full template
            const basicContent = certificateTypes[templateType] || 'Certificate content...';
            return `
                <div class="text-center">
                    <h4>${templateType.toUpperCase()}</h4>
                    <p class="mt-3">${basicContent.substring(0, 100)}...</p>
                </div>
            `;
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 's':
                        e.preventDefault();
                        if (isEditMode) saveTemplate();
                        break;
                    case 'e':
                        e.preventDefault();
                        if (!isEditMode && currentCertificateType) toggleEditMode();
                        break;
                    case 'p':
                        e.preventDefault();
                        previewPrint();
                        break;
                    case 'Escape':
                        if (isEditMode) cancelEdit();
                        break;
                }
            }
        });

        // Initialize tooltips if Bootstrap 5 tooltips are needed
        function initializeTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Call initialize tooltips after DOM is loaded
        document.addEventListener('DOMContentLoaded', initializeTooltips);
    </script>

    <script>
// Enhanced JavaScript functions for logo status updates
function updateLogoStatus() {
    const barangayLogo = localStorage.getItem('barangayLogo');
    const cityLogo = localStorage.getItem('cityLogo');
    
    const barangayStatus = document.getElementById('barangayLogoStatus');
    const cityStatus = document.getElementById('cityLogoStatus');
    const statusBar = document.getElementById('logoStatusBar');
    
    if (barangayStatus) {
        barangayStatus.textContent = barangayLogo ? 'Barangay Logo: ✓ Uploaded' : 'Barangay Logo: Not uploaded';
        barangayStatus.className = barangayLogo ? 'text-success' : 'text-muted';
    }
    
    if (cityStatus) {
        cityStatus.textContent = cityLogo ? 'City Logo: ✓ Uploaded' : 'City Logo: Not uploaded';
        cityStatus.className = cityLogo ? 'text-success' : 'text-muted';
    }
    
    // Show status bar if either logo is uploaded
    if (statusBar && (barangayLogo || cityLogo)) {
        statusBar.classList.remove('d-none');
    }
}

// Enhanced upload functions with status updates
function uploadBarangayLogoEnhanced() {
    uploadBarangayLogo(); // Call your existing function
    setTimeout(updateLogoStatus, 500); // Update status after upload
}

function uploadCityLogoEnhanced() {
    uploadCityLogo(); // Call your existing function
    setTimeout(updateLogoStatus, 500); // Update status after upload
}

function resetLogosEnhanced() {
    resetLogos(); // Call your existing function
    setTimeout(updateLogoStatus, 500); // Update status after reset
}

// Initialize logo status on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(updateLogoStatus, 100);
});
</script>
</body>
</html>