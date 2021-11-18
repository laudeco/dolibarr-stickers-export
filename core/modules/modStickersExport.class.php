<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2019  Nicolas ZABOURI         <info@inovea-conseil.com>
 * Copyright (C) 2019-2020  Frédéric France         <frederic.france@netlogic.fr>
 * Copyright (C) 2021 laurent De Coninck <lau.deconinck@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   stickersexport     Module StickersExport
 *  \brief      StickersExport module descriptor.
 *
 *  \file       htdocs/stickersexport/core/modules/modStickersExport.class.php
 *  \ingroup    stickersexport
 *  \brief      Description and activation file for module StickersExport
 */
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module StickersExport
 */
class modStickersExport extends DolibarrModules
{
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;
		$this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 500001; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module

		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'stickersexport';

		// Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
		// It is used to group modules by family in module setup page
		$this->family = "products";

		// Module position in the family on 2 digits ('01', '10', '20', ...)
		$this->module_position = '90';

		// Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
		//$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
		// Module label (no space allowed), used if translation string 'ModuleStickersExportName' not found (StickersExport is name of module).
		$this->name = preg_replace('/^mod/i', '', get_class($this));

		// Module description, used if translation string 'ModuleStickersExportDesc' not found (StickersExport is name of module).
		$this->description = "Allow to export the stock";
		// Used only if file README.md and README-LL.md not found.
		$this->descriptionlong = "Exports the stock with the quantity of each products";


		// Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
		$this->version = '1.0';

		// Key used in llx_const table to save module status enabled/disabled (where STICKERSEXPORT is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);

		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		// To use a supported fa-xxx css style of font awesome, use this->picto='xxx'
		$this->picto = 'generic';

		// Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
		$this->module_parts = array(
			// Set this to 1 if module has its own trigger directory (core/triggers)
			'triggers' => 0,
			// Set this to 1 if module has its own login method file (core/login)
			'login' => 0,
			// Set this to 1 if module has its own substitution function file (core/substitutions)
			'substitutions' => 0,
			// Set this to 1 if module has its own menus handler directory (core/menus)
			'menus' => 0,
			// Set this to 1 if module overwrite template dir (core/tpl)
			'tpl' => 0,
			// Set this to 1 if module has its own barcode directory (core/modules/barcode)
			'barcode' => 0,
			// Set this to 1 if module has its own models directory (core/modules/xxx)
			'models' => 0,
			// Set this to 1 if module has its own printing directory (core/modules/printing)
			'printing' => 0,
			// Set this to 1 if module has its own theme directory (theme)
			'theme' => 0,
			// Set this to relative path of css file if module has its own css file
			'css' => array(
				//    '/stickersexport/css/stickersexport.css.php',
			),
			// Set this to relative path of js file if module must load a js on all pages
			'js' => array(
				//   '/stickersexport/js/stickersexport.js.php',
			),
			// Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
			'hooks' => array(
				//   'data' => array(
				//       'hookcontext1',
				//       'hookcontext2',
				//   ),
				//   'entity' => '0',
			),
			// Set this to 1 if features of module are opened to external users
			'moduleforexternal' => 0,
		);

		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/stickersexport/temp","/stickersexport/subdir");
		$this->dirs = [];

		// Config pages. Put here list of php page, stored into stickersexport/admin directory, to use to setup module.
		$this->config_page_url = [];

		// Dependencies
		// A condition to hide module
		$this->hidden = false;
		// List of module class names as string that must be enabled if this module is enabled. Example: array('always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
		$this->depends = [];
		$this->requiredby = []; // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
		$this->conflictwith = []; // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)

		// The language file dedicated to your module
		$this->langfiles = array("stickersexport@stickersexport");

		// Prerequisites
		$this->phpmin = array(5, 5); // Minimum version of PHP required by module
		$this->need_dolibarr_version = array(11, -3); // Minimum version of Dolibarr required by module

		// Messages at activation
		$this->warnings_activation = []; // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		$this->warnings_activation_ext = []; // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		//$this->automatic_activation = array('FR'=>'StickersExportWasAutomaticallyActivatedBecauseOfYourCountryChoice');
		//$this->always_enabled = true;								// If true, can't be disabled

		// Constants
		// List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
		// Example: $this->const=array(1 => array('STICKERSEXPORT_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
		//                             2 => array('STICKERSEXPORT_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
		// );
		$this->const = [];

		// Some keys to add into the overwriting translation tables
		/*$this->overwrite_translation = array(
			'en_US:ParentCompany'=>'Parent company or reseller',
			'fr_FR:ParentCompany'=>'Maison mère ou revendeur'
		)*/

		if (!isset($conf->stickersexport) || !isset($conf->stickersexport->enabled)) {
			$conf->stickersexport = new stdClass();
			$conf->stickersexport->enabled = 0;
		}

		// Array to add new pages in new tabs
		$this->tabs = [];

		// Dictionaries
		$this->dictionaries = [];

		// Boxes/Widgets
		// Add here list of php file(s) stored in stickersexport/core/boxes that contains a class to show a widget.
		$this->boxes = [];

		// Cronjobs (List of cron jobs entries to add when module is enabled)
		// unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
		$this->cronjobs = [];

		// Permissions provided by this module
		$this->rights = [];

		// Main menu entries to add
		$this->menu = [];

		// Exports profiles provided by this module
		$r = 1;

		/* BEGIN MODULEBUILDER EXPORT MYOBJECT */
		$langs->load("stickersexport@stickersexport");

		$this->export_code[$r]=$this->rights_class.'_'.$r;

		$this->export_label[$r]='Export des produits en fonction du stock.';

		$this->export_fields_array[$r] = [
		    'product.rowid' => 'ID du produit',
		    'product.label' => 'Libelle du produit',
		    'SUBSTR(product.barcode, 1, 12 ) as barcode' => 'Code barre',
		    'product.price' => 'Prix HTVA',
		    'product.price_ttc' => 'Prix TTC',
		    'product.price_base_type' => 'Type de prix',
            'product.tobuy' => 'En achat',
            'product.tosell' => 'En vente',
            'product.datec'=>'DateCreation',
            'product.tms'=>'DateModification',
            'product_category.fk_categorie' => 'Catégorie',
        ];

		$this->export_TypeFields_array[$r] = [
            'product.rowid' => 'Numeric',
            'product.label' => 'Text',
            'SUBSTR(product.barcode, 1, 12 ) as barcode' => 'Text',
            'product.price' => 'Numeric',
            'product.price_ttc' => 'Numeric',
            'product.price_base_type' => 'Text',
            'product.tobuy' => 'Boolean',
            'product.tosell' => 'Boolean',
            'product.datec'=>'Date',
            'product.tms'=>'Date',
            'product_category.fk_categorie' => 'List:categorie:label:rowid',
        ];

		$this->export_sql_start[$r]='SELECT ';

		$this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'product AS product ';
		$this->export_sql_end[$r] .=' INNER JOIN '.MAIN_DB_PREFIX.'product_stock AS stock ON stock.fk_product = product.rowid ';
		$this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'categorie_product AS product_category ON product_category.fk_product = product.rowid ';
		$this->export_sql_end[$r] .=' JOIN ( SELECT DISTINCT sub_stock.reel as reel_stock FROM '.MAIN_DB_PREFIX.'product_stock AS  sub_stock WHERE sub_stock.reel IS NOT NULL AND sub_stock.reel > 0 ORDER BY sub_stock.reel) AS real_stock ON stock.reel >= real_stock.reel_stock';
		$this->export_sql_order[$r] .=' ORDER BY product.rowid';

	}

	/**
	 *  Function called when module is enabled.
	 *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *  It also creates data directories
	 *
	 *  @param      string  $options    Options when enabling module ('', 'noboxes')
	 *  @return     int             	1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		$result = $this->_load_tables('/stickersexport/sql/');
		if ($result < 0) {
		    return -1;
        }
		// Permissions
		$this->remove($options);

		return $this->_init([], $options);
	}

	/**
	 *  Function called when module is disabled.
	 *  Remove from database constants, boxes and permissions from Dolibarr database.
	 *  Data directories are not deleted
	 *
	 *  @param      string	$options    Options when enabling module ('', 'noboxes')
	 *  @return     int                 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = [];
		return $this->_remove($sql, $options);
	}
}
