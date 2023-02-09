#
# Table structure for table 'tx_cysendmails_domain_model_message' 
#                            
CREATE TABLE tx_cysendmails_domain_model_message (
   sender int (11) UNSIGNED DEFAULT '0' NOT NULL,
   receivers MEDIUMTEXT,
   subject TEXT,
   message MEDIUMTEXT,
   attachments_meta_data MEDIUMTEXT 
);

#
# Table structure for table 'tx_cysendmails_fegroups_receivergroup_mm' 
#
CREATE TABLE tx_cysendmails_fegroups_receivergroup_mm (
   
   uid_local int (11) UNSIGNED DEFAULT '0' NOT NULL,
   uid_foreign int (11) UNSIGNED DEFAULT '0' NOT NULL,
   sorting int (11) UNSIGNED DEFAULT '0' NOT NULL,
   sorting_foreign int (11) UNSIGNED DEFAULT '0' NOT NULL,
   
   PRIMARY KEY
   (
      uid_local,
      uid_foreign
   ),
   
   KEY uid_local (uid_local),
   KEY uid_foreign (uid_foreign)
);


# Table structure for table 'fe_groups' 
#
CREATE TABLE fe_groups (
   receiver_group smallint (5) UNSIGNED DEFAULT '0' NOT NULL,
   receiver_group_name varchar(40) DEFAULT '' NOT NULL
   
);




