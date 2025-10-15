<?php
$conn = new mysqli("localhost", "root", "", "barangay");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM officials WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Official not found.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Official</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Floating Edit Official Modal -->
<div class="modal fade show" id="editOfficialModal" tabindex="-1" aria-labelledby="editOfficialModalLabel" aria-hidden="true" style="display: block; background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOfficialModalLabel">Edit Barangay Official</h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <form action="update_official.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" id="edit-name" name="complete_name" value="<?= $row['complete_name'] ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="edit-committee" class="form-label">Committee</label>
                        <input type="text" id="edit-committee" name="mobile_number" value="<?= $row['mobile_number'] ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="edit-position" class="form-label">Position</label>
                        <input type="text" id="edit-position" name="position" value="<?= $row['position'] ?>" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function closeModal() {
        window.history.back(); // Go back to the previous page
    }
</script>

</body>
</html>
