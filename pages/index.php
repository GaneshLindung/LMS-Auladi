<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title><?= $site['shortname'] ?></title>
  <link rel="shortcut icon" href="static/icon.png" />

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />

  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/fontAwesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet"/>
  <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet" />
  <link href="assets/css/demo.css" rel="stylesheet" />
  <link href="assets/summernote/summernote-lite.css" rel="stylesheet">

  <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
  <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
  <style>
    .modal-backdrop {
        z-index: 0!important;
        background: transparent!important;
    }

    .mt-1 {margin-top: 6px;}
    .mt-2 {margin-top: 12px;}
    .mt-3 {margin-top: 18px;}
    .mt-4 {margin-top: 24px;}
    .mt-5 {margin-top: 30px;}

    table.action tr td:last-child,
    table.action tr th:last-child {
        text-align: right;
    }

    .cursor-pointer {cursor: pointer;}
  </style>
  <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
<style>
        /* MAIN */

        * {
            box-sizing: border-box;
        }

        :root {
            --blue: #4e73df;
            --indigo: #6610f2;
            --purple: #6f42c1;
            --pink: #e83e8c;
            --red: #e74a3b;
            --orange: #fd7e14;
            --yellow: #f6c23e;
            --green: #1cc88a;
            --teal: #20c9a6;
            --cyan: rgb(248, 141, 0);
            --white: #fff;
            --gray: #858796;
            --gray-dark: #5a5c69;
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: rgb(248, 141, 0);
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --font-family-sans-serif: "Nunito",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
            --font-family-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
        }

        body {margin: 0px auto;}
        a:not(.td-true) {text-decoration: none;}

        img {max-width: 100%;}

        .text-center {text-align: center;}

        /* CONTAINER */


        .d-none {display: none;}
        @media (max-width: 768px) {
            .d-sm-none {display: none;}
        }

        /* FLEX */
        .d-flex {display: flex;}
        .d-flex.space-between {justify-content: space-between;}
        
        /* GRID */
        .grid {display: grid;gap: 16px;}
        .grid-1,
        .grid-2,
        .grid-3 {grid-template-columns: repeat(1,1fr);}
        .grid-5,
        .grid-6 {grid-template-columns: repeat(2,1fr);}

        .inside-block * {display: block;}

        @media (min-width: 576px) {
            .grid-2, .grid-3, .grid-4 {grid-template-columns: repeat(2, 1fr);}
        }

        @media (min-width: 768px) {
            .grid-3, .grid-5, .grid-6 {grid-template-columns: repeat(3,1fr);}
        }

        @media (min-width: 992px) {

        }

        @media (min-width: 1100px) {
            .grid-4 {grid-template-columns: repeat(3,1fr);}
            .grid-5 {grid-template-columns: repeat(5,1fr);}
        }

        @media (min-width: 1200px) {
            .grid-4 {grid-template-columns: repeat(4, 1fr);}
        }

        .grid .col {
            border-radius: 10px;
            overflow: hidden;
            padding: 0px 0px 55px;
            color: #EFEFEF;
            background: rgb(158, 33, 33);
            position: relative;
            width: 100%;
        }

        .col .img {
            aspect-ratio: 16/12;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .col .info {background: rgb(158, 33, 33);}

        .col a {color: #EFEFEF;}
        .col .info h3 {margin: 0px 0px 5px;font-weight: normal;font-size: 27px;}
        .col h4 {font-weight: normal;font-size: 20px;margin: 0px 0px;display: inline-block;border-bottom: 1px solid #EFEFEF;padding-bottom: 3px;}
        .col span {font-size: 13.5px;}

        /* CONTAIENR */
        .container {
            margin: 0px auto;
            width: 100%;
            max-width: 992px;
        }

        #content-wrapper {
            /* background: white; */
            padding: 32px 0px;
        }

        /*  */
        .col.product img {
            object-fit: cover;
            aspect-ratio: 4/2.25;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

        @media(max-width: 1160px) {
            .container {
                padding: 0px 15px;
            }
        }

        textarea {resize: none;}

        .fgroup {
            /* display: inline-block; */
            gap: 10px;
            align-items: center;
            font-size: 20px;
            color: #777;
            cursor: pointer;
            padding: 4.5px 15px;
            border: 1px solid #DFDFDF;
            border-radius: 3px;
        }

        .fgroup.active {
            border: 1px solid #5ca3ff;
            color: #5ca3ff;
        } .fgroup input[type='checkbox'] {
            /* display: none; */
        }

        .tableHolder {overflow-x: scroll;}
        .tableHolder table th, .tableHolder table td {white-space: nowrap;}

        .d-block {display: block;}
    </style>
    <script src="assets/js/jquery.min.js"></script>
</head>
<body>

<div class="wrapper">
    <?php require("elements/aside.php") ?>
    <div class="main-panel">
      <?php require("elements/header.php") ?>
      <div class="content">
      <?php require("logic.php") ?>
      <script>
        var target = "<?= $target_page ?>";
        $(`li[data-slug='<?= $target_page ?>']`).addClass("active");
        $(`li[data-second-slug='<?= $target_page ?>']`).addClass("active");
      </script>
      </div>
      <footer class="footer">
          <div class="container-fluid">
              <nav class="pull-left">
                  <ul class="d-none">
                      <li><a href="#">Home</a></li>
                      <li><a href="#">Company</a></li>
                      <li><a href="#">Portfolio</a></li>
                      <li><a href="#">Blog</a></li>
                  </ul>
              </nav>
              <p class="copyright pull-right">
                  &copy; <script>document.write(new Date().getFullYear())</script> <a href="<?= base_url("") ?>"><?= $site['shortname'] ?></a>
              </p>
          </div>
      </footer>

    </div>
</div>

</body>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/summernote/summernote.min.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	<script type="text/javascript">
    	// $(document).ready(function(){
        //     function notification() {
        //         $.ajax({
        //             url: "<?= base_url("endpoint/index.php") ?>",
        //             type: "POST",
        //             data: {"action":"GET/notification"},
        //             success:function(data){
        //                 var response = JSON.parse(data);
        //                 if (response.OK == true) {
        //                     var ntype = response.notification.type;
        //                     if (ntype == "forum") {
        //                         var iconType = 'pe-7s-users', message = response.notification.sender + " membuka forum diskusi : <a href='<?= base_url("forum?action=open&ID=") ?>"+response.notification.reff+"' style='color: #FFF;' target='_blank'>" + response.notification.title + "</a>"
        //                     }
                            
        //                     $.notify({icon: iconType,message: message},{type: 'warning',timer: 15000});
        //                 }
        //                 console.log(data);
        //             }, error:function(data){}
        //         });
        //     }

        //     notification();
        //     setInterval(function(){
        //         notification();
        //     }, 60000);
    	// });
	</script>
</html>