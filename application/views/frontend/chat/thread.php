<?php
$me  = current_user();
$other_id = ($conv['buyer_id'] == $me['id']) ? $conv['seller_id'] : $conv['buyer_id'];
$last_msg_id = !empty($messages) ? end($messages)['id'] : 0;
?>
<div class="container py-4" style="max-width:800px;">
    <!-- Header -->
    <div class="card border-0 shadow-sm mb-0 rounded-bottom-0">
        <div class="card-body d-flex align-items-center gap-3 py-3">
            <a href="<?= site_url('chat') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <?php if ($item): ?>
            <div class="d-flex align-items-center gap-2 flex-grow-1">
                <div>
                    <div class="fw-semibold"><?= htmlspecialchars($item['title']) ?></div>
                    <div class="small text-muted"><?= format_price((float)$item['price']) ?> &middot; <?= item_status_badge($item['status']) ?></div>
                </div>
            </div>
            <a href="<?= site_url('items/' . $item['id']) ?>" class="btn btn-outline-primary btn-sm">View Item</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Messages -->
    <div id="chat-messages" class="border border-top-0 bg-white p-3 overflow-auto" style="height:420px;">
        <?php foreach ($messages as $msg): ?>
        <?php $is_mine = ($msg['sender_id'] == $me['id']); ?>
        <div class="d-flex mb-3 <?= $is_mine ? 'justify-content-end' : 'justify-content-start' ?>">
            <div class="d-flex align-items-end gap-2 <?= $is_mine ? 'flex-row-reverse' : '' ?>">
                <div class="rounded-circle bg-<?= $is_mine ? 'primary' : 'secondary' ?> text-white d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:30px;height:30px;font-size:12px;">
                    <?= strtoupper(substr($msg['sender_name'], 0, 1)) ?>
                </div>
                <div>
                    <div class="chat-bubble px-3 py-2 rounded-3 <?= $is_mine ? 'bg-primary text-white' : 'bg-light text-dark' ?>"
                         style="max-width:320px;word-break:break-word;">
                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                    </div>
                    <div class="text-muted" style="font-size:11px;margin-top:2px;<?= $is_mine ? 'text-align:right' : '' ?>">
                        <?= date('H:i', strtotime($msg['created_at'])) ?>
                        <?php if ($is_mine && $msg['is_read']): ?>
                            <i class="bi bi-check2-all text-info ms-1"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($messages)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-chat display-4"></i>
                <p class="mt-2">Start the conversation!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Input -->
    <div class="card border-top-0 rounded-top-0 shadow-sm">
        <div class="card-body p-2">
            <div class="d-flex gap-2">
                <textarea id="msgInput" class="form-control" rows="2"
                          placeholder="Type a message…" style="resize:none;"></textarea>
                <button id="sendBtn" class="btn btn-primary px-4">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const convId   = <?= (int)$conv['id'] ?>;
    const myId     = <?= (int)$me['id'] ?>;
    const myName   = <?= json_encode($me['name']) ?>;
    let lastMsgId  = <?= (int)$last_msg_id ?>;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    const box  = document.getElementById('chat-messages');
    const input= document.getElementById('msgInput');
    const btn  = document.getElementById('sendBtn');

    function scrollBottom() { box.scrollTop = box.scrollHeight; }
    scrollBottom();

    function appendMessage(msg, isMine) {
        const wrap = document.createElement('div');
        wrap.className = `d-flex mb-3 ${isMine ? 'justify-content-end' : 'justify-content-start'}`;
        wrap.innerHTML = `
        <div class="d-flex align-items-end gap-2 ${isMine ? 'flex-row-reverse' : ''}">
            <div class="rounded-circle bg-${isMine ? 'primary' : 'secondary'} text-white d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:30px;height:30px;font-size:12px;">${(isMine ? myName : msg.sender_name || '?')[0].toUpperCase()}</div>
            <div>
                <div class="chat-bubble px-3 py-2 rounded-3 ${isMine ? 'bg-primary text-white' : 'bg-light text-dark'}"
                     style="max-width:320px;word-break:break-word;">${msg.message.replace(/\n/g,'<br>')}</div>
                <div class="text-muted" style="font-size:11px;margin-top:2px;${isMine ? 'text-align:right' : ''}">${msg.time || new Date().toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'})}</div>
            </div>
        </div>`;
        box.appendChild(wrap);
        scrollBottom();
    }

    // Send
    btn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

    function sendMessage() {
        const msg = input.value.trim();
        if (!msg) return;
        input.value = '';
        btn.disabled = true;

        const csrf = csrfMeta.content;
        const csrfName = csrfMeta.dataset.name;

        fetch('<?= site_url('chat/send') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': csrf },
            body: `conversation_id=${convId}&message=${encodeURIComponent(msg)}&${csrfName}=${csrf}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                appendMessage({ message: msg, time: data.time, sender_name: myName }, true);
                lastMsgId = data.message_id;
            }
        })
        .finally(() => { btn.disabled = false; });
    }

    // Poll
    function poll() {
        fetch(`<?= site_url('chat/poll') ?>?conversation_id=${convId}&last_id=${lastMsgId}`)
        .then(r => r.json())
        .then(msgs => {
            msgs.forEach(msg => {
                if (msg.sender_id != myId) {
                    appendMessage(msg, false);
                    lastMsgId = msg.id;
                }
            });
        })
        .catch(() => {})
        .finally(() => setTimeout(poll, <?= CHAT_POLL_INTERVAL ?>));
    }
    setTimeout(poll, <?= CHAT_POLL_INTERVAL ?>);
})();
</script>
