<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Produk</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Data Produk</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($products as $p): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $p['nama'] ?></td>
                <td><?= $p['harga'] ?></td>
                <td><?= $p['jumlah'] ?></td>
                <td>
                    <?php 
                    $imagePath = FCPATH . 'img/' . $p['foto'];
                    if ($p['foto'] != '' && file_exists($imagePath)) {
                        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                        $data = file_get_contents($imagePath);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        echo '<img src="' . $base64 . '" alt="Foto Produk">';
                    } else {
                        echo 'Tidak ada foto';
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
