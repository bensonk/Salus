/* PRELOAD ICONS */
jQuery.preloadImages = function()
{
  for(var i = 0; i<arguments.length; i++)
  {
    jQuery("<img>").attr("src", arguments[i]);
  }
}

$.preloadImages(
	"images/loading.gif", "images/loading_small.gif",
	"images/icons/help.gif", "images/icons/page_add.gif",
	"images/icons/accept.gif", "images/icons/delete.gif",
	"images/icons/exclamation.gif", "images/icons/error.gif",
	"images/icons/page_delete.gif", "images/icons/disk.gif",
	"images/icons/user_delete.gif", "images/icons/user.gif",
	"images/icons/user_edit.gif", "images/icons/password_field.gif"
	);