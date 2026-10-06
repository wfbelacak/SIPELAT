<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Daftar Akun — <?= SITENAME; ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap');
  
  :root{
    --blue:#1264E8;
    --blue-2:#0E4DB5;
    --primary:#1264E8;
    --ink:#1e293b;
    --muted:#64748b;
    --line:#e2e8f0;
    --bg:#f8fafc;
  }
  *{box-sizing:border-box}
  body{
    margin:0;
    min-height:100vh;
    display:grid;
    place-items:center;
    padding:22px;
    font-family: 'Roboto', sans-serif;
    -webkit-font-smoothing: antialiased;
    background:linear-gradient(135deg,#f1f5f9,#ffffff);
    color:var(--ink);
  }
  .shell{
    width:min(1120px,100%);
    min-height:600px;
    display:grid;
    grid-template-columns:46% 54%;
    overflow:hidden;
    background:#fff;
    border:1px solid #dce7f2;
    border-radius:12px;
    box-shadow:0 12px 34px rgba(29,71,118,.10);
  }
  .brand-panel{
    position:relative;
    isolation:isolate;
    overflow:hidden;
    padding:44px 62px 38px;
    color:#fff;
    background:
      radial-gradient(circle at 80% 16%,rgba(63,145,255,.22),transparent 28%),
      linear-gradient(150deg,#143e7c 0%,#0c2d60 65%,#092653 100%);
  }
  .brand-panel:before{
    content:"";
    position:absolute;
    inset:auto -15% -17% -15%;
    height:48%;
    background:rgba(35,111,213,.24);
    border-radius:50% 50% 0 0;
    transform:rotate(-7deg);
    z-index:-1;
  }
  .brand-panel:after{
    content:"";
    position:absolute;
    right:-90px;
    bottom:90px;
    width:310px;height:180px;
    background:rgba(18,100,232,.16);
    transform:rotate(-13deg);
    border-radius:35px;
    z-index:-1;
  }
  .brand{
    display:flex;
    align-items:center;
    gap:13px;
  }
  .logo{
    width:38px;height:44px;flex:none;
    filter:drop-shadow(0 4px 8px rgba(0,0,0,.12));
  }
  .brand-name{font-size:20px;font-weight:800;letter-spacing:-.5px}
  .brand-sub{font-size:10px;opacity:.82;margin-top:4px}
  .headline{
    margin-top:46px;
    max-width:270px;
    font-size:24px;
    line-height:1.35;
    letter-spacing:-.6px;
  }
  .description{
    max-width:255px;
    margin-top:17px;
    font-size:12px;
    line-height:1.8;
    color:#c6d8f3;
  }
  .scene{
    position:absolute;
    left:34px;right:24px;bottom:28px;
    width:calc(100% - 58px);
    max-height:270px;
  }
  .register-panel{
    padding:36px 66px 30px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    overflow-y:auto;
  }
  .register-inner{width:min(100%,390px);margin:0 auto}
  h1{font-size:20px;letter-spacing:-.4px;margin:0 0 10px;font-weight:800}
  .welcome{font-size:11px;color:#7488a4;margin-bottom:22px}
  label{display:block;font-size:11px;font-weight:700;margin:0 0 7px;color:#3b5373}
  .field{margin-bottom:14px}
  .input-wrap{position:relative}
  input.form-control {
    width:100%;
    height:40px;
    border:1px solid #d8e3f0;
    border-radius:5px;
    outline:none;
    padding:0 14px 0 39px;
    color:#19365d;
    font:inherit;
    font-size:12px;
    background:#fff;
    transition:.2s;
  }
  input.form-control:focus{border-color:#6aa1ff;box-shadow:0 0 0 3px rgba(23,105,255,.10)}
  input.form-control::placeholder{color:#a9b8ca}
  input.form-control.is-invalid { border-color: #dc3545; }

  .field-icon,.eye{
    position:absolute;top:50%;transform:translateY(-50%);
    color:#8fa2bb;display:grid;place-items:center;
  }
  .field-icon{left:13px}
  .eye{right:12px;border:0;background:none;cursor:pointer;padding:3px}
  .field-icon svg,.eye svg{width:15px;height:15px}
  a{color:var(--primary);text-decoration:none}
  .btn{
    width:100%;height:42px;border:0;border-radius:5px;
    background:linear-gradient(90deg,#1264E8,#0E4DB5);
    color:white;font-weight:750;font-size:12px;cursor:pointer;
    box-shadow:0 5px 12px rgba(18,100,232,.16);
    margin-top:6px;
  }
  .btn:active{transform:translateY(1px)}
  .footer{font-size:10px;color:#a0aec0;text-align:center;margin-top:30px}
  .login-link{font-size:11px;text-align:center;margin-top:18px;color:#64748b}
  .login-link a{font-weight:700}

  .alert {
      padding: 10px 15px;
      margin-bottom: 15px;
      border: 1px solid transparent;
      border-radius: 4px;
      font-size: 11px;
  }
  .alert-danger {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
  }
  .alert-success {
      color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;
  }

  @media(max-width:820px){
    body{padding:12px}
    .shell{grid-template-columns:1fr;max-width:520px}
    .brand-panel{min-height:280px;padding:30px 34px}
    .headline{margin-top:30px;font-size:21px}
    .description{margin-top:10px}
    .scene{left:auto;right:5px;bottom:-8px;width:52%;max-height:210px}
    .register-panel{padding:32px 32px 30px}
    .footer{margin-top:24px}
  }
  @media(max-width:430px){
    .brand-panel{min-height:300px}
    .scene{width:72%;right:-10px;bottom:-8px}
    .headline{max-width:210px}
    .description{max-width:210px}
  }
</style>
</head>
<body>
<main class="shell">
  <section class="brand-panel">
    <div class="brand">
      <img src="<?= URLROOT; ?>/assets/img/logo-white.png" alt="Logo" class="logo">
      <div>
        <div class="brand-name">SiPeminjam</div>
        <div class="brand-sub">Sistem Informasi Peminjaman Alat</div>
      </div>
    </div>
    <div class="headline">Bergabung dan Mulai<br/>Pinjam Alat dengan Mudah</div>
    <div class="description">Daftarkan akun Anda untuk mulai<br/>menggunakan sistem peminjaman alat.</div>
    <svg class="scene" viewBox="0 0 520 300" fill="none" aria-label="Ilustrasi laptop, kamera, proyektor dan tanaman">
      <path d="M18 245 390 197l111 31-370 54-113-37Z" fill="#174a91"/>
      <path d="m38 237 340-49 101 26-339 48-102-25Z" fill="#0c3671"/>
      <g transform="translate(68 74) rotate(-7)">
        <path d="M0 20 163 0l22 117-163 23L0 20Z" fill="#1b5aa9"/>
        <path d="M17 31 150 15l16 91-133 18L17 31Z" fill="#0a2348"/>
        <path d="m-7 140 190-27 39 24-190 28-39-25Z" fill="#0d2850"/>
        <path d="m20 132 159-22 19 9-160 23-18-10Z" fill="#2c71c5"/>
      </g>
      <g transform="translate(250 126)">
        <path d="M0 30 106 11l48 20-109 20L0 30Z" fill="#a9c6e9"/>
        <path d="m0 30 45 21v76L0 104V30Z" fill="#dceafa"/>
        <path d="m45 51 109-20v77L45 127V51Z" fill="#b9d0e9"/>
        <path d="m16 43 28 13v42L16 85V43Z" fill="#6d91b9"/>
        <path d="m58 64 78-14v44L58 108V64Z" fill="#315b8f"/>
        <path d="m56 119 100-18-7 12-95 18-10-6Z" fill="#7f9fc5"/>
        <circle cx="124" cy="46" r="5" fill="#2d4f7b"/>
      </g>
      <g transform="translate(170 176)">
        <ellipse cx="64" cy="53" rx="69" ry="22" fill="#082653"/>
        <rect x="10" y="5" width="110" height="68" rx="9" fill="#152e51"/>
        <rect x="17" y="12" width="96" height="54" rx="5" fill="#294a75"/>
        <circle cx="65" cy="39" r="22" fill="#0b1f3b"/>
        <circle cx="65" cy="39" r="15" fill="#426d9e"/>
        <circle cx="65" cy="39" r="8" fill="#112947"/>
        <rect x="44" y="-2" width="41" height="11" rx="3" fill="#27466d"/>
        <rect x="25" y="73" width="80" height="7" rx="3.5" fill="#071d3b"/>
      </g>
      <g transform="translate(373 65)">
        <path d="M45 87C8 73 4 39 16 14c25 6 42 35 29 73Z" fill="#4f8b6a"/>
        <path d="M49 89C74 57 100 55 117 61c-6 30-39 46-68 28Z" fill="#6aa77c"/>
        <path d="M51 92C32 55 42 24 60 0c25 23 21 60-9 92Z" fill="#82b68a"/>
        <path d="M54 91 49 146" stroke="#5b775f" stroke-width="5" stroke-linecap="round"/>
        <path d="M20 139h72l-12 24H32l-12-24Z" fill="#315d85"/>
        <path d="M27 139h58l-8 15H35l-8-15Z" fill="#7eafd1"/>
      </g>
    </svg>
  </section>

  <section class="register-panel">
    <div class="register-inner">
      <h1>Buat Akun Baru</h1>
      <div class="welcome">Lengkapi form di bawah untuk mendaftar.</div>

      <?php flash('message'); ?>

      <form action="<?= URLROOT; ?>/auth/register" method="POST">
        <!-- Nama Lengkap -->
        <div class="field">
          <label for="name">Nama Lengkap</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input id="name" name="name" type="text" class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : ''; ?>" placeholder="Masukkan nama lengkap" value="<?= $data['name'] ?? ''; ?>" autofocus />
          </div>
        </div>

        <!-- Username -->
        <div class="field">
          <label for="username">Username</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M15.5 12a3.5 3.5 0 1 0-7 0 3.5 3.5 0 0 0 7 0Z"/><path d="M19.43 12.98c.04-.32.07-.65.07-.98s-.03-.66-.07-.98l2.11-1.65a.5.5 0 0 0 .12-.64l-2-3.46a.5.5 0 0 0-.61-.22l-2.49 1a7.03 7.03 0 0 0-1.69-.98l-.38-2.65A.49.49 0 0 0 14 2h-4a.49.49 0 0 0-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1a.5.5 0 0 0-.61.22l-2 3.46a.49.49 0 0 0 .12.64l2.11 1.65c-.04.32-.07.66-.07.98s.03.66.07.98l-2.11 1.65a.5.5 0 0 0-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.39 1.08.73 1.69.98l.38 2.65c.05.24.26.42.49.42h4c.24 0 .44-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.22.08.49 0 .61-.22l2-3.46a.5.5 0 0 0-.12-.64l-2.11-1.65Z"/></svg>
            </span>
            <input id="username" name="username" type="text" class="form-control <?= !empty($data['username_err']) ? 'is-invalid' : ''; ?>" placeholder="Masukkan username" value="<?= $data['username'] ?? ''; ?>" />
          </div>
        </div>

        <!-- Email -->
        <div class="field">
          <label for="email">Email</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
            </span>
            <input id="email" name="email" type="email" class="form-control <?= !empty($data['email_err']) ? 'is-invalid' : ''; ?>" placeholder="Masukkan email" value="<?= $data['email'] ?? ''; ?>" />
          </div>
        </div>

        <!-- Password -->
        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
            </span>
            <input id="password" name="password" type="password" class="form-control <?= !empty($data['password_err']) ? 'is-invalid' : ''; ?>" placeholder="Minimal 6 karakter" />
            <button class="eye" type="button" id="togglePassword" aria-label="Tampilkan password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <!-- Konfirmasi Password -->
        <div class="field">
          <label for="confirm_password">Konfirmasi Password</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
            </span>
            <input id="confirm_password" name="confirm_password" type="password" class="form-control <?= !empty($data['confirm_password_err']) ? 'is-invalid' : ''; ?>" placeholder="Ulangi password" />
            <button class="eye" type="button" id="toggleConfirm" aria-label="Tampilkan password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <button class="btn" type="submit">Daftar Sekarang</button>
      </form>

      <div class="login-link">Sudah punya akun? <a href="<?= URLROOT; ?>/auth">Masuk di sini</a></div>
      <div class="footer">&copy; 2026 SiPeminjam. Semua hak dilindungi.</div>
    </div>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('togglePassword').addEventListener('click', () => {
    const p = document.getElementById('password');
    p.type = p.type === 'text' ? 'password' : 'text';
  });
  document.getElementById('toggleConfirm').addEventListener('click', () => {
    const c = document.getElementById('confirm_password');
    c.type = c.type === 'text' ? 'password' : 'text';
  });

  <?php
  $errors = [];
  if(!empty($data['name_err'])) $errors[] = addslashes($data['name_err']);
  if(!empty($data['username_err'])) $errors[] = addslashes($data['username_err']);
  if(!empty($data['email_err'])) $errors[] = addslashes($data['email_err']);
  if(!empty($data['password_err'])) $errors[] = addslashes($data['password_err']);
  if(!empty($data['confirm_password_err'])) $errors[] = addslashes($data['confirm_password_err']);
  if(!empty($errors)):
  ?>
  Swal.fire({
    icon: 'error',
    title: 'Pendaftaran Gagal',
    html: '<ul style="text-align:left;font-size:13px;margin:0;padding-left:18px;"><?php foreach($errors as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>',
    confirmButtonColor: '#1264E8'
  });
  <?php endif; ?>
</script>
</body>
</html>
