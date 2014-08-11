--
-- create_db.sql
--
-- Copyright (c) 2000-2003 Ruth Ivimey-Cook
-- Licensed under the GNU GPL. For full terms see the file COPYING.
--
-- Database schema for paperdb
--
-- $Id: create_db.sql,v 1.4 2004/11/16 00:52:20 rivimey Exp $
--
-- $Log: create_db.sql,v $
-- Revision 1.4  2004/11/16 00:52:20  rivimey
-- Fixes for a number of html and database errors, as well as starting the
-- implementation of more statistics in the database.
--
-- Revision 1.3  2004/11/04 17:55:17  rivimey
-- reftext was too small.
--
-- Revision 1.2  2004/11/04 17:52:12  rivimey
-- Major update of bibtex/refer actions to support text/plain output.
--
-- Revision 1.1  2003/05/11 17:00:50  ruthc
-- Initial release v0.5
--

drop database if exists wotug;

create database wotug;

use wotug;

create table proceedings (
    proceedingid int unsigned not null auto_increment primary key,
    title varchar(240) not null,
    subtitle varchar(240) null,
    publisherid int unsigned not null,
    series varchar(200) null,
    isbn varchar(14) not null,
    issn varchar(14) null,
    organisation int unsigned null,
    volume int unsigned null,
    editors int unsigned null,
    totpages int unsigned null,
    proceedingurl varchar(240) null,
    pubyear int unsigned null key,
    pubmonth int unsigned null,
    pubday int unsigned null

    created datetime null,
    modified datetime null,
    accessed datetime null,
    accesses int unsigned null
);

create table admin (
    username char(16) not null primary key,
    password char(16) not null
);

-- links proceedings to papers.
create table paperlist (
    paperid int unsigned not null,
    proceedingid int unsigned not null,
    firstpage int unsigned,
    lastpage int unsigned,
    KEY(paperid),
    KEY(proceedingid)
);

-- enable people to pass comment on papers
create table commentlist (
     paperid int unsigned not null,
     commentid  int unsigned not null
);

create table comments (
    commentid int unsigned not null auto_increment primary key,
    authorid int unsigned not null,
    comment text not null
);

-- the "main" table. Contains details of the papers in the database.
create table papers (
    paperid int unsigned not null auto_increment primary key,
    title varchar(240) not null,
    reftext varchar(64),
    paper_url varchar(250) null,
    abstract text,
    created datetime null,
    modified datetime null,
    accessed datetime null,
    accesses int unsigned null,
    KEY(title)
);

-- links papers and paper files together. Referenced by papers in papers table.
create table paperfilelist (
    paperid int unsigned not null primary key,
    fileid int unsigned not null
);

-- contains the files associated with each paper. record filename & storage date
-- for user info (not reqd by system).
create table paperfile (
    fileid int unsigned not null auto_increment primary key,
    filetype enum("PDF", "PS", "HTML", "PPT", "MSW" ) not null,
    compressed enum("Gzip","No") not null,
    filename varchar(64),
    created datetime null,
    modified datetime null,
    accessed datetime null,
    accesses int null,
    paper longblob not null
);

-- contains information about publishers of books. Referred to by publisherid
-- in the proceedings table.
create table publisherinfo (
    publisherid int unsigned not null auto_increment primary key,
    name varchar(200) not null,
    homepage varchar(240) null,
    address1 varchar(80) null,
    address2 varchar(80) null,
    address3 varchar(80) null,
    city varchar(40) null,
    area varchar(40) null,
    country varchar(40) null,
    notes varchar(80) null
);


-- links proceedings to people, These people are the proceedings editors. indexed
-- by proceedingid in proceedings table.
create table editorlist (
    editors int unsigned not null,
    editorid int unsigned not null,
    ordering int not null
    KEY(editors),
    KEY(editorid)
);

-- links papers to people, as the authors of the papers. Referenced by
-- paperid in the papers table.
create table authorlist (
    authors int unsigned not null,
    authorid int unsigned not null,
    ordering int not null,
    KEY(authors),
    KEY(authorid)
);

-- contains the details of people referenced in the database. Referenced
-- by editors in proceedings, authors in papers.
create table people (
    personid int unsigned not null auto_increment primary key,
    firstname varchar(80) null,
    lastname varchar(80) not null,
    title varchar(20) null,
    email varchar(100) null,
    organisation int unsigned null,
    homepage varchar(240) null,
    address1 varchar(80) null,
    address2 varchar(80) null,
    address3 varchar(80) null,
    city varchar(40) null,
    area varchar(40) null,
    country varchar(40) null,
    notes varchar(80) null,
    lastverified date null,
    KEY(lastname),
    KEY(firstname)
);

-- contains the details of organisations that people work for. Referenced by
-- organisation in people.
create table organisations (
    orgid int unsigned not null auto_increment primary key,
    name varchar(80) not null,
    address1 varchar(80) null,
    address2 varchar(80) null,
    address3 varchar(80) null,
    city varchar(80) null,
    area varchar(40) null,
    country varchar(40) null,
    email varchar(100) null,
    notes varchar(80) null,
    homepage varchar(240) null
);

insert into admin set username = 'ruthc', password = MD5('gardens');

