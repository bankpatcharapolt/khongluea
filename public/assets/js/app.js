/* ============================================================
   ของเหลือ (Khong Luea) — App JS
   ============================================================ */
'use strict';

document.addEventListener('DOMContentLoaded', function () {

    // Auto-dismiss alerts after 5s
    document.querySelectorAll('.alert-dismissible').forEach(function (el) {
        setTimeout(function () {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // Image preview on file input
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var container = document.querySelector(this.dataset.preview);
            if (!container) return;
            container.innerHTML = '';
            Array.from(this.files).forEach(function (file) {
                if (!file.type.match('image.*')) return;
                var reader = new FileReader();
                reader.onload = function (e) {
                    var wrap = document.createElement('div');
                    wrap.style.cssText = 'position:relative;display:inline-block;';
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'width:80px;height:72px;object-fit:cover;border-radius:8px;border:2px solid var(--kl-border);';
                    wrap.appendChild(img);
                    container.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
        });
    });

    // Favorite toggle buttons
    document.querySelectorAll('.fav-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var itemId = this.dataset.itemId;
            var meta   = document.querySelector('meta[name="csrf-token"]');
            if (!meta) return;
            var csrfToken = meta.content;
            var csrfName  = meta.dataset.name;
            var icon      = this.querySelector('i');
            var self      = this;

            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken
                },
                body: 'item_id=' + itemId + '&' + csrfName + '=' + csrfToken
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.action === 'added') {
                    icon.className = 'bi bi-heart-fill';
                    self.classList.add('active');
                    self.style.color = 'var(--kl-red)';
                } else {
                    icon.className = 'bi bi-heart';
                    self.classList.remove('active');
                    self.style.color = '';
                }
            })
            .catch(function () {});
        });
    });

});
