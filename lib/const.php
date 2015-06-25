<?php
	//Development MODE?
	define("DEV_MODE",true);

	//Theme Information
	define("THEME_NAME","Bootstrap Basic");
	define("THEME_VER","1.0");
	if (!defined("TEMPLATEPATH")) define("TEMPLATEPATH", get_template_directory());
	
	//Template URLs
	define("BOOTSTRAP_LIB_DIR",TEMPLATEPATH."/lib");
	
	define("BOOTSTRAP_ADMIN_DIR",BOOTSTRAP_LIB_DIR."/admin");
	define("BOOTSTRAP_FUNCTIONS_DIR",BOOTSTRAP_LIB_DIR."/functions");
	define("BOOTSTRAP_FORMS_DIR",BOOTSTRAP_ADMIN_DIR."/forms");
	
?>