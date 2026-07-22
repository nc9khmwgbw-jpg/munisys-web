import mongoose from 'mongoose';

const MunisysStateSchema = new mongoose.Schema(
  {
    // Un seul document contiendra tout le JSON
    data: { type: mongoose.Schema.Types.Mixed, required: true },
    version: { type: Number, default: 1 }
  },
  { timestamps: true }
);

const MunisysState = mongoose.models.MunisysState || mongoose.model('MunisysState', MunisysStateSchema);

export default MunisysState;
