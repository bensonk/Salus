	xinha_editors = null;
	xinha_init    = null;
	xinha_config  = null;
	xinha_plugins = null;

	// This contains the names of textareas we will make into Xinha editors
	xinha_init = xinha_init ? xinha_init : function()
	{
   /** STEP 1 ***************************************************************
	   * First, what are the plugins you will be using in the editors on this
	   * page.  List all the plugins you will need, even if not all the editors
	   * will use all the plugins.
	   *
	   * The list of plugins below is a good starting point, but if you prefer
	   * a must simpler editor to start with then you can use the following 
	   * 
	   * xinha_plugins = xinha_plugins ? xinha_plugins : [ ];
	   *
	   * which will load no extra plugins at all.
	   ************************************************************************/

	  xinha_plugins = xinha_plugins ? xinha_plugins :
	  [
	   'GetHtml',
		'FullScreen',
	   'ImageManager'
	  ];
	
			 // THIS BIT OF JAVASCRIPT LOADS THE PLUGINS, NO TOUCHING  :)
			 if(!HTMLArea.loadPlugins(xinha_plugins, xinha_init)) return;

  /** STEP 2 ***************************************************************
	   * Now, what are the names of the textareas you will be turning into
	   * editors?
	   ************************************************************************/

	  xinha_editors = xinha_editors ? xinha_editors :
	  [
		'content'
	  ];

	  /** STEP 3 ***************************************************************
	   * We create a default configuration to be used by all the editors.
	   * If you wish to configure some of the editors differently this will be
	   * done in step 5.
	   *
	   * If you want to modify the default config you might do something like this.
	   *
	   *   xinha_config = new HTMLArea.Config();
	   *   xinha_config.width  = '640px';
	   *   xinha_config.height = '420px';
	   *
	   *************************************************************************/

	   xinha_config = new HTMLArea.Config();
		
		xinha_config.registerButton("save_submit", "Save", _editor_url + "save.gif", true, function() {$("#edit").trigger("submit");});
	   
	   xinha_config.toolbar =
		 [
		   ["createlink", "insertimage", "inserttable", "inserthorizontalrule", "separator", "insertorderedlist", "insertunorderedlist"], 
		   ["separator", "htmlmode", "popupeditor", "separator", "showhelp","separator","save_submit"],
		   ["separator", "formatblock","separator","bold","italic","underline", "separator","justifyleft","justifycenter","separator","outdent","indent"],
		 ];
		 
		    xinha_config.formatblock =
		  {
		    "&mdash; Formatting &mdash;": "",
		    "Heading 1": "h1",
		    "Heading 2": "h2",
		    "Heading 3": "h3",
		    "Heading 4": "h4",
		    "Paragraph" : "p",
			"Code" : "pre",
			"Address" : "address"
		  };
		  
		  xinha_config.sizeIncludesBars = false;
		  
		  xinha_config.statusBar = false;
		  
		  xinha_config.showLoading = true;	  

		
	  /** STEP 4 ***************************************************************
	   * We first create editors for the textareas.
	   *
	   * You can do this in two ways, either
	   *
	   *   xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);
	   *
	   * if you want all the editor objects to use the same set of plugins, OR;
	   *
	   *   xinha_editors = HTMLArea.makeEditors(xinha_editors, xinha_config);
	   *   xinha_editors['myTextArea'].registerPlugins(['Stylist','FullScreen']);
	   *   xinha_editors['anotherOne'].registerPlugins(['CSS','SuperClean']);
	   *
	   * if you want to use a different set of plugins for one or more of the
	   * editors.
	   ************************************************************************/

	  xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);

	  /** STEP 5 ***************************************************************
	   * If you want to change the configuration variables of any of the
	   * editors,  this is the place to do that, for example you might want to
	   * change the width and height of one of the editors, like this...
	   *
	   *   xinha_editors.myTextArea.config.width  = '640px';
	   *   xinha_editors.myTextArea.config.height = '480px';
	   *
	   ************************************************************************/


	  /** STEP 6 ***************************************************************
	   * Finally we "start" the editors, this turns the textareas into
	   * Xinha editors.
	   ************************************************************************/

	  HTMLArea.startEditors(xinha_editors);
	}
	
	
	window.onload = xinha_init;
