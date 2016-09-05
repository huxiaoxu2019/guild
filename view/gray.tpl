{include file='common/header.tpl'}
<body>
    <div class='container'>

      <div class="blog-header">
        <h3 class="blog-title text-success">huati_V7.0.5(Daily Build 1608.3101) Build Success!</h1>
        <p class="lead blog-description"></p>
      </div>

      <div class="row">
        <div class="col-sm-12 blog-main">
          <div class="blog-post">
            <h4 class="blog-post-title">版本介绍</h2>
            <p>超级话题三期V705功能开发<br />版本计划上线时间：2016年09月01日</p>
          </div><!-- /.blog-post -->

        </div><!-- /.blog-main -->

        <div class="col-sm-12 blog-main">
            <h4 class="blog-post-title">Git提交记录</h2>
              <!-- Table -->
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Revision</th>
                      <th>Description</th>
                      <th>Author</th>
                      <th>File</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1b0938d</td>
                      <td>降级超级话题bug修改</td>
                      <td>huxu</td>
                      <td class='break-all'>
                      M application/models/Emcee/LittleEmcee.php<br />
                      M application/models/Relative/Recommend.php<br />
                      M application/modules/Internal/controllers/Admin/Super/Degradetopic.php<br />
                      M conf/crontab.ini<br />
                      </td>
                      <td>2016-08-31 20:02:40</td>
                    </tr>
                    <tr>
                      <td>1b0938d</td>
                      <td>降级超级话题bug修改</td>
                      <td>huxu</td>
                      <td>
                      M application/models/Emcee/LittleEmcee.php<br />
                      M application/models/Relative/Recommend.php<br />
                      M conf/crontab.ini<br />
                      </td>
                      <td>2016-08-31 20:02:40</td>
                    </tr>
                  </tbody>
                </table>
        </div><!-- /.blog-main -->

        <div class="col-sm-12 blog-main">
            <h4 class="blog-post-title">内网集成测试环境</h2>
            <ul>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
              <li>10.210.241.151 i.huati.weibo.com</li>
            </ul>
            <strong>第一种测试方式：</strong>
            <p>请绑定本机电脑HOST信息(api.weibo.cn 10.13.130.66)后，将手机代理设置为本机电脑IP即可访问测试环境。</p>
            <strong>第二种测试方式：</strong>
            <p>
              首先用该账号登陆微博客户端<br />
              账号：showproject<br />
              密码：*#project#*<br />
              如果是IOS：<br />
              在微博客户端设置 -> 查看调试信息 —> 选择服务器 -> 选择http://10.13.130.66 <br />
              如果是安卓：<br />
              在微博客户端设置 -> 账号管理 -> 工程模式 —> 服务器地址 -> 在微博API地址下拉菜单中选择自定义 -> 输入自定义host:http://10.13.130.66 -> 确定保存即可<br />
            </p>
        </div><!-- /.blog-main -->

        <div class="col-sm-12 blog-main">
            <h4 class="blog-post-title">产品信息</h2>
              <!-- Table -->
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Project</td>
                      <td>话题</td>
                    </tr>
                    <tr>
                      <td>Programmers</td>
                      <td>恩淑;王颖;枨宣;胡旭;文东;永丽;思然;立鹏;娟娟;耿浩</td>
                    </tr>
                    <tr>
                      <td>Project Managers</td>
                      <td>王颖;枨宣;胡旭;文东;永丽;思然;立鹏;娟娟;耿浩</td>
                    </tr>
                    <tr>
                      <td>Testers</td>
                      <td>王颖;枨宣;胡旭;文东;永丽;思然;立鹏;娟娟;耿浩</td>
                    </tr>
                    <tr>
                      <td>Stake Holers</td>
                      <td>王颖;枨宣;胡旭;文东;永丽</td>
                    </tr>
                    <tr>
                      <td>VCS URL</td>
                      <td>ssh://git@git.intra.weibo.com:2222/huati/huati-v6.git</td>
                    </tr>
                  </tbody>
                </table>
        </div><!-- /.blog-main -->

      </div><!-- /.row -->

    </div><!-- /.container -->
</body>
{include file='common/footer.tpl'}
