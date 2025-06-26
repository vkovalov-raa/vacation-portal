CREATE TABLE users (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   name TEXT, email TEXT, role TEXT,
   password_hash TEXT
);
CREATE TABLE vacation_requests (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   user_id INTEGER,
   start_date TEXT,
   end_date   TEXT,
   reason     TEXT,
   status     TEXT DEFAULT 'pending',
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users(name,email,role,password_hash) VALUES
 ('Manager','admin@company.test','manager','x'),
 ('User','user@company.test','employee','x');