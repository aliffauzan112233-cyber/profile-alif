<?php
session_start();

// 🔒 proteksi
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/helper/conn.php';

// Folder tempat menyimpan gambar
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Fungsi bantu upload
function handleUpload($file) {
    if ($file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = uniqid('img_') . '.' . $ext;
        $destination = __DIR__ . '/uploads/' . $newName;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'uploads/' . $newName;
        }
    }
    return null;
}

// ================= GET DATA =================
$portofolioItems = [];
$stmt = $pdo->prepare("SELECT * FROM portfolio_items ORDER BY created_at DESC");
$stmt->execute();
$portofolioItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ================= TAMBAH =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && !isset($_POST['id']) && !isset($_POST['delete_index'])) {
    $imagePath = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $imagePath = handleUpload($_FILES['image_file']);
    }

    $stmt = $pdo->prepare("INSERT INTO portfolio_items (title, description, image_url) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['description'], $imagePath]);
    header("Location: admin.php"); exit();
}

// ================= EDIT =================
if (isset($_GET['portofolio_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM portfolio_items WHERE id=?");
    $stmt->execute([$_GET['portofolio_id']]);
    $editItem = $stmt->fetch();
}

// ================= UPDATE =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $imagePath = $_POST['old_image'];
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $newUpload = handleUpload($_FILES['image_file']);
        if ($newUpload) $imagePath = $newUpload;
    }

    $stmt = $pdo->prepare("UPDATE portfolio_items SET title=?, description=?, image_url=? WHERE id=?");
    $stmt->execute([$_POST['title'], $_POST['description'], $imagePath, $_POST['id']]);
    header("Location: admin.php"); exit();
}

// ================= DELETE =================
if (isset($_POST['delete_index'])) {
    $stmt = $pdo->prepare("DELETE FROM portfolio_items WHERE id=?");
    $stmt->execute([$_POST['delete_index']]);
    header("Location: admin.php"); exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #050810;
            --surface: #0f172a;
            --surface-accent: #1e293b;
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.5);
            --text-main: #f1f5f9;
            --text-dim: #94a3b8;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --glass: rgba(15, 23, 42, 0.8);
        }

        * { box-sizing: border-box; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 40px 20px;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(168, 85, 247, 0.05) 0%, transparent 40%);
        }

        .container { max-width: 1100px; margin: 0 auto; }

        /* Header Centered */
        header { text-align: center; margin-bottom: 60px; }
        header h1 { 
            font-size: 2.8rem; 
            font-weight: 800; 
            letter-spacing: -1px;
            margin: 0;
            background: linear-gradient(to bottom, #fff 30%, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        header p { color: var(--text-dim); font-size: 1.1rem; margin-top: 10px; }

        /* Layout Grid */
        .layout-grid { 
            display: grid; 
            grid-template-columns: 1fr; 
            gap: 40px; 
        }

        /* Card System */
        .card { 
            background: var(--glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--primary);
        }

        /* Form Controls */
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            font-size: 0.85rem; 
            font-weight: 600; 
            color: var(--text-dim); 
            margin-bottom: 8px;
            margin-left: 4px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 14px 18px;
            background: var(--surface-accent);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 14px;
            color: white;
            font-size: 0.95rem;
            font-family: inherit;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            background: #1e293b;
        }

        /* Upload Area */
        .upload-zone {
            border: 2px dashed rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            background: rgba(255,255,255,0.02);
        }
        .upload-zone:hover { 
            border-color: var(--primary); 
            background: rgba(59, 130, 246, 0.05); 
        }
        input[type="file"] { display: none; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
            gap: 8px;
        }
        .btn-block { width: 100%; }
        .btn-primary { background: var(--primary); color: white; box-shadow: 0 10px 20px var(--primary-glow); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 25px var(--primary-glow); }
        
        /* Table Styling */
        .table-container { overflow-x: auto; margin-top: 10px; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        th { 
            padding: 0 20px 10px; 
            text-align: left; 
            font-size: 0.8rem; 
            color: var(--text-dim); 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        td { 
            background: rgba(255,255,255,0.03); 
            padding: 20px;
            vertical-align: middle;
        }
        td:first-child { border-radius: 16px 0 0 16px; }
        td:last-child { border-radius: 0 16px 16px 0; }

        .item-preview {
            width: 80px;
            height: 55px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .actions { display: flex; gap: 10px; }
        .btn-icon {
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            text-decoration: none;
            font-size: 1rem;
        }
        .btn-edit { background: rgba(245, 158, 11, 0.15); color: var(--warning); }
        .btn-delete { background: rgba(239, 68, 68, 0.15); color: var(--danger); border: none; cursor:pointer;}
        
        .btn-edit:hover { background: var(--warning); color: white; }
        .btn-delete:hover { background: var(--danger); color: white; }

        /* Status & Preview */
        .preview-box {
            margin-top: 15px;
            display: none;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--primary);
        }
        .preview-box img { width: 100%; max-height: 200px; object-fit: cover; }

        @media (min-width: 992px) {
            .layout-grid { grid-template-columns: 380px 1fr; }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Admin Dashboard</h1>
        <p>Kelola data dan konten portfolio Anda dengan mudah.</p>
    </header>

    <div class="layout-grid">
        <!-- FORM COLUMN -->
        <div class="card">
            <div class="card-title">
                <?= isset($editItem) ? '<span>🔄</span> Edit Data' : '<span></span> Data Baru' ?>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <?php if (isset($editItem)): ?>
                    <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
                    <input type="hidden" name="old_image" value="<?= $editItem['image_url'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Judul Proyek</label>
                    <input type="text" name="title" placeholder="" value="<?= $editItem['title'] ?? '' ?>" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="5" placeholder="Gambarkan proyek ini..." required><?= $editItem['description'] ?? '' ?></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar Portofolio</label>
                    <label for="image_file" class="upload-zone">
                        <div id="file-label" style="font-size: 0.9rem; color: var(--text-dim);">
                            📂 Klik untuk telusuri file
                        </div>
                        <input type="file" name="image_file" id="image_file" accept="image/*" onchange="previewImage(this)">
                    </label>
                    <div id="preview" class="preview-box">
                        <img id="img-src" src="#">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <?= isset($editItem) ? 'Simpan Perubahan' : 'Tambah Data' ?>
                </button>

                <?php if (isset($editItem)): ?>
                    <a href="admin.php" style="display:block; text-align:center; margin-top:20px; color:var(--text-dim); text-decoration:none; font-size:0.85rem;">Batal dan Kembali</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- LIST COLUMN -->
        <div class="card">
            <div class="card-title"><span></span> Koleksi Portofolio</div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Media</th>
                            <th>Info Proyek</th>
                            <th style="text-align: right;">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($portofolioItems)): ?>
                            <tr>
                                <td colspan="3" style="text-align:center; padding: 60px 0; color: var(--text-dim);">
                                    Belum ada data tersedia.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($portofolioItems as $item): ?>
                                <tr>
                                    <td>
                                        <?php if ($item['image_url']): ?>
                                            <img src="<?= $item['image_url'] ?>" class="item-preview">
                                        <?php else: ?>
                                            <div class="item-preview" style="background:#1e293b; display:flex; align-items:center; justify-content:center; font-size:0.6rem; color:#475569;">NO IMG</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: white; margin-bottom: 4px;"><?= htmlspecialchars($item['title']) ?></div>
                                        <div style="font-size: 0.8rem; color: var(--text-dim); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?= htmlspecialchars($item['description']) ?>
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="actions" style="justify-content: flex-end;">
                                            <a href="?portofolio_id=<?= $item['id'] ?>" class="btn-icon btn-edit" title="Edit">✎</a>
                                            <form method="POST" onsubmit="return confirm('Hapus data ini selamanya?')">
                                                <input type="hidden" name="delete_index" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn-icon btn-delete" title="Hapus">✕</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const imgSrc = document.getElementById('img-src');
    const label = document.getElementById('file-label');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgSrc.src = e.target.result;
            preview.style.display = 'block';
            label.innerHTML = "✅ " + input.files[0].name;
            label.style.color = "#10b981";
        }
        reader.readAsDataURL(input.files[0]);
    }
}

<?php if (isset($editItem) && $editItem['image_url']): ?>
    window.onload = () => {
        const preview = document.getElementById('preview');
        const imgSrc = document.getElementById('img-src');
        imgSrc.src = '<?= $editItem['image_url'] ?>';
        preview.style.display = 'block';
    }
<?php endif; ?>
</script>

</body>
</html>