<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'SIMM - Sistema Integrado de Manejo de Maquinarias';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', 'favicon.png') ?>


    <?= $this->Html->css('../bootstrap5.0.2/css/bootstrap.min.css') ?>
    <!-- FONT AWESOME -->
    <?= $this->Html->css('../plugins/fontawesome-free/css/all.css') ?>
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- ADMINLTE -->
    <?= $this->Html->css('../adminlte/css/adminlte.css') ?>
    <?= $this->Html->css('../plugins/toastr/toastr.css') ?>


    <!-- JS necessary for inicial page -->
    <?= $this->Html->script('jquery-3.6.0.min.js') ?>

    <?= $this->Html->script('../bootstrap5.0.2/js/bootstrap.bundle.min.js') ?>
    <?= $this->Html->script('../adminlte/js/adminlte.js') ?>

    <?= $this->Html->css('simm-style.css') ?>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>

    <div class="wrapper">

        <?= $this->fetch('content') ?>
    </div>

</body>

<?= $this->Html->script('../plugins/toastr/toastr.min.js') ?>

</html>
