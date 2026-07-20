<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?= form_open('keranjang/buy') ?>
<div class="row">
    <div class="col-lg-6">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= session()->get('username') ?>">
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="kelurahan" class="form-label">Kelurahan</label>
            <?= form_dropdown('kelurahan', [], '', ['class' => 'form-control', 'id' => 'kelurahan']) ?>
        </div>
        <div class="mb-3">
            <label for="layanan" class="form-label">Layanan</label>
            <?= form_dropdown('layanan', [], '', ['class' => 'form-control', 'id' => 'layanan']) ?>
        </div>
        <div class="mb-3">
            <label for="ongkir" class="form-label">Ongkir</label>
            <input type="text" class="form-control" id="ongkir" value="0" readonly>
            <input type="hidden" name="ongkir" id="ongkir_value" value="0">
        </div>
        <div class="mb-3">
            <label for="kode_kupon" class="form-label">Kode Kupon</label>
            <div class="input-group">
                <input type="text" class="form-control" id="kode_kupon" name="kupon_code" placeholder="Masukkan kode kupon" style="text-transform: uppercase">
                <button type="button" class="btn btn-outline-primary" id="btn_cek_kupon">Cek</button>
            </div>
            <div class="form-text" id="kupon_info"></div>
        </div>
        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
    </div>
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                        <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="alert alert-info">
            Subtotal = <span id="subtotal"><?= number_to_currency($total, 'IDR') ?></span><br>
            Diskon Kupon = <span id="diskon_kupon_text"><?= number_to_currency(0, 'IDR') ?></span><br>
            Biaya Admin = <span id="biaya_admin_text"><?= number_to_currency(0, 'IDR') ?></span><br>
            Ongkir = <span id="ongkir_text"><?= number_to_currency(0, 'IDR') ?></span><br>
            <strong>Total = <span id="total"><?= number_to_currency($total, 'IDR') ?></span></strong><br>
            <small class="text-success">Cashback = <span id="cashback_text"><?= number_to_currency(0, 'IDR') ?></span></small>
        </div>
    </div>
</div>
<?= form_close() ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    const subtotal = <?= $total ?>;
    let ongkir = 0;

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(angka);
    }

    function hitungTotal() {
        const kuponCode = $('#kode_kupon').val().trim();

        $.ajax({
            url: '<?= base_url('ajax/calculate') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                subtotal: subtotal,
                kupon_code: kuponCode,
                ongkir: ongkir
            },
            success: function(data) {
                $('#ongkir').val(ongkir);
                $('#ongkir_value').val(ongkir);
                $('#ongkir_text').text(formatRupiah(ongkir));
                $('#biaya_admin_text').text(formatRupiah(data.biaya_admin));
                $('#diskon_kupon_text').text('- ' + formatRupiah(data.diskon_kupon));
                $('#cashback_text').text(formatRupiah(data.cashback));
                $('#total').text(formatRupiah(data.total));

                if (kuponCode.length > 0) {
                    if (data.kupon_code) {
                        $('#kupon_info').text('Kupon "' + data.kupon_code + '" berhasil! Diskon ' + (data.kupon_persen * 100) + '%').removeClass('text-danger').addClass('text-success');
                    } else {
                        $('#kupon_info').text('Kupon tidak valid').removeClass('text-success').addClass('text-danger');
                    }
                } else {
                    $('#kupon_info').text('');
                }
            }
        });
    }

    $('#btn_cek_kupon').on('click', function() {
        hitungTotal();
    });

    $('#kelurahan').select2({
        placeholder: 'Ketik nama kelurahan',
        ajax: {
            url: '<?= base_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#kelurahan').on('change', function() {
        const idKelurahan = $(this).val();
        $('#layanan').empty();
        ongkir = 0;
        hitungTotal();

        $.ajax({
            url: '<?= base_url('ajax/costs') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                destination: idKelurahan
            },
            success: function(data) {
                data.forEach(function(item) {
                    $('#layanan').append(new Option(item.service + ' - ' + item.description + ' - ' + item.etd + ' hari', item.cost));
                });
            }
        });
    });

    $('#layanan').on('change', function() {
        ongkir = parseInt($(this).val() || 0);
        hitungTotal();
    });

    hitungTotal();
</script>
<?= $this->endSection() ?>
