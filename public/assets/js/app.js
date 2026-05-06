'use strict';
document.addEventListener('DOMContentLoaded', function () {
  // Auto-dismiss alerts after 5s
  document.querySelectorAll('.alert-dismissible').forEach(function(el) {
    setTimeout(function() {
      var a = bootstrap.Alert.getOrCreateInstance(el);
      if (a) a.close();
    }, 5000);
  });

  // Image preview on file input
  document.querySelectorAll('input[type="file"][data-preview]').forEach(function(inp) {
    inp.addEventListener('change', function() {
      var container = document.querySelector(this.dataset.preview);
      if (!container) return;
      container.innerHTML = '';
      Array.from(this.files).forEach(function(file) {
        if (!file.type.match('image.*')) return;
        var r = new FileReader();
        r.onload = function(e) {
          var img = document.createElement('img');
          img.src = e.target.result;
          img.style.cssText = 'width:80px;height:72px;object-fit:cover;border-radius:9px;border:2px solid #e0e7e0;margin-right:6px;margin-bottom:6px;';
          container.appendChild(img);
        };
        r.readAsDataURL(file);
      });
    });
  });

  // Favorite toggle buttons
  document.querySelectorAll('.fav-toggle-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault(); e.stopPropagation();
      var itemId = this.dataset.itemId;
      var meta = document.querySelector('meta[name="csrf-token"]');
      if (!meta) return;
      var self = this;
      var baseUrl = document.querySelector('base') ? document.querySelector('base').href : window.location.origin + '/';

      fetch(baseUrl + 'favorites/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-Token': meta.content
        },
        body: 'item_id=' + itemId + '&' + meta.dataset.name + '=' + meta.content
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        var icon = self.querySelector('i');
        if (data.action === 'added') {
          icon.className = 'bi bi-heart-fill';
          self.classList.add('active');
          self.style.color = '#e53935';
        } else {
          icon.className = 'bi bi-heart';
          self.classList.remove('active');
          self.style.color = '';
        }
      })
      .catch(function() {});
    });
  });
});
