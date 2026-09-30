<?php
$file = 'app/controllers/ToolController.php';
$content = file_get_contents($file);

function extractMethodBody($content, $methodName) {
    $pattern = '/public function ' . $methodName . '\s*\([^)]*\)\s*\{((?:[^{}]++|(?R))*)\}/s';
    preg_match($pattern, $content, $matches);
    return $matches[0] ?? null;
}

$oldStore = extractMethodBody($content, 'store');
$oldUpdate = extractMethodBody($content, 'update');
$oldEdit = extractMethodBody($content, 'edit');
$oldCreate = extractMethodBody($content, 'create');

// New create method
$newCreate = 'public function create() {
        AuthMiddleware::isPetugas();
        $categories = $this->categoryModel->getCategories();

        $data = [
            \'title\'          => \'Tambah Alat\',
            \'categories\'     => $categories,
            \'category_id\'    => \'\',
            \'code\'           => \'\',
            \'name\'           => \'\',
            \'description\'    => \'\',
            \'quantity\'       => \'\',
            \'condition\'      => \'BAIK\',
            \'location\'       => \'\',
            \'status\'         => \'TERSEDIA\',
            \'image\'          => \'\',
            \'code_err\'       => \'\',
            \'name_err\'       => \'\',
            \'quantity_err\'   => \'\',
            \'category_err\'   => \'\',
            \'image_err\'      => \'\'
        ];

        $this->view(\'tools/create\', $data);
    }';

// New store method
$newStore = 'public function store() {
        AuthMiddleware::isPetugas();

        if ($_SERVER[\'REQUEST_METHOD\'] !== \'POST\') {
            $this->redirect(\'tool\');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $categories = $this->categoryModel->getCategories();

        $data = [
            \'title\'          => \'Tambah Alat\',
            \'categories\'     => $categories,
            \'category_id\'    => trim($_POST[\'category_id\']),
            \'code\'           => trim($_POST[\'code\']),
            \'name\'           => trim($_POST[\'name\']),
            \'description\'    => trim($_POST[\'description\']),
            \'quantity\'       => trim($_POST[\'quantity\']),
            \'condition\'      => trim($_POST[\'condition\']),
            \'location\'       => trim($_POST[\'location\']),
            \'status\'         => trim($_POST[\'status\']),
            \'image\'          => \'\',
            \'code_err\'       => \'\',
            \'name_err\'       => \'\',
            \'quantity_err\'   => \'\',
            \'category_err\'   => \'\',
            \'image_err\'      => \'\'
        ];

        // Validasi
        if (empty($data[\'code\'])) {
            $data[\'code_err\'] = \'Kode alat wajib diisi\';
        } elseif ($this->toolModel->getToolByCode($data[\'code\'])) {
            $data[\'code_err\'] = \'Kode alat sudah digunakan\';
        }

        if (empty($data[\'name\'])) {
            $data[\'name_err\'] = \'Nama alat wajib diisi\';
        }

        if (empty($data[\'quantity\']) || $data[\'quantity\'] < 1) {
            $data[\'quantity_err\'] = \'Jumlah minimal 1\';
        }

        if (empty($data[\'category_id\'])) {
            $data[\'category_err\'] = \'Kategori wajib dipilih\';
        }

        // Handle Image Upload
        if (isset($_FILES[\'image\']) && $_FILES[\'image\'][\'error\'] === UPLOAD_ERR_OK) {
            $allowedExts = [\'jpg\', \'jpeg\', \'png\', \'webp\'];
            $fileName = $_FILES[\'image\'][\'name\'];
            $fileSize = $_FILES[\'image\'][\'size\'];
            $fileTmp = $_FILES[\'image\'][\'tmp_name\'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts)) {
                if ($fileSize < 2000000) { // Max 2MB
                    $newFileName = uniqid() . \'_\' . time() . \'.\' . $fileExt;
                    $uploadDir = APPROOT . \'/../public/assets/img/tools/\';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                        $data[\'image\'] = $newFileName;
                    } else {
                        $data[\'image_err\'] = \'Gagal mengunggah gambar\';
                    }
                } else {
                    $data[\'image_err\'] = \'Ukuran gambar maksimal 2MB\';
                }
            } else {
                $data[\'image_err\'] = \'Format gambar hanya JPG, JPEG, PNG, WEBP\';
            }
        }

        if (!empty($data[\'code_err\']) || !empty($data[\'name_err\']) || 
            !empty($data[\'quantity_err\']) || !empty($data[\'category_err\']) || !empty($data[\'image_err\'])) {
            $this->view(\'tools/create\', $data);
            return;
        }

        if ($this->toolModel->addTool($data)) {
            flash(\'message\', \'Alat berhasil ditambahkan\');
            $this->redirect(\'tool\');
        } else {
            flash(\'message\', \'Gagal menambahkan alat\', \'danger\');
            $this->redirect(\'tool\');
        }
    }';

$newEdit = 'public function edit($id = null) {
        AuthMiddleware::isPetugas();

        if (!$id) {
            $this->redirect(\'tool\');
            return;
        }

        $tool = $this->toolModel->getToolById($id);
        if (!$tool) {
            flash(\'message\', \'Alat tidak ditemukan\', \'danger\');
            $this->redirect(\'tool\');
            return;
        }

        $categories = $this->categoryModel->getCategories();

        $data = [
            \'title\'          => \'Edit Alat\',
            \'categories\'     => $categories,
            \'id\'             => $tool->id,
            \'category_id\'    => $tool->category_id,
            \'code\'           => $tool->code,
            \'name\'           => $tool->name,
            \'description\'    => $tool->description,
            \'quantity\'       => $tool->quantity,
            \'condition\'      => $tool->condition,
            \'location\'       => $tool->location,
            \'status\'         => $tool->status,
            \'image\'          => $tool->image,
            \'code_err\'       => \'\',
            \'name_err\'       => \'\',
            \'quantity_err\'   => \'\',
            \'category_err\'   => \'\',
            \'image_err\'      => \'\'
        ];

        $this->view(\'tools/edit\', $data);
    }';

$newUpdate = 'public function update() {
        AuthMiddleware::isPetugas();

        if ($_SERVER[\'REQUEST_METHOD\'] !== \'POST\') {
            $this->redirect(\'tool\');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $categories = $this->categoryModel->getCategories();
        $tool = $this->toolModel->getToolById($_POST[\'id\']);

        $data = [
            \'title\'          => \'Edit Alat\',
            \'categories\'     => $categories,
            \'id\'             => trim($_POST[\'id\']),
            \'category_id\'    => trim($_POST[\'category_id\']),
            \'code\'           => trim($_POST[\'code\']),
            \'name\'           => trim($_POST[\'name\']),
            \'description\'    => trim($_POST[\'description\']),
            \'quantity\'       => trim($_POST[\'quantity\']),
            \'condition\'      => trim($_POST[\'condition\']),
            \'location\'       => trim($_POST[\'location\']),
            \'status\'         => trim($_POST[\'status\']),
            \'image\'          => $tool->image, // Keep existing by default
            \'code_err\'       => \'\',
            \'name_err\'       => \'\',
            \'quantity_err\'   => \'\',
            \'category_err\'   => \'\',
            \'image_err\'      => \'\'
        ];

        // Validasi
        if (empty($data[\'code\'])) {
            $data[\'code_err\'] = \'Kode alat wajib diisi\';
        } else {
            $existingTool = $this->toolModel->getToolByCode($data[\'code\']);
            if ($existingTool && $existingTool->id != $data[\'id\']) {
                $data[\'code_err\'] = \'Kode alat sudah digunakan\';
            }
        }

        if (empty($data[\'name\'])) {
            $data[\'name_err\'] = \'Nama alat wajib diisi\';
        }

        if (empty($data[\'quantity\']) || $data[\'quantity\'] < 1) {
            $data[\'quantity_err\'] = \'Jumlah minimal 1\';
        }

        if (empty($data[\'category_id\'])) {
            $data[\'category_err\'] = \'Kategori wajib dipilih\';
        }

        // Handle Image Upload
        if (isset($_FILES[\'image\']) && $_FILES[\'image\'][\'error\'] === UPLOAD_ERR_OK) {
            $allowedExts = [\'jpg\', \'jpeg\', \'png\', \'webp\'];
            $fileName = $_FILES[\'image\'][\'name\'];
            $fileSize = $_FILES[\'image\'][\'size\'];
            $fileTmp = $_FILES[\'image\'][\'tmp_name\'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts)) {
                if ($fileSize < 2000000) { // Max 2MB
                    $newFileName = uniqid() . \'_\' . time() . \'.\' . $fileExt;
                    $uploadDir = APPROOT . \'/../public/assets/img/tools/\';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                        $data[\'image\'] = $newFileName;
                        // Optionally delete old image
                        if ($tool->image && file_exists($uploadDir . $tool->image)) {
                            @unlink($uploadDir . $tool->image);
                        }
                    } else {
                        $data[\'image_err\'] = \'Gagal mengunggah gambar\';
                    }
                } else {
                    $data[\'image_err\'] = \'Ukuran gambar maksimal 2MB\';
                }
            } else {
                $data[\'image_err\'] = \'Format gambar hanya JPG, JPEG, PNG, WEBP\';
            }
        }

        if (!empty($data[\'code_err\']) || !empty($data[\'name_err\']) || 
            !empty($data[\'quantity_err\']) || !empty($data[\'category_err\']) || !empty($data[\'image_err\'])) {
            $this->view(\'tools/edit\', $data);
            return;
        }

        if ($this->toolModel->updateTool($data)) {
            flash(\'message\', \'Alat berhasil diperbarui\');
            $this->redirect(\'tool\');
        } else {
            flash(\'message\', \'Gagal memperbarui alat\', \'danger\');
            $this->redirect(\'tool\');
        }
    }';

if ($oldCreate) $content = str_replace($oldCreate, $newCreate, $content);
if ($oldStore) $content = str_replace($oldStore, $newStore, $content);
if ($oldEdit) $content = str_replace($oldEdit, $newEdit, $content);
if ($oldUpdate) $content = str_replace($oldUpdate, $newUpdate, $content);

file_put_contents($file, $content);
echo "ToolController updated.\n";
