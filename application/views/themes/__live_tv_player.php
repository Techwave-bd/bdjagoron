<?php
if (!isset($live_tv)) {
    $live_tv_row = $this->db->select('details')->from('settings')->where('id', 120)->get()->row();
    $live_tv = $live_tv_row ? json_decode($live_tv_row->details) : null;
}
if (!$live_tv || $live_tv->status != 'on') return;

$channels = [];
if (!empty($live_tv->channels)) {
    $channels = $live_tv->channels;
} elseif (!empty($live_tv->url)) {
    $channels = [['name' => 'Live TV', 'url' => $live_tv->url]];
}
if (empty($channels)) return;
$first = $channels[0];
?>
<link href="https://unpkg.com/video.js@7/dist/video-js.min.css" rel="stylesheet">
<style>
#live-tv-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background: #e74c3c;
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(231,76,60,0.4);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}
#live-tv-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(231,76,60,0.6);
}
#live-tv-btn .dot {
    width: 10px;
    height: 10px;
    background: #fff;
    border-radius: 50%;
    animation: live-pulse 1.5s infinite;
}
@keyframes live-pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
    100% { opacity: 1; transform: scale(1); }
}
#live-tv-overlay {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 20px;
    z-index: 9998;
    width: 420px;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}
#live-tv-overlay.open {
    display: block;
}
#live-tv-overlay .header {
    background: #e74c3c;
    color: #fff;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    font-size: 14px;
}
#live-tv-overlay .header .close-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
}
#live-tv-overlay .header select {
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 13px;
    font-weight: 400;
    cursor: pointer;
    max-width: 200px;
}
#live-tv-overlay .header select option {
    background: #333;
    color: #fff;
}
#live-tv-overlay .player-wrapper {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
}
#live-tv-overlay .player-wrapper > * {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
#live-tv-overlay .vjs-big-play-button {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
@media (max-width: 576px) {
    #live-tv-overlay {
        width: calc(100% - 40px);
        right: 20px;
    }
}
</style>

<button id="live-tv-btn" onclick="toggleLiveTv()">
    <span class="dot"></span>
    LIVE TV
</button>

<div id="live-tv-overlay">
    <div class="header">
        <span>LIVE TV</span>
        <select id="channel-select" onchange="switchChannel(this.value)">
            <?php foreach ($channels as $i => $ch): ?>
            <option value="<?php echo $i ?>"><?php echo html_escape($ch->name ?? $ch['name'] ?? "Channel") ?></option>
            <?php endforeach; ?>
        </select>
        <button class="close-btn" onclick="toggleLiveTv()">&times;</button>
    </div>
    <div class="player-wrapper">
        <video id="live-tv-player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" playsinline>
            <source src="<?php echo html_escape($first->url ?? $first['url']) ?>" type="application/x-mpegURL">
        </video>
    </div>
</div>

<script>var channels = <?php echo json_encode($channels) ?>;</script>
<script src="https://unpkg.com/video.js@7/dist/video.min.js"></script>
<script src="https://unpkg.com/@videojs/http-streaming@2/dist/videojs-http-streaming.min.js"></script>
<script>
var player = null;
function toggleLiveTv() {
    var overlay = document.getElementById('live-tv-overlay');
    var btn = document.getElementById('live-tv-btn');
    overlay.classList.toggle('open');
    btn.style.display = overlay.classList.contains('open') ? 'none' : 'flex';
    if (overlay.classList.contains('open')) {
        if (!player) {
            player = videojs('live-tv-player', {
                html5: {
                    hls: {
                        enableLowInitialPlaylist: true,
                        smoothQualityChange: true
                    }
                }
            });
        }
        player.play();
    } else {
        if (player) {
            player.pause();
        }
    }
}
function switchChannel(index) {
    var ch = channels[index];
    if (!ch) return;
    var url = ch.url || ch;
    if (player) {
        player.src({src: url, type: 'application/x-mpegURL'});
        player.play();
    }
}
</script>

