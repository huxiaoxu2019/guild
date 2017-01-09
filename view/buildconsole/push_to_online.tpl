<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="/favicon.ico">

    <title>部署控制台 - Guild</title>

    <!-- Bootstrap core CSS -->
    <link href="http://v3.bootcss.com/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://v3.bootcss.com/examples/navbar-fixed-top/navbar-fixed-top.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://github.com/genialx/guild">Guild</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">部署控制台</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="row jumbotron">
        <div class="col-md-10">
            <p class="text-danger">请在该版本自动部署前，确认当前版本是否通过验证标准。若在自动部署前无确认操作，系统将放弃本次部署，并回滚到上一次可用状态。</p>
        </div>
        <div class="col-md-2">
            <p>
            {if $data.build_status eq BUILD_STATUS_DEPLOYED}
                <button type="button" class="btn btn-success disabled">已部署</button>
            {elseif $data.build_status eq BUILD_STATUS_PASSED}
                <button type="button" class="btn btn-success disabled">已通过</button>
            {elseif $data.build_status eq BUILD_STATUS_NOT_PASSED}
                <button type="button" class="btn btn-danger disabled">未通过</button>
            {else}
                <a href='/BuildConsole/setBuildStatus?version={APP_NAME}&build_version={BUILD_VERSION}&status={BUILD_STATUS_PASSED}'><button type="button" class="btn btn-success">通过</button></a>
                <a href='/BuildConsole/setBuildStatus?version={APP_NAME}&build_version={BUILD_VERSION}&status={BUILD_STATUS_NOT_PASSED}'><button type="button" class="btn btn-danger">驳回</button></a>
            {/if}
            </p>
        </div>
      </div>

      <div class="row">
        <h3 class="page-header">构建信息</h3>
        <ul>
            <li>项目：{$data.product.name}</li>
            <li>构建版本：{$data.build_version}</li>
            <li>本次构建时间：{$data.build_time|date_format:"%Y-%m-%d %H:%M:%S"}</li>
            <li>预计部署时间：{$data.deploy_plan_time|date_format:"%Y-%m-%d %H:%M:%S"}</li>
        </ul>
      </div>

      <div class="row">
        <h3 class="page-header">邮件正文</h3>
        {$data.mail_content}
      </div>

      <div class="row">
        <h3 class="page-header">邮件附件</h3>
        {foreach from=$data.mail_attachment item=vo}
        {$vo}<br />
        {/foreach}
      </div>

      <div class="row">
        <h3 class="page-header">构建日志</h3>
        {foreach from=$data.runtime_log item=vo}
        {$vo}<br />
        {/foreach}
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://v3.bootcss.com/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="http://v3.bootcss.com/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
