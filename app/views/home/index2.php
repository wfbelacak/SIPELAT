<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="ALATKU - Sistem Peminjaman Alat" />
  <title><?= $data['title'] ?? 'Dashboard'; ?> — <?= SITENAME; ?></title>
  <!-- Custom CSS -->
   <link href="<?php URLROOT; ?> /assets/css/landing.css?v=<? time(); ?>" rel="stylesheet">
</head>
<body>

<header class="topbar">
  <div class="container nav">
    <a href="<?= URLROOT; ?>" class="brand">
            <img src="<?= URLROOT; ?>/assets/img/logo-blue-lengkap.png" alt="SiPeminjam" style="height: 70px; object-fit: contain; margin-left: -5px;">
        </a>
    </a>

    <nav class="navlinks">
      <a href="#beranda" class="active">Beranda</a>
      <a href="#cara-kerja">Cara Kerja</a>
      <a href="#fitur">Fitur</a>
      <a href="#tentang">Tentang</a>
    </nav>

    <a class="login" href="#login">♙ &nbsp; Login</a>
    <a href="<?= URLROOT; ?>/auth">Masuk </a>
    <button class="mobile-menu" aria-label="Menu">☰</button>
  </div>
</header>

<main id="beranda">
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-content">
      <div class="eyebrow">Sistem Peminjaman Alat</div>
      <h1>Peminjaman Alat Jadi<br><span>Lebih Cepat dan<br>Terorganisir</span></h1>
      <p>Kelola peminjaman alat dengan mudah, transparan, dan efisien. Semua kebutuhan alat kerja Anda, dalam satu sistem.</p>
      <div class="actions">
        <a href="#alat" class="btn btn-yellow">▣ &nbsp; Ajukan Peminjaman &nbsp; →</a>
        <a href="#alat" class="btn btn-outline">Lihat Alat &nbsp; →</a>
      </div>
    </div>
  </section>

  <section class="stats-wrap">
    <div class="container stats">
      <div class="stat"><div class="stat-icon">▣</div><div><strong>48</strong><b>Total Alat</b><small>Semua alat yang tersedia di sistem</small></div></div>
      <div class="stat"><div class="stat-icon">✓</div><div><strong>32</strong><b>Alat Tersedia</b><small>Siap digunakan kapan saja</small></div></div>
      <div class="stat"><div class="stat-icon">◷</div><div><strong>12</strong><b>Sedang Dipinjam</b><small>Dalam proses peminjaman</small></div></div>
      <div class="stat"><div class="stat-icon">♙</div><div><strong>86</strong><b>Total Peminjaman</b><small>Sejak sistem digunakan</small></div></div>
    </div>
  </section>

  <section id="alat">
    <div class="container">
      <div class="section-head">
        <div><div class="section-label">Alat Populer</div><h2 class="section-title">Alat yang Sering Dipinjam</h2></div>
        <a class="see-all" href="#">Lihat Semua Alat →</a>
      </div>
      <div class="tools">
        <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Bor Listrik</h3><p>⚒ &nbsp; Perkakas</p><div class="stock">● &nbsp; Tersedia 5 dari 5</div><button class="detail">Detail &nbsp; →</button></div></article>
        <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Tangga Aluminium</h3><p>♙ &nbsp; Keselamatan Kerja</p><div class="stock">● &nbsp; Tersedia 3 dari 3</div><button class="detail">Detail &nbsp; →</button></div></article>
        <article class="tool"><div class="tool-img"><span class="badge red">Dipinjam</span></div><div class="tool-body"><h3>Mesin Las</h3><p>⚙ &nbsp; Mesin & Alat Berat</p><div class="stock">● &nbsp; Tersedia 0 dari 2</div><button class="detail">Detail &nbsp; →</button></div></article>
        <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Gerinda Tangan</h3><p>⚒ &nbsp; Perkakas</p><div class="stock">● &nbsp; Tersedia 4 dari 4</div><button class="detail">Detail &nbsp; →</button></div></article>
        <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Toolbox Set</h3><p>⚒ &nbsp; Perkakas</p><div class="stock">● &nbsp; Tersedia 6 dari 6</div><button class="detail">Detail &nbsp; →</button></div></article>
      </div>
    </div>
  </section>

  <section id="cara-kerja" class="steps-section">
    <div class="container steps-layout">
      <div>
        <div class="section-label">Cara Kerja</div>
        <h2 class="section-title">Mudah dalam 5 Langkah</h2>
        <p>Proses peminjaman alat di sistem kami sangat sederhana dan tidak memakan waktu lama.</p>
        <a href="#alat" class="btn btn-yellow">Mulai Peminjaman &nbsp; →</a>
      </div>
      <div class="steps">
        <div class="step"><div class="step-icon">⚒</div><div class="step-num">1</div><h4>Pilih Alat</h4><p>Cari alat yang Anda butuhkan di katalog.</p></div>
        <div class="step"><div class="step-icon">▤</div><div class="step-num">2</div><h4>Ajukan Peminjaman</h4><p>Isi form peminjaman dengan detail yang jelas.</p></div>
        <div class="step"><div class="step-icon">✓</div><div class="step-num">3</div><h4>Tunggu Persetujuan</h4><p>Tim akan memverifikasi permintaan Anda.</p></div>
        <div class="step"><div class="step-icon">♙</div><div class="step-num">4</div><h4>Ambil Alat</h4><p>Setelah disetujui, ambil alat sesuai jadwal.</p></div>
        <div class="step"><div class="step-icon">↻</div><div class="step-num">5</div><h4>Kembalikan</h4><p>Setelah selesai digunakan, segera kembalikan alat.</p></div>
      </div>
    </div>
  </section>

  <section id="fitur" class="feature-section">
    <div class="container feature-layout">
      <div class="feature-copy">
        <div class="section-label">Fitur Utama</div>
        <h2>Semua yang Anda Butuhkan</h2>
        <p>Fitur lengkap untuk mendukung pengelolaan peminjaman alat yang lebih efisien dan profesional.</p>
        <div class="feature-grid">
          <div class="feature"><div class="feature-icon">♙</div><div><h4>Manajemen Alat</h4><p>Kelola data alat, kategori, dan stok dengan mudah.</p></div></div>
          <div class="feature"><div class="feature-icon">▣</div><div><h4>Peminjaman</h4><p>Ajukan peminjaman dengan proses yang cepat.</p></div></div>
          <div class="feature"><div class="feature-icon">▤</div><div><h4>Pengembalian</h4><p>Pantau pengembalian alat secara real-time.</p></div></div>
          <div class="feature"><div class="feature-icon">✓</div><div><h4>Persetujuan</h4><p>Atur alur persetujuan sesuai kebijakan perusahaan.</p></div></div>
          <div class="feature"><div class="feature-icon">▣</div><div><h4>Riwayat</h4><p>Lihat riwayat peminjaman dan pengembalian.</p></div></div>
          <div class="feature"><div class="feature-icon">♧</div><div><h4>Notifikasi</h4><p>Dapatkan update melalui email atau sistem.</p></div></div>
          <div class="feature"><div class="feature-icon">◷</div><div><h4>Monitoring Ketersediaan</h4><p>Cek ketersediaan alat secara langsung dan real-time.</p></div></div>
        </div>
      </div>

      <div class="mockup" aria-label="Preview dashboard">
        <div class="screen">
          <img src="assets/alatku-hero-assets.png" alt="Preview dashboard ALATKU" style="height:260px;object-fit:cover;object-position:center top;">
        </div>
        <div class="phone">
          <div class="fake-phone">
            <div class="fake-line" style="width:55%"></div>
            <div class="fake-line" style="width:82%"></div>
            <div class="fake-card"></div>
            <div class="fake-card"></div>
            <div class="fake-card"></div>
            <div class="fake-card"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="tentang" style="padding-top:0">
    <div class="container">
      <div class="cta">
        <div class="cta-copy">
          <div class="cta-icon">⚒</div>
          <div><small>SIAP MEMULAI?</small><h3>Butuh alat untuk pekerjaanmu?</h3><p>Ajukan peminjaman sekarang dan dapatkan kemudahan dalam setiap prosesnya.</p></div>
        </div>
        <a href="#alat" class="btn btn-yellow">Mulai Peminjaman &nbsp; →</a>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a class="brand" href="#"><div class="brand-mark">⚒</div><div><div class="brand-name">ALAT<span>KU</span></div><small>Sistem Peminjaman Alat</small></div></a>
        <p>Kelola alat, tingkatkan produktivitas.</p>
      </div>
      <div><h4>Navigasi</h4><a href="#beranda">Beranda</a><br><a href="#cara-kerja">Cara Kerja</a><br><a href="#fitur">Fitur</a><br><a href="#tentang">Tentang</a></div>
      <div><h4>Kontak</h4><p>✉ &nbsp; info@alatku.id<br>☎ &nbsp; +62 812 3456 7890<br>⌖ &nbsp; Jl. Industri No. 10, Bandung</p></div>
      <div><h4>Kelola Alat, Tingkatkan Produktivitas</h4><p>● &nbsp; &nbsp;◎ &nbsp; &nbsp;▶</p></div>
    </div>
    <div class="copyright"><span>© 2025 ALATKU. Semua hak dilindungi.</span><span>Sistem Peminjaman Alat</span></div>
  </div>
</footer>

<script>
  document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', e=>{
      const id=a.getAttribute('href');
      if(id && id!=="#"){
        const el=document.querySelector(id);
        if(el){e.preventDefault();el.scrollIntoView({behavior:'smooth',block:'start'});}
      }
    });
  });

  const sections=[...document.querySelectorAll('main section[id]')];
  const nav=[...document.querySelectorAll('.navlinks a')];
  const io=new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
      if(entry.isIntersecting){
        nav.forEach(n=>n.classList.remove('active'));
        const n=document.querySelector(`.navlinks a[href="#${entry.target.id}"]`);
        if(n)n.classList.add('active');
      }
    });
  },{rootMargin:"-45% 0px -45% 0px"});
  sections.forEach(s=>io.observe(s));
</script>
</body>
</html>
