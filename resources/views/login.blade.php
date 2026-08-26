<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 50%, #0e7490 100%);
            position: relative;
            overflow: hidden;
            direction: rtl;
        }

        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, .03);
            border-radius: 50%;
            top: -200px;
            left: -200px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, .03);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            padding: 48px 40px;
            width: 420px;
            max-width: 90vw;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
            position: relative;
            z-index: 1;
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo .icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: #fff;
            font-size: 32px;
        }

        .logo h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }

        .logo p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            background: #f8fafc;
            outline: none;
            transition: .2s;
        }

        .form-group input:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
            background: #fff;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #fff;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, .3);
        }

        .error-msg {
            background: #fef2f2;
            color: #dc2626;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            display: none;
            margin-bottom: 16px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo">
            <div class="icon"><i class="fas fa-heartbeat"></i></div>
            <h1>إدارة النقاط الطبية</h1>
            <p>تسجيل الدخول إلى لوحة التحكم</p>
        </div>
        <div class="error-msg" id="errorMsg"><i class="fas fa-exclamation-circle"></i> يرجى إدخال البريد الإلكتروني
            وكلمة المرور</div>
        <form onsubmit="handleLogin(event)">
            <div class="form-group">
                <label><i class="far fa-envelope"></i> البريد الإلكتروني</label>
                <input type="email" id="emailInput" placeholder="example@domain.com" dir="ltr" style="text-align:right;"
                    autofocus required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> كلمة المرور</label>
                <input type="password" id="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-arrow-left"></i> دخول
            </button>
        </form>
    </div>

    <script>
    const API_BASE_URL = 'http://127.0.0.1:8000/api';

    async function handleLogin(e) {
      e.preventDefault();
      const email = document.getElementById('emailInput').value.trim();
      const password = document.getElementById('password').value.trim();
      const err = document.getElementById('errorMsg');

      if (!email || !password) {
        err.textContent = 'يرجى إدخال البريد الإلكتروني وكلمة المرور';
        err.style.display = 'block';
        return;
      }

      try {
        const response = await fetch(`${API_BASE_URL}/login`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ email, password })
        });

        if (response.ok) {
          const data = await response.json();

          // حفظ التوكن وبيانات المستخدم في المتصفح
          localStorage.setItem('auth_token', data.token);
          localStorage.setItem('user', JSON.stringify(data.user));

          err.style.display = 'none';

          // الانتقال التلقائي للرابط المخصص لدور المستخدم القادم من الـ Backend
          window.location.href = data.redirect_url;
        } else {
          const errData = await response.json();
          err.textContent = errData.message || 'بيانات الدخول غير صحيحة';
          err.style.display = 'block';
        }
      } catch (ex) {
        console.error(ex);
        err.textContent = 'حدث خطأ في الاتصال بالسيرفر';
        err.style.display = 'block';
      }
    }
  </script>
</body>

</html>
