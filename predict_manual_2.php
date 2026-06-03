<?php
session_start();

if(!isset($_SESSION['step1_data'])) {
    header("Location: predict_manual.php");
    exit;
}

if(isset($_POST['next_2'])) {
    $_SESSION['step2_data'] = $_POST;
    header("Location: predict_manual_3.php");
    exit;
}

include 'components/header.php';
?>

<style>
    .page-wrapper{
        display:grid;
        grid-template-columns:48% 52%;
        min-height:100vh;
        margin-top:96px;
    }

    .left-panel{
        background:#1a1916;
        padding:60px;
        color:#fff;
        display:flex;
        flex-direction:column;
        justify-content:center;
    }

    .right-panel{
        background:#fafaf7;
        padding:60px 80px !important;
        overflow-y:auto;
    }

    .section-label{
        letter-spacing:2px;
        font-size:12px;
        text-transform:uppercase;
        color:#6b7280;
        margin-bottom:24px;
        font-weight:600;
    }

    .field-input{
        width:100%;
        padding:14px;
        border:1px solid #d4d1c4;
        border-radius:12px;
        margin-bottom:20px;
        background:#ffffff;
        transition:.3s;
    }

    .field-input:focus{
        outline:none;
        border-color:#8b5cf6;
        box-shadow:0 0 0 4px rgba(139,92,246,.1);
    }

    label{
        font-size:14px;
        font-weight:600;
        margin-bottom:8px;
        display:block;
        color:#374151;
    }

    .progress-wrapper{
        display:flex;
        gap:8px;
        margin-bottom:40px;
    }

    .progress-item{
        flex:1;
        height:8px;
        border-radius:999px;
    }

    .progress-active{
        background:#8b5cf6;
    }

    .progress-inactive{
        background:#e5e7eb;
    }

    .button-group{
        display:flex;
        gap:16px;
        margin-top:20px;
    }

    .btn-back{
        flex:1;
        padding:18px;
        border-radius:12px;
        border:1px solid #d4d1c4;
        background:#fff;
        color:#1a1916;
        text-decoration:none;
        text-align:center;
        font-weight:700;
        text-transform:uppercase;
        transition:.3s;
    }

    .btn-back:hover{
        background:#f5f5f5;
    }

    .btn-next{
        flex:1;
        padding:18px;
        background:#1a1916;
        color:#fff;
        border-radius:12px;
        font-weight:700;
        text-transform:uppercase;
        border:none;
        cursor:pointer;
        transition:.3s;
    }

    .btn-next:hover{
        opacity:.9;
    }

    .custom-navbar{
        z-index:99999 !important;
    }

    @media (max-width:991px){

        .page-wrapper{
            grid-template-columns:1fr;
            margin-top:100px;
        }

        .left-panel{
            padding:40px 30px;
        }

        .right-panel{
            padding:40px 25px !important;
            height:auto;
        }

        .button-group{
            flex-direction:column;
        }
    }
</style>

<div class="page-wrapper">

    <aside class="left-panel">

        <div style="margin-bottom:30px;">
            <span style="
                background:rgba(196,185,255,.15);
                border:1px solid rgba(196,185,255,.25);
                color:#c4b9ff;
                padding:10px 16px;
                border-radius:999px;
                font-size:12px;
                font-weight:700;
                letter-spacing:2px;
                text-transform:uppercase;
            ">
                Step 2 of 3
            </span>
        </div>

        <h1 style="
            font-family:'Instrument Serif', serif;
            font-size:56px;
            line-height:1.05;
        ">
            Kondisi<br>
            <em style="color:#c4b9ff;">fisik</em><br>
            dan gaya hidupmu
        </h1>

        <p style="
            color:rgba(255,255,255,.65);
            margin-top:20px;
            max-width:420px;
            line-height:1.8;
        ">
            Informasi kesehatan fisik membantu sistem memahami dampak aktivitas gaming terhadap kualitas tidur, aktivitas sosial, dan kebugaran harian Anda.
        </p>

    </aside>

    <main class="right-panel">

        <div class="progress-wrapper">
            <div class="progress-item progress-active"></div>
            <div class="progress-item progress-active"></div>
            <div class="progress-item progress-inactive"></div>
        </div>

        <form method="POST">

            <h5 class="section-label">
                Kesehatan Fisik & Aktivitas
            </h5>

            <div class="row">

                <div class="col-md-6">
                    <label>Sleep Hours</label>
                            <input type="number"
                                step="0.01"
                                name="sleep_hours"
                                class="field-input"
                                placeholder="Contoh: 7.5"
                                required>                
                            </div>

                <div class="col-md-6">
                    <label>Sleep Quality</label>
                    <select name="sleep_quality" class="field-input">
                        <option value="Very Poor">Very Poor</option>
                        <option value="Poor">Poor</option>
                        <option value="Fair">Fair</option>
                        <option value="Good">Good</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Sleep Disruption Frequency</label>
                    <select name="sleep_disruption_frequency" class="field-input">
                        <option value="Never">Never</option>
                        <option value="Sometimes">Sometimes</option>
                        <option value="Frequently">Frequently</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Academic / Work Performance</label>
                    <select name="academic_work_performance" class="field-input">
                        <option value="Excellent">Excellent</option>
                        <option value="Average">Average</option>
                        <option value="Poor">Poor</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Grades GPA</label>
                    <input type="number" step="0.01" name="grades_gpa" class="field-input" placeholder="Contoh: 3.85" required>
                </div>

                <div class="col-md-6">
                    <label>Work Productivity Score</label>
                    <input type="number" name="work_productivity_score" class="field-input" placeholder="Skala 1-10" required>
                </div>

                <div class="col-md-6">
                    <label>Monthly Game Spending (USD)</label>
                    <input type="number"
                        step="0.01"
                        name="monthly_game_spending_usd"
                        class="field-input"
                        placeholder="Contoh: 25.50"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Exercise Hours Weekly</label>
                    <input type="number" step="0.1" name="exercise_hours_weekly" class="field-input" placeholder="Contoh: 3" required>
                </div>

                <div class="col-md-4">
                    <label>Eye Strain</label>
                    <select name="eye_strain" class="field-input">
                        <option value="True">Yes</option>
                        <option value="False">No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Neck / Back Pain</label>
                    <select name="back_neck_pain" class="field-input">
                        <option value="True">Yes</option>
                        <option value="False">No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Weight Change (Kg)</label>
                    <input type="number" step="0.1" name="weight_change_kg" class="field-input" placeholder="Contoh: 0" required>
                </div>

                <div class="col-md-6">
                    <label>Social Isolation Score</label>
                    <input type="number" name="social_isolation_score" class="field-input" placeholder="Skala 1-10" required>
                </div>

                <div class="col-md-6">
                    <label>Face To Face Social Hours Weekly</label>
                    <input type="number" step="0.1" name="face_to_face_social_hours_weekly" class="field-input" placeholder="Contoh: 5" required>
                </div>

            </div>

            <div class="button-group">
                <a href="predict_manual.php" class="btn-back">
                    &larr; Back
                </a>

                <button type="submit" name="next_2" class="btn-next">
                    Continue to Mental Analysis &rarr;
                </button>
            </div>

        </form>

    </main>

</div>

<?php include 'components/footer.php'; ?>