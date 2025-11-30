<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret - Cashier Application</title>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
    <style>
        .main-header {
            background: linear-gradient(135deg, #fef9f3 0%, #fff5f8 100%);
            padding: 20px 0;
            box-shadow: 0 2px 15px rgba(209, 107, 165, 0.1);
            margin-bottom: 30px;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-title h1 {
            color: #d16ba5;
            font-size: 32px;
            font-weight: 600;
            margin: 0;
            background: linear-gradient(135deg, #d16ba5 0%, #c774b2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .main-nav {
            background: linear-gradient(135deg, #d16ba5 0%, #c774b2 100%);
            padding: 0;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(209, 107, 165, 0.3);
        }

        .main-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 0;
            justify-content: center;
            flex-wrap: wrap;
        }

        .main-nav ul li {
            display: inline;
        }

        .main-nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 15px 25px;
            display: block;
            border-radius: 15px;
            transition: all 0.3s ease;
            position: relative;
        }

        .main-nav ul li a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .main-nav ul li a.active {
            background: rgba(255, 255, 255, 0.25);
            font-weight: 600;
        }

        .main-nav ul li a::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }

        .main-nav ul li a:hover::after {
            width: 60%;
        }

        @media (max-width: 768px) {
            .header-title h1 {
                font-size: 24px;
            }

            .main-nav {
                border-radius: 10px;
            }

            .main-nav ul {
                flex-direction: column;
                gap: 0;
            }

            .main-nav ul li a {
                padding: 12px 20px;
                text-align: center;
                border-radius: 0;
            }

            .main-nav ul li:first-child a {
                border-radius: 10px 10px 0 0;
            }

            .main-nav ul li:last-child a {
                border-radius: 0 0 10px 10px;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="header-title">
                <h1>🏪 FemStore</h1>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/indomaret/pages/dashboard.php">📊 Dashboard</a></li>
                    <li><a href="/indomaret/pages/cashiers/list.php">👤 Cashiers</a></li>
                    <li><a href="/indomaret/pages/products/list.php">📦 Products</a></li>
                    <li><a href="/indomaret/pages/transactions/list.php">💳 Transactions</a></li>
                </ul>
            </nav>
        </div>
    </header>