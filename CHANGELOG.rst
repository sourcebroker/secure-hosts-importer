Changelog
---------

1.2.1
~~~~~
1) [BUGFIX] Fix wrong tag in changelog
2) [BUGFIX] Fix typo in subtask description.

1.2.0
~~~~~
1) [TASK] Replace "\n" with EOL for some cases.
2) [TASK] Make the backup name more unique like "hosts.20181012091013.secure-hosts-importer.backup".
3) [TASK] Move exception "Host file under path not found" to beginning of flow.
4) [FEATURE] Create markers at the end of current host file if they do not exists. Move backup of hosts file at top of
   flow because of this.

1.1.0
~~~~~
1) [TASK] Remove composer.json / change documentation.

1.0.0
~~~~~
1) [TASK] Init version.