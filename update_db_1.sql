--
-- update_db_1.sql
--
-- Copyright (c) 2014 Ruth Ivimey-Cook
-- Licensed under the GNU GPL. For full terms see the file COPYING.
--
-- Database schema update for paperdb
--
-- To use ALTER TABLE, you need ALTER, CREATE, and INSERT privileges for the table!

use wotug;

alter table proceedings modify isbn varchar(20) not null;

alter table papers add itemtype enum("KEYNOTE", "ENDNOTE", "PAPER", "FRINGE", "WORKSHOP") not null default "PAPER";

-- supplementary links associated with papers.
create table paperlink (
    linkid int unsigned not null auto_increment primary key,
    paperid int unsigned not null,
    href varchar(240) not null,
    title varchar(240) not null,
    filetype varchar(64) null,
    verbose boolean not null default 0,
    ordering int unsigned not null default 0,
    KEY(paperid),
    KEY(href)
);

-- lists paperlinks provide an alternative to paperfiles.
create table oldpaperfile (
    fileid int unsigned not null primary key,
    linkid int unsigned not null
);
