/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// var $ = require('jquery');

$(document).ready(function () {
    wlogTabInit();
});

$('#sidebarToggle').click(function () {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('#wlog-sidebar').css('right', '-320px');
    } else {
        $(this).addClass('active');
        $('#wlog-sidebar').css('right', '0px');
    }
});

$('#wlog-tab-bar .wlog-tab').click(function () {
    var width = $('#wlog-tab-bar .wlog-tab:eq(0)')[0].clientWidth;
    var panelWidth = $('#wlog-tab-panels')[0].clientWidth;
    var index = $(this).index();
    var move = index * width;
    var innerMove = -(index * panelWidth);
    $(this).addClass('wlog-tab--active').siblings('#wlog-tab-bar .wlog-tab').removeClass('wlog-tab--active');
    $('#wlog-tab-bar .layout-tab-bar__indicator').animate({ 'left': move + 'px' }, 100);
    $('#wlog-tab-panels .layout-tab-panels__container').css('transform', 'translate3D(' + innerMove + 'px, 0, 0)');
});

$('.wlog-cus-select label').click(function () {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $(this).parent().find('.select').hide(100);
    } else {
        $(this).addClass('active');
        $(this).parent().find('.select').show(200);
    }
});

$('.wlog-cus-select .select li').click(function () {
    $(this).parent().parent().find('input').val($(this).attr('data'));
    $(this).parent().parent().find('.default').attr('class', $(this).find('i').attr('class') + ' default');
    $(this).parent().parent().find('.select').hide(100);
});

$('.wlog-message-toggle').click(function () {
    $('.wlog-message').toggleClass('showing');
});

$('.wlog-alert button').click(function () {
    $('.wlog-alert').hide();
});

// wlogTabInitial
function wlogTabInit() {
    var length = $('#wlog-tab-bar .wlog-tab').length;
    if (length == 0) {
        return;
    } else {
        // let eachWidth = ;
        $('#wlog-tab-bar .wlog-tab').css('width', 100 / length + '%');
    }

    var tabWidth = $('#wlog-tab-bar .wlog-tab:eq(0)')[0].clientWidth;
    $('#wlog-tab-bar .layout-tab-bar__indicator').css('width', tabWidth + 'px');

    // initialize panel-slider
    var panelWidth = $('#wlog-tab-panels')[0].clientWidth;
    $('#wlog-tab-panels .wlog-tab-panel').each(function () {
        var div = document.createElement('div');
        $(div).addClass('slide');
        $(div).css({ 'width': panelWidth + 'px' });
        $(this).wrap(div);
    });
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.wlog-get-action').click(function () {
    var url = $(this).attr('data-url');
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function beforeSend() {
            $('.wlog-message .message__inner').html('');
        },
        success: function success(result) {
            var span = '<span>' + result.message + '</span>';
            $('.wlog-message .message__inner').append(span);
            $('.wlog-message').addClass('showing');
        }
    });
});

$('.wlog-delete-action').click(function () {
    var confirmMsg = confirm('Are You Sure ?');
    if (confirmMsg == true) {
        var data = $(this).parent().serialize();
        var url = $(this).parent()[0].action;
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            beforeSend: function beforeSend() {
                $('.wlog-message .message__inner').html('');
            },
            success: function success(result) {
                var span = '<span>' + result.message + '</span>';
                $('.wlog-message .message__inner').append(span);
                $('.wlog-message').addClass('showing');
                $('#' + result.delteID).remove();
            }
        });
    }
});

$('.wlog-change-state').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var iTag = $(this).find('i');
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function beforeSend() {
            $('.wlog-message .message__inner').html('');
        },
        success: function success(result) {
            if (result.status) {
                iTag.attr('class', 'fas fa-eye');
            } else {
                iTag.attr('class', 'fas fa-eye-slash');
            }
            var span = '<span>' + result.message + '</span>';
            $('.wlog-message .message__inner').append(span);
            $('.wlog-message').addClass('showing');
        }
    });
});

/***/ })
/******/ ]);