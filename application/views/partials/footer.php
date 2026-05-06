<footer class="kl-footer mt-5 py-5">
    <div class="container-fluid px-4" style="max-width:1300px;margin:0 auto;">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="footer-brand mb-2">🏷️ ของเหลือ</div>
                <p class="small" style="color:rgba(255,255,255,.5);line-height:1.7;">
                    ซื้อ ขาย และแจกของมือสองในชุมชนของคุณ<br>
                    ไม่มีค่าธรรมเนียม ไม่ต้องใช้ระบบชำระเงิน<br>
                    นัดรับส่งกันเองได้เลย
                </p>
            </div>
            <div class="col-6 col-md-2">
                <h6>เลือกชม</h6>
                <ul class="list-unstyled small mt-2">
                    <li class="mb-1"><a href="<?= site_url('items') ?>">ของทั้งหมด</a></li>
                    <li class="mb-1"><a href="<?= site_url('items?is_free=1') ?>">แจกฟรี</a></li>
                    <li class="mb-1"><a href="<?= site_url('items/create') ?>">ลงของ</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-2">
                <h6>บัญชี</h6>
                <ul class="list-unstyled small mt-2">
                    <li class="mb-1"><a href="<?= site_url('login') ?>">เข้าสู่ระบบ</a></li>
                    <li class="mb-1"><a href="<?= site_url('register') ?>">สมัครสมาชิก</a></li>
                    <li class="mb-1"><a href="<?= site_url('premium') ?>">แพ็กเกจพรีเมียม</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>ของเหลือ</h6>
               
                <div class="d-flex gap-2 mt-2">
                    <span class="badge" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);font-weight:400;font-size:.72rem;">ไม่มีค่าธรรมเนียม</span>
                    <span class="badge" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);font-weight:400;font-size:.72rem;">ชำระนอกระบบ</span>
                </div>
            </div>
        </div>
        <hr class="footer-divider my-4">
        <div class="d-flex justify-content-between flex-wrap gap-2">
            <p class="footer-bottom mb-0">&copy; <?= date('Y') ?> ของเหลือ · Khong Luea — All rights reserved.</p>
            <p class="footer-bottom mb-0">Made with ❤️ in Thailand</p>
        </div>
    </div>
</footer>
