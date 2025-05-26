<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Stock Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center mb-4">Blood Stock Update</h2>

        <!-- Display Blood Stock -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">Current Blood Stock</div>
            <div class="card-body">
                <?php include "fetch_stock.php"; ?>
            </div>
        </div>

        <!-- Update Stock Form -->
        <div class="card">
            <div class="card-header bg-primary text-white">Update Blood Stock</div>
            <div class="card-body">
                <form action="update_stock.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Blood Type:</label>
                        <select name="bloodType" class="form-select" required>
                            <option value="">Select Blood Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Units:</label>
                        <input type="number" name="units" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Update Stock</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
