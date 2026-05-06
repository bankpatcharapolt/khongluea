<?php
$me          = current_user();
$other_id    = ($conv['buyer_id'] == $me['id']) ? $conv['seller_id'] : $conv['buyer_id'];
$last_msg_id = !empty($messages) ? (int)end($messages)['id'] : 0;
$base        = rtrim(site_url(), '/');
$csrf_name   = $this->security->get_csrf_token_name();
$csrf_token  = $this->security->get_csrf_hash();
?>
<div class="container py-3" style="max-width:820px;">

  <!-- Header -->
  <div class="kl-chat-wrap">
    <div class="kl-chat-header">
      <a href="<?= site_url('chat') ?>" class="btn btn-sm me-1"
         style="background:rgba(255,255,255,.2);color:#fff;border:none;border-radius:8px;">
        <i class="bi bi-arrow-left"></i>
      </a>
      <?php if ($item): ?>
        <div class="flex-grow-1 min-w-0">
          <div class="fw-700 text-truncate"><?= htmlspecialchars($item['title']) ?></div>
          <div style="font-size:.78rem;opacity:.85;">
            <?= format_price((float)$item['price']) ?>
            <span class="ms-2"><?= item_status_badge($item['status']) ?></span>
          </div>
        </div>
        <a href="<?= site_url('items/'.$item['id']) ?>" class="btn btn-sm flex-shrink-0"
           style="background:rgba(255,255,255,.2);color:#fff;border:none;border-radius:8px;font-size:.8rem;">
          ดูสินค้า
        </a>
      <?php endif; ?>
    </div>

    <!-- Messages area -->
    <div id="chat-messages" class="kl-chat-messages">
      <?php if (empty($messages)): ?>
        <div class="text-center py-5" style="color:var(--muted);">
          <i class="bi bi-chat-dots" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.5rem;"></i>
          เริ่มการสนทนาได้เลย!
        </div>
      <?php else: ?>
        <?php foreach ($messages as $msg):
          $is_mine = ((int)$msg['sender_id'] === (int)$me['id']);
        ?>
        <div class="d-flex mb-3 <?= $is_mine ? 'justify-content-end' : 'justify-content-start' ?>">
          <div class="d-flex align-items-end gap-2 <?= $is_mine ? 'flex-row-reverse' : '' ?>"
               style="max-width:75%;">
            <!-- Avatar -->
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-700 flex-shrink-0"
                 style="width:30px;height:30px;font-size:12px;
                        background:<?= $is_mine ? 'var(--g)' : '#e0e7e0' ?>;
                        color:<?= $is_mine ? '#fff' : 'var(--text)' ?>;">
              <?= strtoupper(mb_substr($msg['sender_name'] ?? '?', 0, 1)) ?>
            </div>
            <div>
              <!-- Bubble -->
              <div class="<?= $is_mine ? 'chat-bubble-mine' : 'chat-bubble-other' ?> px-3 py-2"
                   style="word-break:break-word;font-size:.9rem;">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
              </div>
              <!-- Time + read status -->
              <div class="text-muted mt-1 <?= $is_mine ? 'text-end' : '' ?>" style="font-size:.7rem;">
                <?= date('H:i', strtotime($msg['created_at'])) ?>
                <?php if ($is_mine): ?>
                  <?= $msg['is_read']
                    ? '<i class="bi bi-check2-all ms-1" style="color:var(--g);"></i>'
                    : '<i class="bi bi-check2 ms-1 text-muted"></i>' ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Input -->
    <div class="kl-chat-input">
      <textarea id="msgInput" rows="1" placeholder="พิมพ์ข้อความ..."
                style="border:1.5px solid var(--border);border-radius:22px;resize:none;flex:1;padding:.55rem 1rem;font-family:inherit;font-size:.9rem;max-height:100px;overflow-y:auto;"
                oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"></textarea>
      <button id="sendBtn" class="kl-chat-input .btn-send"
              style="width:42px;height:42px;border-radius:50%;background:var(--g);color:#fff;border:none;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;transition:.15s;">
        <i class="bi bi-send-fill"></i>
      </button>
    </div>
  </div>
</div>

<script>
(function () {
  const BASE     = <?= json_encode(rtrim(site_url(), '/')) ?>;
  const convId   = <?= (int)$conv['id'] ?>;
  const myId     = <?= (int)$me['id'] ?>;
  const myName   = <?= json_encode($me['name']) ?>;
  let lastMsgId  = <?= $last_msg_id ?>;
  let csrfToken  = <?= json_encode($csrf_token) ?>;
  const csrfName = <?= json_encode($csrf_name) ?>;

  const box   = document.getElementById('chat-messages');
  const input = document.getElementById('msgInput');
  const btn   = document.getElementById('sendBtn');

  // Scroll ลงล่างเสมอ
  function scrollBottom() {
    box.scrollTop = box.scrollHeight;
  }
  scrollBottom();

  // สร้าง message bubble
  function appendMessage(msg, isMine) {
    // ลบ empty state
    const empty = box.querySelector('.text-center.py-5');
    if (empty) empty.remove();

    const name  = isMine ? myName : (msg.sender_name || '?');
    const time  = msg.time || msg.created_at
      ? (msg.time || new Date(msg.created_at).toLocaleTimeString('th-TH', {hour:'2-digit',minute:'2-digit'}))
      : '--:--';

    const wrap = document.createElement('div');
    wrap.className = `d-flex mb-3 ${isMine ? 'justify-content-end' : 'justify-content-start'}`;
    wrap.innerHTML = `
      <div class="d-flex align-items-end gap-2 ${isMine ? 'flex-row-reverse' : ''}" style="max-width:75%;">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-700 flex-shrink-0"
             style="width:30px;height:30px;font-size:12px;
                    background:${isMine ? 'var(--g)' : '#e0e7e0'};
                    color:${isMine ? '#fff' : 'var(--text)'};">
          ${name[0].toUpperCase()}
        </div>
        <div>
          <div class="${isMine ? 'chat-bubble-mine' : 'chat-bubble-other'} px-3 py-2"
               style="word-break:break-word;font-size:.9rem;">
            ${msg.message.replace(/\n/g,'<br>').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/&lt;br&gt;/g,'<br>')}
          </div>
          <div class="text-muted mt-1 ${isMine ? 'text-end' : ''}" style="font-size:.7rem;">
            ${time}
            ${isMine ? '<i class="bi bi-check2 ms-1 text-muted"></i>' : ''}
          </div>
        </div>
      </div>`;
    box.appendChild(wrap);
    scrollBottom();
  }

  // ===== SEND =====
  btn.addEventListener('click', sendMessage);
  input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  function sendMessage() {
    const msg = input.value.trim();
    if (!msg) return;
    input.value = '';
    input.style.height = 'auto';
    btn.disabled = true;

    fetch(BASE + '/chat/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': csrfToken
      },
      body: `conversation_id=${convId}&message=${encodeURIComponent(msg)}&${csrfName}=${csrfToken}`
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success) {
        appendMessage({ message: msg, time: data.time, sender_name: myName }, true);
        lastMsgId = data.message_id;
        // อัปเดต CSRF token สำหรับ request ถัดไป
        if (data.csrf_token) csrfToken = data.csrf_token;
      } else {
        console.error('Send failed:', data.error || 'Unknown error');
      }
    })
    .catch(function(err) { console.error('Fetch error:', err); })
    .finally(function() { btn.disabled = false; input.focus(); });
  }

  // ===== POLL (ดึงข้อความใหม่จากอีกฝ่าย) =====
  function poll() {
    fetch(BASE + '/chat/poll?conversation_id=' + convId + '&last_id=' + lastMsgId, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(msgs) {
      if (!Array.isArray(msgs)) return;
      msgs.forEach(function(msg) {
        if (parseInt(msg.sender_id) !== myId) {
          appendMessage(msg, false);
        }
        lastMsgId = Math.max(lastMsgId, parseInt(msg.id));
      });
    })
    .catch(function() {})
    .finally(function() { setTimeout(poll, 4000); });
  }
  setTimeout(poll, 4000);

})();
</script>
