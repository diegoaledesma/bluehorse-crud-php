<?php
require_once CLASS_TOOLS;
$tools  = new Tools();
$conf   = require SETTINGS;
?>
<title><?=$conf['company_name']?></title>
<meta charset="<?=$conf['charset']?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/png" href="<?=URL_IMG?>/favicon.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="../js/autosuggest-1.5.js"></script>
<?php if (isset($javascript)) { ?>
<script>
    <?=$javascript?>
</script>
<?php } ?>
<script>
    $(function() {
        $(window).on('load', function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('.dropdown-toggle').dropdown();

            let middle = (($(window).outerWidth() - 344) / 2) + 'px';
            $('#snackbar').css({'left': middle});

            setTimeout(function() {
                $('#snackbar').animate({bottom:'15px'},200);
                setTimeout(function() {
                    $('#snackbar').addClass('snackbar-open');
                },100);
            },500);
            setTimeout(function() {
                $('#snackbar').animate({bottom:'-90px'},100);
            },3500);
        });
        $('body').on('keyup', 'input[type="text"], textarea', function() {

            let type = $(this).data('filter'),match;

            switch (type) {
                case 'alpha':
                    match = <?=$tools->arrMatch['alpha']['js']?>;
                    break;
                case 'alphanumeric':
                    match = <?=$tools->arrMatch['alphanumeric']['js']?>;
                    break;
                case 'alphanumeric_sc':
                    match = <?=$tools->arrMatch['alphanumeric_sc']['js']?>;
                    break;
                case 'int':
                    match = <?=$tools->arrMatch['int']['js']?>;
                    break;
                case 'float':
                    match = <?=$tools->arrMatch['float']['js']?>;
                    break;
                case 'password':
                    match = <?=$tools->arrMatch['password']['js']?>;
                    break;
                case 'tel':
                    match = <?=$tools->arrMatch['tel']['js']?>;
                    break;
                default:
                    match = <?=$tools->arrMatch['alphanumeric_sc']['js']?>;
                        }
            this.value = (this.value + '').replace(match, '');
        });
    });
</script>
<style>
    body {
        background-color: #e9e9e9;
        overflow-x: hidden;
        font-size: 14px;
    }
    #snackbar {
        position: fixed;
        bottom: -90px;
        width: 344px;
        text-align: left;
        -webkit-box-shadow: 0 3px 5px -1px rgba(0,0,0,.2), 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12);
        -moz-box-shadow: 0 3px 5px -1px rgba(0,0,0,.2), 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12);
        box-shadow: 0 3px 5px -1px rgba(0,0,0,.2), 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12);
        color: hsla(0,0%,100%,.87);
        background-color: #333;
        border: 0;

        -webkit-font-smoothing: antialiased;
        font-size: 1rem;
        line-height: 1.25rem;
        font-weight: 400;
        letter-spacing: .0178571429em;
        text-decoration: inherit;
        text-transform: inherit;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        margin: 0;
        padding: 14px 16px;
        border-radius: 4px;
        opacity: 0;
        transition: opacity .15s cubic-bezier(0,0,.2,1) 0ms,transform .15s cubic-bezier(0,0,.2,1) 0ms,-webkit-transform .15s cubic-bezier(0,0,.2,1) 0ms;
    }
    .snackbar-open {
        opacity: 1!important;
        transition: opacity .15s cubic-bezier(0,0,.2,1) 0ms,transform .15s cubic-bezier(0,0,.2,1) 0ms,-webkit-transform .15s cubic-bezier(0,0,.2,1) 0ms;
    }
    .table td {
        padding: 4px;
        padding-left: 0.75rem;
        vertical-align: middle;
    }
    .table, .btn {
        font-size: 14px;
    }
    .btn-group .dropdown-toggle::after {
        display: inline-block;
        margin-left: .255em;
        vertical-align: .255em;
        content: "";
        border-top: none;
        border-right: none;
        border-bottom: none;
        border-left: none;
    }
    .material-icons {
        font-family: 'Material Icons';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;  /* Preferred icon size */
        display: inline-block;
        line-height: 1;
        text-transform: none;
        letter-spacing: normal;
        word-wrap: normal;
        white-space: nowrap;
        direction: ltr;

        /* Support for all WebKit browsers. */
        -webkit-font-smoothing: antialiased;
        /* Support for Safari and Chrome. */
        text-rendering: optimizeLegibility;

        /* Support for Firefox. */
        -moz-osx-font-smoothing: grayscale;

        /* Support for IE. */
        font-feature-settings: 'liga';
    }
    .btn-addon {
        position: absolute;
        bottom: 0px;
        right: 0px;
        margin-bottom: 10px;
        margin-right: 10px;
    }
    .dropdown-menu > li > a.selected,
    .dropdown-menu:not(.head-list) > li > a:hover {
        background-color: #1976d2;
        color: #fff;
    }
    .bootbox
    .modal-header {
        display: block;
    }
    .card {
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    }
    .card:hover,
    .card:active,
    .card:focus,
    .modal-content,
    .dropdown-menu {
        -webkit-box-shadow: 0 7px 16px 0 rgba(0,0,0,.2), 0 1px 3px 0 rgba(0,0,0,.1);
        box-shadow: 0 7px 16px 0 rgba(0,0,0,.2), 0 1px 3px 0 rgba(0,0,0,.1);
    }
    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background-color: #4ca2c8;
        border-color: #4ca2c8;
    }
    .footer {
        color: #4ca2c8!important;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 30px;
        line-height: 30px;
        background-color: #003261;
    }
    .dropdown-item:focus,
    .dropdown-item:hover {
        color: #007bff;
        text-decoration: none;
        background-color: #fff;
    }
    .dropdown-item {
        align-items: center;
        padding: 12px 12px 12px 26px;
        display: flex;
        height: 47px;
        color: #5f6368;
        font-size: 14px;
    }
    .dropdown-item div {
        -webkit-align-items: center;
        align-items: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-shrink: 0;
        flex-shrink: 0;
        height: 20px;
        -webkit-justify-content: flex-start;
        justify-content: flex-start;
        margin-right: 18px;
        /* opacity: .54; */
        width: 20px;
    }
    .btn-info {
        background-color: #4ca2c8;
        border-color: #4ca2c8;
    }
    .modal-backdrop {
        background-color: #e9e9e9;
    }
    .list-group-item {
        align-items: center;
        padding: 12px 12px 12px 26px;
        display: flex;
        height: 47px;
        color: #5f6368!important;
    }
    .text-muted {
        color: #5f6368!important;
    }
    .list-group-item div {
        -webkit-align-items: center;
        align-items: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-shrink: 0;
        flex-shrink: 0;
        height: 20px;
        -webkit-justify-content: flex-start;
        justify-content: flex-start;
        margin-right: 18px;
        /* opacity: .54; */
        width: 20px;
    }
    .list-group-item.active {
        color: #007bff!important;
        background-color:transparent!important;
        border-color:transparent!important;
    }
    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23007bff' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
    }
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23007bff' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
    }
    .h-530 {
        height: 530px!important;
    }
    [data-id="list"] tbody td {
        height: 52px;
    }
    .small, small {
        font-size: 95%;
    }
</style>