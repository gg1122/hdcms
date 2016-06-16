<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS - 免费开源多站点管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../../web/common/css/common.css" rel="stylesheet">
    <script>
        var hdjs = {
            path: 'resource/hdjs',
            upimage: {
                upload: "http://localhost/hdcms/index.php?s=core/util/upImage", //上传地址
                list: "http://localhost/hdcms/index.php?s=core/util/getImageLists",//图片列表地址
                del: "http://localhost/hdcms/index.php?s=core/util/removeImage",//删除图片
            },
            kindeditor: {
                uploadJson: 'ab',
                fileManagerJson: 'aa'
            }
        }
    </script>
    <script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="resource/hdjs/dist/hd.js"></script>
    <script src="../../../web/common/js/common.js"></script>
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        if (navigator.appName == 'Microsoft Internet Explorer') {
            if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
                alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
            }
        }
    </script>
</head>
<body class="hdcms">
                
<div class="container-fluid admin-top ">
    <!--导航-->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="http://localhost/hdcms/index.php?s=core/index/index"><i class="fa fa-w fa-cogs"></i> 系统管理</a>
                    </li>
                    <li>
                        <a href="http://www.hdphp.com/doc" target="_blank"><i class="fa fa-w fa-file-code-o"></i> 在线文档</a>
                    </li>
                    <li>
                        <a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 后盾论坛</a>
                    </li>
                    <li>
                        <a href="http://www.houdunwang.com" target="_blank"><i class="fa fa-w fa-phone-square"></i> 联系后盾</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; " aria-expanded="false">
                            <i class="fa fa-group"></i> 后盾网 <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="" target="_blank"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a></li>
                            <li><a href="" target="_blank"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="fa fa-w fa-user"></i>
                            admin(系统管理员)
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">修改密码</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="http://localhost/hdcms/index.php?s=core/login/out">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--导航end-->
</div>

               <div class="container-fluid  system-container">
    <div class="container-fluid" style="margin-top: 30px;margin-bottom: 20px;">
        <div class="col-md-6" style="background: url('../../../web/common/images/logo.png') no-repeat;background-size: contain;height: 60px;opacity: .9;">
        </div>
        <div class="col-md-6">
            <ul class="nav nav-pills pull-right">
                <li>
                    <a href="http://localhost/hdcms/index.php?s=core/index/index" class="tile ">
                        <i class="fa fa-sitemap fa-2x"></i>网站管理
                    </a>
                </li>
                <li>
                    <a href="http://localhost/hdcms/index.php?s=core/system/index" class="tile ">
                        <i class="fa fa-support fa-2x"></i>系统设置
                    </a>
                </li>
                <li>
                    <a href="http://localhost/hdcms/index.php?s=core/login/out" class="tile">
                        <i class="fa fa-sign-out fa-2x"></i>退出
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="well clearfix">
        
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="<?php echo u('system/index')?>">系统</a></li>
        <li class="active">已经安装模块</li>
    </ol>
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class="active"><a href="<?php echo u('installed')?>">设置网站信息</a></li>
        <li role="presentation"><a href="">设置公众号信息</a></li>
        <li role="presentation"><a href="?s=core/module/design">设置权限</a></li>
        <li role="presentation"><a href="?s=core/module/store">微信平台设置信息</a></li>
    </ul>
    <form action="?s=core/site/post&step=1" method="post" role="form" class="form-horizontal">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">设置网站基本信息</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label star">网站名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="如: 后盾网">
                        <span class="help-block">网站中显示的网站名称,可以使用中文等任意字符</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网站关键字</label>

                    <div class="col-sm-10">
                        <textarea name="keywords" class="form-control" cols="30" rows="3"></textarea>
                        <span class="help-block">
                            多个关键词用逗号分隔长度不能超过80个字符
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">网站描述</label>

                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">网站备案号</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="icp" placeholder="">
                        <span class="help-block">国内网站访问必须具有备案号,否则将可能受到封站的处理</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">系统业务处理</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label star">网站域名</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="domain">
                        <span class="help-block">
                            网站域名用来在除了微信访问时识别网站唯一性的标识,多个域名用英文半角逗号进行分隔, 如: houdunwang.com,hdphp.com,localhost,127.0.0.1都是合法域名
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">排序</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="orderby">
                        <span class="help-block">
                            当有相应域名在使用时的优先级,最大不能超过255数值越大优先级越高
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">下一步</button>
    <input type='hidden' name='__TOKEN__' value='ad1805af846745b13469cceefd9b1e5e'/></form>


    </div>
    <div class="text-muted footer">
        <a href="http://www.houdunwang.com">高端培训</a>
        <a href="http://www.hdphp.com">开源框架</a>
        <a href="http://bbs.houdunwang.com">后盾论坛</a>
        <br/>
        Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
    </div>
</div>
</body>
</html>
