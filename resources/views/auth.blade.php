<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','ExploreNusa')</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* 1. Variabel Warna Didefinisikan di Sini */
        :root {
            --primary-blue: #5050F4;
            --dark-bg-primary: #111827;
            --dark-bg-secondary: #1F2937;
            --text-light-primary: #E5E7EB;
            --text-light-secondary: #A8B2D1;
            --border-color: #374151;
            --border-radius: 12px;
        }

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            list-style: none;
        }

        body{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-bg-primary), var(--dark-bg-secondary));
        }

        .container{
            position: relative;
            width: 850px;
            height: 550px;
            background: var(--dark-bg-secondary);
            margin: 20px;
            border-radius: 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, .2);
            overflow: hidden;
        }

        .container h1{
            font-size: 36px;
            margin: -10px 0;
        }

        .container p{
            font-size: 14.5px;
            margin: 15px 0;
        }

        form{ width: 100%; }

        .form-box{
            position: absolute;
            right: 0;
            width: 50%;
            height: 100%;
            background: var(--dark-bg-secondary);
            display: flex;
            align-items: center;
            color: var(--text-light-primary);
            text-align: center;
            padding: 40px;
            z-index: 1;
            transition: .6s ease-in-out 1.2s, visibility 0s 1s;
        }

        .container.active .form-box{ right: 50%; }

        .form-box.register{ visibility: hidden; }
        .container.active .form-box.register{ visibility: visible; }

        .input-box{
            position: relative;
            margin: 30px 0;
        }

        .input-box input{
            width: 100%;
            padding: 13px 50px 13px 20px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            outline: none;
            font-size: 16px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .input-box input::placeholder{
            color: var(--text-light-secondary);
            font-weight: 400;
        }

        .input-box i{
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .icon {
            color: var(--primary-blue);
        }

        .forgot-link{ margin: -15px 0 15px; }
        .forgot-link a{
            font-size: 14.5px;
            color: var(--text-dark);
        }

        .btn{
            width: 100%;
            height: 48px;
            background: var(--primary-blue);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: var(--text-light-primary);
            font-weight: 600;
        }

        .toggle-box{
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .toggle-box::before{
            content: '';
            position: absolute;
            left: -250%;
            width: 300%;
            height: 100%;
            background:  var(--primary-blue);
            border-radius: 150px;
            z-index: 2;
            transition: 1.8s ease-in-out;
        }

        .container.active .toggle-box::before{ left: 50%; }

        .toggle-panel{
            position: absolute;
            width: 50%;
            height: 100%;
            color: var(--text-light-primary);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 2;
            transition: .6s ease-in-out;
        }

        .toggle-panel.toggle-left{
            left: 0;
            transition-delay: 1.2s;
        }
        .container.active .toggle-panel.toggle-left{
            left: -50%;
            transition-delay: .6s;
        }

        .toggle-panel.toggle-right{
            right: -50%;
            transition-delay: .6s;
        }
        .container.active .toggle-panel.toggle-right{
            right: 0;
            transition-delay: 1.2s;
        }

        .toggle-panel p{ margin-bottom: 20px; }

        .toggle-panel .btn{
            width: 160px;
            height: 46px;
            background: transparent;
            border: 2px solid var(--text-light-primary);
            box-shadow: none;
        }

        /* 2. Style untuk pesan error */
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 10px;
            text-align: left;
        }

        @media screen and (max-width: 650px){
            /* ... (Media queries tidak diubah) ... */
        }
        @media screen and (max-width: 400px){
            /* ... (Media queries tidak diubah) ... */
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- FORM LOGIN --}}
        <div class="form-box login">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <h1>Sign In</h1>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope icon'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt icon'></i>
                </div>
                <button type="submit" class="btn">Sign In</button>

                {{-- Error login menggunakan class --}}
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- FORM REGISTER --}}
        <div class="form-box register">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <h1>Sign Up</h1>
                <div class="input-box">
                    <input type="text" name="name" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn">Sign Up</button>

                {{-- Error register menggunakan class --}}
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="error-message">{{ $error }}</p>
                    @endforeach
                @endif
            </form>
        </div>

        {{-- TOGGLE PANEL --}}
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn" type="button">Sign Up</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn" type="button">Sign In</button>
            </div>
        </div>
    </div>

    <script>
        const container = document.querySelector('.container');
        const registerBtn = document.querySelector('.register-btn');
        const loginBtn = document.querySelector('.login-btn');

        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });
    </script>
</body>
</html>
