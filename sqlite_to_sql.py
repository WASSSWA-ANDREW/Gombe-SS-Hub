import sqlite3
import sys
from pathlib import Path
db_path = Path('database') / 'database.sqlite'
if not db_path.exists():
    print('database file not found:', db_path)
    sys.exit(1)
con = sqlite3.connect(str(db_path))
con.text_factory = lambda b: b.decode(errors='ignore')
cur = con.cursor()
out = open('sqlite_dump.sql','w',encoding='utf-8')
# write schema
for row in cur.execute("SELECT sql FROM sqlite_master WHERE type='table' AND sql NOT NULL ORDER BY name"):
    sql = row[0]
    out.write(sql + ' ;\n')
# write data
for (tbl,) in cur.execute("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name"):
    cols = [d[0] for d in cur.execute(f"PRAGMA table_info('{tbl}')")]
    cols = [c[1] for c in cur.execute(f"PRAGMA table_info('{tbl}')")]
    # fetch rows
    rows = list(cur.execute(f"SELECT * FROM '{tbl}'"))
    if not rows:
        continue
    for r in rows:
        vals = []
        for v in r:
            if v is None:
                vals.append('NULL')
            else:
                s = str(v)
                s = s.replace("'", "''")
                vals.append("'" + s + "'")
        out.write(f"INSERT INTO `{tbl}` (`" + "`,`".join(cols) + "`) VALUES (" + ",".join(vals) + ");\n")
out.close()
con.close()
print('sqlite_dump.sql written')
