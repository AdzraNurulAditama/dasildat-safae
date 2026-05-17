import sys
import pandas as pd
import numpy as np
import os
import joblib
import json

# =========================================================
# GAMING & MENTAL HEALTH MACHINE LEARNING PREDICTION SYSTEM
# =========================================================

# =========================
# DEFINE DATASET FEATURES
# =========================

columns = [
    'Age',
    'Gender',
    'Daily_Gaming_Hours',
    'Game_Genre',
    'Platform',
    'Weekly_Play_Days',
    'Monthly_Spending',
    'Sleep_Hours',
    'Stress_Level',
    'Anxiety_Level',
    'Social_Isolation',
    'Academic_Performance',
    'Work_Productivity',
    'Physical_Activity_Hours',
    'Caffeine_Intake',
    'Screen_Time',
    'Mood_Score'
]

# =========================
# MODEL LOADER
# =========================

def load_model(model_code):

    model_map = {
        'DT': 'model/dt_model.joblib',
        'RF': 'model/rf_model.joblib',
        'KNN': 'model/knn_model.joblib'
    }

    scaler_map = {
        'DT': 'model/scaler_dt.joblib',
        'RF': 'model/scaler_rf.joblib',
        'KNN': 'model/scaler_knn.joblib'
    }

    encoder_map = {
        'gender': 'model/gender_encoder.joblib',
        'genre': 'model/genre_encoder.joblib',
        'platform': 'model/platform_encoder.joblib'
    }

    model_path = model_map.get(model_code.upper())
    scaler_path = scaler_map.get(model_code.upper())

    if not model_path:
        raise ValueError(f"Model '{model_code}' tidak ditemukan")

    if not os.path.exists(model_path):
        raise FileNotFoundError(f"File model tidak ditemukan: {model_path}")

    model = joblib.load(model_path)

    scaler = None

    if os.path.exists(scaler_path):
        scaler = joblib.load(scaler_path)

    encoders = {}

    for key, path in encoder_map.items():

        if os.path.exists(path):
            encoders[key] = joblib.load(path)

    return model, scaler, encoders


# =========================
# VALIDATION
# =========================

def validate_numeric(value, field_name):

    try:
        return float(value)

    except:
        raise ValueError(f"{field_name} harus berupa angka")


def validate_categorical(value, valid_values, field_name):

    if value not in valid_values:
        raise ValueError(
            f"{field_name} harus salah satu dari: {', '.join(valid_values)}"
        )

    return value


# =========================
# DATA VALIDATION
# =========================

def validate_data(df):

    # ======================
    # CHECK MISSING COLUMNS
    # ======================

    missing_cols = set(columns) - set(df.columns)

    if missing_cols:
        raise ValueError(
            f"Kolom berikut tidak ditemukan: {', '.join(missing_cols)}"
        )

    # ======================
    # NUMERIC COLUMNS
    # ======================

    numeric_columns = [
        'Age',
        'Daily_Gaming_Hours',
        'Weekly_Play_Days',
        'Monthly_Spending',
        'Sleep_Hours',
        'Stress_Level',
        'Anxiety_Level',
        'Social_Isolation',
        'Academic_Performance',
        'Work_Productivity',
        'Physical_Activity_Hours',
        'Caffeine_Intake',
        'Screen_Time',
        'Mood_Score'
    ]

    for col in numeric_columns:

        if not pd.to_numeric(df[col], errors='coerce').notnull().all():
            raise ValueError(f"Kolom '{col}' harus numerik")

    # ======================
    # VALIDATE GENDER
    # ======================

    valid_gender = ['Male', 'Female']

    if not df['Gender'].isin(valid_gender).all():
        raise ValueError(
            "Gender hanya boleh Male atau Female"
        )

    # ======================
    # VALIDATE GENRE
    # ======================

    valid_genre = [
        'Action',
        'RPG',
        'FPS',
        'MOBA',
        'Sports',
        'Simulation',
        'Adventure',
        'Strategy'
    ]

    if not df['Game_Genre'].isin(valid_genre).all():
        raise ValueError(
            "Game Genre tidak valid"
        )

    # ======================
    # VALIDATE PLATFORM
    # ======================

    valid_platform = [
        'PC',
        'Mobile',
        'Console'
    ]

    if not df['Platform'].isin(valid_platform).all():
        raise ValueError(
            "Platform harus PC, Mobile, atau Console"
        )


# =========================
# PREPROCESSING
# =========================

def preprocess_data(df, scaler, encoders):

    df = df.copy()

    # ======================
    # ENCODING
    # ======================

    if 'gender' in encoders:

        df['Gender'] = encoders['gender'].transform(
            df['Gender']
        )

    if 'genre' in encoders:

        df['Game_Genre'] = encoders['genre'].transform(
            df['Game_Genre']
        )

    if 'platform' in encoders:

        df['Platform'] = encoders['platform'].transform(
            df['Platform']
        )

    # ======================
    # SCALING
    # ======================

    if scaler:

        df = scaler.transform(df)

    return df


# =========================
# CSV PREDICTION
# =========================

def predict_from_csv(file_path, model, scaler, encoders):

    try:

        # ======================
        # READ CSV
        # ======================

        df = pd.read_csv(file_path)

        # ======================
        # VALIDATE
        # ======================

        validate_data(df)

        # ======================
        # ORDER COLUMNS
        # ======================

        df_features = df[columns]

        # ======================
        # PREPROCESS
        # ======================

        processed_data = preprocess_data(
            df_features,
            scaler,
            encoders
        )

        # ======================
        # PREDICT
        # ======================

        predictions = model.predict(processed_data)

        probabilities = None

        if hasattr(model, "predict_proba"):

            probabilities = model.predict_proba(processed_data)

        # ======================
        # OUTPUT
        # ======================

        results = []

        for i, pred in enumerate(predictions):

            result = {
                "row": i + 1,
                "prediction": str(pred)
            }

            if probabilities is not None:

                confidence = round(
                    np.max(probabilities[i]) * 100,
                    2
                )

                result["confidence"] = confidence

            results.append(result)

        print(json.dumps(results))

    except Exception as e:

        print(f"ERROR: {str(e)}")
        sys.exit(1)


# =========================
# MANUAL PREDICTION
# =========================

def predict_from_args(args, model, scaler, encoders):

    try:

        # ======================
        # CREATE DATAFRAME
        # ======================

        df = pd.DataFrame([args], columns=columns)

        # ======================
        # VALIDATE
        # ======================

        validate_data(df)

        # ======================
        # PREPROCESS
        # ======================

        processed_data = preprocess_data(
            df,
            scaler,
            encoders
        )

        # ======================
        # PREDICT
        # ======================

        prediction = model.predict(processed_data)[0]

        confidence = None

        if hasattr(model, "predict_proba"):

            probabilities = model.predict_proba(processed_data)

            confidence = round(
                np.max(probabilities[0]) * 100,
                2
            )

        result = {
            "prediction": str(prediction),
            "confidence": confidence
        }

        print(json.dumps(result))

    except Exception as e:

        print(f"ERROR: {str(e)}")
        sys.exit(1)


# =========================
# MAIN PROGRAM
# =========================

if __name__ == '__main__':

    try:

        # ======================
        # CHECK ARGUMENT
        # ======================

        if len(sys.argv) < 3:

            print("ERROR: Argumen tidak lengkap")
            sys.exit(1)

        # ======================
        # GET PARAMETER
        # ======================

        model_code = sys.argv[1]
        input_type = sys.argv[2]

        # ======================
        # LOAD MODEL
        # ======================

        model, scaler, encoders = load_model(model_code)

        # ======================
        # CSV PREDICTION
        # ======================

        if input_type == 'csv':

            if len(sys.argv) != 4:

                print(
                    "ERROR: Format CSV -> python predict.py <model> csv <file>"
                )

                sys.exit(1)

            file_path = sys.argv[3]

            if not os.path.exists(file_path):

                print(
                    f"ERROR: File tidak ditemukan: {file_path}"
                )

                sys.exit(1)

            predict_from_csv(
                file_path,
                model,
                scaler,
                encoders
            )

        # ======================
        # MANUAL PREDICTION
        # ======================

        elif input_type == 'manual':

            if len(sys.argv) != 20:

                print(
                    "ERROR: Jumlah parameter manual tidak sesuai"
                )

                sys.exit(1)

            args = sys.argv[3:]

            predict_from_args(
                args,
                model,
                scaler,
                encoders
            )

        else:

            print(
                "ERROR: input_type harus 'manual' atau 'csv'"
            )

            sys.exit(1)

    except Exception as e:

        print(f"ERROR: {str(e)}")
        sys.exit(1)