-- This is the structure of my table. you can modify it as you need.
-- On my system I run a script to gather the category information
-- and store it in this table. That is beyond the scope of this code.

CREATE TABLE ebay_categories (
  CategoryID int(10) NOT NULL default '0',
  CategoryLevel int(5) NOT NULL default '0',
  CategoryName varchar(120) NOT NULL default '',
  CategoryParentID int(10) NOT NULL default '0',
  LeafCategory int(1) NOT NULL default '0',
  AutoPayEnabled int(1) NOT NULL default '0',
  Expired int(1) NOT NULL default '0',
  IntlAutosFixedCat int(1) NOT NULL default '0',
  Virtual int(1) NOT NULL default '0',
  LSD int(1) NOT NULL default '0',
  ORPA int(1) NOT NULL default '0',
  PRIMARY KEY  (CategoryID),
  KEY catlevel (CategoryLevel),
  KEY parent (CategoryParentID),
  KEY ape (AutoPayEnabled),
  KEY expired (Expired),
  KEY IAFC (IntlAutosFixedCat),
  KEY virtual (Virtual),
  KEY lsd (LSD),
  KEY orpa (ORPA),
  KEY leaf (LeafCategory)
) TYPE=MyISAM;

