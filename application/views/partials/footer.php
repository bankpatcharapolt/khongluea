<footer class="kl-footer">
  <div class="container-fluid px-3 px-lg-4" style="max-width:1400px;margin:0 auto;">
    <div class="row g-4 pb-4">
      <div class="col-md-4">
        <div class="footer-brand mb-2 d-flex align-items-center gap-2">
          <img src="<?= base_url('assets/img/logo-icon.png') ?>" alt="ของเหลือ"
               style="width:38px;height:38px;object-fit:contain;">
          ของเหลือ
        </div>
        <p class="small" style="color:rgba(255,255,255,.5);line-height:1.8;">
          แพลตฟอร์มซื้อขายของมือสอง C2C<br>
          ไม่มีค่าธรรมเนียม · ชำระนอกระบบ<br>
          นัดรับส่งกันเองได้เลย
        </p>
        <div class="d-flex gap-2 mt-2 flex-wrap">
          <span class="badge rounded-pill px-3 py-1" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.55);font-weight:400;">ฟรีตลอด</span>
          <span class="badge rounded-pill px-3 py-1" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.55);font-weight:400;">Made in Thailand 🇹🇭</span>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="mb-3">เลือกชม</h6>
        <ul class="list-unstyled small" style="line-height:2.2;">
          <li><a href="<?= site_url('items') ?>">ของทั้งหมด</a></li>
          <li><a href="<?= site_url('items?is_free=1') ?>">แจกฟรี 🎁</a></li>
          <li><a href="<?= site_url('items/create') ?>">ลงของ</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="mb-3">บัญชี</h6>
        <ul class="list-unstyled small" style="line-height:2.2;">
          <li><a href="<?= site_url('login') ?>">เข้าสู่ระบบ</a></li>
          <li><a href="<?= site_url('register') ?>">สมัครสมาชิก</a></li>
          <li><a href="<?= site_url('premium') ?>">พรีเมียม ⭐</a></li>
        </ul>
      </div>
      <!-- <div class="col-md-4">
        <h6 class="mb-3">เกี่ยวกับ</h6>
        <p class="small" style="color:rgba(255,255,255,.45);line-height:1.8;">
          ของเหลือ สร้างด้วย CodeIgniter 3<br>
          Bootstrap 5 · PHP · MySQL
        </p>
      </div> -->
    </div>
    <hr>
    <div class="d-flex justify-content-between flex-wrap gap-2 footer-btm pb-2">
      <span>&copy; <?= date('Y') ?> ของเหลือ (Khong Luea) — All rights reserved.</span>
      <span>ซื้อขายของมือสองอย่างปลอดภัย</span>
    </div>
  </div>
</footer>
