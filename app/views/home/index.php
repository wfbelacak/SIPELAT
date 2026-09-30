<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="ALATKU - Sistem Peminjaman Alat" />
  <title><?= $data['title'] ?? 'Dashboard'; ?> — <?= SITENAME; ?></title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  
  <!-- Custom CSS Terpadu -->
  <link href="<?= URLROOT; ?>/assets/css/landing.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body>

<header class="topbar">
  <div class="container nav">
    <a href="<?= URLROOT; ?>" class="brand">
      <img src="<?= URLROOT; ?>/assets/img/logo-white-lengkap.png" alt="SiPeminjam" class="brand-logo">
    </a>

    <nav class="navlinks">
      <a href="#beranda" class="active">Beranda</a>
      <a href="#cara-kerja">Cara Kerja</a>
      <a href="#fitur">Fitur</a>
      <a href="#tentang">Tentang</a>
    </nav>

    <!-- Kumpulan Tombol Auth -->
    <div class="auth-buttons">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a class="login" href="<?= URLROOT; ?>/dashboard"><i class="fa-solid fa-gauge"></i> &nbsp; Dashboard</a>
        <a href="<?= URLROOT; ?>/auth/logout" class="btn btn-yellow btn-compact"><i class="fa-solid fa-right-from-bracket"></i> &nbsp; Keluar</a>
      <?php else: ?>
        <a href="<?= URLROOT; ?>/auth" class="btn-link-login">Masuk</a>
        <a class="login" href="<?= URLROOT; ?>/auth"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
      <?php endif; ?>
    </div>

    <button class="mobile-menu" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
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
        <a href="<?= URLROOT; ?>/borrowing/create" class="btn btn-yellow"><i class="fa-solid fa-file-pen"></i> &nbsp; Ajukan Peminjaman &nbsp; <i class="fa-solid fa-arrow-right"></i></a>
        <a href="<?= URLROOT; ?>/tool" class="btn btn-outline">Lihat Alat &nbsp; <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </section>

  <section class="stats-wrap">
    <div class="container stats">
      <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div><strong><?= $data['total_alat'] ?? 48; ?></strong><b>Total Alat</b><small>Semua alat yang tersedia di sistem</small></div>
      </div>
      <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div><strong><?= $data['alat_tersedia'] ?? 32; ?></strong><b>Alat Tersedia</b><small>Siap digunakan kapan saja</small></div>
      </div>
      <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div><strong><?= $data['sedang_dipinjam'] ?? 12; ?></strong><b>Sedang Dipinjam</b><small>Dalam proses peminjaman</small></div>
      </div>
      <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-handshake"></i></div>
        <div><strong><?= $data['total_peminjaman_global'] ?? 86; ?></strong><b>Total Peminjaman</b><small>Sejak sistem digunakan</small></div>
      </div>
    </div>
  </section>

  <section id="alat">
    <div class="container">
      <div class="section-head">
        <div><div class="section-label">Alat Populer</div><h2 class="section-title">Alat yang Sering Dipinjam</h2></div>
        <a class="see-all" href="<?= URLROOT; ?>/tool">Lihat Semua Alat <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="tools">
        <?php if(!empty($data['popular_tools'])): foreach($data['popular_tools'] as $tool):$isTersedia = ($tool->status == 'TERSEDIA' &&$tool->available_qty > 0);
            $badgeText =$isTersedia ? 'Tersedia' : 'Dipinjam';
            $badgeClass =$isTersedia ? '' : 'red';
        ?>
          <article class="tool">
            <div class="tool-img">
              <span class="badge <?= $badgeClass; ?>"><?= $badgeText; ?></span>
              <img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>" alt="<?= htmlspecialchars($tool->name); ?>">
            </div>
            <div class="tool-body">
              <h3><?= htmlspecialchars($tool->name); ?></h3>
              <p><i class="fa-solid fa-wrench"></i> &nbsp; <?= htmlspecialchars($tool->category_name ?? 'Umum'); ?></p>
              <div class="stock"><i class="fa-solid fa-circle-info"></i> &nbsp; Tersedia <?= (int)$tool->available_qty; ?> dari <?= (int)$tool->quantity; ?></div>
              <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="detail">Detail &nbsp; <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </article>
        <?php endforeach; else: ?>
          <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Bor Listrik</h3><p><i class="fa-solid fa-wrench"></i> &nbsp; Perkakas</p><div class="stock"><i class="fa-solid fa-circle-info"></i> &nbsp; Tersedia 5 dari 5</div><a href="<?= URLROOT; ?>/tool" class="detail">Detail &nbsp; <i class="fa-solid fa-arrow-right"></i></a></div></article>
          <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Tangga Aluminium</h3><p><i class="fa-solid fa-shield-halved"></i> &nbsp; Keselamatan Kerja</p><div class="stock"><i class="fa-solid fa-circle-info"></i> &nbsp; Tersedia 3 dari 3</div><a href="<?= URLROOT; ?>/tool" class="detail">Detail &nbsp; <i class="fa-solid fa-arrow-right"></i></a></div></article>
          <article class="tool"><div class="tool-img"><span class="badge red">Dipinjam</span></div><div class="tool-body"><h3>Mesin Las</h3><p><i class="fa-solid fa-gear"></i> &nbsp; Mesin & Alat Berat</p><div class="stock"><i class="fa-solid fa-circle-info"></i> &nbsp; Tersedia 0 dari 2</div><a href="<?= URLROOT; ?>/tool" class="detail">Detail &nbsp; <i class="fa-solid fa-arrow-right"></i></a></div></article>
          <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Gerinda Tangan</h3><p><i class="fa-solid fa-wrench"></i> &nbsp; Perkakas</p><div class="stock"><i class="fa-solid fa-circle-info"></i> &nbsp; Tersedia 4 dari 4</div><a href="<?= URLROOT; ?>/tool" class="detail">Detail &nbsp; <i class="fa-solid fa-arrow-right"></i></a></div></article>
          <article class="tool"><div class="tool-img"><span class="badge">Tersedia</span></div><div class="tool-body"><h3>Toolbox Set</h3><p><i class="fa-solid fa-toolbox"></i> &nbsp; Perkakas</p><div class="stock"><i class="fa-solid fa-circle-info"></i> &nbsp; Tersedia 6 dari 6</div><a href="<?= URLROOT; ?>/tool" class="detail">Detail &nbsp; <i class="fa-solid fa-arrow-right"></i></a></div></article>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section id="cara-kerja" class="steps-section">
    <div class="container steps-layout">
      <div>
        <div class="section-label">Cara Kerja</div>
        <h2 class="section-title">Mudah dalam 5 Langkah</h2>
        <p>Proses peminjaman alat di sistem kami sangat sederhana dan tidak memakan waktu lama.</p>
        <a href="<?= URLROOT; ?>/borrowing/create" class="btn btn-yellow">Mulai Peminjaman &nbsp; <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="steps">
        <div class="step"><div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div><div class="step-num">1</div><h4>Pilih Alat</h4><p>Cari alat yang Anda butuhkan di katalog.</p></div>
        <div class="step"><div class="step-icon"><i class="fa-solid fa-file-signature"></i></div><div class="step-num">2</div><h4>Ajukan Peminjaman</h4><p>Isi form peminjaman dengan detail yang jelas.</p></div>
        <div class="step"><div class="step-icon"><i class="fa-solid fa-user-check"></i></div><div class="step-num">3</div><h4>Tunggu Persetujuan</h4><p>Tim akan memverifikasi permintaan Anda.</p></div>
        <div class="step"><div class="step-icon"><i class="fa-solid fa-hand-holding"></i></div><div class="step-num">4</div><h4>Ambil Alat</h4><p>Setelah disetujui, ambil alat sesuai jadwal.</p></div>
        <div class="step"><div class="step-icon"><i class="fa-solid fa-rotate-left"></i></div><div class="step-num">5</div><h4>Kembalikan</h4><p>Setelah selesai digunakan, segera kembalikan alat.</p></div>
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
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-boxes-packing"></i></div><div><h4>Manajemen Alat</h4><p>Kelola data alat, kategori, dan stok dengan mudah.</p></div></div>
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-handshake-angle"></i></div><div><h4>Peminjaman</h4><p>Ajukan peminjaman dengan proses yang cepat.</p></div></div>
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-arrow-rotate-left"></i></div><div><h4>Pengembalian</h4><p>Pantau pengembalian alat secara real-time.</p></div></div>
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-square-check"></i></div><div><h4>Persetujuan</h4><p>Atur alur persetujuan sesuai kebijakan perusahaan.</p></div></div>
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-clock-rotate-left"></i></div><div><h4>Riwayat</h4><p>Lihat riwayat peminjaman dan pengembalian.</p></div></div>
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-bell"></i></div><div><h4>Notifikasi</h4><p>Dapatkan update melalui email atau sistem.</p></div></div>
          <div class="feature"><div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div><div><h4>Monitoring Ketersediaan</h4><p>Cek ketersediaan alat secara langsung dan real-time.</p></div></div>
        </div>
      </div>

      <!-- Wrapper Mockup -->
      <div class="mockup" aria-label="Preview dashboard">
        <div class="screen">
          <img src="<?= URLROOT; ?>/assets/img/apps/app-mockup.png" alt="Preview dashboard Sipeminjam" onerror="this.src='<?= URLROOT; ?>/assets/img/hero-mockup.png'">
        </div>
        <div class="phone">
          <div class="fake-phone">
            <img src="<?= URLROOT; ?>/assets/img/apps/app-mockup-mobile.png" alt="Preview dashboard mobile sipeminjam" onerror="this.src='<?= URLROOT; ?>/assets/img/hero-mockup.png'">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="tentang">
    <div class="container">
      <div class="cta">
        <div class="cta-copy">
          <div class="cta-icon"><i class="fa-solid fa-toolbox"></i></div>
          <div><small>SIAP MEMULAI?</small><h3>Butuh alat untuk pekerjaanmu?</h3><p>Ajukan peminjaman sekarang dan dapatkan kemudahan dalam setiap prosesnya.</p></div>
        </div>
        <a href="<?= URLROOT; ?>/borrowing/create" class="btn btn-yellow">Mulai Peminjaman &nbsp; <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a class="brand" href="<?= URLROOT; ?>"><div class="brand-mark"><i class="fa-solid fa-wrench"></i></div><div><div class="brand-name">ALAT<span>KU</span></div><small>Sistem Peminjaman Alat</small></div></a>
        <p>Kelola alat, tingkatkan produktivitas.</p>
      </div>
      <div><h4>Navigasi</h4><a href="#beranda">Beranda</a><br><a href="#cara-kerja">Cara Kerja</a><br><a href="#fitur">Fitur</a><br><a href="#tentang">Tentang</a></div>
      <div>
        <h4>Kontak</h4>
        <p>
          <i class="fa-solid fa-envelope"></i> &nbsp; info@alatku.id<br>
          <i class="fa-solid fa-phone"></i> &nbsp; +62 812 3456 7890<br>
          <i class="fa-solid fa-location-dot"></i> &nbsp; Jl. Industri No. 10, Bandung
        </p>
      </div>
      <div>
        <h4>Ikuti Kami</h4>
        <p class="social-links">
          <a href="#"><i class="fa-brands fa-facebook"></i></a>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin"></i></a>
        </p>
      </div>
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