import dbConnect from './src/lib/mongodb.js';
import MunisysState from './src/models/MunisysState.js';

async function main() {
  await dbConnect();
  const state = await MunisysState.findOne({}).lean();
  console.log(JSON.stringify(state.homepage.solutions, null, 2));
  process.exit(0);
}
main();
