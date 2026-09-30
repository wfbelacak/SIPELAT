<?php $this->view('layouts/header', $data); ?>

<div class="stepper-container">
    <div class="step-item active">
        <div class="step-circle">1</div>
        Pilih Alat
    </div>
    <div class="step-line"></div>
    <div class="step-item">
        <div class="step-circle" style="background:var(--border); color:var(--text-muted);">2</div>
        Detail Peminjaman
    </div>
    <div class="step-line"></div>
    <div class="step-item">
        <div class="step-circle" style="background:var(--border); color:var(--text-muted);">3</div>
        Konfirmasi
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Ajukan Peminjaman - Pilih Alat</h3>
</div>

<form action="<?= URLROOT; ?>/borrowing/store" method="POST" id="borrowingForm">
    <input type="hidden" name="borrow_date" id="borrow_date" value="<?= htmlspecialchars($data['borrow_date']); ?>">
    <input type="hidden" name="due_date" id="due_date" value="<?= htmlspecialchars($data['due_date']); ?>">
    <input type="hidden" name="purpose" id="purpose" value="<?= htmlspecialchars($data['purpose']); ?>">
    <input type="hidden" name="notes" id="notes" value="<?= htmlspecialchars($data['notes']); ?>">
    
    <div class="wizard-grid">
        <div class="wizard-panel">
            <div class="wizard-panel-header">
                <div class="d-flex align-items-center gap-3 w-100">
                    <div class="search-box" style="flex:1;">
                        <i class="bi bi-search"></i>
                        <input type="text" id="wizardSearch" placeholder="Cari nama alat...">
                    </div>
                    <select class="filter-select" style="min-width: 150px;" id="catFilter">
                        <option value="">Semua Kategori</option>
                        <?php 
                        $cats=[]; 
                        if(!empty($data['tools'])) {
                            foreach($data['tools'] as $t) { 
                                if(!empty($t->category_name)) $cats[$t->category_name] = 1; 
                            } 
                        }
                        foreach(array_keys($cats) as $cat): 
                        ?>
                            <option value="<?= htmlspecialchars(strtolower($cat)); ?>"><?= htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <table class="wizard-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">Pilih</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok Tersedia</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($data['tools'])): foreach($data['tools'] as $tool): if($tool->available_qty > 0 && $tool->status === 'TERSEDIA'): ?>
                    <tr data-search="<?= htmlspecialchars(strtolower($tool->name.' '.$tool->code.' '.($tool->category_name??''))); ?>" data-cat="<?= htmlspecialchars(strtolower($tool->category_name??'')); ?>">
                        <td style="text-align: center;">
                            <input type="checkbox" class="wizard-checkbox tool-check" data-id="<?= $tool->id; ?>" data-name="<?= htmlspecialchars($tool->name); ?>" data-stock="<?= (int)$tool->available_qty; ?>" data-image="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>">
                        </td>
                        <td class="fw-medium"><?= htmlspecialchars($tool->name); ?></td>
                        <td><?= htmlspecialchars($tool->category_name ?? 'Umum'); ?></td>
                        <td><?= (int)$tool->available_qty; ?></td>
                    </tr>
                <?php endif; endforeach; else: ?>
                    <tr><td colspan="4" class="text-center text-muted">Tidak ada alat tersedia.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="wizard-panel d-flex flex-column" style="background: var(--bg-body);">
            <div class="wizard-panel-header" style="background: var(--white);">
                <h4>Alat yang Dipilih</h4>
                <span id="selectedCount" class="badge bg-primary rounded-pill">0</span>
            </div>
            
            <div id="selectedList" style="flex:1; overflow-y: auto; background: var(--white);">
                <div class="text-center text-muted p-5" id="emptyState">
                    Pilih alat dari daftar di sebelah kiri.
                </div>
            </div>
            
            <div class="wizard-footer" style="background: var(--white);">
                <button type="button" class="btn-primary-solid w-100" id="continueBtn">
                    Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal untuk Detail Peminjaman (Tahap 2) -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
          <div class="modal-header border-bottom">
            <h5 class="modal-title fw-bold">Detail Peminjaman</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
              <div class="row g-3">
                  <div class="col-6">
                      <label class="form-label fw-semibold">Tanggal Pinjam</label>
                      <input type="date" class="form-control" id="modalBorrowDate" value="<?= htmlspecialchars($data['borrow_date']); ?>">
                  </div>
                  <div class="col-6">
                      <label class="form-label fw-semibold">Tanggal Kembali</label>
                      <input type="date" class="form-control" id="modalDueDate" value="<?= htmlspecialchars($data['due_date']); ?>">
                  </div>
                  <div class="col-12">
                      <label class="form-label fw-semibold">Tujuan Peminjaman</label>
                      <textarea class="form-control" id="modalPurpose" rows="3" placeholder="Jelaskan tujuan peminjaman alat"><?= htmlspecialchars($data['purpose']); ?></textarea>
                  </div>
                  <div class="col-12">
                      <label class="form-label fw-semibold">Catatan (Opsional)</label>
                      <textarea class="form-control" id="modalNotes" rows="2" placeholder="Catatan tambahan"><?= htmlspecialchars($data['notes']); ?></textarea>
                  </div>
              </div>
          </div>
          <div class="modal-footer border-top bg-light" style="border-bottom-left-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md);">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Ajukan Peminjaman</button>
          </div>
        </div>
      </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const checks = [...document.querySelectorAll('.tool-check')];
    const list = document.getElementById('selectedList');
    const emptyState = document.getElementById('emptyState');
    const count = document.getElementById('selectedCount');
    const form = document.getElementById('borrowingForm');
    
    function render() {
        const selected = checks.filter(c => c.checked);
        count.textContent = selected.length;
        
        list.innerHTML = '';
        if(selected.length === 0) {
            list.appendChild(emptyState);
            emptyState.style.display = 'block';
            return;
        }
        
        selected.forEach(c => {
            const item = document.createElement('div');
            item.className = 'cart-item';
            item.innerHTML = `
                <div class="cart-item-img"><img src="${c.dataset.image}"></div>
                <div class="cart-item-info">
                    <h5 class="cart-item-title">${c.dataset.name}</h5>
                    <p class="cart-item-stock">Stok: ${c.dataset.stock}</p>
                </div>
                <div class="cart-qty-ctrl">
                    <button type="button" class="minus">-</button>
                    <input type="number" min="1" max="${c.dataset.stock}" value="1" class="qty-val" readonly>
                    <button type="button" class="plus">+</button>
                </div>
            `;
            
            const input = item.querySelector('input');
            item.querySelector('.minus').onclick = () => { input.value = Math.max(1, +input.value - 1); };
            item.querySelector('.plus').onclick = () => { input.value = Math.min(+input.max, +input.value + 1); };
            
            list.appendChild(item);
        });
    }
    
    checks.forEach(c => c.addEventListener('change', render));
    
    const searchInput = document.getElementById('wizardSearch');
    const catSelect = document.getElementById('catFilter');
    
    function filterTable() {
        const q = searchInput.value.toLowerCase();
        const cat = catSelect.value;
        
        document.querySelectorAll('.wizard-table tbody tr[data-search]').forEach(r => {
            const matchSearch = r.dataset.search.includes(q);
            const matchCat = cat === '' || r.dataset.cat === cat;
            r.style.display = matchSearch && matchCat ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    catSelect.addEventListener('change', filterTable);
    
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    
    document.getElementById('continueBtn').onclick = () => {
        if(!checks.some(c => c.checked)) {
            alert('Pilih minimal 1 alat terlebih dahulu.');
            return;
        }
        detailModal.show();
    };
    
    form.addEventListener('submit', (e) => {
        document.querySelectorAll('.dynamic-tool-field').forEach(x => x.remove());
        const selectedChecks = checks.filter(c => c.checked);
        
        selectedChecks.forEach((c, i) => {
            const cartItems = document.querySelectorAll('.cart-item');
            const qty = cartItems[i] ? cartItems[i].querySelector('.qty-val').value : 1;
            
            const a = document.createElement('input');
            a.type = 'hidden'; a.name = 'tool_id[]'; a.value = c.dataset.id; a.className = 'dynamic-tool-field';
            
            const b = document.createElement('input');
            b.type = 'hidden'; b.name = 'qty[]'; b.value = qty; b.className = 'dynamic-tool-field';
            
            form.append(a, b);
        });
        
        document.getElementById('borrow_date').value = document.getElementById('modalBorrowDate').value;
        document.getElementById('due_date').value = document.getElementById('modalDueDate').value;
        document.getElementById('purpose').value = document.getElementById('modalPurpose').value;
        document.getElementById('notes').value = document.getElementById('modalNotes').value;
        
        if(!document.getElementById('purpose').value.trim()) {
            e.preventDefault();
            alert('Tujuan peminjaman wajib diisi.');
        }
    });
});
</script>

<?php $this->view('layouts/footer'); ?>