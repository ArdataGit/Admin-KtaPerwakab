<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
    <p>
        <a href="{{ $resetLink }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
    </p>
    <p>Link ini akan kadaluarsa dalam 60 menit dan hanya bisa digunakan 1 kali.</p>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
</body>
</html>
