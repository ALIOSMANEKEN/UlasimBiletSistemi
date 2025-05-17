<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            margin: 50px auto;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
    </style>
</head>
<body>

<div class="container register-container">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Kayıt Ol</h3>

            <?php if (!empty($hata_mesaji)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($hata_mesaji) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-3">
                    <label for="ad" class="form-label">Kullanıcı Adı</label>
                    <input type="text" id="ad" name="ad" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-Posta</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="sifre" class="form-label">Şifre</label>
                    <input type="password" id="sifre" name="sifre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="sifre_tekrar" class="form-label">Şifre Tekrar</label>
                    <input type="password" id="sifre_tekrar" name="sifre_tekrar" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
            </form>

            <div class="text-center mt-3">
                <p>Zaten hesabınız var mı? <a href="index.php">Giriş Yapın</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
