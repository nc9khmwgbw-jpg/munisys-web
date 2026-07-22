const translateApi = require('@vitalets/google-translate-api');
async function test() {
  try {
    const res = await translateApi.translate('Hello ||| World', { to: 'es' });
    console.log("RES:", res.text);
  } catch(e) {
    console.error("ERR:", e.message);
  }
}
test();
