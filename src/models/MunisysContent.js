import mongoose from 'mongoose';

const MunisysContentSchema = new mongoose.Schema(
  {
    page: { type: String, required: true, trim: true, lowercase: true },
    section: { type: String, required: true, trim: true, lowercase: true },
    key: { type: String, required: true, trim: true, unique: true },
    value: { type: String, required: true },
    type: { type: String, enum: ['text', 'html', 'image', 'link'], default: 'text' }
  },
  { timestamps: true }
);

MunisysContentSchema.index({ page: 1 });
MunisysContentSchema.index({ page: 1, key: 1 }, { unique: true });

const MunisysContent = mongoose.models.MunisysContent || mongoose.model('MunisysContent', MunisysContentSchema);

export default MunisysContent;
