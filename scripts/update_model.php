<?php
$file = 'app/models/Tool.php';
$content = file_get_contents($file);

// Update addTool
$addToolQueryOrig = 'INSERT INTO tools (category_id, code, name, description, quantity, available_qty, `condition`, location, status) VALUES (:category_id, :code, :name, :description, :quantity, :available_qty, :condition, :location, :status)';
$addToolQueryNew = 'INSERT INTO tools (category_id, code, name, description, quantity, available_qty, `condition`, location, image, status) VALUES (:category_id, :code, :name, :description, :quantity, :available_qty, :condition, :location, :image, :status)';

$content = str_replace($addToolQueryOrig, $addToolQueryNew, $content);
$content = str_replace(
    "\$this->db->bind(':location', \$data['location']);",
    "\$this->db->bind(':location', \$data['location']);\n        \$this->db->bind(':image', \$data['image'] ?? null);",
    $content
);

// Update updateTool
$updateToolQueryOrig = 'UPDATE tools SET category_id = :category_id, code = :code, name = :name, description = :description, quantity = :quantity, `condition` = :condition, location = :location, status = :status WHERE id = :id';
$updateToolQueryNew = 'UPDATE tools SET category_id = :category_id, code = :code, name = :name, description = :description, quantity = :quantity, `condition` = :condition, location = :location, image = :image, status = :status WHERE id = :id';

$content = str_replace($updateToolQueryOrig, $updateToolQueryNew, $content);
$content = str_replace(
    "\$this->db->bind(':status', \$data['status']);\n\n        return \$this->db->execute();",
    "\$this->db->bind(':status', \$data['status']);\n        \$this->db->bind(':image', \$data['image'] ?? null);\n\n        return \$this->db->execute();",
    $content
);

file_put_contents($file, $content);
echo "Tool.php updated.\n";
