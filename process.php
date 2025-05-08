<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    if (!isset($_FILES['wordFile']) || !isset($_POST['names'])) {
        die("File or names not provided.");
    }

    // Direktori untuk upload dan hasil
    $uploadDir = 'uploads/';
    $generatedDir = 'generated/';

    // Buat direktori jika belum ada
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($generatedDir)) mkdir($generatedDir, 0777, true);

    // Upload file Word
    $uploadedFile = $_FILES['wordFile']['tmp_name'];
    $uploadedFileName = $_FILES['wordFile']['name'];
    $targetFile = $uploadDir . basename($uploadedFileName);

    if (!move_uploaded_file($uploadedFile, $targetFile)) {
        die("Failed to upload file.");
    }

    // Pengecekan ekstensi ZipArchive
    if (!class_exists('ZipArchive')) {
        die("Error: ZipArchive class not found. Please enable the ZIP extension in PHP.");
    }

    // Ambil nama-nama dari input
    $names = explode(',', trim($_POST['names']));
    $names = array_map('trim', $names); // Hilangkan spasi berlebih

    // Proses dokumen untuk setiap nama
    foreach ($names as $name) {
        // Sanitasi nama file output
        $safeName = preg_replace('/[^A-Za-z0-9_\- ]/', '', $name);
        $outputFile = $generatedDir . "Document_For_{$safeName}.docx";

        // Salin file Word ke file baru
        copy($targetFile, $outputFile);

        // Buka file Word sebagai ZIP
        $zip = new ZipArchive();
        if ($zip->open($outputFile) === TRUE) {
            // Baca file XML utama
            $xmlContent = $zip->getFromName('word/document.xml');
            if ($xmlContent === false) {
                die("Failed to read document.xml in {$outputFile}");
            }

            // Ganti teks placeholder "nama" dengan nama yang diberikan
            $xmlContent = str_replace('nama', htmlspecialchars($name, ENT_XML1), $xmlContent);

            // Simpan kembali file XML ke dalam ZIP
            $zip->addFromString('word/document.xml', $xmlContent);
            $zip->close();
        } else {
            die("Failed to open {$outputFile} as ZIP.");
        }
    }

    // Tampilkan pesan sukses
    echo "Files have been generated in the 'generated' folder:<br>";
    foreach ($names as $name) {
        $safeName = preg_replace('/[^A-Za-z0-9_\- ]/', '', $name);
        $generatedFile = "generated/Document_For_{$safeName}.docx";
        echo "<a href='{$generatedFile}' download>Download Document for {$name}</a><br>";
    }
    echo "<br><a href='index.php'>Back</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
