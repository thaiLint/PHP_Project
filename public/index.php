<?php
require 'db.php';

$success = $_GET['success'] ?? '';
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shodai — Products</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --bg:#0d0d14;
  --surface:#14141f;
  --surface2:#1c1c2e;
  --border:#2a2a40;
  --accent:#f0c040;
  --accent2:#e8a020;
  --text:#f0f0f8;
  --text2:#9090b0;
  --text3:#5a5a78;
  --green:#34d399;
  --green-bg:rgba(52,211,153,.12);
  --red:#f87171;
  --red-bg:rgba(248,113,113,.12);
  --radius:14px;
  --radius-sm:8px;
}

body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;}


nav{
  display:flex;align-items:center;justify-content:space-between;
  padding:0 40px;height:68px;
  background:var(--surface);
  border-bottom:1px solid var(--border);
  position:sticky;top:0;z-index:100;
  backdrop-filter:blur(12px);
}
.nav-logo{
  font-family:'Playfair Display',serif;
  font-size:22px;font-weight:900;
  color:var(--accent);
  letter-spacing:-.5px;
}
.nav-logo span{color:var(--text);font-weight:700}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-badge{
  background:var(--surface2);border:1px solid var(--border);
  border-radius:20px;padding:5px 14px;
  font-size:12px;font-weight:600;color:var(--text2);
}
.nav-badge strong{color:var(--accent)}

/* ── HERO ── */
.hero{
  padding:52px 40px 36px;
  display:flex;align-items:flex-end;justify-content:space-between;
  gap:20px;flex-wrap:wrap;
  border-bottom:1px solid var(--border);
}
.hero-title{
  font-family:'Playfair Display',serif;
  font-size:clamp(32px,5vw,52px);
  font-weight:900;
  line-height:1.05;
  letter-spacing:-1.5px;
}
.hero-title span{color:var(--accent)}
.hero-sub{
  font-size:14px;color:var(--text2);margin-top:8px;font-weight:400;
}
.btn-add{
  display:inline-flex;align-items:center;gap:8px;
  padding:14px 28px;
  background:var(--accent);color:#0d0d14;
  font-family:'DM Sans',sans-serif;font-weight:700;font-size:14px;
  border:none;border-radius:var(--radius-sm);cursor:pointer;
  text-decoration:none;
  transition:all .2s;
  white-space:nowrap;
}
.btn-add:hover{background:var(--accent2);transform:translateY(-1px);box-shadow:0 8px 24px rgba(240,192,64,.25)}
.btn-add svg{width:16px;height:16px}

/* ── TOAST ── */
.toast{
  margin:20px 40px 0;
  padding:14px 20px;
  border-radius:var(--radius-sm);
  font-size:13.5px;font-weight:600;
  display:flex;align-items:center;gap:10px;
  animation:slideIn .3s ease;
}
.toast.success{background:var(--green-bg);border:1px solid rgba(52,211,153,.3);color:var(--green)}
.toast.error{background:var(--red-bg);border:1px solid rgba(248,113,113,.3);color:var(--red)}
@keyframes slideIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

/* ── GRID ── */
.products-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
  gap:24px;
  padding:36px 40px 60px;
}

/* ── PRODUCT CARD ── */
.card{
  background:var(--surface);
  border:1px solid var(--border);
  border-radius:var(--radius);
  overflow:hidden;
  transition:transform .25s,border-color .25s,box-shadow .25s;
  animation:fadeUp .4s ease both;
}
.card:hover{
  transform:translateY(-4px);
  border-color:rgba(240,192,64,.3);
  box-shadow:0 20px 48px rgba(0,0,0,.4),0 0 0 1px rgba(240,192,64,.1);
}
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.card:nth-child(1){animation-delay:.05s}
.card:nth-child(2){animation-delay:.1s}
.card:nth-child(3){animation-delay:.15s}
.card:nth-child(n+4){animation-delay:.2s}

.card-img-wrap{
  position:relative;height:220px;overflow:hidden;
  background:var(--surface2);
}
.card-img{
  width:100%;height:100%;object-fit:cover;
  transition:transform .4s ease;
}
.card:hover .card-img{transform:scale(1.04)}
.card-img-placeholder{
  width:100%;height:100%;
  display:flex;align-items:center;justify-content:center;
  color:var(--text3);font-size:48px;
}

.status-pill{
  position:absolute;top:14px;left:14px;
  padding:4px 12px;border-radius:20px;
  font-size:11px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;
}
.status-Active{background:var(--green-bg);color:var(--green);border:1px solid rgba(52,211,153,.3)}
.status-Inactive{background:var(--red-bg);color:var(--red);border:1px solid rgba(248,113,113,.2)}
.status-Draft{background:rgba(160,160,200,.12);color:var(--text2);border:1px solid var(--border)}

.card-body{padding:20px 22px}
.card-sku{font-size:11px;font-weight:600;color:var(--text3);letter-spacing:1px;text-transform:uppercase;margin-bottom:6px}
.card-name{
  font-family:'Playfair Display',serif;
  font-size:19px;font-weight:700;
  color:var(--text);line-height:1.2;margin-bottom:14px;
}

.card-meta{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:18px;
}
.card-price{
  font-size:24px;font-weight:700;color:var(--accent);
  font-family:'Playfair Display',serif;
}
.card-stock{
  display:flex;align-items:center;gap:6px;
  font-size:13px;color:var(--text2);font-weight:500;
}
.card-stock svg{width:13px;height:13px}

.card-divider{height:1px;background:var(--border);margin-bottom:18px}

.card-actions{display:flex;gap:10px}
.btn-edit,.btn-delete{
  flex:1;
  display:inline-flex;align-items:center;justify-content:center;gap:6px;
  padding:10px 0;
  border-radius:var(--radius-sm);
  font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;
  cursor:pointer;border:none;text-decoration:none;
  transition:all .2s;
}
.btn-edit{
  background:rgba(240,192,64,.12);color:var(--accent);
  border:1px solid rgba(240,192,64,.25);
}
.btn-edit:hover{background:rgba(240,192,64,.22);border-color:rgba(240,192,64,.5)}
.btn-delete{
  background:var(--red-bg);color:var(--red);
  border:1px solid rgba(248,113,113,.2);
}
.btn-delete:hover{background:rgba(248,113,113,.22);border-color:rgba(248,113,113,.4)}
.btn-edit svg,.btn-delete svg{width:14px;height:14px}

/* ── EMPTY STATE ── */
.empty{
  grid-column:1/-1;
  text-align:center;padding:80px 20px;
}
.empty-icon{font-size:56px;margin-bottom:20px;opacity:.4}
.empty-title{font-family:'Playfair Display',serif;font-size:26px;color:var(--text);margin-bottom:10px}
.empty-sub{font-size:14px;color:var(--text2);margin-bottom:28px}

/* ── CONFIRM MODAL ── */
.overlay{
  display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);
  z-index:200;align-items:center;justify-content:center;
  backdrop-filter:blur(4px);
}
.overlay.show{display:flex}
.modal{
  background:var(--surface);border:1px solid var(--border);
  border-radius:var(--radius);padding:36px;
  max-width:380px;width:90%;text-align:center;
  animation:popIn .25s ease;
}
@keyframes popIn{from{opacity:0;transform:scale(.92)}to{opacity:1;transform:scale(1)}}
.modal-icon{font-size:42px;margin-bottom:16px}
.modal-title{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;margin-bottom:8px}
.modal-sub{font-size:14px;color:var(--text2);margin-bottom:28px;line-height:1.6}
.modal-actions{display:flex;gap:10px}
.btn-cancel{
  flex:1;padding:12px;border-radius:var(--radius-sm);
  background:var(--surface2);border:1px solid var(--border);
  color:var(--text2);font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;
  cursor:pointer;transition:all .2s;
}
.btn-cancel:hover{border-color:var(--text2);color:var(--text)}
.btn-confirm-delete{
  flex:1;padding:12px;border-radius:var(--radius-sm);
  background:var(--red-bg);border:1px solid rgba(248,113,113,.3);
  color:var(--red);font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;
  cursor:pointer;transition:all .2s;
  text-decoration:none;display:flex;align-items:center;justify-content:center;
}
.btn-confirm-delete:hover{background:rgba(248,113,113,.25)}

/* ── FOOTER ── */
footer{
  text-align:center;padding:28px;
  border-top:1px solid var(--border);
  font-size:12px;color:var(--text3);
}
</style>
</head>
<body>

<nav>
  <div class="nav-logo">Shodai<span>.</span></div>
  <div class="nav-right">
    <div class="nav-badge">Total: <strong><?= count($products) ?> Products</strong></div>
    <a href="create.php" class="btn-add">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Product
    </a>
  </div>
</nav>

<?php if ($success === 'created'): ?>
  <div class="toast success">✓ Product created successfully!</div>
<?php elseif ($success === 'updated'): ?>
  <div class="toast success">✓ Product updated successfully!</div>
<?php elseif ($success === 'deleted'): ?>
  <div class="toast success">✓ Product deleted successfully!</div>
<?php endif; ?>

<div class="hero">
  <div>
    <h1 class="hero-title">Product<br><span>Catalogue</span></h1>
    <p class="hero-sub">Manage your inventory — create, update, and remove products.</p>
  </div>
  <a href="create.php" class="btn-add">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    New Product
  </a>
</div>

<div class="products-grid">
  <?php if (empty($products)): ?>
    <div class="empty">
      <div class="empty-icon">📦</div>
      <div class="empty-title">No Products Yet</div>
      <p class="empty-sub">Add your first product to get started.</p>
      <a href="create.php" class="btn-add">+ Add First Product</a>
    </div>
  <?php else: ?>
    <?php foreach ($products as $p): ?>
    <div class="card">
      <div class="card-img-wrap">
        <?php if (!empty($p['image_url'])): ?>
          <img class="card-img" src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <?php else: ?>
          <div class="card-img-placeholder">🧥</div>
        <?php endif; ?>
        <span class="status-pill status-<?= $p['status'] ?>"><?= $p['status'] ?></span>
      </div>
      <div class="card-body">
        <div class="card-sku"><?= htmlspecialchars($p['sku']) ?></div>
        <div class="card-name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="card-meta">
          <div class="card-price">$<?= number_format($p['price'], 2) ?></div>
          <div class="card-stock">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            <?= number_format($p['stock']) ?> in stock
          </div>
        </div>
        <div class="card-divider"></div>
        <div class="card-actions">
          <a href="update.php?id=<?= $p['id'] ?>" class="btn-edit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
          </a>
          <button class="btn-delete" onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['name'])) ?>')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
            Delete
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Delete Confirm Modal -->
<div class="overlay" id="deleteOverlay">
  <div class="modal">
    <div class="modal-icon">🗑️</div>
    <div class="modal-title">Delete Product?</div>
    <p class="modal-sub" id="modalMsg">Are you sure you want to delete this product? This action cannot be undone.</p>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal()">Cancel</button>
      <a href="#" class="btn-confirm-delete" id="confirmBtn">Yes, Delete</a>
    </div>
  </div>
</div>

<footer>Shodai CRUD — XAMPP / PHP &amp; MySQL &nbsp;·&nbsp; <?= date('Y') ?></footer>

<script>
function confirmDelete(id, name) {
  document.getElementById('modalMsg').textContent = 'Are you sure you want to delete "' + name + '"? This cannot be undone.';
  document.getElementById('confirmBtn').href = 'delete.php?id=' + id;
  document.getElementById('deleteOverlay').classList.add('show');
}
function closeModal() {
  document.getElementById('deleteOverlay').classList.remove('show');
}
document.getElementById('deleteOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// Auto-hide toast
const toast = document.querySelector('.toast');
if (toast) setTimeout(() => toast.style.opacity = '0', 3500);
</script>
</body>
</html>
