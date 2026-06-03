import pandas as pd
import joblib
import os

from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.neighbors import KNeighborsClassifier
from sklearn.svm import SVC
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import accuracy_score

print("=" * 50)
print("STARTING MODEL RETRAINING")
print("=" * 50)

# =====================================================
# LOAD DATASET
# =====================================================

BASE_DIR = os.path.dirname(
    os.path.abspath(__file__)
)

dataset_path = os.path.join(
    BASE_DIR,
    "dataset",
    "Gaming and Mental Health.csv"
)
if not os.path.exists(dataset_path):
    raise Exception(
        f"Dataset tidak ditemukan: {dataset_path}"
    )

import pymysql

# Dataset asli
df = pd.read_csv(
    dataset_path,
    encoding='latin1'
)

print(f"Dataset Loaded : {df.shape}")
print(df.columns.tolist())
# =========================
# LOAD HISTORY FROM MYSQL
# =========================

from sqlalchemy import create_engine

engine = create_engine(
    "mysql+pymysql://root:190606@localhost/gaming_ml"
)

history_df = pd.read_sql("""
SELECT
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
prediction_result
FROM history
""", engine)

history_df['prediction_result'] = (
    history_df['prediction_result']
    .replace({
        'Low Risk': 'Low',
        'Moderate Risk': 'Moderate',
        'High Risk': 'High'
    })
)
# Samakan nama target
history_df = history_df.rename(
    columns={
        "prediction_result":
        "gaming_addiction_risk_level"
    }
)

print(f"History Loaded : {history_df.shape}")

# Gabungkan dataset + history
combined_df = pd.concat(
    [df, history_df],
    ignore_index=True
)

print(f"Combined Dataset : {combined_df.shape}")

# =====================================================
# CLEAN COLUMN NAME
# =====================================================

combined_df.columns = [
    str(col).strip()
    for col in combined_df.columns
]

# =====================================================
# FEATURE & TARGET
# =====================================================

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

x = combined_df[feature_columns]

print(x.columns.tolist())

y = combined_df['gaming_addiction_risk_level']


# =====================================================
# HANDLE NULL
# =====================================================

x = x.fillna(0)

# =====================================================
# LABEL ENCODING
# =====================================================
categorical_columns = [

    'gender',
    'game_genre',
    'primary_game',
    'gaming_platform',
    'sleep_quality',
    'sleep_disruption_frequency',
    'academic_work_performance',
    'mood_state',
    'mood_swing_frequency'

]

for col in categorical_columns:

    if col in x.columns:

        encoder = LabelEncoder()

        x[col] = encoder.fit_transform(
            x[col].astype(str)
        )

boolean_columns = [

    'continued_despite_problems',

    'eye_strain',

    'back_neck_pain',

    'loss_of_other_interests'

]

for col in boolean_columns:

    if col in x.columns:

        x[col] = x[col].astype(str)

        x[col] = x[col].map({
            'True': 1,
            'False': 0
        })

        x[col] = x[col].fillna(0)
# =====================================================
# ENCODE TARGET
# =====================================================

target_encoder = LabelEncoder()

y = target_encoder.fit_transform(y)

# =====================================================
# SPLIT DATA
# =====================================================
x_train, x_test, y_train, y_test = train_test_split(

    x,

    y,

    test_size=0.2,

    random_state=0

)

print("Training Shape :", x_train.shape)
print("Testing Shape  :", x_test.shape)

# =====================================================
# TRAIN DECISION TREE
# =====================================================

dt_model = DecisionTreeClassifier()

dt_model.fit(
    x_train,
    y_train
)

dt_pred = dt_model.predict(x_test)

dt_accuracy = accuracy_score(
    y_test,
    dt_pred
)

dt_path = os.path.join(
    BASE_DIR,
    "models",
    "Gaming_prediction_DT.sav"
)

joblib.dump(
    dt_model,
    dt_path
)

print(
    f"Decision Tree Accuracy : {round(dt_accuracy*100,2)}%"
)

# =====================================================
# TRAIN KNN
# =====================================================

knn_model = KNeighborsClassifier(
    n_neighbors=5
)

knn_model.fit(
    x_train,
    y_train
)

knn_pred = knn_model.predict(x_test)

knn_accuracy = accuracy_score(
    y_test,
    knn_pred
)

knn_path = os.path.join(
    BASE_DIR,
    "models",
    "Gaming_prediction_KNN.sav"
)

joblib.dump(
    knn_model,
    knn_path
)

print(
    f"KNN Accuracy : {round(knn_accuracy*100,2)}%"
)

# =====================================================
# TRAIN SVM
# =====================================================

svm_model = SVC(
    kernel='rbf',
    probability=True
)

svm_model.fit(
    x_train,
    y_train
)

svm_pred = svm_model.predict(x_test)

svm_accuracy = accuracy_score(
    y_test,
    svm_pred
)

svm_path = os.path.join(
    BASE_DIR,
    "models",
    "Gaming_prediction_SVM.sav"
)

joblib.dump(
    svm_model,
    svm_path
)

print(
    f"SVM Accuracy : {round(svm_accuracy*100,2)}%"
)

# =====================================================
# SAVE INFO
# =====================================================

last_update_path = os.path.join(
    BASE_DIR,
    "models",
    "last_update.txt"
)

with open(
    last_update_path,
    "w",
    encoding="utf-8"
) as f:

    f.write(
        f"DecisionTree={round(dt_accuracy*100,2)}\n"
        f"KNN={round(knn_accuracy*100,2)}\n"
        f"SVM={round(svm_accuracy*100,2)}"
    )

print("=" * 50)
print("ALL MODELS UPDATED SUCCESSFULLY")
print("=" * 50)

