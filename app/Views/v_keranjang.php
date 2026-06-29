<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>

<div class="alert alert-info" role="alert">
    Total Nilai Keranjang: <?= number_to_currency($total, 'IDR') ?>
</div>

<a href="<?= base_url('keranjang/clear') ?>" class="btn btn-danger mb-3">Kosongkan Keranjang</a>
<?php if (!empty($items)) : ?>
    <a class="btn btn-success mb-3" href="<?php echo base_url() ?>checkout">Selesai Belanja</a>
<?php endif; ?>

<?= form_open('keranjang/edit') ?>
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($items as $item) : ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td>
                    <?php if (isset($item['options']['foto']) && $item['options']['foto'] != '' && file_exists("img/" . $item['options']['foto'])) : ?>
                        <img src="<?= base_url() . "img/" . $item['options']['foto'] ?>" width="100">
                    <?php endif; ?>
                </td>
                <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                <td>
                    <input type="number" name="qty<?= $i++ ?>" value="<?= $item['qty'] ?>" class="form-control" min="1" style="width: 100px;">
                </td>
                <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
                <td>
                    <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<button type="submit" class="btn btn-primary mt-3">Perbarui Keranjang</button>
<?= form_close() ?>

<?= $this->endSection() ?>
