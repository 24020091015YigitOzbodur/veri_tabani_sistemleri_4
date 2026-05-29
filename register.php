<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kaydet'])) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $adres = $_POST['adres'];

    $sorgu = $db->prepare("INSERT INTO kullanicilar (ad, soyad, email, adres) VALUES (?, ?, ?, ?)");
    $sorgu->execute([$ad, $soyad, $email, $adres]);
    
    header("Location: register.php"); 
    exit;
}

$kullanicilar = $db->query("SELECT * FROM kullanicilar ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Formu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="py-5 text-center">
        <h2>📝 Kullanıcı Kayıt Formu</h2>
        <p class="lead">Lütfen sisteme kayıt olmak için aşağıdaki bilgileri eksiksiz doldurun.</p>
    </div>

    <div class="row g-5">
        <div class="col-md-5 col-lg-4 order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Kayıtlı Kişiler</span>
                <span class="badge bg-primary rounded-pill"><?= count($kullanicilar) ?></span>
            </h4>
            <ul class="list-group mb-3 shadow-sm">
                <?php if(count($kullanicilar) > 0): ?>
                    <?php foreach($kullanicilar as $kisi): ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0"><?= htmlspecialchars($kisi['ad'] . ' ' . $kisi['soyad']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($kisi['email']) ?></small>
                        </div>
                        <span class="text-muted" style="font-size: 0.8em;"><?= date('d.m.Y', strtotime($kisi['kayit_tarihi'])) ?></span>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <small class="text-muted">Henüz kayıtlı kullanıcı yok.</small>
                    </li>
                <?php endif; ?>
            </ul>
            
            <a href="index.php" class="btn btn-outline-secondary w-100">📸 Albüm Sayfasına Git</a>
        </div>

        <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Kişisel Bilgiler</h4>
            <form method="POST" class="needs-validation shadow-sm p-4 bg-white rounded">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Ad</label>
                        <input type="text" name="ad" class="form-control" required>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label">Soyad</label>
                        <input type="text" name="soyad" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">E-Posta <span class="text-muted">(Zorunlu)</span></label>
                        <input type="email" name="email" class="form-control" placeholder="sen@ornek.com" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Adres</label>
                        <input type="text" name="adres" class="form-control" placeholder="1234. Sokak, No:5, Mahalle..." required>
                    </div>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit" name="kaydet">Sisteme Kayıt Ol</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>