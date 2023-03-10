<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?= get_option('site_description'); ?>">
	<meta name="keywords" content="<?= get_option('keywords'); ?>">
	<meta name="author" content="<?= get_option('author'); ?>">

	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Log in | <?= get_option('site_name');?></title>

	<link rel="apple-touch-icon" sizes="57x57" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?= BASE_ASSET; ?>img/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_ASSET; ?>img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= BASE_ASSET; ?>img/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_ASSET; ?>img/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?= BASE_ASSET; ?>img/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?= BASE_ASSET; ?>img/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">



	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/square/blue.css">
	<style type="text/css">
		.login-box-body {
			border-top: 5px solid #D7320C;
		}

		.login-page{
			background:linear-gradient(0deg, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url('<?= BASE_ASSET;?>img/Inlog-Bansos.jpg');
			background-size: cover;
			background-repeat: no-repeat;
			height: auto;
			background-position: center;
		}

		.login-logo a{
			color: white;
		}
	</style>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="javascript:void(0);"><b><?= cclang('login'); ?></b> <?= get_option('site_name'); ?></a>
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg"><?= cclang('sign_to_start_your_session'); ?></p>
			<?php if(isset($error) AND !empty($error)): ?>
			<div class="callout callout-error" style="color:#C82626">
				<h4><?= cclang('error'); ?>!</h4>
				<p><?= $error; ?></p>
			</div>
			<?php endif; ?>
			<?php
	$message = $this->session->flashdata('f_message'); 
	$type = $this->session->flashdata('f_type'); 
	if ($message):
	?>
			<div class="callout callout-<?= $type; ?>" style="color:#C82626">
				<p><?= $message; ?></p>
			</div>
			<?php endif; ?>
			<?= form_open('', [
		'name'    => 'form_login', 
		'id'      => 'form_login', 
		'method'  => 'POST'
	  ]); ?>
			<div class="form-group has-feedback <?= form_error('username') ? 'has-error' :''; ?>">
				<input type="email" class="form-control" placeholder="Email" name="username"
					value="<?= set_value('username', 'admin@admin.com'); ?>">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback <?= form_error('password') ? 'has-error' :''; ?>">
				<input type="password" class="form-control" placeholder="Password" name="password" value="admin123">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" name="remember" value="1"> <?= cclang('remember_me'); ?>
						</label>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat"><?= cclang('sign_in'); ?></button>
				</div>
				<!-- /.col -->
			</div>
			<?= form_close(); ?>

		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 2.2.3 -->
	<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="<?= BASE_ASSET; ?>/admin-lte/bootstrap/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/icheck.min.js"></script>
	<script>
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			});
		});
	</script>
</body>

</html>