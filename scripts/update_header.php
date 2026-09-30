<?php
$file = 'app/views/layouts/header.php';
$content = file_get_contents($file);

// Replace avatar string logic
$avatarLogic = '<?php $userAvatarUrl = !empty($_SESSION[\'user_avatar\']) ? URLROOT . \'/assets/img/avatars/\' . $_SESSION[\'user_avatar\'] : \'https://ui-avatars.com/api/?name=\' . urlencode($_SESSION[\'user_name\']) . \'&background=1264E8&color=fff\'; ?>';

// Admin header replacement
$adminOrig = '<div class="d-flex align-items-center gap-3 border-start ps-4">
                    <div class="d-none d-sm-flex flex-column text-end">
                        <span class="fw-bold text-navy" style="font-size: 0.9rem; line-height: 1.2;"><?= $_SESSION[\'user_name\']; ?></span>
                        <span class="text-muted" style="font-size: 0.75rem;"><?= getRoleName($_SESSION[\'user_role\']); ?></span>
                    </div>
                    <img src="<?= URLROOT; ?>/assets/img/avatar.jpg" class="rounded-circle shadow-sm" width="40" height="40" alt="Avatar" onerror="this.src=\'https://ui-avatars.com/api/?name=<?= urlencode($_SESSION[\'user_name\']); ?>&background=1264E8&color=fff\'">
                    <i class="bi bi-chevron-down text-muted small ms-1"></i>
                </div>';

$adminNew = $avatarLogic . '
                <div class="dropdown">
                    <div class="d-flex align-items-center gap-3 border-start ps-4" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <div class="d-none d-sm-flex flex-column text-end">
                            <span class="fw-bold text-navy" style="font-size: 0.9rem; line-height: 1.2;"><?= $_SESSION[\'user_name\']; ?></span>
                            <span class="text-muted" style="font-size: 0.75rem;"><?= getRoleName($_SESSION[\'user_role\']); ?></span>
                        </div>
                        <img src="<?= $userAvatarUrl; ?>" class="rounded-circle shadow-sm" width="40" height="40" alt="Avatar" style="object-fit: cover;">
                        <i class="bi bi-chevron-down text-muted small ms-1"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="<?= URLROOT; ?>/profile"><i class="bi bi-person me-2 text-muted"></i>Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="<?= URLROOT; ?>/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </div>';

$content = str_replace($adminOrig, $adminNew, $content);

// Peminjam header replacement
$peminjamOrig = '<ul class="dropdown-menu dropdown-menu-end design-dropdown account-menu">
                    <li><a class="dropdown-item" href="<?= URLROOT; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i>Keluar</a></li>
                </ul>';

$peminjamNew = '<ul class="dropdown-menu dropdown-menu-end design-dropdown account-menu">
                    <li><a class="dropdown-item" href="<?= URLROOT; ?>/profile"><i class="bi bi-person"></i>Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= URLROOT; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i>Keluar</a></li>
                </ul>';

$content = str_replace($peminjamOrig, $peminjamNew, $content);

// Update peminjam avatar image if exists
$content = preg_replace('/<img src="[^"]+avatar.jpg"[^>]+class="account-avatar"[^>]*>/', 
    '<img src="<?= $userAvatarUrl; ?>" class="account-avatar" alt="Avatar" style="object-fit: cover;">', $content);

// Ensure the avatar logic is injected at the top of the Peminjam layout if not there
$content = str_replace('<div class="peminjam-account">', $avatarLogic . '<div class="peminjam-account">', $content);

file_put_contents($file, $content);
echo "Header updated.";
