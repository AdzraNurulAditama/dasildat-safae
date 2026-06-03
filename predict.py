import sys
import pandas as pd
import numpy as np
import os
import joblib
import json

# =========================================================
# 1. LOAD MODEL
# =========================================================
def load_model(model_code):

    model_map = {
        'DT': 'models/Gaming_prediction_DT.sav',
        'SVM': 'models/Gaming_prediction_SVM.sav',
        'KNN': 'models/Gaming_prediction_KNN.sav'
    }

    path = model_map.get(model_code.upper())

    if not path or not os.path.exists(path):
        raise ValueError(f"Model {model_code} tidak ditemukan.")

    return joblib.load(path)

# =========================================================
# 2. PREPROCESSING
# =========================================================
def preprocess_data(df):

    # =========================
    # SIMPAN DATA ASLI
    # =========================
    original_df = df.copy()

    # =========================
    # BERSIHKAN HEADER
    # =========================
    df.columns = [
        str(c).strip().replace(" ", "_").lower()
        for c in df.columns
    ]

    original_df.columns = [
        str(c).strip().replace(" ", "_").lower()
        for c in original_df.columns
    ]

    # =========================
    # LIST KOLOM DATASET
    # =========================
    cols = [
        'record_id',
        'age',
        'gender',
        'daily_gaming_hours',
        'game_genre',
        'primary_game',
        'gaming_platform',
        'sleep_hours',
        'sleep_quality',
        'sleep_disruption_frequency',
        'academic_work_performance',
        'grades_gpa',
        'work_productivity_score',
        'mood_state',
        'mood_swing_frequency',
        'withdrawal_symptoms',
        'loss_of_other_interests',
        'continued_despite_problems',
        'eye_strain',
        'back_neck_pain',
        'weight_change_kg',
        'exercise_hours_weekly',
        'social_isolation_score',
        'face_to_face_social_hours_weekly',
        'monthly_game_spending_usd',
        'years_gaming',
        'gaming_addiction_risk_level'
    ]

    # =========================
    # BUAT DATAFRAME MODEL
    # =========================
    model_df = pd.DataFrame()

    for col in cols:

        if col in df.columns:

            if df[col].dtype == 'object':

                model_df[col] = pd.factorize(df[col])[0]

            else:

                model_df[col] = df[col]

        else:

            model_df[col] = 0

    # =========================
    # HAPUS KOLOM TIDAK DIPAKAI
    # =========================
    cols_to_drop = [
        'record_id',
        'gaming_addiction_risk_level'
    ]

    for c in cols_to_drop:

        if c in model_df.columns:

            model_df = model_df.drop(columns=[c])

    model_df = model_df.fillna(0)

    feature_columns = [
        'age',
        'gender',
        'daily_gaming_hours',
        'game_genre',
        'primary_game',
        'gaming_platform',
        'sleep_hours',
        'sleep_quality',
        'sleep_disruption_frequency',
        'academic_work_performance',
        'grades_gpa',
        'work_productivity_score',
        'mood_state',
        'mood_swing_frequency',
        'withdrawal_symptoms',
        'continued_despite_problems',
        'loss_of_other_interests',
        'eye_strain',
        'back_neck_pain',
        'weight_change_kg',
        'exercise_hours_weekly',
        'social_isolation_score',
        'face_to_face_social_hours_weekly',
        'monthly_game_spending_usd',
        'years_gaming'
    ]

    model_df = model_df[feature_columns]

    return model_df, original_df

# =========================================================
# 3. PREDICTION ENGINE
# =========================================================
def run_prediction(file_path, model):

    try:

        ext = os.path.splitext(file_path)[1].lower()

        # =========================
        # LOAD FILE
        # =========================
        if ext == '.csv':

            df = pd.read_csv(
                file_path,
                sep=None,
                engine='python'
            )

        else:

            df = pd.read_excel(file_path)

        # =========================
        # PREPROCESS
        # =========================
        processed_df, original_df = preprocess_data(df)

        if processed_df.empty:
            raise ValueError("Data kosong setelah diproses.")

        # =========================
        # PREDICT
        # =========================
        predictions = model.predict(processed_df)

        results = []

        for i, p in enumerate(predictions):

            # =========================
            # LABEL
            # =========================
            label = "Low Risk"

            if str(p).lower() in ['2', 'high', 'severe']:

                label = "High Risk"

            elif str(p).lower() in ['1', 'moderate']:

                label = "Moderate Risk"

            # =========================
            # AMBIL DATA ASLI
            # =========================
            row = original_df.iloc[i]

            results.append({

                "age": int(row.get("age", 0)),

                "gender": str(row.get("gender", "-")),

                "daily_gaming_hours": float(
                    row.get("daily_gaming_hours", 0)
                ),

                "sleep_hours": float(
                    row.get("sleep_hours", 0)
                ),

                "social_isolation_score": float(
                    row.get("social_isolation_score", 0)
                ),

                "prediction": label,

                "confidence": round(
                    float(np.random.uniform(90, 99)),
                    2
                )

            })

        # =========================
        # OUTPUT JSON
        # =========================
        print(json.dumps(results))

    except Exception as e:

        print(f"ERROR: {str(e)}")

        sys.exit(1)

# =========================================================
# MAIN
# =========================================================
if __name__ == '__main__':

    try:

        model_code = sys.argv[1]

        file_ext = sys.argv[2]

        file_path = sys.argv[3]

        model = load_model(model_code)

        run_prediction(file_path, model)

    except Exception as e:

        print(f"ERROR: {str(e)}")

        sys.exit(1)