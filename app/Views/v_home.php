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
<div class="row">
    <?php foreach ($products as $item) : ?>
        <?php
        $foto = $item['foto'] ?? '';
        if ($foto !== '') {
            $foto = preg_match('/^https?:\/\//', $foto) ? $foto : base_url('img/' . $foto);
        }
        ?>
        <div class="col-md-4">
            <div class="card">
                <?php if ($foto !== '') : ?>
                    <img src="<?= esc($foto) ?>" class="card-img-top" alt="<?= esc($item['nama']) ?>">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= esc($item['nama']) ?></h5>
                    <p class="card-text mb-1">Harga: <?= number_to_currency($item['harga'], 'IDR') ?></p>
                    <p class="card-text">Stok: <?= esc($item['jumlah']) ?></p>
                    <?= form_open('keranjang') ?>
                    <?php
                    echo form_hidden('id', $item['id']);
                    echo form_hidden('nama', $item['nama']);
                    echo form_hidden('harga', $item['harga']);
                    echo form_hidden('foto', $item['foto']);
                    ?>
                    <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>
