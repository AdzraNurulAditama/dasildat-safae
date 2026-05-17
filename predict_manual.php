<?php

include 'components/header.php';
include 'config/database.php';

$result = null;
$error = null;

if(isset($_POST['predict'])){

    // =========================
    // GET INPUT
    // =========================

    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $gaming_hours = $_POST['gaming_hours'];
    $genre = $_POST['genre'];
    $platform = $_POST['platform'];
    $sleep_hours = $_POST['sleep_hours'];
    $stress_level = $_POST['stress_level'];
    $anxiety_level = $_POST['anxiety_level'];
    $social_isolation = $_POST['social_isolation'];
    $academic_performance = $_POST['academic_performance'];
    $work_productivity = $_POST['work_productivity'];
    $physical_activity = $_POST['physical_activity'];
    $caffeine = $_POST['caffeine'];
    $screen_time = $_POST['screen_time'];
    $mood_score = $_POST['mood_score'];

    // =========================
    // MODEL
    // =========================

    $model = $_POST['model'];

    // =========================
    // PYTHON COMMAND
    // =========================

    $command = "python predict.py $model manual "
    . "\"$age\" "
    . "\"$gender\" "
    . "\"$gaming_hours\" "
    . "\"$genre\" "
    . "\"$platform\" "
    . "\"5\" "
    . "\"0\" "
    . "\"$sleep_hours\" "
    . "\"$stress_level\" "
    . "\"$anxiety_level\" "
    . "\"$social_isolation\" "
    . "\"$academic_performance\" "
    . "\"$work_productivity\" "
    . "\"$physical_activity\" "
    . "\"$caffeine\" "
    . "\"$screen_time\" "
    . "\"$mood_score\"";

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

?>

<div class="container">

    <div class="form-card">

        <h1 class="mb-4">
            Prediksi Gaming Addiction
        </h1>

        <form method="POST">

            <div class="row">

                <!-- AGE -->

                <div class="col-md-6 mb-4">

                    <label>Age</label>

                    <input
                        type="number"
                        name="age"
                        class="form-control custom-input"
                        required
                    >

                </div>

                <!-- GENDER -->

                <div class="col-md-6 mb-4">

                    <label>Gender</label>

                    <select
                        name="gender"
                        class="form-select custom-input"
                    >

                        <option value="Male">Male</option>
                        <option value="Female">Female</option>

                    </select>

                </div>

                <!-- GAMING HOURS -->

                <div class="col-md-6 mb-4">

                    <label>Daily Gaming Hours</label>

                    <input
                        type="number"
                        step="0.1"
                        name="gaming_hours"
                        class="form-control custom-input"
                        required
                    >

                </div>

                <!-- GENRE -->

                <div class="col-md-6 mb-4">

                    <label>Game Genre</label>

                    <select
                        name="genre"
                        class="form-select custom-input"
                    >

                        <option>Action</option>
                        <option>RPG</option>
                        <option>FPS</option>
                        <option>MOBA</option>
                        <option>Sports</option>

                    </select>

                </div>

                <!-- PLATFORM -->

                <div class="col-md-6 mb-4">

                    <label>Platform</label>

                    <select
                        name="platform"
                        class="form-select custom-input"
                    >

                        <option>PC</option>
                        <option>Mobile</option>
                        <option>Console</option>

                    </select>

                </div>

                <!-- SLEEP -->

                <div class="col-md-6 mb-4">

                    <label>Sleep Hours</label>

                    <input
                        type="number"
                        step="0.1"
                        name="sleep_hours"
                        class="form-control custom-input"
                    >

                </div>

                <!-- STRESS -->

                <div class="col-md-6 mb-4">

                    <label>Stress Level</label>

                    <input
                        type="number"
                        step="0.1"
                        name="stress_level"
                        class="form-control custom-input"
                    >

                </div>

                <!-- ANXIETY -->

                <div class="col-md-6 mb-4">

                    <label>Anxiety Level</label>

                    <input
                        type="number"
                        step="0.1"
                        name="anxiety_level"
                        class="form-control custom-input"
                    >

                </div>

                <!-- SOCIAL -->

                <div class="col-md-6 mb-4">

                    <label>Social Isolation</label>

                    <input
                        type="number"
                        step="0.1"
                        name="social_isolation"
                        class="form-control custom-input"
                    >

                </div>

                <!-- ACADEMIC -->

                <div class="col-md-6 mb-4">

                    <label>Academic Performance</label>

                    <input
                        type="number"
                        step="0.1"
                        name="academic_performance"
                        class="form-control custom-input"
                    >

                </div>

                <!-- WORK -->

                <div class="col-md-6 mb-4">

                    <label>Work Productivity</label>

                    <input
                        type="number"
                        step="0.1"
                        name="work_productivity"
                        class="form-control custom-input"
                    >

                </div>

                <!-- PHYSICAL -->

                <div class="col-md-6 mb-4">

                    <label>Physical Activity</label>

                    <input
                        type="number"
                        step="0.1"
                        name="physical_activity"
                        class="form-control custom-input"
                    >

                </div>

                <!-- CAFFEINE -->

                <div class="col-md-6 mb-4">

                    <label>Caffeine Intake</label>

                    <input
                        type="number"
                        step="0.1"
                        name="caffeine"
                        class="form-control custom-input"
                    >

                </div>

                <!-- SCREEN -->

                <div class="col-md-6 mb-4">

                    <label>Screen Time</label>

                    <input
                        type="number"
                        step="0.1"
                        name="screen_time"
                        class="form-control custom-input"
                    >

                </div>

                <!-- MOOD -->

                <div class="col-md-6 mb-4">

                    <label>Mood Score</label>

                    <input
                        type="number"
                        step="0.1"
                        name="mood_score"
                        class="form-control custom-input"
                    >

                </div>

                <!-- MODEL -->

                <div class="col-md-6 mb-4">

                    <label>Machine Learning Model</label>

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

            </div>

            <button
                type="submit"
                name="predict"
                class="btn btn-success"
            >

                Prediksi Sekarang

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

            <h2>
                Hasil Prediksi
            </h2>

            <h1>
                <?= $result['prediction']; ?>
            </h1>

            <p>
                Confidence:
                <?= $result['confidence']; ?>%
            </p>

        </div>

    <?php endif; ?>

</div>

<?php include 'components/footer.php'; ?>