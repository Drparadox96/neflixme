<?php
// ---------------- CONFIG ----------------
$botToken   = "7017424822:AAHWk3yYofSq0xz2kr6fPxTvwMXjJr0PPpw";  // from @BotFather
$channelId  = "@paradoxtipss";   // your channel username
$videoUrl   = "hot.mp4";         // path to your video
$thumbnail  = "preview1.PNG";    // your thumbnail

// ---------------- VERIFY TELEGRAM LOGIN ----------------
function checkTelegramAuthorization($auth_data, $botToken) {
    $check_hash = $auth_data['hash'];
    unset($auth_data['hash']);
    $data_check_arr = [];
    foreach ($auth_data as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }
    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);
    $secret_key = hash('sha256', $botToken, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);
    return ($hash === $check_hash);
}

$isMember = false;
if (!empty($_GET['id'])) {
    if (checkTelegramAuthorization($_GET, $botToken)) {
        $userId = $_GET['id'];
        // Call Telegram API to check membership
        $apiUrl = "https://api.telegram.org/bot$botToken/getChatMember?chat_id=$channelId&user_id=$userId";
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);
        if ($data['ok'] && in_array($data['result']['status'], ['member','administrator','creator'])) {
            $isMember = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watch Hot Video</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="max-w-xl w-full bg-white p-6 rounded-2xl shadow-lg text-center">
    <?php if ($isMember): ?>
      <!-- Video Section -->
      <video class="w-full rounded-xl shadow" controls>
        <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    <?php else: ?>
      <!-- Locked Section -->
      <img src="<?= htmlspecialchars($thumbnail) ?>" alt="Video Thumbnail" class="w-full rounded-xl shadow mb-4">
      <p class="mb-4 text-gray-700 text-lg">Join our Telegram channel to unlock this video!</p>

      <!-- Join button -->
      <a href="https://t.me/paradoxtipss" target="_blank"
         class="block w-full bg-blue-500 text-white py-3 rounded-xl font-semibold hover:bg-blue-600 mb-3">
         Join Telegram
      </a>

      <!-- Telegram Login Button -->
      <script async src="https://telegram.org/js/telegram-widget.js?22"
              data-telegram-login="YourBotUsername"
              data-size="large"
              data-auth-url="index.php"
              data-request-access="write">
      </script>
    <?php endif; ?>
  </div>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-2ECDYTVN2Y"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-2ECDYTVN2Y');
  </script>
</body>
</html>
