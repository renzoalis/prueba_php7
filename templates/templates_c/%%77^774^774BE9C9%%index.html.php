<?php /* Smarty version 2.6.26, created on 2017-05-08 18:37:59
         compiled from index.html */ ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="es"> <!--<![endif]-->  
<head>
    <title>Documentaci&oacute;n OCEBA</title>
    <!-- Meta -->
    <meta charset="utf-8">  
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">    
    <link rel="shortcut icon" href="favicon.png">  
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="../templates/templates/assets/plugins/bootstrap/css/bootstrap.min.css">   
    <!-- Plugins CSS -->    
    <link rel="stylesheet" href="../templates/templates/assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="../templates/templates/assets/plugins/elegant_font/css/style.css">
    
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="../templates/templates/assets/css/styles.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head> 

<body class="landing-page">   
     
    <!--FACEBOOK LIKE BUTTON JAVASCRIPT SDK-->
    <div id="fb-root"></div>

    
    <div class="page-wrapper">
        
        <!-- ******Header****** -->
        <header class="header text-center">
            <div class="container">
                <div class="branding">
                    <h1 class="logo">
                        <span aria-hidden="true" class="icon_documents_alt icon"></span>
                        <span class="text-highlight">Documentación</span><span class="text-bold"> OCEBA</span>
                        <!-- <?php echo '<?php'; ?>
 echo utf8_decode("Documentación OCEBA");<?php echo '?>'; ?>
 -->
                    </h1>
                </div>
            </div><!--//container-->
        </header><!--//header-->
        
        <section class="cards-section text-center">
            <div class="container">
                <h2 class="title">Bienvenido</h2>
                <div class="intro">
                    <p>Seleccione la documentación que desee ver.</p>
                    
                </div><!--//intro-->
                <div id="cards-wrapper" class="cards-wrapper row">
                    <?php $_from = $this->_tpl_vars['menuitems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['myId'] => $this->_tpl_vars['i']):
?>
                    <div class="item item-green col-md-4 col-sm-6 col-xs-6">
                        <div class="item-inner">
                            <div class="icon-holder">
                                <i class="icon fa fa-file-text-o"></i>
                            </div><!--//icon-holder-->
                            <h3 class="title"> <?php echo $this->_tpl_vars['i']['item_nombre']; ?>
</h3>
                            <p class="intro">Sistema de expedientes utilizado en el CAU</p>
                            <a class="link" href="expedientes.html"><span></span></a>
                        </div><!--//item-inner-->
                    </div><!--//item-->
                    <?php endforeach; endif; unset($_from); ?>
                </div><!--//cards-->
                
            </div><!--//container-->
        </section><!--//cards-section-->
    </div><!--//page-wrapper-->
    
    <footer class="footer text-center">
        <div class="container">

            <small class="copyright">Desarrollado por <a href="http://www.dev-gam.com.ar/" target="_blank"> DevGAM </a></small>
            
        </div><!--//container-->
    </footer><!--//footer-->
    
     
    <!-- Main Javascript -->          
    <script type="text/javascript" src="../templates/templates/assets/plugins/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="../templates/templates/assets/plugins/bootstrap/js/bootstrap.min.js"></script>                                                                     
    <script type="text/javascript" src="../templates/templates/assets/plugins/jquery-match-height/jquery.matchHeight-min.js"></script>
    <script type="text/javascript" src="../templates/templates/assets/js/main.js"></script>
    
</body>
</html> 
