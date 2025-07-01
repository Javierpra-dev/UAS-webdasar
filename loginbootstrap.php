<!DOCTYPE html>
<html lang="id" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <meta name="color-scheme" content="light dark">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lottie-web@5.12.2/build/player/lottie.min.js"></script>
    <style>
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            opacity: 0;
            transition: opacity 0.6s ease-in-out, background-color 0.3s, color 0.3s;
        }

        body.loaded {
            opacity: 1;
        }

        .form-login {
            max-width: 340px;
            width: 100%;
            padding: 2rem;
            border-radius: 12px;
            background-color: var(--bs-body-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .logo {
            height: 80px;
            margin-bottom: 1rem;
        }

        .alert-box {
            display: none;
        }

        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        body.theme-transition {
            transition: background-color 0.6s ease, color 0.6s ease;
            transform: scale(0.98);
            filter: blur(2px);
            opacity: 0.5;
        }

        body.theme-final {
            transform: scale(1);
            filter: blur(0);
            opacity: 1;
            transition: all 0.5s ease-in-out;
        }
    </style>
</head>

<body>
    <div id="lottie-theme-switch" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: 500px; height: 500px; z-index: 2000; display: none; pointer-events: none;"></div>

    <main class="form-login text-center">
        <img class="mb-4 mx-auto d-block" src="https://cdn-0.motorcycle-logos.com/wp-content/uploads/2016/10/Ducati-Logo-Description.png" alt="Logo Ducati" width="100" height="100">
        <div class="form-title">Please sign in</div>

        <!-- Alert gagal login -->
        <div id="loginAlert" class="alert alert-danger alert-box py-2">
            Login gagal
        </div>

        <form id="loginForm">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-check text-start mb-3">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Ingat saya</label>
            </div>
            <button class="btn btn-primary w-100" type="submit">Masuk</button>
            <p class="mt-4 mb-0 text-muted small">&copy; 2017–2025 Artha Cerdas SMG</p>
        </form>
    </main>

    <!-- Mode Switcher -->
    <div class="theme-toggle">
        <select id="themeSelect" class="form-select form-select-sm" onchange="changeTheme(this.value)">
            <option value="auto" selected> Otomatis</option>
            <option value="light"> Terang</option>
            <option value="dark"> Gelap</option>
        </select>
    </div>

    <script>
        // Saat halaman selesai dimuat
        window.addEventListener("DOMContentLoaded", () => {
            document.body.classList.add("loaded");
        });

        // Fungsi ubah tema dengan animasi Lottie
        function changeTheme(value) {
            const html = document.documentElement;
            const body = document.body;
            const container = document.getElementById("lottie-theme-switch");

            const lottieUrl = value === 'dark' ? 'anim.json' : 'lottie_loading.json';

            container.style.display = 'block';

            const anim = lottie.loadAnimation({
                container: container,
                renderer: 'svg',
                loop: false,
                autoplay: true,
                path: lottieUrl
            });

            body.classList.add("theme-transition");

            setTimeout(() => {
                html.setAttribute('data-bs-theme', value);
                localStorage.setItem('theme-mode', value);
            }, 100);

            setTimeout(() => {
                body.classList.remove("theme-transition");
                body.classList.add("theme-final");
                setTimeout(() => {
                    body.classList.remove("theme-final");
                }, 600);
            }, 400);

            setTimeout(() => {
                lottie.destroy();
                container.style.display = 'none';
            }, 2000);
        }

        // Terapkan tema tersimpan
        window.addEventListener("load", () => {
            const saved = localStorage.getItem('theme-mode');
            if (saved) {
                document.documentElement.setAttribute('data-bs-theme', saved);
                document.getElementById('themeSelect').value = saved;
            }
        });

        // Validasi login
        // Validasi login
        document.getElementById("loginForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();
            const alertBox = document.getElementById("loginAlert");

            // Ambil user dari localStorage (kalau belum ada, pakai default usm/123)
            const savedUser = JSON.parse(localStorage.getItem("userData")) || {
                username: "usm",
                password: "123"
            };

            if (username === savedUser.username && password === savedUser.password) {
                sessionStorage.setItem("loggedIn", "true");
                window.location.href = "dashboard.bootstrap.php";
            } else {
                alertBox.style.display = "block";
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>