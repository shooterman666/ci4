<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<h1>Profil</h1>
<div style="max-width:480px;padding:12px;border:1px solid #e6e6e6;border-radius:6px;background:#fbfbfb;">
    <?php
    $picture = $user['picture'] ?? '';
    if ($picture !== '' && ! preg_match('/^https?:\/\//', $picture)) {
        $picture = base_url($picture);
    }
    ?>
    <?php if ($picture !== '') : ?>
        <div style="margin-bottom:16px;">
            <img src="<?= esc($picture) ?>" alt="Foto profil" style="width:96px;height:96px;border-radius:50%;object-fit:cover;">
        </div>
    <?php endif; ?>
    <ul style="list-style:none;padding:0;margin:0;">
        <li style="padding:8px 0;border-bottom:1px solid #eee;">
            <strong>Username</strong>: <?= esc($user['username'] ?? session('username')) ?>
        </li>
        <li style="padding:8px 0;border-bottom:1px solid #eee;">
            <strong>Email</strong>: <?= esc($user['email'] ?? session('email')) ?>
        </li>
        <li style="padding:8px 0;border-bottom:1px solid #eee;">
            <strong>Role</strong>: <?= esc($user['role'] ?? session('role')) ?>
        </li>
        <li style="padding:8px 0;border-bottom:1px solid #eee;">
            <strong>Terdaftar pada</strong>: <?= esc($user['created_at'] ?? '-') ?>
        </li>
        <li style="padding:8px 0;border-bottom:1px solid #eee;">
            <strong>Waktu login</strong>: <?= date('Y-m-d H:i:s', session('time_when_login')) ?>
        </li>
        <li style="padding:8px 0;">
            <strong>Status login</strong>: <?= session('isLoggedIn') ? 'Sudah login' : 'Belum login' ?>
        </li>
    </ul>
</div>

<?= $this->endSection(); ?>
