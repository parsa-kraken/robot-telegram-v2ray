<?php




/*
Developer : parsa taheri (kraken)
github : parsa-kraken
web : www.parsakraken.ir
*/
 
$botToken = 'توکن رباتتون';
$targetChannel = 'ایدی چنل';
$sourceChannels = [
   '@NPROXY',
'@ConfigsHubPlus',
'@ConfigTech1',
'@e2vpn',
'@proxyiranip',
'@vpnplusee_free',
'@iMTProto',
'@YamYamProxy',
'@sunflowerplato',
'@StreisandAp',
'@Confings_K',
'@horn_proxy',
'@RUSSIAPROXYY',
'@V2All',
'@GetConfigIR',
'@neo_proxy',
'@Proxy_Qavi',
'@Proxycj',
'@proxyvr',
'@HiProxy',
'@Proxyqawi',
'@proxy_ghavy',
'@Up_Proxy',
'@proxy_i2',
'@ProxyKhabri',
'@ProxyKL',
'@VPNOD',
'@Proxyjadidi',
'@proxyjt',
'@PROXYA0',
'@proxyag',
'@Myporoxy',
'@saghivpnx',
'@ProtoJet',
'@vpns',
'@HotProxy_Free',
'@SuperFreeVpnProxy',
'@proxy_who',
'@ProxyMTProto',
'@WarV2Ray',
'@mti_vpn7',
'@proxymtprotoj',
'@darkproxy',
'@GlypeX',
'@v2rayfree_iran',
'@iRFilteringVpn',
'@mtpproxyirani',
'@ProxyGH',
'@ProxyDaemi',
'@irproxy',
'@net_baz1',
'@PabloProxy',
'@TP_MTProxy',
'@v2rayng_fars',
'@Vpnsmartcam',
'@freedomnetir',
'@xsfilternet',
'@tuenvpn',
'@v2rayTG',
'@proxymoments',
'@Hoorvpn',
'@ROJproxy',
'@Super_v2ray24',
'@donot66',
'@iphone02016vpn',
'@pricedolar1',
'@BestSpeedProxy',
'@proxyhive',
'@ProxyWR',
'@proxym',
'@Evay_vpn',
'@Spotify_Porteghali',
'@PewezaVPN',
'@V2rey_Hiddify',
'@ToxicVid',
'@NewWarp',
'@aataatee',
'@V2ranNG_vpn',
'@ghalagyann',
'@ghalagyann2',
'@nofilter_proxi',
'@V2rayfastt',
'@FarsiProxi',
'@Vahid_Page',
'@VPN_IRANT',
'@iRoProxy',
'@ProxyNewsVatani',
'@vonline247',
'@V2VIPCHANNEL',
'@Surfboardv2ray',
'@Express_freevpn',
'@IRBestFree',
'@ProxyMTProto_tel',
'@marketing_marziyeh',
'@config_proxy',
'@Scary_Proxy',
'@iproxy',
'@Academi_vpn',
'@v2rayshahin',
'@hex_proxy',
'@iranproxy80',
'@channel_proxy1',
'@zedmodeonVPN',
'@v2FreeHub',
];

$configPatterns = [
    '/vless:\/\/[^\s\'"<>()#]+/',
    '/vmess:\/\/[^\s\'"<>()#]+/'
];

function extractConfigs($text) {
    global $configPatterns;
    $configs = [];
    foreach ($configPatterns as $pattern) {
        preg_match_all($pattern, $text, $matches);
        foreach ($matches[0] as $config) {
            $configs[] = trim($config);
        }
    }
    return array_unique($configs);
}

function isValidConfig($config) {
    $config = trim($config);
    return !(empty($config) || strlen($config) < 15 || preg_match('/[#]{2,}$/', $config));
}

function sendMessage($chatID, $text, $keyboard = null) {
    global $botToken;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    $data = [
        'chat_id' => $chatID,
        'text' => $text,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    if ($keyboard !== null) {
        $data['reply_markup'] = json_encode($keyboard);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

 
function getState($chat_id) {
    $file = __DIR__ . "/state_$chat_id.txt";
    return file_exists($file) ? trim(file_get_contents($file)) : 'idle';
}

function setState($chat_id, $state) {
    file_put_contents(__DIR__ . "/state_$chat_id.txt", $state);
}

function isUserJoined($user_id, $channel) {
    global $botToken;
    $url = "https://api.telegram.org/bot$botToken/getChatMember?chat_id=$channel&user_id=$user_id";
    $response = json_decode(file_get_contents($url), true);
    if (!isset($response['result']['status'])) return false;
    return in_array($response['result']['status'], ['member', 'creator', 'administrator']);
}

$update = json_decode(file_get_contents('php://input'), true);
if (!isset($update['message']['text'])) exit;

$text = trim($update['message']['text']);
$chat_id = $update['message']['chat']['id'];
$user_id = $update['message']['from']['id'];
$channelTag = '#' . ltrim($targetChannel, '@');
$channelId = ltrim($targetChannel, '@');


if (!isUserJoined($user_id, $targetChannel)) {
    $joinText = "📛 <b>دسترسی محدود!</b>\n\n🔐 برای استفاده از ربات ابتدا عضو کانال زیر شوید:\n\n👉 $targetChannel\n\nسپس مجدداً دستور خود را ارسال کنید.";
    $keyboard = [
        'inline_keyboard' => [[['text' => '🔗 عضویت در کانال', 'url' => "https://t.me/$channelId"]]]
    ];
    sendMessage($chat_id, $joinText, $keyboard);
    exit;
}


if ($text === '❌ کنسل') {
    setState($chat_id, 'idle');
    $mainKeyboard = [
        'keyboard' => [[['text' => 'استخراج کانفیگ‌ها🤘']]],
        'resize_keyboard' => true,
        'one_time_keyboard' => false,
    ];
    sendMessage($chat_id, "✅ عملیات متوقف شد.", $mainKeyboard);
    exit;
}

$state = getState($chat_id);

if ($state === 'waitingForCount') {
    if (is_numeric($text)) {
        $count = min(max((int)$text, 1), 1000);
        sendMessage($chat_id, "⏳ در حال استخراج $count کانفیگ از کانال‌ها، لطفا صبر کنید...");

        $allConfigs = [];
        foreach ($sourceChannels as $channel) {
            $channelName = ltrim($channel, '@');
            $channelData = @file_get_contents("https://t.me/s/" . $channelName);
            if ($channelData === false) continue;

            $configs = extractConfigs($channelData);
            foreach ($configs as $config) {
                $config = preg_replace('/#.*$/', '', $config);
                $config = trim($config, '# ');
                if (!isValidConfig($config)) continue;
                if (count($allConfigs) >= $count) break;
                $allConfigs[] = $config;
            }
            if (count($allConfigs) >= $count) break;
        }

        foreach ($allConfigs as $config) {
            $message = "🚀✨ <b>کانفیگ VPN فوق‌العاده و اختصاصی</b> ✨🚀\n\n";
            $message .= "🔹 <b>کانفیگ:</b>\n<code>$config</code>\n\n";
            $message .= "⏱️ <b>پینگ تقریبی:</b> 45ms 🕒\n";
            $message .= "🌐 <b>سرور:</b> <i>$channelId</i>\n\n";
            $message .= "📌 برای کپی راحت، روی متن بالا کلیک کنید!\n";
            $message .= "🔥 <b>کانفیگ از کانال:</b> <i>$channelTag</i>\n";
            $message .= "🎉 موفق باشید! 🌟";

            sendMessage($targetChannel, $message);
            usleep(250000);
        }

        sendMessage($chat_id, "✅ استخراج کانفیگ‌ها تمام شد!\nتعداد کانفیگ‌های ارسال شده: " . count($allConfigs));
        setState($chat_id, 'idle');
        exit;
    } else {
        sendMessage($chat_id, "❌ لطفا فقط یک عدد بین 1 تا 1000 وارد کنید.");
        exit;
    }
}



if (mb_strtolower($text) === 'استخراج کانفیگ‌🤘') {
    setState($chat_id, 'waitingForCount');
    $cancelKeyboard = [
        'keyboard' => [[['text' => '❌ کنسل']]],
        'resize_keyboard' => true,
        'one_time_keyboard' => false,
    ];
    sendMessage($chat_id, "لطفا تعداد کانفیگ‌هایی که می‌خواهید استخراج شود را وارد کنید (بین 1 تا 1000):", $cancelKeyboard);
    exit;
}

if ($text === '/start') {
    $keyboard = [
        'keyboard' => [[['text' => 'استخراج کانفیگ‌🤘']]],
        'resize_keyboard' => true,
        'one_time_keyboard' => false,
    ];
    sendMessage($chat_id, "سلام! برای شروع روی دکمه زیر بزن:", $keyboard);
    exit;
}

sendMessage($chat_id, "لطفا از دکمه‌ها استفاده کنید یا دستور /start را ارسال کنید.");


?>

	

