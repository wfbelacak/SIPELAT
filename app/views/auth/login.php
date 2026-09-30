<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login — <?= SITENAME; ?></title>
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
  .login-panel{
    padding:52px 66px 30px;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }
  .login-inner{width:min(100%,390px);margin:0 auto}
  h1{font-size:20px;letter-spacing:-.4px;margin:0 0 10px;font-weight:800}
  .welcome{font-size:11px;color:#7488a4;margin-bottom:28px}
  label{display:block;font-size:11px;font-weight:700;margin:0 0 9px;color:#3b5373}
  .field{margin-bottom:19px}
  .input-wrap{position:relative}
  input.form-control {
    width:100%;
    height:42px;
    border:1px solid #d8e3f0;
    border-radius:5px;
    outline:none;
    padding:0 39px 0 39px;
    color:#19365d;
    font:inherit;
    font-size:12px;
    background:#fff;
    transition:.2s;
  }
  input.form-control:focus{border-color:#6aa1ff;box-shadow:0 0 0 3px rgba(23,105,255,.10)}
  input.form-control::placeholder{color:#a9b8ca}
  input.form-control.is-invalid { border-color: #dc3545; }
  .invalid-feedback { color: #dc3545; font-size: 11px; margin-top: 5px; }

  .field-icon,.eye{
    position:absolute;top:50%;transform:translateY(-50%);
    color:#8fa2bb;display:grid;place-items:center;
  }
  .field-icon{left:13px}
  .eye{right:12px;border:0;background:none;cursor:pointer;padding:3px}
  .field-icon svg,.eye svg{width:15px;height:15px}
  .options{display:flex;justify-content:space-between;align-items:center;margin:3px 0 20px;font-size:10px}
  .remember{display:flex;align-items:center;gap:6px;color:#536985}
  .remember input{width:12px;height:12px;padding:0;accent-color:var(--primary)}
  a{color:#1769ff;text-decoration:none}
  .forgot{font-size:10px}
  .btn{
    width:100%;height:42px;border:0;border-radius:5px;
    background:linear-gradient(90deg,#1264E8,#0E4DB5);
    color:white;font-weight:750;font-size:12px;cursor:pointer;
    box-shadow:0 5px 12px rgba(18,100,232,.16);
  }
  .btn:active{transform:translateY(1px)}
  .divider{display:flex;align-items:center;gap:12px;color:#91a1b5;font-size:10px;margin:24px 0 12px}
  .divider:before,.divider:after{content:"";height:1px;background:#e7edf5;flex:1}
  .google{
    height:42px;width:100%;border:1px solid #e0e8f2;border-radius:5px;
    background:#fff;color:#3b5373;font-size:11px;font-weight:650;
    display:flex;align-items:center;justify-content:center;gap:9px;cursor:pointer;
  }
  .google svg{width:15px;height:15px}
  .footer{font-size:10px;color:#a0aec0;text-align:center;margin-top:54px}
  .message{font-size:11px;margin-top:12px;min-height:16px;color:#2d7a52}
  
  /* Alert Flash Messages styles using inline CSS to match the style */
  .alert {
      padding: 10px 15px;
      margin-bottom: 20px;
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
    .login-panel{padding:42px 32px 30px}
    .footer{margin-top:42px}
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
      <!-- <svg class="logo" viewBox="0 0 64 72" fill="none" aria-label="Logo SiPeminjaman">
        <path d="M32 2 60 18v36L32 70 4 54V18L32 2Z" stroke="#fff" stroke-width="4" stroke-linejoin="round"/>
        <path d="M32 12 50 22v25L32 58 14 47V22L32 12Z" fill="#1674ff" stroke="#fff" stroke-width="3" stroke-linejoin="round"/>
        <path d="m20 27 12 7 12-7M32 34v17" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
      </svg> -->
      <div>
        <div class="brand-name">SiPeminjam</div>
        <div class="brand-sub">Sistem Informasi Peminjaman Alat</div>
      </div>
    </div>
    <div class="headline">Pinjam Alat, Wujudkan<br/>Kegiatan dengan Lebih Mudah</div>
    <div class="description">Kelola peminjaman alat secara digital,<br/>cepat, dan terintegrasi.</div>
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

  <section class="login-panel">
    <div class="login-inner">
      <h1>Selamat Datang!</h1>
      <div class="welcome">Silakan masuk ke akun Anda untuk melanjutkan.</div>

      <?php flash('message'); ?>

      <form action="<?= URLROOT; ?>/auth/login" method="POST">
        <div class="field">
          <label for="email">Email atau Username</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
            </span>
            <input id="email" name="username" type="text" class="form-control <?= !empty($data['username_err']) ? 'is-invalid' : ''; ?>" placeholder="Masukkan email atau username" autocomplete="username" value="<?= $data['username'] ?? ''; ?>" autofocus />
          </div>
          <?php if(!empty($data['username_err'])): ?>
              <div class="invalid-feedback"><?= $data['username_err']; ?></div>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="field-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
            </span>
            <input id="password" name="password" type="password" class="form-control <?= !empty($data['password_err']) ? 'is-invalid' : ''; ?>" placeholder="Masukkan password" autocomplete="current-password" />
            <button class="eye" type="button" id="togglePassword" aria-label="Tampilkan password">
              <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <?php if(!empty($data['password_err'])): ?>
              <div class="invalid-feedback"><?= $data['password_err']; ?></div>
          <?php endif; ?>
        </div>

        <div class="options">
          <label class="remember"><input type="checkbox" id="remember" name="remember" /> Ingat saya</label>
          <a class="forgot" href="#">Lupa password?</a>
        </div>

        <button class="btn" type="submit">Masuk</button>
      </form>

      <div class="divider">atau login dengan</div>
      <button class="google" type="button" id="googleBtn">
        <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.4h6.5a5.6 5.6 0 0 1-2.4 3.7v3h3.9c2.3-2.1 3.5-5.1 3.5-8.8Z"/><path fill="#34A853" d="M12 24c3.2 0 5.9-1.1 7.9-2.9l-3.9-3c-1.1.7-2.4 1.1-4 1.1-3.1 0-5.7-2.1-6.6-4.9h-4v3.1A12 12 0 0 0 12 24Z"/><path fill="#FBBC05" d="M5.4 14.3a7.2 7.2 0 0 1 0-4.6V6.6h-4a12 12 0 0 0 0 10.8l4-3.1Z"/><path fill="#EA4335" d="M12 4.8c1.8 0 3.4.6 4.7 1.8l3.5-3.5C17.9 1.1 15.2 0 12 0A12 12 0 0 0 1.4 6.6l4 3.1C6.3 6.9 8.9 4.8 12 4.8Z"/></svg>
        Login dengan Google
      </button>
      <div class="footer">© 2026 SiPeminjam. Semua hak dilindungi.</div>
    </div>
  </section>
</main>
<script>
  const password = document.getElementById('password');
  const toggle = document.getElementById('togglePassword');

  toggle.addEventListener('click', () => {
    const visible = password.type === 'text';
    password.type = visible ? 'password' : 'text';
    toggle.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
  });
</script>
</body>
</html>
