<?php
// Update edit.php
$fileEdit = 'app/views/tools/edit.php';
$contentEdit = file_get_contents($fileEdit);

// Add enctype
$contentEdit = str_replace(
    '<form action="<?= URLROOT; ?>/tool/update" method="POST">',
    '<form action="<?= URLROOT; ?>/tool/update" method="POST" enctype="multipart/form-data">',
    $contentEdit
);

// Add image input right before the description textarea
$imageInputEdit = '
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">Gambar Alat <span class="text-muted small">(Opsional)</span></label>
                            <?php if(!empty($data[\'image\'])): ?>
                                <div class="mb-2">
                                    <img src="<?= URLROOT; ?>/assets/img/tools/<?= $data[\'image\']; ?>" alt="Preview" style="max-height: 100px; border-radius: 8px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" id="image" class="form-control <?= !empty($data[\'image_err\']) ? \'is-invalid\' : \'\'; ?>" accept="image/*">
                            <div class="invalid-feedback"><?= $data[\'image_err\'] ?? \'\'; ?></div>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Max 2MB (JPG, PNG, WEBP).</small>
                        </div>
                    </div>
';
$contentEdit = str_replace(
    '<div class="mb-3">
                        <label for="description"',
    $imageInputEdit . '
                    <div class="mb-3">
                        <label for="description"',
    $contentEdit
);
file_put_contents($fileEdit, $contentEdit);


// Update create.php
$fileCreate = 'app/views/tools/create.php';
$contentCreate = file_get_contents($fileCreate);

$contentCreate = str_replace(
    '<form action="<?= URLROOT; ?>/tool/store" method="POST">',
    '<form action="<?= URLROOT; ?>/tool/store" method="POST" enctype="multipart/form-data">',
    $contentCreate
);

$imageInputCreate = '
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">Gambar Alat <span class="text-muted small">(Opsional)</span></label>
                            <input type="file" name="image" id="image" class="form-control <?= !empty($data[\'image_err\']) ? \'is-invalid\' : \'\'; ?>" accept="image/*">
                            <div class="invalid-feedback"><?= $data[\'image_err\'] ?? \'\'; ?></div>
                            <small class="text-muted">Max 2MB (JPG, PNG, WEBP).</small>
                        </div>
                    </div>
';
$contentCreate = str_replace(
    '<div class="mb-3">
                        <label for="description"',
    $imageInputCreate . '
                    <div class="mb-3">
                        <label for="description"',
    $contentCreate
);
file_put_contents($fileCreate, $contentCreate);

echo "Forms updated.\n";
