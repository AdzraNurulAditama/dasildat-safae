<?php

include 'components/header.php';

$result = null;
$error = null;

if(isset($_POST['predict_csv'])){

    // =========================
    // GET MODEL
    // =========================

    $model = $_POST['model'];

    // =========================
    // CHECK FILE
    // =========================

    if(isset($_FILES['csv_file'])){

        $file_name = $_FILES['csv_file']['name'];
        $tmp_name  = $_FILES['csv_file']['tmp_name'];

        // =========================
        // FILE EXTENSION
        // =========================

        $file_ext = strtolower(
            pathinfo($file_name, PATHINFO_EXTENSION)
        );

        if($file_ext != 'csv'){

            $error = "File harus format CSV";

        }else{

            // =========================
            // CREATE UPLOAD FOLDER
            // =========================

            if(!is_dir('uploads')){

                mkdir('uploads');

            }

            // =========================
            // MOVE FILE
            // =========================

            $new_name = time() . "_" . $file_name;

            $upload_path = "uploads/" . $new_name;

            move_uploaded_file(
                $tmp_name,
                $upload_path
            );

            // =========================
            // PYTHON COMMAND
            // =========================

            $command =
                "python predict.py "
                . "$model csv "
                . "\"$upload_path\"";

            // =========================
            // RUN PYTHON
            // =========================

            $output = shell_exec($command);

            // =========================
            // CHECK ERROR
            // =========================

            if(str_contains($output, "ERROR")){

                $error = $output;

            }else{

                $result = json_decode($output, true);

            }

        }

    }

}

?>

<div class="container">

    <div class="form-card">

        <h1 class="mb-4">

            Prediksi CSV

        </h1>

        <p class="mb-4">

            Upload file CSV untuk melakukan
            prediksi banyak data sekaligus.

        </p>

        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <!-- MODEL -->

            <div class="mb-4">

                <label>
                    Machine Learning Model
                </label>

                <select
                    name="model"
                    class="form-select custom-input"
                >

                    <option value="DT">
                        Decision Tree
                    </option>

                    <option value="RF">
                        Random Forest
                    </option>

                    <option value="KNN">
                        KNN
                    </option>

                </select>

            </div>

            <!-- FILE -->

            <div class="mb-4">

                <label>
                    Upload CSV File
                </label>

                <input
                    type="file"
                    name="csv_file"
                    class="form-control custom-input"
                    required
                >

            </div>

            <!-- BUTTON -->

            <button
                type="submit"
                name="predict_csv"
                class="btn btn-success"
            >

                Prediksi CSV

            </button>

        </form>

    </div>

    <!-- ERROR -->

    <?php if($error): ?>

        <div class="alert alert-danger mt-4">

            <?= $error; ?>

        </div>

    <?php endif; ?>

    <!-- RESULT -->

    <?php if($result): ?>

        <div class="result-card mt-5">

            <h2 class="mb-4">

                Hasil Prediksi CSV

            </h2>

            <table class="table table-dark table-bordered">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Prediction</th>
                        <th>Confidence</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($result as $row): ?>

                        <tr>

                            <td>
                                <?= $row['row']; ?>
                            </td>

                            <td>
                                <?= $row['prediction']; ?>
                            </td>

                            <td>
                                <?= $row['confidence']; ?>%
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?php include 'components/footer.php'; ?>