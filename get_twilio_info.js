const http = require('http');
const { WebSocket } = require('ws'); // or built-in if available, or we can use ws if installed or raw tcp / ws package

async function run() {
  http.get('http://localhost:9222/json/list', (res) => {
    let data = '';
    res.on('data', chunk => data += chunk);
    res.on('end', async () => {
      const tabs = JSON.parse(data);
      const twilioTab = tabs.find(t => t.url && t.url.includes('1console.twilio.com'));
      if (!twilioTab) {
        console.log('No Twilio tab');
        return;
      }
      console.log('Found Twilio tab WebSocket:', twilioTab.webSocketDebuggerUrl);
      
      // Let's use ws
      let WS;
      try {
        WS = require('ws');
      } catch (e) {
        // Install ws if needed
        const { execSync } = require('child_process');
        execSync('npm install ws --no-save');
        WS = require('ws');
      }
      
      const ws = new WS(twilioTab.webSocketDebuggerUrl);
      ws.on('open', () => {
        ws.send(JSON.stringify({
          id: 1,
          method: 'Runtime.evaluate',
          params: {
            expression: `
              (function() {
                // Find all inputs, buttons, and text
                const buttons = Array.from(document.querySelectorAll('button')).map(b => b.innerText);
                const text = document.body.innerText;
                return { text: text.substring(0, 4000) };
              })()
            `,
            returnByValue: true
          }
        }));
      });
      
      ws.on('message', (msg) => {
        const parsed = JSON.parse(msg);
        console.log('Result:', JSON.stringify(parsed, null, 2));
        ws.close();
      });
    });
  });
}

run();
