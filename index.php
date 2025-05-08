<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Word Document</title>
</head>
<body>
    <h1>Edit Word Document</h1>
    <form action="process.php" method="post" enctype="multipart/form-data">
        <label for="wordFile">Upload Word File (.docx):</label>
        <input type="file" name="wordFile" id="wordFile" accept=".docx" required>
        <br><br>
        <label for="names">Input Names (separated by commas):</label>
        <textarea name="names" id="names" rows="4" cols="50" required></textarea>
        <br><br>
        <button type="submit">Process</button>
    </form>
</body>
</html>
