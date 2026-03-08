<?php
require 'db.php';

$errors = [];
$old = ['name'=>'','sku'=>'','price'=>'','stock'=>'','status'=>'Active','image_url'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name'] ?? '');
    $sku       = trim($_POST['sku'] ?? '');
    $price     = $_POST['price'] ?? '';
    $stock     = $_POST['stock'] ?? '';
    $status    = $_POST['status'] ?? 'Active';
    $image_url = trim($_POST['image_url'] ?? '');

    $old = compact('name','sku','price','stock','status','image_url');

    if (!$name)                      $errors['name']  = 'Product name is required.';
    if (!$sku)                       $errors['sku']   = 'SKU is required.';
    if (!is_numeric($price)||$price<0) $errors['price'] = 'Enter a valid price.';
    if (!is_numeric($stock)||$stock<0) $errors['stock'] = 'Enter a valid stock number.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, sku, price, stock, status, image_url) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$name, $sku, $price, $stock, $status, $image_url]);
        header('Location: index.php?success=created');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shodai — Add Product</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#0d0d14;--surface:#14141f;--surface2:#1c1c2e;
  --border:#2a2a40;--accent:#f0c040;--accent2:#e8a020;
  --text:#f0f0f8;--text2:#9090b0;--text3:#5a5a78;
  --green:#34d399;--green-bg:rgba(52,211,153,.12);
  --red:#f87171;--red-bg:rgba(248,113,113,.12);
  --radius:14px;--radius-sm:8px;
}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;}

nav{
  display:flex;align-items:center;justify-content:space-between;
  padding:0 40px;height:68px;background:var(--surface);
  border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;
}
.nav-logo{font-family:'Playfair Display',serif;font-size:22px;font-weight:900;color:var(--accent);letter-spacing:-.5px;}
.nav-logo span{color:var(--text);font-weight:700}
.btn-back{
  display:inline-flex;align-items:center;gap:7px;
  padding:9px 18px;border-radius:var(--radius-sm);
  background:var(--surface2);border:1px solid var(--border);
  color:var(--text2);font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;
  text-decoration:none;transition:all .2s;
}
.btn-back:hover{border-color:var(--text2);color:var(--text)}
.btn-back svg{width:14px;height:14px}

.page-wrap{max-width:640px;margin:52px auto;padding:0 24px 80px;}
.page-header{margin-bottom:36px;}
.page-title{
  font-family:'Playfair Display',serif;font-size:38px;font-weight:900;
  letter-spacing:-1px;line-height:1.05;
}
.page-title span{color:var(--accent)}
.page-sub{font-size:14px;color:var(--text2);margin-top:8px}

.form-card{
  background:var(--surface);border:1px solid var(--border);
  border-radius:var(--radius);padding:36px;
}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
@media(max-width:520px){.form-row{grid-template-columns:1fr}}

.form-group{margin-bottom:22px}
label{
  display:block;font-size:12px;font-weight:700;
  letter-spacing:.8px;text-transform:uppercase;color:var(--text2);
  margin-bottom:8px;
}
.required-star{color:var(--accent)}

input[type=text],input[type=number],select,input[type=url]{
  width:100%;padding:13px 16px;
  background:var(--surface2);border:1.5px solid var(--border);
  border-radius:var(--radius-sm);color:var(--text);
  font-family:'DM Sans',sans-serif;font-size:14px;
  outline:none;transition:border-color .2s,box-shadow .2s;
  -webkit-appearance:none;
}
input:focus,select:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 3px rgba(240,192,64,.12);
}
input::placeholder{color:var(--text3)}
select option{background:var(--surface2)}

.input-error{border-color:var(--red) !important;}
.error-msg{font-size:12px;color:var(--red);margin-top:5px;font-weight:500;}

.img-preview{
  margin-top:10px;height:120px;border-radius:var(--radius-sm);
  border:1px solid var(--border);overflow:hidden;
  display:flex;align-items:center;justify-content:center;
  background:var(--surface2);color:var(--text3);font-size:13px;
}
.img-preview img{width:100%;height:100%;object-fit:cover;display:none;}

.form-divider{height:1px;background:var(--border);margin:8px 0 24px;}

.btn-submit{
  width:100%;padding:15px;
  background:var(--accent);color:#0d0d14;
  font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;
  border:none;border-radius:var(--radius-sm);cursor:pointer;
  transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;
}
.btn-submit:hover{background:var(--accent2);box-shadow:0 8px 24px rgba(240,192,64,.25)}
.btn-submit svg{width:16px;height:16px}
</style>
</head>
<body>

<nav>
  <div class="nav-logo">Shodai<span>.</span></div>
  <a href="index.php" class="btn-back">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Products
  </a>
</nav>

<div class="page-wrap">
  <div class="page-header">
    <h1 class="page-title">Add New<br><span>Product</span></h1>
    <p class="page-sub">Fill in the details below to add a product to your catalogue.</p>
  </div>

  <div class="form-card">
    <form method="POST" action="" novalidate>

      <div class="form-group">
        <label>Product Name <span class="required-star">*</span></label>
        <input type="text" name="name" placeholder="e.g. Cashmere Blazer" value="<?= htmlspecialchars($old['name']) ?>" class="<?= isset($errors['name']) ? 'input-error' : '' ?>">
        <?php if (isset($errors['name'])): ?><div class="error-msg"><?= $errors['name'] ?></div><?php endif; ?>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>SKU <span class="required-star">*</span></label>
          <input type="text" name="sku" placeholder="e.g. SKU-001" value="<?= htmlspecialchars($old['sku']) ?>" class="<?= isset($errors['sku']) ? 'input-error' : '' ?>">
          <?php if (isset($errors['sku'])): ?><div class="error-msg"><?= $errors['sku'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status">
            <?php foreach(['Active','Inactive','Draft'] as $s): ?>
            <option value="<?= $s ?>" <?= $old['status']===$s?'selected':'' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Price ($) <span class="required-star">*</span></label>
          <input type="number" name="price" placeholder="0.00" step="0.01" min="0" value="<?= htmlspecialchars($old['price']) ?>" class="<?= isset($errors['price']) ? 'input-error' : '' ?>">
          <?php if (isset($errors['price'])): ?><div class="error-msg"><?= $errors['price'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Stock Quantity <span class="required-star">*</span></label>
          <input type="number" name="stock" placeholder="0" min="0" value="<?= htmlspecialchars($old['stock']) ?>" class="<?= isset($errors['stock']) ? 'input-error' : '' ?>">
          <?php if (isset($errors['stock'])): ?><div class="error-msg"><?= $errors['stock'] ?></div><?php endif; ?>
        </div>
      </div>

      <div class="form-divider"></div>

      <div class="form-group">
        <label>Image URL <span style="color:var(--text3);font-weight:400;text-transform:none;letter-spacing:0">(optional)</span></label>
        <input type="text" name="image_url" id="imageUrl" placeholder="https://..." value="<?= htmlspecialchars($old['image_url']) ?>" oninput="previewImage(this.value)">
        <div class="img-preview" id="imgPreview">
          <img id="previewImg" src="" alt="Preview">
          <span id="previewPlaceholder">Image preview will appear here</span>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Create Product
      </button>

    </form>
  </div>
</div>

<script>
function previewImage(url) {
  const img = document.getElementById('previewImg');
  const placeholder = document.getElementById('previewPlaceholder');
  if (url.trim()) {
    img.src = url;
    img.style.display = 'block';
    placeholder.style.display = 'none';
    img.onerror = () => { img.style.display='none'; placeholder.style.display='block'; };
  } else {
    img.style.display = 'none';
    placeholder.style.display = 'block';
  }
}
// Run on load if value exists
previewImage(document.getElementById('imageUrl').value);
</script>
</body>
</html>
