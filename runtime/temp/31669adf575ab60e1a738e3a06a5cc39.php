<?php /*a:3:{s:72:"D:\program\phpEnv\www\localhost\tp6_room_reservation\view\room\edit.html";i:1662906279;s:76:"D:\program\phpEnv\www\localhost\tp6_room_reservation\view\Public\header.html";i:1662603797;s:76:"D:\program\phpEnv\www\localhost\tp6_room_reservation\view\Public\footer.html";i:1662599518;}*/ ?>
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
                <h1 class="page-title">编辑会议室</h1>
            </div>
            <ul class="breadcrumb">
                <li><a href="/"> 控制面板 </a> <span class="divider">/</span></li>
                <li><a href="/room"> 会议室列表 </a> <span class="divider">/</span></li>
                <li class="active">编辑会议室</li>
            </ul>
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="bb-alert alert alert-info" style="display: none;">
                        <span>操作提示</span>
                    </div>
                    <div class="well">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#home" data-toggle="tab">请填写会议室资料</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane active in" id="home">
                                <script src="/assets/js/My97DatePicker/WdatePicker.js"></script>
                                <form id="tab" method="post" action="" data-href="/room/edit" data-redirect="/room">
                                    <input type="hidden" name="id" value="<?php echo htmlentities($info['id']); ?>">
                                    <label>会议室名称</label>
                                    <input type="text" name="room_name" value="<?php echo htmlentities($info['room_name']); ?>" class="input-xlarge" autofocus="true" />
                                    <label>锁定开始时间</label>
                                    <input type="datetime" onClick="WdatePicker({lang: 'zh-cn', el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})" name="lock_start_time" value="<?php echo htmlentities(format_time($info['lock_start_time'])); ?>" class="input-xlarge" />
                                    <label>锁定结束时间</label>
                                    <input type="datetime" onClick="WdatePicker({lang: 'zh-cn', el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})" name="lock_end_time" value="<?php echo htmlentities(format_time($info['lock_start_time'])); ?>" class="input-xlarge" />
                                    <label>容纳人数</label>
                                    <input type="text" name="room_people_num" value="<?php echo htmlentities($info['room_people_num']); ?>" class="input-xlarge" required="true"  />
                                    <label>状态</label>
                                    <select name="status" class="input-xlarge">
                                        <option value="1" <?php if($info['status'] == 1): ?>selected="selectd"<?php endif; ?> class="input-xlarge option">可用</option>
                                        <option value="0" <?php if($info['status'] == 0): ?>selected="selectd"<?php endif; ?> class="input-xlarge option">不可用</option>
                                    </select>
                                    <div class="btn-toolbar">
                                        <button type="button" id="sub_btn" class="btn btn-primary"><strong>提交</strong></button>
                                    </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>

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