import json
import urllib.request
import asyncio
import sys

# Try importing websockets or fallback
try:
    import websockets
except ImportError:
    import subprocess
    subprocess.check_call([sys.executable, "-m", "pip", "install", "websockets"])
    import websockets

async def fetch_info():
    req = urllib.request.urlopen("http://localhost:9222/json/list")
    tabs = json.loads(req.read().decode())
    target = None
    for t in tabs:
        if "1console.twilio.com" in t.get("url", ""):
            target = t
            break
            
    if not target:
        print("Twilio tab not found")
        return
        
    ws_url = target["webSocketDebuggerUrl"]
    print(f"Connecting to: {ws_url}")
    
    async with websockets.connect(ws_url) as ws:
        # Evaluate script on the page
        cmd = {
            "id": 1,
            "method": "Runtime.evaluate",
            "params": {
                "expression": """
                (function() {
                    // Extract text content around Account Info
                    const bodyText = document.body.innerText;
                    return bodyText;
                })()
                """,
                "returnByValue": True
            }
        }
        await ws.send(json.dumps(cmd))
        res = await ws.recv()
        data = json.loads(res)
        text = data.get("result", {}).get("result", {}).get("value", "")
        print("=== EXTRACTED TEXT ===")
        print(text[:3000])

asyncio.run(fetch_info())
