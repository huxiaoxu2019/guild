# Guild

This daily build(CI/CD) tool using at topic team of Weibo.com, includes taking the version control system(git, svn) information, such as log and diff, taking the project basic information, checking(compling) the project files syntax, deploying the project to the servers automatically, sending email blah blah blah...

## Demo

You could build the project just by the following cmds:

`php /your/build/path/bootstrap.php v700_huati SHOW_COMMIT:false  > /root/run/huati_console &`

The `v700_huati` string in the above is the config param, such as v700_huati, v700_hotmblog and so on.

the secound `SHOW_COMMIT:false` string is the config param, too. The `:` left is the key, the `:` right is the value.

`SHOW_COMMIT:false` equals `define("SHOW_COMMIT", 'false')`.
