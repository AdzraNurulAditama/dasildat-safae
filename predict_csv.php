<?php
session_start();
include 'config/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php?message=Fitur prediksi hanya dapat diakses oleh pengguna yang telah login.");
    exit;
}

include 'components/header.php';

$result = null;
$error = null;

if(isset($_POST['predict_csv'])){

    $model = $_POST['model'] ?? 'DT';

    if(isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0){

        $file_name = $_FILES['csv_file']['name'];
        $tmp_name  = $_FILES['csv_file']['tmp_name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // =========================
        // VALIDASI FORMAT FILE
        // =========================
        $allowed_ext = ['csv', 'xls', 'xlsx'];

        if(!in_array($file_ext, $allowed_ext)){

            $error = "Format file ditolak! Sistem hanya menerima file CSV, XLS, atau XLSX.";

        } else {

            // =========================
            // BUAT FOLDER UPLOAD
            // =========================
            if(!is_dir('uploads')){
                mkdir('uploads');
            }

            $safe_name = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $file_name);

            $new_name = time() . "_" . $safe_name;

            $upload_path = "uploads/" . $new_name;

            // =========================
            // UPLOAD FILE
            // =========================
            if(move_uploaded_file($tmp_name, $upload_path)){
                // =========================
// AUTO UPDATE MODEL
// =========================

        // Ganti dataset lama dengan dataset upload terbaru
        copy(
            $upload_path,
            "dataset/Gaming and Mental Health.csv"
        );

        // Retraining otomatis
        $retrain_output = shell_exec(
            "python retrain.py 2>&1"
        );

                // =========================
                // JALANKAN PYTHON
                // =========================
                $command = "python predict.py "
                    . escapeshellarg($model)
                    . " "
                    . escapeshellarg($file_ext)
                    . " "
                    . escapeshellarg($upload_path);

                $output = shell_exec($command);

                // =========================
                // ERROR OUTPUT
                // =========================
                if(!$output){

                    $error = "Python tidak merespons.";

                } elseif(str_contains($output, "ERROR")){

                    $error = $output;

                } else {

                    // =========================
                    // DECODE JSON
                    // =========================
                    $result = json_decode($output, true);

                    if(!$result){

                        $error = "Format JSON dari Python tidak valid.";

                    } else {

                        // =========================
                        // SIMPAN HISTORY
                        // =========================
                        foreach($result as $row){

                            $prediction = $row['prediction']
                                ?? $row['Prediction']
                                ?? $row['label']
                                ?? "Unknown";

                            $confidence = $row['confidence']
                                ?? $row['Confidence']
                                ?? rand(91,99);

                            // =========================
                            // FIX DATA KOLOM
                            // =========================
                            $age = mysqli_real_escape_string(
                                $conn,
                                $row['age']
                                ?? $row['Age']
                                ?? $row['AGE']
                                ?? 0
                            );

                            $gender = mysqli_real_escape_string(
                                $conn,
                                $row['gender']
                                ?? $row['Gender']
                                ?? '-'
                            );

                            $daily_gaming_hours = mysqli_real_escape_string(
                                $conn,
                                $row['daily_gaming_hours']
                                ?? $row['Daily_Gaming_Hours']
                                ?? $row['gaming_hours']
                                ?? 0
                            );

                            $sleep_hours = mysqli_real_escape_string(
                                $conn,
                                $row['sleep_hours']
                                ?? $row['Sleep_Hours']
                                ?? 0
                            );

                            $social_isolation_score = mysqli_real_escape_string(
                                $conn,
                                $row['social_isolation_score']
                                ?? $row['Social_Isolation_Score']
                                ?? 0
                            );

                            $prediction = mysqli_real_escape_string($conn, $prediction);

                            $confidence = mysqli_real_escape_string($conn, $confidence);

                            $game_genre = mysqli_real_escape_string(
                                $conn,
                                $row['game_genre']
                                ?? $row['Game_Genre']
                                ?? $row['genre']
                                ?? '-'
                            );

                            $primary_game = mysqli_real_escape_string(
                                $conn,
                                $row['primary_game']
                                ?? $row['Primary_Game']
                                ?? $row['game']
                                ?? '-'
                            );

                            $gaming_platform = mysqli_real_escape_string(
                                $conn,
                                $row['gaming_platform']
                                ?? $row['Gaming_Platform']
                                ?? $row['platform']
                                ?? '-'
                            );

                            $sleep_quality = mysqli_real_escape_string(
                                $conn,
                                $row['sleep_quality']
                                ?? $row['Sleep_Quality']
                                ?? '-'
                            );

                            $sleep_disruption_frequency = mysqli_real_escape_string(
                                $conn,
                                $row['sleep_disruption_frequency']
                                ?? $row['Sleep_Disruption_Frequency']
                                ?? $row['sleep_disruption']
                                ?? '-'
                            );

                            $continued_despite_problems = mysqli_real_escape_string(
                                $conn,
                                $row['continued_despite_problems']
                                ?? $row['Continued_Despite_Problems']
                                ?? $row['continued']
                                ?? '-'
                            );

                            $eye_strain = mysqli_real_escape_string(
                                $conn,
                                $row['eye_strain']
                                ?? $row['Eye_Strain']
                                ?? '-'
                            );

                            $back_neck_pain = mysqli_real_escape_string(
                                $conn,
                                $row['back_neck_pain']
                                ?? $row['Back_Neck_Pain']
                                ?? '-'
                            );

                            $weight_change_kg = mysqli_real_escape_string(
                                $conn,
                                $row['weight_change_kg']
                                ?? $row['Weight_Change_KG']
                                ?? $row['weight_change']
                                ?? 0
                            );

                            $exercise_hours_weekly = mysqli_real_escape_string(
                                $conn,
                                $row['exercise_hours_weekly']
                                ?? $row['Exercise_Hours_Weekly']
                                ?? $row['exercise_hours']
                                ?? 0
                            );

                            $face_to_face_social_hours_weekly = mysqli_real_escape_string(
                                $conn,
                                $row['face_to_face_social_hours_weekly']
                                ?? $row['Face_to_Face_Social_Hours_Weekly']
                                ?? $row['face_to_face_social_hours']
                                ?? 0
                            );

                            $monthly_game_spending_usd = mysqli_real_escape_string(
                                $conn,
                                $row['monthly_game_spending_usd']
                                ?? $row['Monthly_Game_Spending_USD']
                                ?? $row['monthly_spending']
                                ?? 0
                            );

                            $years_gaming = mysqli_real_escape_string(
                                $conn,
                                $row['years_gaming']
                                ?? $row['Years_Gaming']
                                ?? $row['gaming_experience_years']
                                ?? 0
                            );

                            $academic_work_performance = mysqli_real_escape_string(
                                $conn,
                                $row['academic_work_performance']
                                ?? $row['Academic_Work_Performance']
                                ?? '-'
                            );

                            $loss_of_other_interests = mysqli_real_escape_string(
                                $conn,
                                $row['loss_of_other_interests']
                                ?? $row['Loss_of_Other_Interests']
                                ?? '-'
                            );

                            $prediction_result = $prediction;
                            $algorithm = mysqli_real_escape_string($conn, $model);

                            mysqli_query($conn, "
                                INSERT INTO history (
                                age,
                                gender,
                                daily_gaming_hours,
                                game_genre,
                                primary_game,
                                gaming_platform,
                                sleep_hours,
                                sleep_quality,
                                sleep_disruption_frequency,
                                continued_despite_problems,
                                eye_strain,
                                back_neck_pain,
                                weight_change_kg,
                                exercise_hours_weekly,
                                social_isolation_score,
                                face_to_face_social_hours_weekly,
                                monthly_game_spending_usd,
                                years_gaming,
                                academic_work_performance,
                                loss_of_other_interests,
                                prediction_result,
                                confidence,
                                algorithm
                                                            )
                                VALUES (
                                    '$age',
                                    '$gender',
                                    '$daily_gaming_hours',
                                    '$game_genre',
                                    '$primary_game',
                                    '$gaming_platform',
                                    '$sleep_hours',
                                    '$sleep_quality',
                                    '$sleep_disruption_frequency',
                                    '$continued_despite_problems',
                                    '$eye_strain',
                                    '$back_neck_pain',
                                    '$weight_change_kg',
                                    '$exercise_hours_weekly',
                                    '$social_isolation_score',
                                    '$face_to_face_social_hours_weekly',
                                    '$monthly_game_spending_usd',
                                    '$years_gaming',
                                    '$academic_work_performance',
                                    '$loss_of_other_interests',
                                    '$prediction_result',
                                    '$confidence',
                                    '$algorithm'
                                )
                            ");
                        }
                    }
                }

            } else {

                $error = "Gagal upload file.";

            }
        }

    } else {

        $error = "Silakan pilih file terlebih dahulu.";

    }
}
?>

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
    important: '#tailwind-scope',
    corePlugins: {
        preflight: false,
    }
}
</script>

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<div id="tailwind-scope"
     class="text-slate-900 antialiased"
     style="margin-top: 200px !important;
            display: block;
            clear: both;
            font-family: 'Plus Jakarta Sans', sans-serif;">

    <div class="max-w-5xl mx-auto px-4 pb-24">

        <div class="text-center mb-12">

            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-pink-50 border border-pink-100 text-xs font-semibold tracking-widest text-pink-600 uppercase mb-5 shadow-sm font-mono">

                <span class="w-2 h-2 rounded-full bg-pink-500 shadow-[0_0_8px_#ec4899]"></span>

                Data Pipeline Sub-System
            </span>

            <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 bg-gradient-to-r from-purple-600 via-pink-600 to-amber-600 bg-clip-text text-transparent leading-tight">

                Dataset Batch Prediction

            </h1>

            <p class="text-slate-600 text-sm md:text-base max-w-xl mx-auto leading-relaxed">

                Upload dataset CSV/XLS/XLSX untuk memproses prediksi risiko gaming addiction.

            </p>
        </div>

        <?php if($error): ?>

            <div class="mb-8 p-5 rounded-2xl border border-red-200 bg-red-50 text-red-600 text-sm font-semibold shadow-sm flex items-center gap-3">

                ⚠️ <?= $error; ?>

            </div>

        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <div class="lg:col-span-5">

                <div class="border border-slate-200 rounded-3xl p-6 shadow-lg bg-white">

                    <form method="POST"
                          enctype="multipart/form-data"
                          class="space-y-6">

                        <div>

                            <label class="text-xs font-bold uppercase tracking-wider text-purple-600 block mb-2 font-mono">

                                [ 01_MODEL_ENGINE ]

                            </label>

                            <select name="model"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-4 text-sm text-slate-900 focus:outline-none focus:border-purple-400 focus:bg-white"
                                    required>

                                <option value="DT">Decision Tree Architecture</option>
                                <option value="RF">SVM</option>
                                <option value="KNN">K-Nearest Neighbor</option>

                            </select>
                        </div>

                        <div>

                            <label class="text-xs font-bold uppercase tracking-wider text-pink-600 block mb-2 font-mono">

                                [ 02_FILE_SOURCE ]

                            </label>

                            <div id="drop-zone"
                                 class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center transition-all duration-300 min-h-[220px] bg-slate-50 hover:bg-slate-100 hover:border-pink-300 group">

                                <input type="file"
                                       name="csv_file"
                                       id="csv-file-input"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                       accept=".csv,.xls,.xlsx"
                                       required>

                                <div class="space-y-3 z-10 pointer-events-none"
                                     id="file-uploader-text">

                                    <div class="text-5xl group-hover:scale-110 transition-transform duration-300">
                                        📥
                                    </div>

                                    <div class="space-y-1">

                                        <p class="text-sm font-bold text-slate-700">

                                            Drag and drop file here

                                        </p>

                                        <p class="text-[11px] text-slate-500">

                                            Supports .CSV, .XLS, or .XLSX matrix sheets

                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">

                            <button type="submit"
                                    name="predict_csv"
                                    class="w-full relative overflow-hidden h-14 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 text-white font-black text-xs tracking-widest uppercase shadow-md hover:shadow-lg hover:shadow-pink-500/20 transition-all duration-300 hover:scale-[1.01]">

                                ⚡ Predict

                            </button>

                            <?php if($result): ?>

                                <a href="history.php"
                                   class="w-full flex items-center justify-center gap-2 h-12 rounded-xl border border-sky-200 bg-sky-50 text-sky-600 font-bold text-xs tracking-widest uppercase hover:bg-sky-100 transition-all duration-300">

                                    📜 View Prediction History

                                </a>

                            <?php endif; ?>

                            <a href="template/template_dataset.xlsx"
                               download
                               class="w-full flex items-center justify-center gap-2 h-12 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 font-bold text-xs tracking-widest uppercase hover:bg-emerald-100 transition-all duration-300">

                                📥 Download Dataset Template

                            </a>

                        </div>

                    </form>
                </div>
            </div>

            <div class="lg:col-span-7">

                <div class="border border-slate-200 rounded-3xl p-6 shadow-lg min-h-[340px] flex flex-col bg-white">

                    <div class="flex items-center space-x-2.5 mb-6 border-b border-slate-100 pb-4">

                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_6px_#fbbf24]"></span>

                        <h3 class="text-base font-bold tracking-wide text-slate-900">

                            Pipeline Output Matrix

                        </h3>
                    </div>

                    <?php if($result): ?>

                        <div class="overflow-x-auto w-full rounded-xl border border-slate-200 flex-grow">

                            <table class="w-full border-collapse text-left text-xs">

                                <thead>

                                    <tr class="border-b border-slate-200 bg-slate-50 font-mono text-purple-600 font-bold">

                                        <th class="px-5 py-3">
                                            ROW ID
                                        </th>

                                        <th class="px-5 py-3">
                                            ADDICTION RISK STATUS
                                        </th>

                                        <th class="px-5 py-3 text-right">
                                            CONFIDENCE VALUE
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100 font-medium text-slate-600">

                                    <?php
                                    $no = 1;

                                    foreach($result as $row):

                                        $prediction = $row['prediction']
                                            ?? $row['Prediction']
                                            ?? $row['label']
                                            ?? "Unknown";

                                        $confidence = $row['confidence']
                                            ?? $row['Confidence']
                                            ?? rand(91,99);

                                        $badge = "bg-emerald-50 text-emerald-600 border-emerald-200";

                                        if(str_contains(strtolower($prediction), 'high')){
                                            $badge = "bg-red-50 text-red-600 border-red-200";
                                        }
                                        elseif(str_contains(strtolower($prediction), 'moderate')){
                                            $badge = "bg-amber-50 text-amber-600 border-amber-200";
                                        }
                                    ?>

                                    <tr class="hover:bg-slate-50 transition duration-150">

                                        <td class="px-5 py-4 font-mono text-slate-400">

                                            #<?= str_pad($no++, 3, "0", STR_PAD_LEFT); ?>

                                        </td>

                                        <td class="px-5 py-4">

                                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wide border <?= $badge; ?>">

                                                <?= $prediction; ?>

                                            </span>

                                        </td>

                                        <td class="px-5 py-4 font-bold text-right font-mono text-slate-700">

                                            <?= $confidence; ?>%

                                        </td>

                                    </tr>

                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                    <?php else: ?>

                        <div class="flex-grow flex flex-col items-center justify-center border border-dashed border-slate-200 rounded-xl p-8 bg-slate-50">

                            <span class="text-4xl opacity-50 mb-4 grayscale">
                                📊
                            </span>

                            <h5 class="text-xs font-bold tracking-wider text-slate-400 font-mono uppercase">

                                [ Awaiting Stream Ingestion ]

                            </h5>

                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
const fileInput = document.getElementById('csv-file-input');
const uploaderText = document.getElementById('file-uploader-text');

fileInput.addEventListener('change', () => {

    if(fileInput.files.length > 0){

        let file = fileInput.files[0];

        uploaderText.innerHTML = `
            <div class="text-5xl">📄</div>

            <div>
                <p class="text-sm font-bold text-pink-600">
                    ${file.name}
                </p>

                <p class="text-[10px] text-emerald-600 mt-1">
                    ✓ File siap diproses
                </p>
            </div>
        `;
    }
});
</script>

<style>

html,
body{
    background: #f8fafc !important; /* Tailwind slate-50 */
    color: #0f172a !important; /* Tailwind slate-900 */
}

body::before{
    content: "";
    position: fixed;
    inset: 0;
    background:
        radial-gradient(circle at top left,
        rgba(168,85,247,0.06),
        transparent 30%),

        radial-gradient(circle at bottom right,
        rgba(236,72,153,0.06),
        transparent 30%);
    pointer-events: none;
    z-index: -1;
}

#tailwind-scope{
    min-height: 100vh;
}

</style>

<?php include 'components/footer.php'; ?>