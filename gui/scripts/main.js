/**
 * Main index scripts.
 *
 * @version 1.6
 * @author MPI
 * */
var VALIDATOR = "";

$(function() {
	(function ($) {
	    $.fn.error = function () {
	        errorHighlight(this, 'error');
	    };
	})(jQuery);

	//highlight (alert) dialog
	(function ($) {
	    $.fn.highlight = function () {
	        errorHighlight(this, 'highlight');
	    };
	})(jQuery);
	
	// default
	$("#nav").menu({position: {at: "left bottom"}});
	$("input[type=submit]").button();
	$("#exception").hide();
	$("input[type=text], input[type=password]").button().addClass('ui-textfield').off('mouseenter').off('mousedown').off('keydown');
	$(document).tooltip();
	
	// exception box
	var exc = $("#exception").html();
	if(exc != undefined && exc.length > 0){
		$("#exception").text(exc.substring(exc.search(/\]:/i) + 3));
		if(exc.search("NoticeException") >= 0){
			$('#exception').highlight();
		}else{
			$('#exception').error();
		}
		$("#exception").show();
	}
	$("#exception").on("click", function() {
		if($(this).hasClass("exception_hide"))
	    	$(this).removeClass("exception_hide");
		else	
			$(this).addClass("exception_hide");
	});
	
	// list pagesize
	$("#pagesize_box select[name=action_pagesize]").on("change", function() {
	    $("#pagesize_box").submit();
	});
	$("#pagesize_box input[type=submit]").hide();
});

function errorHighlight(e, type, icon) {
    if (!icon) {
        if (type === 'highlight') {
            icon = 'ui-icon-info';
        } else {
            icon = 'ui-icon-alert';
        }
    }
    return e.each(function () {
        $(this).addClass('ui-widget');
        var h = '<div class="ui-state-' + type + ' ui-corner-all" style="padding:0 .7em;">';
        h += '<p>';
        h += '<span class="ui-icon ' + icon + '" style="float:left;margin-right: .3em;"></span>';
        h += $(this).text();
        h += '</p>';
        h += '</div>';

        $(this).html(h);
    });
}