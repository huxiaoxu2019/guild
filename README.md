# Guild

Guild是一款持续集成与部署的工具，目前正应用在微博话题组。她包含了提取版本控制（git，svn - 待支持）信息的提取，比如log和diff信息，提取项目的基本信息，检查（编译）项目源文件，自动部署和发送邮件等功能。

## 注意

当前的项目并不是很完善，并且文档（README.md）也很简略，在此表示歉意。所以，如果你对该项目有着浓厚的兴趣，可以按照下面的联系方式联系我。

## TODO
- [CD]Merge build branch to master branch before deploying to all online servers step. DOING
- Support for SVN repository. UNDO
- Complete the wiki in english. DONE
- Complete the wiki in chinese. DONE

## 命令简介

你可以使用下面的命令来构建你的项目：

```
00 23 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v6_inner_v720  DEPLOY_TYPE:inner,ONLINE_ALL:false,VCS:git &
```

上面的`huati_v6_inner_v720`字段是配置项的名称，对应着config下面的目录。你可以任意写，比如huati_v6_inner_v725, huati_v6_inner_v730.

第二个字段`DEPLOY_TYPE:inner,ONLINE_ALL:false,VCS:git`同样也是配置参数. `:`符号左边的是配置项, 右边的是配置项的值.

`DEPLOY_TYPE:inner`相当于程序中的`define('DEPLOY_TYPE', 'inner')`.


## 命令举例

 - 持续部署
```
00 10 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v715  ONLINE_ALL:false,VCS:git &
00 16 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v715  ONLINE_ALL:true,VCS:git &
```

在执行上述命令后，您会看到如下的界面。

![](https://github.com/GenialX/guild/blob/master/demo/build_console_1.png?raw=true)

![](https://github.com/GenialX/guild/blob/master/demo/build_console_2.png?raw=true)

![](https://github.com/GenialX/guild/blob/master/demo/build_console_3.png?raw=true)

 - 持续集成
```
00 23 * * * /usr/local/bin/php /data1/www/htdocs/guild.com/bootstrap.php huati_v6_inner_v720  DEPLOY_TYPE:inner,ONLINE_ALL:false,VCS:git &
```

## 贡献

任何会用PHP语言的人都可以成为该项目的贡献人，同时贡献人的名字也会有所展示。

但是, 任何的贡献都需要遵循下面的规范：[coding standard](/CODING_STANDARD.md)。


## 联系

 - [关于我](http://www.ihuxu.com/blog/about)
