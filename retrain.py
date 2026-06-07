import pandas as pd
import joblib
import os
import pymysql
from sqlalchemy import create_engine
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.neighbors import KNeighborsClassifier
from sklearn.svm import SVC
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import accuracy_score
import warnings

warnings.filterwarnings("ignore", category=FutureWarning)

print("=" * 50)
print("STARTING MODEL RETRAINING (HPO OPTIMIZED)")
print("=" * 50)

# =====================================================
# LOAD DATASET
# =====================================================
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
dataset_path = os.path.join(BASE_DIR, "dataset", "Gaming and Mental Health.csv")

if not os.path.exists(dataset_path):
    raise Exception(f"Dataset tidak ditemukan: {dataset_path}")

df = pd.read_csv(dataset_path, sep=';', encoding='utf-8-sig')

# =========================
# LOAD HISTORY FROM MYSQL
# =========================
engine = create_engine("mysql+pymysql://root:190606@localhost/gaming_ml")

history_df = pd.read_sql("SELECT * FROM history", engine)

print(f"Total history: {len(history_df)}")

# Hanya ambil data dengan confidence tinggi
history_df = history_df[
    history_df['confidence'] >= 95
]

print(f"History valid untuk retraining: {len(history_df)} data")

MIN_HISTORY = 50

if len(history_df) < MIN_HISTORY:
    print(
        f"History valid belum mencapai {MIN_HISTORY} data. "
        "Retraining dibatalkan."
    )
    exit()

history_columns = [
    'age', 'gender', 'daily_gaming_hours', 'game_genre', 'primary_game',
    'gaming_platform', 'sleep_hours', 'sleep_quality',
    'sleep_disruption_frequency', 'academic_work_performance',
    'grades_gpa', 'work_productivity_score', 'mood_state',
    'mood_swing_frequency', 'withdrawal_symptoms',
    'continued_despite_problems', 'loss_of_other_interests',
    'eye_strain', 'back_neck_pain', 'weight_change_kg',
    'exercise_hours_weekly', 'social_isolation_score',
    'face_to_face_social_hours_weekly',
    'monthly_game_spending_usd', 'years_gaming',
    'gaming_addiction_risk_level'
]

if len(history_df) > 0:

    history_df['prediction_result'] = history_df['prediction_result'].replace({
        'Low Risk': 'Low',
        'Moderate Risk': 'Moderate',
        'High Risk': 'High'
    })

    history_df = history_df.rename(columns={
        'prediction_result': 'gaming_addiction_risk_level'
    })

    print("\nDistribusi label history:")
    print(
        history_df['gaming_addiction_risk_level']
        .value_counts()
    )

    missing_cols = [
        col for col in history_columns
        if col not in history_df.columns
    ]

    if missing_cols:
        raise Exception(
            f"Kolom tidak ditemukan di tabel history: {missing_cols}"
        )

    history_df = history_df[history_columns]

    combined_df = pd.concat(
        [df, history_df],
        ignore_index=True
    )

else:

    print("Tidak ada history yang memenuhi syarat retraining")
    combined_df = df.copy()

combined_df.columns = [
    str(col).strip()
    for col in combined_df.columns
]

# =====================================================
# CLEANING DATA
# =====================================================
combined_df = combined_df.drop_duplicates()
combined_df = combined_df.fillna(0)

feature_columns = [
    'age', 'gender', 'daily_gaming_hours', 'game_genre', 'primary_game', 
    'gaming_platform', 'sleep_hours', 'sleep_quality', 'sleep_disruption_frequency',
    'academic_work_performance', 'grades_gpa', 'work_productivity_score', 'mood_state', 
    'mood_swing_frequency', 'withdrawal_symptoms', 'continued_despite_problems', 
    'loss_of_other_interests', 'eye_strain', 'back_neck_pain', 'weight_change_kg', 
    'exercise_hours_weekly', 'social_isolation_score', 'face_to_face_social_hours_weekly', 
    'monthly_game_spending_usd', 'years_gaming'
]

x = combined_df[feature_columns].copy()
y = combined_df['gaming_addiction_risk_level']

# Encoding Fitur
categorical_columns = [
    'gender', 'game_genre', 'primary_game', 'gaming_platform', 'sleep_quality', 
    'sleep_disruption_frequency', 'academic_work_performance', 'mood_state', 'mood_swing_frequency'
]

for col in categorical_columns:
    if col in x.columns:
        encoder = LabelEncoder()
        x[col] = encoder.fit_transform(x[col].astype(str))

boolean_columns = ['continued_despite_problems', 'eye_strain', 'back_neck_pain', 'loss_of_other_interests']
for col in boolean_columns:
    if col in x.columns:
        x[col] = x[col].astype(str).map({'True': 1, 'False': 0, '1.0': 1, '0.0': 0, '1': 1, '0': 0}).fillna(0)

target_encoder = LabelEncoder()
y = target_encoder.fit_transform(y.astype(str))

os.makedirs(os.path.join(BASE_DIR, "models"), exist_ok=True)
joblib.dump(target_encoder, os.path.join(BASE_DIR, "models", "target_encoder.pkl"))

# =====================================================
# SPLIT & TRAIN
# =====================================================
x_train, x_test, y_train, y_test = train_test_split(x, y, test_size=0.2, random_state=0, stratify=y)

# Konfigurasi Model dengan Parameter HPO (Hasil Tuning Colab)
dt_model = DecisionTreeClassifier(max_depth=10, min_samples_split=5, criterion='gini', random_state=0)
knn_model = KNeighborsClassifier(n_neighbors=7, weights='distance', metric='minkowski')
svm_model = SVC(C=1.0, kernel='rbf', gamma='scale', probability=True, random_state=0)

last_update_path = os.path.join(BASE_DIR, "models", "last_update.txt")

def load_old_accuracy():
    acc = {}
    if os.path.exists(last_update_path):
        with open(last_update_path, "r") as f:
            for line in f:
                if "=" in line:
                    model, value = line.strip().split("=")
                    acc[model] = float(value)
    return acc

old_acc = load_old_accuracy()

def train_and_update(model, x_train, y_train, x_test, y_test, name, old_val):
    model.fit(x_train, y_train)
    acc = round(accuracy_score(y_test, model.predict(x_test)) * 100, 2)
    print(f"\n{name} - New Accuracy: {acc}% | Old Accuracy: {old_val}%")
    
    if acc >= old_val:
        joblib.dump(model, os.path.join(BASE_DIR, "models", f"Gaming_prediction_{name}.sav"))
        print(f"{name} updated successfully.")
        return acc
    else:
        print(f"{name} rejected, keeping old model.")
        return old_val

final_dt_acc = train_and_update(dt_model, x_train, y_train, x_test, y_test, "DecisionTree", old_acc.get("DecisionTree", 0))
final_knn_acc = train_and_update(knn_model, x_train, y_train, x_test, y_test, "KNN", old_acc.get("KNN", 0))
final_svm_acc = train_and_update(svm_model, x_train, y_train, x_test, y_test, "SVM", old_acc.get("SVM", 0))

with open(last_update_path, "w", encoding="utf-8") as f:
    f.write(f"DecisionTree={final_dt_acc}\nKNN={final_knn_acc}\nSVM={final_svm_acc}\n")

print("\n" + "=" * 50)
print("ALL MODELS UPDATED SUCCESSFULLY")
print("=" * 50)