<?php
require_once 'db.php';

if (isset($_GET['sil'])) {
    $sorgu = $db->prepare("DELETE FROM album WHERE id = ?");
    $sorgu->execute([$_GET['sil']]);
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = $_POST['resim_url'];
    $aciklama = $_POST['aciklama'];

    if (isset($_POST['guncelle'])) {
        $id = $_POST['id'];
        $sorgu = $db->prepare("UPDATE album SET resim_url = ?, aciklama = ? WHERE id = ?");
        $sorgu->execute([$url, $aciklama, $id]);
    } elseif (isset($_POST['ekle'])) {
        $sorgu = $db->prepare("INSERT INTO album (resim_url, aciklama) VALUES (?, ?)");
        $sorgu->execute([$url, $aciklama]);
    }
    header("Location: index.php"); 
    exit;
}

$duzenlenecek_kayit = null;
if (isset($_GET['duzenle'])) {
    $sorgu = $db->prepare("SELECT * FROM album WHERE id = ?");
    $sorgu->execute([$_GET['duzenle']]);
    $duzenlenecek_kayit = $sorgu->fetch(PDO::FETCH_ASSOC);
}

$resimler = $db->query("SELECT * FROM album ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albüm Projesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">📸 Albüm Yönetim Sistemi</h1>

        <div class="card mb-5 shadow-sm">
            <div class="card-header <?= $duzenlenecek_kayit ? 'bg-warning text-dark' : 'bg-primary text-white' ?>">
                <?= $duzenlenecek_kayit ? 'Resmi Düzenle' : 'Yeni Resim Ekle' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php">
                    <?php if($duzenlenecek_kayit): ?>
                        <input type="hidden" name="id" value="<?= $duzenlenecek_kayit['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Resim Linki (URL)</label>
                        <input type="text" name="resim_url" class="form-control" required 
                               value="<?= $duzenlenecek_kayit ? htmlspecialchars($duzenlenecek_kayit['resim_url']) : '' ?>" 
                               placeholder="Örn: https://ornek.com/resim.jpg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="aciklama" class="form-control" rows="2" required placeholder="Bu resim hakkında bir şeyler yaz..."><?= $duzenlenecek_kayit ? htmlspecialchars($duzenlenecek_kayit['aciklama']) : '' ?></textarea>
                    </div>
                    
                    <?php if($duzenlenecek_kayit): ?>
                        <button type="submit" name="guncelle" class="btn btn-warning w-100">Değişiklikleri Kaydet</button>
                        <a href="index.php" class="btn btn-secondary w-100 mt-2">İptal Et</a>
                    <?php else: ?>
                        <button type="submit" name="ekle" class="btn btn-success w-100">Albüme Ekle</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($resimler as $resim): ?>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <img src="<?= htmlspecialchars($resim['resim_url']) ?>" class="card-img-top" alt="Albüm Resmi" style="height: 225px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <p class="card-text"><?= htmlspecialchars($resim['aciklama']) ?></p>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="?duzenle=<?= $resim['id'] ?>" class="btn btn-sm btn-outline-secondary">Düzenle</a>
                                <a href="?sil=<?= $resim['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu resmi silmek istediğine emin misin?');">Sil</a>
                            </div>
                            <small class="text-muted"><?= date('d.m.Y H:i', strtotime($resim['eklenme_tarihi'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>