CREATE TABLE companies (
  "companyId" INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT,
  "createdOn" TEXT DEFAULT (strftime('%Y-%m-%dT%H:%MZ', 'now'))
);

INSERT INTO companies (name) values ('Company1'), ('Company2');
