<?php
// claim.php - Interactive 1-Click Mobile Job Dispatch Claim System
$leadId = isset($_GET['id']) ? trim($_GET['id']) : '';
$techClaimant = isset($_POST['tech_name']) ? trim($_POST['tech_name']) : '';

$leadsFile = __DIR__ . '/api/.leads_store.json';
$leads = file_exists($leadsFile) ? json_decode(file_get_contents($leadsFile), true) : [];

if (!is_array($leads)) {
    $leads = [];
}

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
        
        // Technicians list
        $techPhones = [
            '+16156258000'
        ];

        $broadcastMsg = "📢 LEAD CLAIMED!\n"
                      . "👤 Customer: " . $lead['name'] . "\n"
                      . "✅ Claimed by: " . $claimer . " at " . $claimedAt . "\n"
                      . "🚫 Do NOT call this customer.";

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
  <title>Armstrong Dispatch &bull; Claim Job</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">

  <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-3xl p-6 sm:p-8 shadow-2xl">
    
    <!-- Top Header -->
    <div class="flex items-center justify-between border-b border-slate-700 pb-4 mb-5">
      <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
        <span class="text-xs font-black uppercase tracking-wider text-amber-400">Armstrong Dispatch</span>
      </div>
      <span class="text-xs font-mono text-slate-400">#<?= htmlspecialchars($leadId) ?></span>
    </div>

    <?php if (!$lead): ?>
      <div class="text-center py-8">
        <div class="text-4xl mb-2">🔍</div>
        <h1 class="text-xl font-bold text-white mb-1">Lead Not Found</h1>
        <p class="text-xs text-slate-400">This lead ticket may have expired or been removed.</p>
      </div>

    <?php elseif ($alreadyClaimed): ?>
      <!-- Already Claimed View -->
      <div class="text-center py-4">
        <div class="w-16 h-16 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/40 flex items-center justify-center text-3xl mx-auto mb-4">
          🔒
        </div>
        <h1 class="text-2xl font-black text-white mb-1">Already Claimed!</h1>
        <p class="text-sm text-slate-300 mb-6">
          This customer is already being handled by <strong class="text-amber-400 font-bold"><?= htmlspecialchars($claimer) ?></strong> at <?= htmlspecialchars($claimedAt) ?>.
        </p>

        <div class="bg-slate-900/60 p-4 rounded-2xl border border-slate-700/60 text-left text-xs mb-6 flex flex-col gap-1.5">
          <div class="flex justify-between"><span class="text-slate-400">Customer:</span><strong class="text-white"><?= htmlspecialchars($lead['name']) ?></strong></div>
          <div class="flex justify-between"><span class="text-slate-400">Service:</span><span class="text-slate-200"><?= htmlspecialchars($lead['service']) ?></span></div>
          <div class="flex justify-between"><span class="text-slate-400">Details:</span><span class="text-slate-200"><?= htmlspecialchars($lead['details']) ?></span></div>
        </div>

        <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-bold">
          ⚠️ Please do not call the customer.
        </div>
      </div>

    <?php elseif ($claimSuccess): ?>
      <!-- Claim Success View -->
      <div class="text-center py-4">
        <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center text-3xl mx-auto mb-4">
          ✅
        </div>
        <h1 class="text-2xl font-black text-white mb-1">Job Claimed!</h1>
        <p class="text-xs text-emerald-400 font-bold mb-5">
          Confirmed for <?= htmlspecialchars($claimer) ?>. Team notified.
        </p>

        <!-- Customer Lead Card -->
        <div class="bg-slate-900/80 p-5 rounded-2xl border border-slate-700 text-left mb-6 flex flex-col gap-2 text-sm">
          <div>
            <span class="text-[11px] text-slate-400 block uppercase font-bold">Customer Name</span>
            <strong class="text-lg text-white font-bold"><?= htmlspecialchars($lead['name']) ?></strong>
          </div>
          <div>
            <span class="text-[11px] text-slate-400 block uppercase font-bold">Service &amp; Details</span>
            <div class="text-slate-200"><?= htmlspecialchars($lead['service']) ?> &bull; <?= htmlspecialchars($lead['details']) ?></div>
          </div>
          <?php if (!empty($lead['notes'])): ?>
            <div>
              <span class="text-[11px] text-slate-400 block uppercase font-bold">Notes</span>
              <div class="text-slate-300 text-xs bg-slate-800/80 p-2.5 rounded-lg border border-slate-700"><?= htmlspecialchars($lead['notes']) ?></div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Big Click to Call Button -->
        <a 
          href="tel:<?= preg_replace('/[^0-9+]/', '', $lead['phone']) ?>" 
          class="w-full py-4 px-6 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-base uppercase tracking-wider flex items-center justify-center gap-3 shadow-lg active:scale-95 transition"
        >
          <svg class="w-6 h-6 text-slate-950" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.5 3 3.6 5.1 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.2 1 .4 2.1.6 3.2.6.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.9 21.2 2.8 13.1 2.8 3.2 2.8 2.5 3.3 2 4 2h3.2c.7 0 1.2.5 1.2 1.2 0 1.1.2 2.2.6 3.2.1.4 0 .9-.2 1.2L6.6 10.8Z"/></svg>
          <span>Call <?= htmlspecialchars($lead['phone']) ?></span>
        </a>
      </div>

    <?php else: ?>
      <!-- Unclaimed Lead: Select Who Is Taking It -->
      <div>
        <div class="mb-5">
          <span class="text-[11px] text-amber-400 font-extrabold uppercase tracking-wider">Unassigned Job</span>
          <h1 class="text-xl sm:text-2xl font-black text-white"><?= htmlspecialchars($lead['service']) ?></h1>
          <div class="text-slate-300 text-xs mt-1">👤 <?= htmlspecialchars($lead['name']) ?> &bull; 🚗 <?= htmlspecialchars($lead['details']) ?></div>
        </div>

        <p class="text-xs text-slate-300 mb-4">Tap your name below to claim this ticket and notify the team:</p>

        <form method="POST" class="flex flex-col gap-2.5">
          <button 
            type="submit" 
            name="tech_name" 
            value="Rahim" 
            class="w-full py-3.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-sm uppercase tracking-wider flex items-center justify-between shadow transition active:scale-95 cursor-pointer"
          >
            <span>👨‍🔧 Rahim (Owner)</span>
            <span>Claim Job &rarr;</span>
          </button>

          <button 
            type="submit" 
            name="tech_name" 
            value="Technician 1" 
            class="w-full py-3.5 px-4 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-black text-sm uppercase tracking-wider flex items-center justify-between shadow transition active:scale-95 cursor-pointer"
          >
            <span>🚐 Technician 1</span>
            <span>Claim Job &rarr;</span>
          </button>

          <button 
            type="submit" 
            name="tech_name" 
            value="Technician 2" 
            class="w-full py-3.5 px-4 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-black text-sm uppercase tracking-wider flex items-center justify-between shadow transition active:scale-95 cursor-pointer"
          >
            <span>🚐 Technician 2</span>
            <span>Claim Job &rarr;</span>
          </button>
        </form>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>
