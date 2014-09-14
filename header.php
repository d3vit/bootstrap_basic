<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE]> 
<html xmlns="http://www.w3.org/1999/xhtml" class="ie">
<![endif]-->
<!--[if !IE]> 
<html xmlns="http://www.w3.org/1999/xhtml">
<![endif]--><head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta name="keywords" content="<?php echo $keywords_meta; ?>" />
    <meta name="author" content="" />
    
    <title><?php if (is_front_page()) : echo bloginfo( 'name' ) . ' | '; echo bloginfo( 'description'); else: wp_title(""); echo ' | '; echo bloginfo( 'name' ); endif; ?></title>

    <link rel="shortcut icon" type="image/ico" href="<?php bloginfo( 'template_url' ); ?>/favicon.ico">
    <link href="<?php bloginfo( 'template_url' ); ?>/css/reset.css" rel="stylesheet">
    <link href="<?php bloginfo( 'template_url' ); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php bloginfo( 'template_url' ); ?>/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!--[if IE]>
        <link href="<?php bloginfo( 'template_url' ); ?>/css/ie.css" rel="stylesheet" />
    <![endif]-->
    
    <link href="<?php bloginfo( 'template_url' ); ?>/css/media-queries.css" rel="stylesheet" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_get_archives('type=monthly&format=link'); ?>
    <?php wp_head(); ?>
</head>
<?php

if (is_single() || is_category())
	$class = "blog";
else
	$class = "";
?>
<body <? body_class($class);?>>
	<div class="container">
        <div id="main-navigation" class="navbar navbar-default" role="navigation">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php bloginfo( 'name' ); ?></a>
          </div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          	<?php wp_nav_menu (array('menu'=>'Main Navigation', 'menu_class' =>'nav navbar-nav') ); ?>
          </div><!-- /.navbar-collapse -->
        </div>