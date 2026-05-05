<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Alif Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 for nice alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Ultra Premium Login */
        :root {
            --bg-main: #0f172a;
            --accent-primary: #3b82f6;
            --accent-secondary: #14b8a6;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-primary);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Premium Ambient Background Glows */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.5;
            animation: pulseGlow 15s ease-in-out infinite alternate;
        }

        body::before {
            top: -10%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: rgba(59, 130, 246, 0.15); /* Blue glow */
        }

        body::after {
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(20, 184, 166, 0.1); /* Teal glow */
            animation-delay: -5s;
        }

        @keyframes pulseGlow {
            0% { transform: scale(1) translate(0, 0); opacity: 0.4; }
            50% { transform: scale(1.2) translate(50px, 50px); opacity: 0.6; }
            100% { transform: scale(0.9) translate(-50px, -20px); opacity: 0.4; }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--glass-bg);
            backdrop-filter: blur(30px);
            border: 1px solid var(--border-color);
            border-radius: 32px;
            padding: 50px 40px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 10;
        }

        .back-btn {
            position: absolute;
            top: 25px;
            left: 25px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .back-btn:hover {
            color: var(--text-primary);
            transform: translateX(-3px);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 15px;
        }

        .header h2 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -1px;
            background: linear-gradient(to right, #fff, #a1a1aa);
            background-clip: text;
            -webkit-background-clip: text;
            background-clip: transparent;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .form-control {
            width: 100%;
            padding: 16px 20px 16px 50px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-primary);
            font-size: 1rem;
            outline: none;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--accent-primary);
            background: rgba(139, 92, 246, 0.05);
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
        }
        
        .form-control:focus + i, .form-group:focus-within i {
            color: var(--accent-primary);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(139, 92, 246, 0.5);
        }

        .toggle-form {
            text-align: center;
            margin-top: 30px;
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .toggle-form span {
            background: linear-gradient(to right, var(--accent-primary), var(--accent-secondary));
            background-clip: text;
            -webkit-background-clip: text;
            background-clip: transparent;
            -webkit-text-fill-color: transparent;
            cursor: pointer;
            font-weight: 700;
            transition: var(--transition);
        }

        .toggle-form span:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <a href="index.html" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        
        <div class="header">
            <h2 id="form-title">Welcome Back</h2>
            <p id="form-subtitle">Silakan login ke akun Anda.</p>
        </div>

       <form action="proses_login.php" method="POST">
    <div class="form-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    </div>
    <div class="form-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" class="btn-submit">Login</button>
</form>
        <div class="toggle-form">
            <p id="toggle-text">Belum punya akun? <span onclick="toggleMode()">Bikin Akun</span></p>
        </div>
    </div>

    <script>
        let isLoginMode = true;

        const formTitle = document.getElementById('form-title');
        const formSubtitle = document.getElementById('form-subtitle');
        const nameGroup = document.getElementById('name-group');
        const nameInput = document.getElementById('name');
        const submitBtn = document.getElementById('submit-btn');
        const toggleText = document.getElementById('toggle-text');
        const authForm = document.getElementById('auth-form');

        function toggleMode() {
            isLoginMode = !isLoginMode;
            if (isLoginMode) {
                // Switch to Login Mode
                formTitle.textContent = 'Welcome Back';
                formSubtitle.textContent = 'Silakan login ke akun Anda.';
                nameGroup.style.display = 'none';
                nameInput.removeAttribute('required');
                submitBtn.textContent = 'Login';
                toggleText.innerHTML = 'Belum punya akun? <span onclick="toggleMode()">Bikin Akun</span>';
            } else {
                // Switch to Register Mode
                formTitle.textContent = 'Buat Akun';
                formSubtitle.textContent = 'Daftarkan diri Anda sekarang.';
                nameGroup.style.display = 'block';
                nameInput.setAttribute('required', 'true');
                submitBtn.textContent = 'Register';
                toggleText.innerHTML = 'Sudah punya akun? <span onclick="toggleMode()">Login</span>';
            }
        }

        authForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (isLoginMode) {
                // Handle Login
                const users = JSON.parse(localStorage.getItem('users')) || [];
                const user = users.find(u => u.email === email && u.password === password);

                if (user) {
                    // Berhasil Login
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil!',
                        text: `Selamat datang kembali, ${user.name}!`,
                        background: '#1e293b',
                        color: '#f8fafc',
                        confirmButtonColor: '#3b82f6',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'index.html'; // Redirect ke portfolio
                    });
                } else {
                    // Gagal Login
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: 'Email atau password salah. Cek kembali atau bikin akun jika belum punya.',
                        background: '#1e293b',
                        color: '#f8fafc',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            } else {
                // Handle Register
                const name = document.getElementById('name').value;
                const users = JSON.parse(localStorage.getItem('users')) || [];
                
                // Cek apakah email sudah terdaftar
                const existingUser = users.find(u => u.email === email);
                if (existingUser) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Email Terdaftar',
                        text: 'Email ini sudah digunakan. Silakan langsung login.',
                        background: '#1e293b',
                        color: '#f8fafc',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                // Simpan user baru
                users.push({ name, email, password });
                localStorage.setItem('users', JSON.stringify(users));

                Swal.fire({
                    icon: 'success',
                    title: 'Akun Berhasil Dibuat!',
                    text: 'Silakan login dengan akun baru Anda.',
                    background: '#1e293b',
                    color: '#f8fafc',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    // Balik ke mode login
                    authForm.reset();
                    toggleMode();
                });
            }
        });
    </script>
</body>
</html>
