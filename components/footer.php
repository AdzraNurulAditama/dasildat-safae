<footer class="footer-modern">
    <div class="footer-glow footer-glow-1"></div>
    <div class="footer-glow footer-glow-2"></div>

    <div class="container">
        <div class="footer-top">
            
            <div class="footer-brand">
                <div class="footer-logo" style="font-family: 'Space Mono', monospace !important; font-weight: 700 !important; letter-spacing: .14em !important; background: linear-gradient(135deg, #0284c7, #9333ea, #db2777) !important; -webkit-background-clip: text !important; -webkit-text-fill-color: transparent !important;">
                    MindTrack Gaming
                </div>
                <p>
                    Platform analitik cerdas berbasis Machine Learning untuk memetakan hubungan antara durasi bermain game, kualitas tidur, dan status emosional secara real-time.
                </p>
                <div class="footer-status">
                    <span class="status-dot"></span>
                    ⚡ NODE ENGINE ACTIVE
                </div>
            </div>

            <div class="footer-links-wrapper">
                <div class="footer-links">
                    <h3>Navigation</h3>
                    <a href="index.php">🛸 Overview</a>
                    <a href="models.php">🔮 ML Models</a>
                    <a href="predict_manual.php">🧠 Manual Prediction</a>
                    <a href="predict_csv.php">📁 Batch File</a>
                </div>

                <div class="footer-links">
                    <h3>Core Metrics</h3>
                    <a href="history.php">📜 History</a>
                    <a href="models.php">💎 Decision Tree</a>
                    <a href="models.php">🧬 Support Vector</a>
                    <a href="models.php">⛓️ K-Nearest Neighbor</a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>
                © 2026 MindTrack Gaming Intelligence — Gaming Mental Health Prediction System. All matrix logs encrypted.
            </p>
            
            <div class="footer-badge" style="font-family: 'Space Mono', monospace !important; letter-spacing: .08em !important; background: linear-gradient(135deg, rgba(147, 51, 234, 0.08), rgba(219, 39, 119, 0.08)) !important; border: 1px solid rgba(219, 39, 119, 0.2) !important;">
                🔐 SECURE SSL LAYER v3.8
            </div>
        </div>
    </div>
</footer>

<style>
.footer-modern {
    position: relative;
    overflow: hidden;
    padding: 100px 0 40px;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%) !important; /* Slate 50 to Slate 100 */
    border-top: 1px solid rgba(226, 232, 240, 0.8) !important; /* Slate 200 */
    backdrop-filter: blur(24px);
}

/* BLURRY BACKGROUND GLOWS - Adjusted for Light Mode */
.footer-glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(130px);
    opacity: .15; /* Sedikit dinaikkan agar warna pastelnya terlihat di latar terang */
    pointer-events: none;
}

.footer-glow-1 {
    width: 350px;
    height: 350px;
    background: #db2777; /* Pink 600 */
    top: -120px;
    left: -100px;
}

.footer-glow-2 {
    width: 350px;
    height: 350px;
    background: #0284c7; /* Sky 600 */
    bottom: -140px;
    right: -100px;
}

/* LAYOUT CONTAINER COMPLIANCE */
.footer-top {
    display: flex;
    justify-content: space-between;
    gap: 80px;
    padding-bottom: 50px;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important; /* Slate 200 */
    position: relative;
    z-index: 2;
}

.footer-brand {
    max-width: 460px;
}

.footer-logo {
    display: inline-block;
    font-size: 28px;
    margin-bottom: 20px;
    position: relative;
}

.footer-logo::after {
    content: '';
    position: absolute;
    right: -16px;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #db2777;
    box-shadow: 0 0 8px rgba(219, 39, 119, 0.6);
}

.footer-brand p {
    color: #475569 !important; /* Slate 600 */
    line-height: 1.8;
    margin-bottom: 24px;
    font-size: 14px;
}

/* CYBER LIVE STATUS CHIP */
.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(2, 132, 199, 0.05) !important;
    border: 1px solid rgba(2, 132, 199, 0.2) !important;
    font-size: 10px;
    font-family: 'Space Mono', monospace;
    letter-spacing: .06em;
    color: #0284c7; /* Sky 600 */
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #0ea5e9;
    box-shadow: 0 0 8px rgba(14, 165, 233, 0.6);
}

/* LINK COLUMN INTERACTION */
.footer-links-wrapper {
    display: flex;
    gap: 80px;
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.footer-links h3 {
    font-size: 14px;
    margin-bottom: 16px;
    color: #0f172a !important; /* Slate 900 */
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.footer-links a {
    color: #64748b !important; /* Slate 500 */
    text-decoration: none !important;
    font-size: 13px;
    transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    width: fit-content;
}

.footer-links a:hover {
    color: #db2777 !important; /* Pink 600 */
    transform: translateX(4px);
}

/* FOOTER BASELINE */
.footer-bottom {
    padding-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 2;
}

.footer-bottom p {
    color: #64748b !important; /* Slate 500 */
    margin: 0 !important;
    font-size: 13px;
}

.footer-badge {
    padding: 8px 16px;
    border-radius: 999px;
    color: #db2777; /* Pink 600 */
    font-size: 10px;
    font-weight: 700;
    backdrop-filter: blur(12px);
}

/* MEDIA MEDIA COMPACT VIEW QUERY */
@media(max-width: 900px){
    .footer-top {
        flex-direction: column;
        gap: 40px;
    }
    .footer-links-wrapper {
        gap: 50px;
    }
    .footer-bottom {
        flex-direction: column-reverse;
        text-align: center;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>