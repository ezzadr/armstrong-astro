<?php
// claim.php - High-End 1-Click Mobile Job Dispatch & Claim Portal
$leadId = isset($_GET['id']) ? trim($_GET['id']) : '';
$techClaimant = isset($_POST['tech_name']) ? trim($_POST['tech_name']) : '';

$leadsFile = __DIR__ . '/api/.leads_store.json';
$leads = file_exists($leadsFile) ? json_decode(file_get_contents($leadsFile), true) : [];
if (!is_array($leads)) { $leads = []; }

// 1. Locate Lead
$lead = null;
$leadIndex = -1;
foreach ($leads as $idx => $l) {
    if (isset($l['id']) && $l['id'] === $leadId) {
        $lead = $l;
        $leadIndex = $idx;
        break;
    }
}

// 2. Handle Technician Claim Submission
$claimSuccess = false;
$alreadyClaimed = false;
$claimer = '';
$claimedAt = '';

if ($lead) {
    if (!empty($lead['claimed_by'])) {
        $alreadyClaimed = true;
        $claimer = $lead['claimed_by'];
        $claimedAt = $lead['claimed_at'];
    } elseif (!empty($techClaimant)) {
        // Mark as Claimed
        $claimer = $techClaimant;
        $claimedAt = date('g:i A');
        $leads[$leadIndex]['claimed_by'] = $claimer;
        $leads[$leadIndex]['claimed_at'] = $claimedAt;
        file_put_contents($leadsFile, json_encode($leads, JSON_PRETTY_PRINT), LOCK_EX);
        $claimSuccess = true;
        $lead = $leads[$leadIndex];

        // Broadcast notification to team via Twilio
        $accountSid = 'AC1c283a892ca8f15081d8b000a2a5d5b2';
        $authToken  = '659fb2febe1f3ab60703a1f74439843a';
        $twilioFrom = '+16293389619';
        
        $techPhones = [
            '+16156258000', // Rahim
            '+16299991050', // KB
            '+16155688000'  // Sako
        ];

        $broadcastMsg = "📢 JOB CLAIMED!\n"
                      . "👤 Customer: " . $lead['name'] . "\n"
                      . "✅ Taken by: " . $claimer . " (" . $claimedAt . ")\n"
                      . "🚫 DO NOT CALL — Job is covered!";

        foreach ($techPhones as $phone) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Messages.json");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'From' => $twilioFrom,
                'To'   => $phone,
                'Body' => $broadcastMsg
            ]));
            curl_setopt($ch, CURLOPT_USERPWD, "$accountSid:$authToken");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Armstrong Dispatch &bull; Lead #<?= htmlspecialchars($leadId) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-[#0b132b] text-slate-100 min-h-screen flex items-center justify-center p-3 sm:p-5">

  <div class="max-w-md w-full bg-[#1c2541] border border-slate-700/80 rounded-3xl p-5 sm:p-7 shadow-2xl relative overflow-hidden">
    
    <!-- Top Glowing Accent -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 via-emerald-400 to-amber-500"></div>

    <!-- Top Dispatch Header Bar -->
    <div class="flex items-center justify-between border-b border-slate-700/60 pb-3.5 mb-5">
      <div class="flex items-center gap-2">
        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></div>
        <span class="text-xs font-black uppercase tracking-wider text-amber-400">Armstrong Fast Dispatch</span>
      </div>
      <span class="text-[11px] font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700">
        TICKET #<?= htmlspecialchars($leadId) ?>
      </span>
    </div>

    <?php if (!$lead): ?>
      <!-- Lead Not Found -->
      <div class="text-center py-10">
        <div class="text-5xl mb-3">🔍</div>
        <h1 class="text-xl font-bold text-white mb-2">Lead Expired or Not Found</h1>
        <p class="text-xs text-slate-400">This dispatch link may have already been archived.</p>
      </div>

    <?php elseif ($alreadyClaimed): ?>
      <!-- ALREADY CLAIMED VIEW (PREVENTS DOUBLE CALLS) -->
      <div class="text-center py-2">
        <div class="w-16 h-16 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/40 flex items-center justify-center text-3xl mx-auto mb-3 shadow-inner">
          🔒
        </div>
        <span class="text-xs font-extrabold uppercase tracking-wider text-amber-400 block mb-1">Status: Assigned</span>
        <h1 class="text-2xl font-black text-white mb-1">Already Claimed!</h1>
        <p class="text-xs text-slate-300 mb-5">
          This customer is actively being handled by <strong class="text-amber-300 font-bold"><?= htmlspecialchars($claimer) ?></strong> (taken at <?= htmlspecialchars($claimedAt) ?>).
        </p>

        <!-- Customer Summary Box -->
        <div class="bg-[#0b132b]/80 p-4 rounded-2xl border border-slate-700/80 text-left text-xs mb-5 flex flex-col gap-2">
          <div class="flex justify-between items-center"><span class="text-slate-400">Customer:</span><strong class="text-white text-sm"><?= htmlspecialchars($lead['name']) ?></strong></div>
          <div class="flex justify-between items-center"><span class="text-slate-400">Service:</span><span class="text-amber-400 font-semibold"><?= htmlspecialchars($lead['service']) ?></span></div>
          <div class="flex justify-between items-center"><span class="text-slate-400">Details:</span><span class="text-slate-200"><?= htmlspecialchars($lead['details']) ?></span></div>
        </div>

        <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-bold flex items-center justify-center gap-2">
          <span>🚫</span>
          <span>Please do not call the customer.</span>
        </div>
      </div>

    <?php elseif ($claimSuccess): ?>
      <!-- CLAIM SUCCESS VIEW (SHOW PHONE CALL BUTTON) -->
      <div class="text-center py-1">
        <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center text-3xl mx-auto mb-3 shadow-inner">
          ✅
        </div>
        <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-400 block mb-1">Confirmed Assigned</span>
        <h1 class="text-2xl font-black text-white mb-1">You Got This Job!</h1>
        <p class="text-xs text-emerald-300 font-semibold mb-5">
          Claimed by <strong class="text-white"><?= htmlspecialchars($claimer) ?></strong>. The team has been notified.
        </p>

        <!-- Big Customer Details Card -->
        <div class="bg-[#0b132b]/90 p-5 rounded-2xl border border-slate-700 text-left mb-5 flex flex-col gap-2.5">
          <div>
            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Customer Name</span>
            <strong class="text-xl text-white font-black"><?= htmlspecialchars($lead['name']) ?></strong>
          </div>
          <div>
            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Service &amp; Vehicle Details</span>
            <div class="text-amber-300 font-bold text-sm"><?= htmlspecialchars($lead['service']) ?></div>
            <div class="text-slate-200 text-xs mt-0.5"><?= htmlspecialchars($lead['details']) ?></div>
          </div>
          <?php if (!empty($lead['notes'])): ?>
            <div class="pt-2 border-t border-slate-800">
              <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold mb-1">Customer Notes</span>
              <div class="text-slate-300 text-xs bg-slate-800/90 p-3 rounded-xl border border-slate-700/80"><?= htmlspecialchars($lead['notes']) ?></div>
            </div>
          <?php endif; ?>
        </div>

        <!-- GIANT 1-TAP CALL BUTTON -->
        <a 
          href="tel:<?= preg_replace('/[^0-9+]/', '', $lead['phone']) ?>" 
          class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-400 hover:from-emerald-400 hover:to-emerald-300 text-slate-950 font-black text-lg uppercase tracking-wider flex items-center justify-center gap-3 shadow-2xl active:scale-95 transition"
        >
          <svg class="w-6 h-6 text-slate-950 animate-bounce" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.5 3 3.6 5.1 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.2 1 .4 2.1.6 3.2.6.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.9 21.2 2.8 13.1 2.8 3.2 2.8 2.5 3.3 2 4 2h3.2c.7 0 1.2.5 1.2 1.2 0 1.1.2 2.2.6 3.2.1.4 0 .9-.2 1.2L6.6 10.8Z"/></svg>
          <span>Call <?= htmlspecialchars($lead['phone']) ?></span>
        </a>
      </div>

    <?php else: ?>
      <!-- UNCLAIMED LEAD: FAST 1-TAP CLAIM SCREEN -->
      <div>
        
        <!-- Job Banner -->
        <div class="bg-[#0b132b]/80 p-4 rounded-2xl border border-slate-700/80 mb-5 text-left">
          <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-400 block mb-1">Incoming Job Request</span>
          <h1 class="text-xl sm:text-2xl font-black text-white leading-tight"><?= htmlspecialchars($lead['service']) ?></h1>
          
          <div class="mt-2.5 pt-2.5 border-t border-slate-800 flex flex-col gap-1 text-xs">
            <div class="flex items-center gap-1.5"><span class="text-slate-400">👤 Customer:</span><strong class="text-white"><?= htmlspecialchars($lead['name']) ?></strong></div>
            <div class="flex items-center gap-1.5"><span class="text-slate-400">🚗 Details:</span><span class="text-slate-200"><?= htmlspecialchars($lead['details']) ?></span></div>
            <?php if (!empty($lead['notes'])): ?>
              <div class="flex items-start gap-1.5 mt-1 text-[11px] text-slate-300 bg-slate-800/80 p-2 rounded-lg"><span class="text-slate-400">📝</span><span><?= htmlspecialchars($lead['notes']) ?></span></div>
            <?php endif; ?>
          </div>
        </div>

        <form method="POST" class="flex flex-col gap-4">
          
          <!-- Technician Selector (Pill Segment) -->
          <div>
            <label class="text-[11px] font-bold text-slate-300 block mb-2 text-left uppercase tracking-wider">Select Who Is Claiming:</label>
            <div class="grid grid-cols-3 gap-2">
              <label class="relative cursor-pointer">
                <input type="radio" name="tech_name" value="Rahim" checked class="peer sr-only" />
                <div class="p-3 text-center rounded-xl bg-slate-800 border border-slate-700 peer-checked:bg-amber-500 peer-checked:text-slate-950 peer-checked:border-amber-400 font-extrabold text-xs transition">
                  👨‍🔧 Rahim
                </div>
              </label>

              <label class="relative cursor-pointer">
                <input type="radio" name="tech_name" value="KB" class="peer sr-only" />
                <div class="p-3 text-center rounded-xl bg-slate-800 border border-slate-700 peer-checked:bg-amber-500 peer-checked:text-slate-950 peer-checked:border-amber-400 font-extrabold text-xs transition">
                  🚐 KB
                </div>
              </label>

              <label class="relative cursor-pointer">
                <input type="radio" name="tech_name" value="Sako" class="peer sr-only" />
                <div class="p-3 text-center rounded-xl bg-slate-800 border border-slate-700 peer-checked:bg-amber-500 peer-checked:text-slate-950 peer-checked:border-amber-400 font-extrabold text-xs transition">
                  🚐 Sako
                </div>
              </label>
            </div>
          </div>

          <!-- ONE GIANT CLAIM BUTTON -->
          <button 
            type="submit" 
            class="w-full py-5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-black text-lg uppercase tracking-wider flex items-center justify-center gap-2.5 shadow-2xl active:scale-95 transition cursor-pointer"
          >
            <span>⚡ CLAIM THIS JOB</span>
            <span>&rarr;</span>
          </button>

          <div class="text-center text-[10px] text-slate-400">
            🔒 Claiming immediately notifies your team via SMS so no one else calls.
          </div>

        </form>

      </div>
    <?php endif; ?>

  </div>

</body>
</html>
