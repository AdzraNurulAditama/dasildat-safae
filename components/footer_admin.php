<style>
/* Styling Premium Footer */
.custom-footer {
    background-color: #ffffff;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: 50px 0 20px 0;
    margin-top: auto; /* Memastikan footer selalu ada di paling bawah */
    font-family: inherit;
}

.footer-brand {
    font-weight: 800;
    color: #0f172a;
    font-size: 1.25rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.5px;
}

.footer-brand i {
    color: #0ea5e9;
    font-size: 1.4rem;
}

.footer-text {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-top: 12px;
}

.footer-link {
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.footer-link:hover {
    color: #0ea5e9;
}

.footer-divider {
    height: 1px;
    background-color: rgba(0, 0, 0, 0.05);
    margin: 30px 0 20px 0;
}

/* Hover Social Icons */
.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background-color: rgba(14, 165, 233, 0.05);
    color: #0ea5e9;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(14, 165, 233, 0.1);
}

.social-icon:hover {
    background-color: #0ea5e9;
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(14, 165, 233, 0.2);
}
</style>

<footer class="custom-footer">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
                <a href="#" class="footer-brand">
                    <i class="bi bi-layers-fill"></i> MindTrack
                </a>
                <p class="footer-text mb-0 pe-md-5">
                   Gaming Health Analysis.<br>
                    Membantu gamer memprediksi antara hobi bermain game dan kesehatan mental.
                </p>
            </div>
            
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end gap-4 mb-3">
                    <a href="#" class="footer-link">Dashboard</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
                <div class="d-flex justify-content-center justify-content-md-end gap-2">
                    <a href="#" class="social-icon" title="Github"><i class="bi bi-github"></i></a>
                    <a href="#" class="social-icon" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon" title="Email"><i class="bi bi-envelope-fill"></i></a>
                </div>
            </div>

        </div>
        
        <div class="footer-divider"></div>
        
        <div class="text-center">
            <p class="footer-text mb-0" style="font-size: 0.85rem;">
                &copy; <?php echo date("Y"); ?> MindTrack Gaming. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>