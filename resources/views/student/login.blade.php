<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول الطالب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow">
                <h4 class="text-center mb-3">تسجيل دخول الطالب</h4>

                <form method="POST" action="{{ route('student.login.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>


                    <button class="btn btn-primary w-100">دخول</button>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
