<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management API</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow-x: hidden;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            padding: 3rem 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            border-radius: 30px;
            font-weight: 600;
            padding: 10px 24px;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background-color: #f8f9fa;
            color: #007bff;
            transform: translateY(-3px);
        }

        .btn-outline-light:hover {
            background-color: #fff;
            color: #007bff;
            transform: translateY(-3px);
        }

        footer {
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        h1 {
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="card-glass mx-auto col-11 col-md-8 col-lg-6">
            <h1 class="mb-3 display-5">Task Management API</h1>
            <p class="lead mb-4">
                A modern Laravel powered API to help you manage tasks, boost productivity, and streamline your workflow.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="https://laravel.com/docs/12.x" target="_blank" class="btn btn-light btn-custom">
                    📘 Laravel Docs
                </a>
                <a href="https://documenter.getpostman.com/view/17493797/2sB3WtsJYj" target="_blank" class="btn btn-outline-light btn-custom">
                    🔗 API Documentation
                </a>
            </div>

            <footer class="mt-5">
                <p>
                    Using <strong>Laravel 12 + Sanctum</strong><br>
                    &copy; {{ date('Y') }} All Rights Reserved.
                </p>
            </footer>
        </div>
    </div>
</body>
</html>
