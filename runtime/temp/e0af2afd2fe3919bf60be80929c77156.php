<?php /*a:3:{s:73:"D:\program\phpEnv\www\localhost\tp6_room_reservation\view\room\index.html";i:1662601900;s:76:"D:\program\phpEnv\www\localhost\tp6_room_reservation\view\Public\header.html";i:1662603797;s:76:"D:\program\phpEnv\www\localhost\tp6_room_reservation\view\Public\footer.html";i:1662599518;}*/ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo htmlentities($title); ?></title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="stylesheet" href="/assets/lib/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="/assets/stylesheets_wintertide/theme.css" />
        <link rel="stylesheet" href="/assets/lib/font-awesome/css/font-awesome.css" />
        <link rel="stylesheet" href="/assets/css/other.css" />
        <link rel="stylesheet" href="/assets/css/jquery-ui.css" />
        <script src="/assets/lib/jquery-1.8.1.min.js"></script>
        <script src="/assets/lib/jquery.cookie.js"></script>
        <script src="/assets/lib/bootstrap/js/bootbox.min.js"></script>
        <script src="/assets/lib/bootstrap/js/bootstrap-modal.js"></script>
        <script src="/assets/js/other.js"></script>
        <script src="/assets/js/jquery-ui.js"></script>
        <!-- Demo page code -->
        <style type="text/css">
            #line-chart {
                height:300px;
                width:800px;
                margin: 0px auto;
                margin-top: 1em;
            }
            .brand { font-family: georgia, serif; }
            .brand .first {
                color: #ccc;
                font-style: italic;
            }
            .brand .second {
                color: #fff;
                font-weight: bold;
            }
        </style>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="assets/js/html5.js"></script>
        <![endif]-->
    </head>
    <body id="body" class="body">
        <div class="navbar">
            <div class="navbar-inner">
                <ul class="nav pull-right">
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-user"></i> <?php echo htmlentities($user_info['username']); ?> <i class="icon-caret-down"></i> </a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="/login/logout">注销</a></li>
                        </ul>
                    </li>
                </ul>
                <a class="brand" href="/"><span class="first"></span> <span class="second">管理中心</span> </a>
            </div>
        </div>
        <div id="sidebar-nav" class="sidebar-nav">
            <?php if(is_array($sidebar_list) || $sidebar_list instanceof \think\Collection || $sidebar_list instanceof \think\Paginator): $i = 0; $__LIST__ = $sidebar_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <a href="#sidebar_menu_<?php echo htmlentities($key); ?>" class="nav-header collapsed" data-toggle="collapse"><i class="icon-th"></i><?php echo htmlentities(format_menu_pid_name($key)); ?> <i class="icon-chevron-up"></i></a>
            <ul id="sidebar_menu_<?php echo htmlentities($key); ?>" class="nav nav-list collapse in">
                <?php if(is_array($vo) || $vo instanceof \think\Collection || $vo instanceof \think\Paginator): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;if($item['is_nav'] == 1): ?>
                <li><a href="<?php echo htmlentities(format_node_url($item['node_id'])); ?>"><?php echo htmlentities($item['menu_name']); ?></a></li>
                <?php endif; ?>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!-- 以上为左侧菜单栏 sidebar -->
        <div id="content" class="content">
            <div class="header">
                <h1 class="page-title">会议室列表</h1>
            </div>
            <ul class="breadcrumb">
                <li><a href="/"> 控制面板 </a> <span class="divider">/</span></li>
                <li class="active">会议室列表</li>
            </ul>
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="btn-toolbar" style="margin-bottom:2px;">
                        <a href="/room/add" class="btn btn-primary"><i class="icon-plus"></i> 新增</a>
                    </div>
                    <div class="block">
                        <a href="#page-stats" class="block-heading" data-toggle="collapse">会议室列表</a>
                        <div id="page-stats" class="block-body collapse in">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>会议室名称</th>
                                        <th>锁定开始时间</th>
                                        <th>锁定结束时间</th>
                                        <th>容纳人数</th>
                                        <th>状态</th>
                                        <th width="80px">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td><?php echo htmlentities($vo['id']); ?></td>
                                        <td><?php echo htmlentities($vo['room_name']); ?></td>
                                        <td><?php echo htmlentities(format_time($vo['lock_start_time'])); ?></td>
                                        <td><?php echo htmlentities(format_time($vo['lock_end_time'])); ?></td>
                                        <td><?php echo htmlentities($vo['room_people_num']); ?></td>
                                        <td><?php echo htmlentities(format_room_status($vo['status'])); ?></td>
                                        <td>
                                            <a href="/room/edit?id=<?php echo htmlentities($vo['id']); ?>" title="修改"><i class="icon-pencil"></i></a> &nbsp; &nbsp; 
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script>
                        $('.icon-remove').click(function(){
                            var href=$(this).attr('href');
                            bootbox.confirm('确定要这样做吗？', function(result) {
                                if(result){
                                    location.replace(href);
                                }
                            });
                        })
                    </script>

                    <footer>
                        <hr />
                        <p class="pull-right">技术支持 by <a href="">thinkphp</a></p>
                        <p>&copy; <?php echo date('Y');?> <a href="">版权所有</a></p>
                    </footer>
                </div>
            </div>
        </div>
        <script src="/assets/lib/bootstrap/js/bootstrap.js"></script>
    </body>
</html>