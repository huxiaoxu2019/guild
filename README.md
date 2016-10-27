# Guild

This daily build(CI/CD) tool using at topic team of Weibo.com, includes taking the version control system(git, svn - to be realized) information, such as log and diff, taking the project basic information, checking(compling) the project files syntax, deploying the project to the servers automatically, sending email blah blah blah...

## Demo

You could build the project just by the following cmds:

`00 23 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v6_inner_v720  DEPLOY_TYPE:inner,ONLINE_ALL:false,VCS:git &`

The `huati_v6_inner_v720` string in the above is the config param, such as huati_v6_inner_v725, huati_v6_inner_v730 and so on.

the secound `DEPLOY_TYPE:inner,ONLINE_ALL:false,VCS:git` string is the config param, too. The `:` left is the key, the `:` right is the value.

`DEPLOY_TYPE:inner` equals `define('DEPLOY_TYPE', 'inner')`.


## CMD Example

 - 线上持续部署
```
00 10 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v715  ONLINE_ALL:false,VCS:git &
00 16 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v715  ONLINE_ALL:true,VCS:git &
```

 - 内网持续构建
```
00 23 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v6_inner_v720  DEPLOY_TYPE:inner,ONLINE_ALL:false,VCS:git &
```

