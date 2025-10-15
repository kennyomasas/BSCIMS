<?php
$servername = "localhost";
$username = "root"; // Default for XAMPP
$password = ""; // Default for XAMPP
$database = "barangay"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get request ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch data from database
$sql = "SELECT * FROM document_requests WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Request not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Document Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .document {
            width: 8.5in; /* Long bond paper width */
            height: 13in; /* Long bond paper height */
            padding: 1in;
            border: 1px solid black;
            background: white;
            font-family: 'Times New Roman', Times, serif;
            color: black;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            margin: auto;
            position: relative;
            line-height: 1.5; /* Adds 1.5 spacing */
        }

        .document h3, .document h5 {
            text-align: center;
        }

        .document p {
            text-align: justify;
            text-indent: 50px;
            font-size: 16px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            margin-bottom: 70px;
        }

        .header .left-logo {
            width: 90px; /* Adjust left logo size */
            height: 90px;
            margin-top: -190px;
        }

        .header .right-logo {
            width: 95px; /* Adjust right logo size */
            height: 95px;
            margin-top: -190px;
        }

        .header div {
            flex: 1;
            text-align: center;
        }

        .header h3, .header h5 {
            margin: 2px 0;
        }

        p {
            text-align: justify;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature div {
            text-align: center;
        }

        .signature p {
            margin: 5px 0;
            text-align: center;
        }

        @media print {
            @page {
                size: 8.5in 14in; /* Legal paper size (8.5x14 inches) */
                margin: 0.5in;
            }
        }
    </style>
</head>
<body>
    
    <div class="container mt-5">
        <h2 class="text-center">REVIEW SUBMITTED REQUEST</h2>
        <table class="table table-bordered">
            <tr><th>O.R No.</th><td><?php echo htmlspecialchars($row['or_no']); ?></td></tr>
            <tr><th>CTC No.</th><td><?php echo htmlspecialchars($row['ctc_no']); ?></td></tr>
            <tr><th>Issued To</th><td><?php echo htmlspecialchars($row['issued_to']); ?></td></tr>
            <tr><th>Document Type</th><td><?php echo htmlspecialchars($row['document_type']); ?></td></tr>
            <tr><th>Purpose</th><td><?php echo htmlspecialchars($row['purpose']); ?></td></tr>
            <tr><th>Issued Date</th><td><?php echo htmlspecialchars($row['issue_date']); ?></td></tr>
            <tr><th>Signatory</th><td><?php echo htmlspecialchars($row['signatory']); ?></td></tr>
        </table>
        <div class="text-center">
            <a href="Request_Documents.html" class="btn btn-primary">Back to Request Form</a>
            <button class="btn btn-info" onclick="showDocumentModal()">View Certificate</button>
        </div>
    </div>

  <!-- Certificate of Indigency Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificateModalLabel">Certificate Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="document">
                    <div class="header">
                        <img src="logo.png" alt="Left Logo" class="left-logo">
                        <div>
                            <h5 style="margin-top: -35px;">Republic of the Philippines</h5>
                            <h5>Province of Bukidnon</h5>
                            <h5>City of Valencia</h5>
                            <h4 style="position: relative; display: inline-block; text-align: center; font-weight: bold;">
                                Barangay San Carlos
                                <span style="display: block; font-weight: bold; font-size: 18px; margin-top: 5px;">~ o ~ o ~ O ~ o ~ o ~</span>
                            </h4>
                            <h4 style="margin-top: -3px; font-weight: bold; text-align: center; white-space: nowrap;">
                                OFFICE OF THE PUNONG BARANGAY
                            </h4>
                            <hr style="width: 150%; border: 1px solid black; position: relative; left: -25%; margin: 10px 0;">
                            <h3 style="margin-top: 30px; font-weight: bold; font-size: 34px;">Certificate of Indigency</h3>
                        </div>
                        <img src="image/valencia.png" alt="Right Logo" class="right-logo">
                    </div>
                    <p style="font-size: 16px; margin-left: -70px; margin-top: -35px;">TO WHOM IT MAY CONCERN:</p>
                    <p style="font-size: 16px; margin-top: 30px;">THIS IS TO CERTIFY that <strong><?php echo htmlspecialchars($row['issued_to']); ?></strong> has requested a Barangay Certification from this office...</p>
                    <p style="font-size: 16px; margin-top: 30px;">CERTIFYING FURTHER that as far as this office is concerned as of this date, the above named person is indigent based on the existing records of our Barangay.</p>
                    <p style="font-size: 16px; margin-top: 30px;">THIS CERTIFICATION is being issued upon the verbal request of the above-named person for: <strong><?php echo htmlspecialchars($row['purpose']); ?></strong></p>
                    <p style="font-size: 16px; margin-top: 30px;">Issued this <strong><?php echo htmlspecialchars($row['issue_date']); ?></strong> at the office of the Punong Barangay, San Carlos, City of Valencia, Bukidnon.</p>
                    <div class="signature">
                        <div style="position: absolute; right: 104px; margin-top: 350px;">
                            <p style="font-size: 18px;"><strong><?php echo htmlspecialchars($row['signatory']); ?></strong></p>
                            <p style="font-size: 16px;">Sangguniang Barangay Member</p>
                        </div>
                        <div style="position: absolute; right: 128px; margin-top: 240px;">
                            <p style="font-size: 18px;"><strong>Hon. Mariza T. Labe</strong></p>
                            <p style="font-size: 16px;">Punong Barangay</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button onclick="printCertificate('certificateModal')" class="btn btn-success">Print</button>
            </div>
        </div>
    </div>
</div>

    

<!-- Residency Certificate Modal -->
<div class="modal fade" id="residencyModal" tabindex="-1" aria-labelledby="residencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residencyModalLabel">Residency Certificate Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="document">
                        <div class="header">
                            <img src="logo.png" alt="Left Logo" class="left-logo">
                            <div>
                                <h5 style="margin-top: -35px;">Republic of the Philippines</h5>
                                <h5>Province of Bukidnon</h5>
                                <h5>City of Valencia</h5>
                                <h4 style="position: relative; display: inline-block; text-align: center; font-weight: bold;">
                                    Barangay San Carlos
                                    <span style="display: block; font-weight: bold; font-size: 18px; margin-top: 5px;">~ o ~ o ~ O ~ o ~ o ~</span>
                                </h4>
                                <h4 style="margin-top: -3px; font-weight: bold; text-align: center; white-space: nowrap;">
                                    OFFICE OF THE PUNONG BARANGAY
                                </h4>
                                <hr style="width: 150%; border: 1px solid black; position: relative; left: -25%; margin: 10px 0;">
                                <h3 style="margin-top: 30px; font-weight: bold; font-size: 34px;">Certificate of Residency</h3>
                            </div>
                            <img src="image/valencia.png" alt="Right Logo" class="right-logo">
                        </div>
                        <p style="font-size: 16px; margin-left: -70px; margin-top: -35px;">TO WHOM IT MAY CONCERN:</p>
                        <p style="font-size: 16px; margin-top: 30px;">THIS IS TO CERTIFY that <strong><?php echo htmlspecialchars($row['issued_to']); ?></strong> has requested a Barangay Certification from this Office..</p>
                        <p style="font-size: 16px; margin-top: 30px;">CERTIFYING FURTHER that as far as this Office is concerned as of this date, the above named person is a resident of this barangay.<strong></strong></p>
                        <p style="font-size: 16px; margin-top: 30px;">THIS CERTIFICATION is being issued upon the verbal request of the above named person for: <strong><?php echo htmlspecialchars($row['purpose']); ?></strong></p>
                        <p style="font-size: 16px; margin-top: 30px;">Issued this <strong><?php echo htmlspecialchars($row['issue_date']); ?></strong> at the office of the Punong Barangay, San Carlos, City of Valencia, Bukidnon.</p>
                        <div class="signature">
                            <div style="position: absolute; right: 104px; margin-top: 350px;">
                                <p style="font-size: 18px;"><strong><?php echo htmlspecialchars($row['signatory']); ?></strong></p>
                                <p style="font-size: 16px;">Sangguniang Barangay Member</p>
                            </div>
                            <div style="position: absolute; right: 128px; margin-top: 240px;">
                                <p style="font-size: 18px;"><strong>Hon. Mariza T. Labe</strong></p>
                                <p style="font-size: 16px;">Punong Barangay</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button onclick="printCertificate('residencyModal')" class="btn btn-success">Print</button>
            </div>
        </div>
    </div>
</div>

   <!-- Barangay Clearance Modal -->
<div class="modal fade" id="clearanceModal" tabindex="-1" aria-labelledby="clearanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearanceModalLabel">Barangay Clearance Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="document">
                        <div class="header">
                            <img src="logo.png" alt="Left Logo" class="left-logo">
                            <div>
                                <h5 style="margin-top: -35px;">Republic of the Philippines</h5>
                                <h5>Province of Bukidnon</h5>
                                <h5>City of Valencia</h5>
                                <h4 style="position: relative; display: inline-block; text-align: center; font-weight: bold;">
                                    Barangay San Carlos
                                    <span style="display: block; font-weight: bold; font-size: 18px; margin-top: 5px;">~ o ~ o ~ O ~ o ~ o ~</span>
                                </h4>
                                <h4 style="margin-top: -3px; font-weight: bold; text-align: center; white-space: nowrap;">
                                    OFFICE OF THE PUNONG BARANGAY
                                </h4>
                                <hr style="width: 150%; border: 1px solid black; position: relative; left: -25%; margin: 10px 0;">
                                <h3 style="margin-top: 30px; font-weight: bold; font-size: 34px;">Barangay Clearance</h3>
                            </div>
                            <img src="image/valencia.png" alt="Right Logo" class="right-logo">
                        </div>
                        <p style="font-size: 16px; margin-left: -70px; margin-top: -35px;">TO WHOM IT MAY CONCERN:</p>
                        <p style="font-size: 16px; margin-top: 30px;">THIS IS TO CERTIFY that <strong><?php echo htmlspecialchars($row['issued_to']); ?></strong> has requested a Barangay Clearance from this Office..</p>
                        <p style="font-size: 16px; margin-top: 30px;">THIS IS TO  FURTHER CERTIFY that as far as this Office is concerned as of this date, the above named person has no criminal record or pending charge for any violation or infraction of rules or regulations, ordinances and laws.<strong></strong></p>
                        <p style="font-size: 16px; margin-top: 30px;">THIS CERTIFICATION is being issued upon the request of the above named-person for: <strong><?php echo htmlspecialchars($row['purpose']); ?></strong></p>
                        <p style="font-size: 16px; margin-top: 30px;">Issued this <strong><?php echo htmlspecialchars($row['issue_date']); ?></strong> at the office of the Punong Barangay, San Carlos, City of Valencia, Bukidnon.</p>
                        <div class="signature">
                            <div style="position: absolute; right: 104px; margin-top: 350px;">
                                <p style="font-size: 18px;"><strong><?php echo htmlspecialchars($row['signatory']); ?></strong></p>
                                <p style="font-size: 16px;">Sangguniang Barangay Member</p>
                            </div>
                            <div style="position: absolute; right: 128px; margin-top: 240px;">
                                <p style="font-size: 18px;"><strong>Hon. Mariza T. Labe</strong></p>
                                <p style="font-size: 16px;">Punong Barangay</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button onclick="printCertificate('clearanceModal')" class="btn btn-success">Print</button>
            </div>
        </div>
    </div>
</div>

    <script>
    function printCertificate(modalId) {
        // Get the modal content
        let modal = document.getElementById(modalId);
        let modalContent = modal.querySelector('.document').outerHTML;

        // Create a new window for printing
        let printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Certificate</title>
                    <style>
                        @media print {
                            @page {
                                size: 8.5in 13in; /* Long bond paper size */
                                margin: 0.5in;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                            }
                            .document {
                                width: 8.5in;
                                height: 13in;
                                padding: 1in;
                                border: 1px solid black;
                                background: white;
                                font-family: 'Times New Roman', Times, serif;
                                color: black;
                                box-shadow: none;
                                margin: 0 auto;
                                position: relative;
                                line-height: 1.5;
                            }
                            .document h3, .document h5 {
                                text-align: center;
                            }
                            .document p {
                                text-align: justify;
                                text-indent: 50px;
                                font-size: 16px;
                            }
                            .header {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                text-align: center;
                                margin-bottom: 70px;
                            }
                            .header .left-logo {
                                width: 90px;
                                height: 90px;
                                margin-top: -190px;
                            }
                            .header .right-logo {
                                width: 95px;
                                height: 95px;
                                margin-top: -190px;
                            }
                            .header div {
                                flex: 1;
                                text-align: center;
                            }
                            .header h3, .header h5 {
                                margin: 2px 0;
                            }
                            .signature {
                                display: flex;
                                justify-content: space-between;
                                margin-top: 40px;
                            }
                            .signature div {
                                text-align: center;
                            }
                            .signature p {
                                margin: 5px 0;
                                text-align: center;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${modalContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
        function showDocumentModal() {
            let documentType = "<?php echo $row['document_type']; ?>"; // Get document type from PHP

            if (documentType === "Indigency Certificate") {
                new bootstrap.Modal(document.getElementById('certificateModal')).show();
            } else if (documentType === "Residency Certificate") {
                new bootstrap.Modal(document.getElementById('residencyModal')).show();
            } else if (documentType === "Barangay Clearance") {
                new bootstrap.Modal(document.getElementById('clearanceModal')).show();
            } else {
                alert("Only Certificate of Indigency, Residency Certificate, or Barangay Clearance can be viewed.");
            }
        }
    </script>
</body>
</html>