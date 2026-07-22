import fs from 'fs/promises';
import path from 'path';
import { NextResponse } from 'next/server';

export const dynamic = 'force-dynamic';

const dbPath = path.join(process.cwd(), 'src/data/db.json');
const backupsDir = path.join(process.cwd(), 'src/data/backups');

export async function createBackup() {
  try {
    // Ensure backups directory exists
    await fs.mkdir(backupsDir, { recursive: true });

    // Read current db
    const fileContents = await fs.readFile(dbPath, 'utf8');

    // Generate filename with timestamp
    const date = new Date();
    const timestamp = date.toISOString().replace(/[:.]/g, '-');
    const backupPath = path.join(backupsDir, `db_backup_${timestamp}.json`);

    // Write backup
    await fs.writeFile(backupPath, fileContents, 'utf8');
    
    // Optional: Keep only the last 30 backups to avoid filling the disk
    const files = await fs.readdir(backupsDir);
    const backupFiles = files.filter(f => f.startsWith('db_backup_')).sort();
    if (backupFiles.length > 30) {
      const filesToDelete = backupFiles.slice(0, backupFiles.length - 30);
      for (const file of filesToDelete) {
        await fs.unlink(path.join(backupsDir, file));
      }
    }

    return { success: true, file: `db_backup_${timestamp}.json` };
  } catch (error) {
    console.error('Backup creation failed:', error);
    return { success: false, error };
  }
}

export async function POST() {
  const result = await createBackup();
  if (result.success) {
    return NextResponse.json({ success: true, message: 'Sauvegarde créée avec succès', file: result.file });
  } else {
    return NextResponse.json({ success: false, message: 'Échec de la sauvegarde' }, { status: 500 });
  }
}
