<!-- app/Resources/views/base.html.php -->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php $view['slots']->output('title', 'Sigue') ?></title>
        <?php $view['slots']->output('stylesheets') ?>
        <?php date_default_timezone_set("UTC")?>
        <script src="<?php echo $view['assets']->getUrl('scripts/jquery-1.9.1.js') ?>"  type="text/javascript"> </script>
        <script src="<?php echo $view['assets']->getUrl('scripts/jquery-ui-1.10.3.custom.js') ?>"  type="text/javascript" > </script>
        <script src="<?php echo $view['assets']->getUrl('scripts/jquery-ui-1.10.3.custom.min.js') ?>"  type="text/javascript" ></script>
        <script src="<?php echo $view['assets']->getUrl('scripts/jquery.validationEngine.js') ?>"  type="text/javascript" ></script>
        <script src="<?php echo $view['assets']->getUrl('scripts/jquery.validationEngine-es.js') ?>" type="text/javascript"> </script>
        <script src="<?php echo $view['assets']->getUrl('scripts/dropdown.js') ?>" type="text/javascript"> </script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        
        <link href="<?php echo $view['assets']->getUrl('css/estilos.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $view['assets']->getUrl('css/alumnoCSS.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $view['assets']->getUrl('css/validationEngine.jquery.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $view['assets']->getUrl('css/jquery-ui-1.10.3.custom.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $view['assets']->getUrl('css/jquery-ui-1.10.3.custom.min.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $view['assets']->getUrl('css/dropdown.css') ?>" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo $view['assets']->getUrl('favicon.ico') ?>" />
    </head>
    <body>
        <div id="content">                   
            <?php $view['slots']->output('_content') ?>
            <?php $view['slots']->output('javascripts') ?>
        </div>
    </body>
</html>