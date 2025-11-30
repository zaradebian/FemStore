<footer class="main-footer">
        <div class="footer-content">
            <div class="footer-info">
                <p class="footer-text">
                    <strong>FemStore Application</strong> &copy; 2025
                </p>
                <p class="footer-subtext">
                    Modern Cashier System
                </p>
            </div>
            <div class="footer-links">
                <a href="#" class="footer-link">Privacy</a>
                <span class="footer-divider">•</span>
                <a href="#" class="footer-link">Terms</a>
                <span class="footer-divider">•</span>
                <a href="#" class="footer-link">Support</a>
            </div>
        </div>
    </footer>

    <style>
        .main-footer {
            margin-top: 60px;
            padding: 30px 20px;
            background: linear-gradient(135deg, #fef9f3 0%, #fff5f8 100%);
            border-top: 3px solid #fce4ec;
            box-shadow: 0 -2px 15px rgba(209, 107, 165, 0.1);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-info {
            text-align: left;
        }

        .footer-text {
            color: #d16ba5;
            font-size: 16px;
            margin: 0 0 5px 0;
            font-weight: 500;
        }

        .footer-text strong {
            font-weight: 600;
            background: linear-gradient(135deg, #d16ba5 0%, #c774b2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-subtext {
            color: #999;
            font-size: 13px;
            margin: 0;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-link {
            color: #d16ba5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .footer-link:hover {
            background: #fce4ec;
            transform: translateY(-2px);
        }

        .footer-divider {
            color: #fce4ec;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-info {
                text-align: center;
            }

            .footer-links {
                justify-content: center;
            }
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            flex: 1;
        }
    </style>
</body>
</html>