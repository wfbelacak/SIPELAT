<?php $this->view('layouts/header', $data); ?>

<?php
$keyword = $data['keyword'] ?? '';
$isSearching = $keyword !== '';
?>

<div class="section-header d-flex justify-content-between align-items-center">
    <div>
        <h3>Daftar Alat</h3>
        <?php if($isSearching): ?>
        <p class="text-muted mb-0" style="font-size:0.875rem;">
            Menampilkan <strong><?= count($data['tools']) ?></strong> hasil untuk kata kunci
            "<strong><?= htmlspecialchars($keyword) ?></strong>"
            — <a href="<?= URLROOT; ?>/tool" class="text-primary text-decoration-none">Tampilkan semua</a>
        </p>
        <?php endif; ?>
    </div>
    <?php if(hasRole([1,2])): ?>
        <a href="<?= URLROOT; ?>/tool/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Alat</a>
    <?php endif; ?>
</div>

<div class="filter-bar">
    <form method="GET" action="<?= URLROOT; ?>/tool" class="search-box" style="display:flex; align-items:center; flex:1;">
        <i class="bi bi-search"></i>
        <input id="toolSearch" name="search" type="search"
               placeholder="Cari nama alat, kode, atau kategori..."
               value="<?= htmlspecialchars($keyword) ?>"
               autocomplete="off">
    </form>

    <select class="filter-select" id="categoryFilter">
        <option value="">Semua Kategori</option>
        <?php
        $cats = [];
        foreach($data['tools'] as $t) {
            if(!empty($t->category_name)) $cats[$t->category_name] = 1;
        }
        foreach(array_keys($cats) as $cat): ?>
            <option value="<?= htmlspecialchars(strtolower($cat)); ?>"><?= htmlspecialchars($cat); ?></option>
        <?php endforeach; ?>
    </select>

    <select class="filter-select" id="statusFilter">
        <option value="">Semua Status</option>
        <option value="tersedia">Tersedia</option>
        <option value="maintenance">Maintenance</option>
        <option value="dipinjam">Dipinjam</option>
    </select>

    <button class="btn-filter" type="button" id="filterButton">
        <i class="bi bi-funnel"></i> Filter
    </button>
</div>

<section class="tool-grid" id="catalogGrid">
<?php if(!empty($data['tools'])): foreach($data['tools'] as $tool):
    $status = strtolower(str_replace('_',' ',$tool->status));
    $cat    = strtolower($tool->category_name ?? '');
?>
    <article class="tool-card"
             data-name="<?= htmlspecialchars(strtolower($tool->name)); ?>"
             data-code="<?= htmlspecialchars(strtolower($tool->code)); ?>"
             data-category="<?= htmlspecialchars($cat); ?>"
             data-status="<?= htmlspecialchars($status); ?>">
        <div class="tool-img-wrap">
            <img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>" alt="<?= htmlspecialchars($tool->name); ?>">
        </div>
        <div class="tool-info">
            <h4 class="tool-title"><?= htmlspecialchars($tool->name); ?></h4>
            <div class="tool-code">Kode : <?= htmlspecialchars($tool->code); ?></div>
            <div class="tool-cat">@<?= htmlspecialchars($tool->category_name ?? 'Umum'); ?></div>

            <div class="mt-2 mb-2">
            <?php if($tool->available_qty > 0 && $tool->status === 'TERSEDIA'): ?>
                <span class="badge-status tersedia">Tersedia</span>
            <?php elseif($tool->status === 'MAINTENANCE'): ?>
                <span class="badge-status maintenance">Maintenance</span>
            <?php else: ?>
                <span class="badge-status dipinjam"><?= htmlspecialchars(ucwords(strtolower(str_replace('_',' ',$tool->status)))); ?></span>
            <?php endif; ?>
            </div>

            <div class="tool-stock <?= $tool->available_qty <= 0 ? 'text-danger' : ''; ?>">
                Stok : <?= (int)$tool->available_qty; ?> unit
            </div>

            <?php if(hasRole([1,2])): ?>
                <div class="mt-auto d-flex gap-2">
                    <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn-outline-primary flex-grow-1 text-center" style="padding:0.4rem;">Detail</a>
                    <a href="<?= URLROOT; ?>/tool/edit/<?= $tool->id; ?>" class="btn btn-warning btn-sm text-white" style="display:flex;align-items:center;padding:0.4rem;"><i class="bi bi-pencil-square"></i></a>
                    <form action="<?= URLROOT; ?>/tool/delete/<?= $tool->id; ?>" method="POST" class="m-0" onsubmit="return confirm('Yakin hapus alat ini?');">
                        <button type="submit" class="btn btn-danger btn-sm h-100" style="padding:0.4rem;"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            <?php else: ?>
                <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn-outline-primary mt-auto text-center" style="padding:0.4rem;">Detail</a>
            <?php endif; ?>
        </div>
    </article>
<?php endforeach; else: ?>
    <div style="grid-column:1/-1; text-align:center; padding: 60px 20px; color: var(--text-muted);">
        <?php if($isSearching): ?>
            <i class="bi bi-search" style="font-size: 2.5rem; opacity: 0.3; display:block; margin-bottom:12px;"></i>
            Tidak ada alat yang cocok dengan "<strong><?= htmlspecialchars($keyword) ?></strong>".
            <br><a href="<?= URLROOT; ?>/tool" class="btn btn-outline-primary btn-sm mt-3">Lihat Semua Alat</a>
        <?php else: ?>
            Belum ada data alat.
        <?php endif; ?>
    </div>
<?php endif; ?>
</section>

<script>
// Client-side filter for Category & Status dropdowns (after server search)
document.addEventListener('DOMContentLoaded', () => {
    const c  = document.getElementById('categoryFilter'),
          st = document.getElementById('statusFilter'),
          cards = [...document.querySelectorAll('.tool-card')];

    function run() {
        const cat    = c.value;
        const status = st.value;
        let visible = 0;
        cards.forEach(x => {
            const ok = (!cat || x.dataset.category === cat) &&
                       (!status || x.dataset.status === status);
            x.style.display = ok ? 'flex' : 'none';
            if(ok) visible++;
        });
    }

    [c, st].forEach(x => x.addEventListener('change', run));
    document.getElementById('filterButton').addEventListener('click', run);
});
</script>

<?php $this->view('layouts/footer'); ?>