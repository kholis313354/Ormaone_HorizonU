const fs = require('fs');
const path = require('path');
const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

async function main() {
  const sqlFilePath = path.join(__dirname, '..', '..', '..', 'u234715368_ormaone26.sql');
  console.log(`Reading SQL file: ${sqlFilePath}`);
  
  const content = fs.readFileSync(sqlFilePath, 'utf8');
  console.log('Splitting statements by semicolon...');
  const statements = content.split(';');

  console.log(`Found ${statements.length} potential SQL statements.`);
  
  await prisma.$executeRawUnsafe('SET session_replication_role = \'replica\';');

  let importedCount = 0;
  let errorCount = 0;

  for (let statement of statements) {
    let sql = statement.trim();
    if (!sql || !sql.toUpperCase().startsWith('INSERT INTO')) continue;

    // Table / Column Cleanups for PostgreSQL
    let cleanSql = sql
      .replace(/`(?=[^']*(?:'[^']*'[^']*)*$)/g, '"') // Backticks to quotes
      // Table mapping
      .replace(/INSERT INTO "organisasi" \(/g, 'INSERT INTO "organisasis" (')
      .replace(/INSERT INTO "users" \(/g, 'INSERT INTO "users" (') // Already correct
      // Field mappings
      .replace(/("kalender" \("id", "user_id", )"event_title", "event_color"/g, '$1"title", "color"')
      // Timestamp fix
      .replace(/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+/g, (m) => m.split('.')[0])
      ;

    try {
      await prisma.$executeRawUnsafe(cleanSql);
      importedCount++;
      if (importedCount % 5 === 0) console.log(`Successfully imported ${importedCount} tables/batches...`);
    } catch (err) {
      errorCount++;
      console.error(`Error in statement: ${err.message.substring(0, 150)}`);
    }
  }

  await prisma.$executeRawUnsafe('SET session_replication_role = \'origin\';');
  console.log(`\nImport Finished!`);
  console.log(`Total Batches Imported: ${importedCount}`);
  console.log(`Total Errors: ${errorCount}`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
