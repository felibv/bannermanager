<?php
/*
* Banner Manager Module - Allows to create multiple banners and
*  	sort them for both right and left column
*
*  @author Gastón Franzé; v0.9.1 FeliBV
*  @link https://github.com/felibv/bannermanager
*  @version  Release: $Revision: 121227 $
*  @tested prestashop 1.4.9.0
*  @license  http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_'))
	exit;

class bannermanager extends Module
{
	private $_postErrors = array();
	/** @var max image size */
	protected $maxImageSize = 1207200;

	function __construct()
	{
		$this->name = 'bannermanager';
		$this->tab = 'advertising_marketing';
		$this->version = '0.9.1';
		$this->need_instance = 0;

		parent::__construct();

        $this->displayName = $this->l('Banner Manager');
		$this->description = $this->l('Allows you to add as many banners as you want on both right or left columns, and also from home page');

		$this->_errors = array();
		$this->banners_path = _PS_IMG_.'bnrs/';
		$this->img_path = _PS_IMG_DIR_.'bnrs/';
	}

	function install()
	{
		if (parent::install() == false
				OR !$this->registerHook('leftColumn')
				OR !$this->registerHook('rightColumn')
				OR !$this->registerHook('home')
				OR !$this->registerHook('header')
				OR !$this->registerHook('backOfficeHeader')
				OR $this->_createTables() == false
			)
			return false;
			
			if (!file_exists($this->img_path))
				if (!@mkdir($this->img_path, 0777))
					return false;
		return true;
	}

	function uninstall()
	{
		$db = Db::getInstance();
		$query = 'DROP TABLE `'._DB_PREFIX_.'banner_manager`';
		$result = $db->Execute($query);

        if (!parent::uninstall() OR !$result)
			return false;
		return true;
	}

	/**
	*	createTables()
	*	Called from within bannermanager.php when intalling
	*/
	public function _createTables()
	{
		$db = Db::getInstance();
		/*	Create banners card table */
		$query = 'CREATE TABLE `'._DB_PREFIX_.'banner_manager` (
			  `id_banner_manager` int(6) NOT NULL AUTO_INCREMENT,
			  `description` varchar(30) NOT NULL default "",
			  `image_name` varchar(255) NOT NULL default "",
			  `image_link` varchar(255) NOT NULL default "",
			  `open_blank` tinyint(1) NOT NULL default "0",
			  `active` tinyint(1) NOT NULL default "1",
			  `block_id` int(2) NOT NULL default "0",
			  `order` int(10) NOT NULL default "0",
			  PRIMARY KEY (`id_banner_manager`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8';
		$result = $db->Execute($query);
		if (!$result)
			return false;
		return true;
	}

	/**
	*	getContent()
	*	Called in Back Office when user clicks "Configure"
	*/
	function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (!empty($_POST)){
			if (!sizeof($this->_postErrors))
				$output .= $this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$output .= "<div class='alert error'>{$err}</div>";
		} else
			$output .= "<br />";

		return $output.$this->_displayBannerManager();
	}

	private function _displayBannerManager()
	{
		global $smarty, $currentIndex;

		$smarty->assign(array(
			'banners_path'	=> $this->banners_path,
			'banners_1'		=> $this->getBanners('1'),
			'banners_2'		=> $this->getBanners('2'),
			'banners_3'		=> $this->getBanners('3'),
			'leftBanners'	=> '2',
			'currentIndex'	=> $currentIndex,
			'rand'			=> rand()
		));
		return $this->display( dirname(__FILE__), 'bannermanager_bo.tpl');
	}

	/**
	*	_postProcess()
	*	Called upon successful module configuration validation
	*/
	private function _postProcess()
	{
		$output = "";
		// Banners update submit
		if (isset($_POST['bannersSubmit'])){
			$banners = Tools::getValue('bannerManagerId');
			if ($banners AND is_array($banners) AND count($banners)){
				foreach ($banners AS $row){
					$bnr = array();
					$bnr['id'] = $row;
					$bnr['description'] = Tools::getValue('desc_'.$row);
					$bnr['image_link'] = Tools::getValue('link_'.$row);
					$bnr['image_name'] = Tools::getValue('image_name_'.$row);
					$bnr['block_id'] = Tools::getValue('block_'.$row);
					$bnr['order'] = Tools::getValue('order_'.$row);
					$bnr['blank'] = (Tools::getValue('blank_'.$row) ? '1' : '0');
					$bnr['active'] = (Tools::getValue('active_'.$row) ? '1' : '0');
					$bnrs[] = $bnr;
				}
				if ($this->saveBanners($bnrs)){
					/*Lang Variables*/ $modOk = $this->l('Ok'); $modUpdated = $this->l('Banners Updated Successfully');
					$output .= "<div class='conf confirm'><img src='../img/admin/ok.gif' alt='{$modOk}' />{$modUpdated}</div>";
				}
				else
					$output .= '<div class="alert error">'.$this->l('There were problems updating banners.').'</div>';
			}
		}
		// Banners add submit
		if (isset($_POST['addBannerSubmit']))
		{
			$bnr['description'] = Tools::getValue('banner_description');
			$bnr['image_link'] = Tools::getValue('banner_link');
			$bnr['image_name'] = $_FILES['banner_image']['name'];
			$bnr['block_id'] = Tools::getValue('banner_block_id');
			$bnr['order'] = Tools::getValue('banner_order');
			$bnr['blank'] = (Tools::getValue('banner_blank') ? '1' : '0');
			$bnr['active'] = (Tools::getValue('banner_active') ? '1' : '0');
			/* upload the image */
			if (isset($_FILES['banner_image']) AND isset($_FILES['banner_image']['tmp_name']) AND !empty($_FILES['banner_image']['tmp_name']))
			{
				Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
				$name = $_FILES['banner_image']['name'];
				$ext = strtolower(substr($name, strrpos($name, ".") + 1));
				$path = $this->img_path.basename( $_FILES['banner_image']['name']);
				if (!($ext == 'png' || $ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp'))
					$errors .= $this->displayError($this->l('Incorrect file type.'));
					
				if ($error = checkImage($_FILES['banner_image'], $this->maxImageSize))
					$errors .= $this->displayError($error);
					
				elseif (!move_uploaded_file($_FILES['banner_image']['tmp_name'], $path))
					$errors .= $this->displayError($this->l('An error occurred during the image upload.'));

				if (isset($errors) && $errors)
					$errors .= $this->displayError($this->l('Error creating banner.'));
					
				elseif (!$this->addBanner($bnr))
					$errors .= $this->displayError($this->l('Error creating banner on database.'));
			}
			else
				$errors .= $this->displayError($this->l('An error occurred during the banner creation.'));
			$output .= (isset($errors) && $errors != '') ? $errors : $this->displayConfirmation('Banner added successfully');
		}
		// Delete banner
		if (isset($_POST['deleteBannerSubmit'])){
			$bnr = Tools::getValue('bannerDelete');
			if ($this->deleteBanner($bnr)){
				/*Lang Variables*/ $modOk = $this->l('Ok'); $modUpdated = $this->l('Banner succesfully deleted');
				$output .= "<div class='conf confirm'><img src='../img/admin/ok.gif' alt='{$modOk}' />{$modUpdated}</div>";
			} else
				$output .= '<div class="alert error">'.$this->l('Problems deleting the banner.').'</div>';
		}
		return $output;
	}

	function hookHome($params)
	{
		global $cookie, $smarty;
		$smarty->assign(array(
			'banner_class' => 'home',
			'banners' => $this->getBanners('3'),
			'banners_path' => $this->banners_path
		));
		return $this->display( dirname(__FILE__), 'bannermanager.tpl');
	}

	function hookRightColumn($params)
	{
		global $cookie, $smarty;
		$smarty->assign(array(
			'banner_class' => 'right',
			'banners' => $this->getBanners('2'),
			'banners_path' => $this->banners_path
		));
		return $this->display( dirname(__FILE__), 'bannermanager.tpl');
	}

	function hookLeftColumn($params)
	{
		global $cookie, $smarty;
		$smarty->assign(array(
			'banner_class' => 'left',
			'banners' => $this->getBanners('1'),
			'banners_path' => $this->banners_path
		));
		return $this->display( dirname(__FILE__), 'bannermanager.tpl');
	}
	
	public function hookHeader()
	{
		Tools::addCSS(($this->_path).'bnrmanager.css', 'all');
	}
	
	public function hookBackOfficeHeader()
	{
		if ( _PS_VERSION_ < '1.5' )
			return '<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/jquery-ui-1.8.10.custom.min.js"></script>
				<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/jquery.fancybox-1.3.4.js"></script>
				<link type="text/css" rel="stylesheet" href="'.__PS_BASE_URI__.'css/jquery.fancybox-1.3.4.css" />
				<script type="text/javascript">			
					var pos_select = \'0\';
					function loadTab(id)
					{};
				</script>
				<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tabpane.js"></script>
				<link type="text/css" rel="stylesheet" href="'.__PS_BASE_URI__.'css/tabpane.css" />
			';
		else
		{
			$this->context->controller->addJquery();
			$this->context->controller->addJQueryPlugin('fancybox');
		}
		return '';
	}

	/**
	*	getBanners()
	*	Returns the banners from the database
	*/
	public function getBanners($hook = NULL)
	{
		/*
			block_id = 1 	=> left
			block_id = 2 	=> right
			block_id = 3 	=> home
		*/
		$db = Db::getInstance();
		$result = $db->ExecuteS('
		SELECT `id_banner_manager`, `description`, `image_name`, `image_link`, `block_id`, `order`, `active`, `open_blank` FROM `'._DB_PREFIX_.'banner_manager`'.(isset($hook) ? 'WHERE `block_id` = '.$hook : ' ').' ORDER BY `block_id`, `order`;');
		return $result;
	}

	/**
	*	addBanner($bnr)
	*	Add new banner
	*/
	public function addBanner($bnr)
	{
		$db = Db::getInstance();
		// Insert new record
		$sql = 'INSERT INTO `'._DB_PREFIX_.'banner_manager` (`active`, `description`, `image_name`, `image_link`, `block_id`, `order`, `open_blank`) VALUES ("'.$bnr['active'].'", "'.$bnr['description'].'", "'.$bnr['image_name'].'", "'.$bnr['image_link'].'", "'.$bnr['block_id'].'", "'.$bnr['order'].'", "'.$bnr['blank'].'")';
		$result = $db->Execute($sql);
		if (!$result)
			return false;
		return true;
	}

	/**
	*	saveBanners($bnrs)
	*	Save data of banners
	*/
	public function saveBanners($bnrs)
	{
		$idx = 0;
		foreach ($bnrs AS $bnr)
		{
			$idx++;
			$db = Db::getInstance();
			//update existing record
			$sql = 'UPDATE `'._DB_PREFIX_.'banner_manager` SET `active` = "'.$bnr['active'].'", `description` = "'.$bnr['description'].'", `image_name` = "'.$bnr['image_name'].'", `image_link` = "'.$bnr['image_link'].'" , `block_id` = "'.$bnr['block_id'].'", `order` = "'.$idx.'", `open_blank` = "'.$bnr['blank'].'" WHERE id_banner_manager = '.$bnr['id'];
			$result = $db->Execute($sql);
			if (!$result)
				return false;
		}
		return true;
	}
	/**
	*	deleteBanner($bnr)
	*	Delete a banner
	*/
	public function deleteBanner($bnr)
	{
		$db = Db::getInstance();
		$result = $db->Execute('DELETE FROM `'._DB_PREFIX_.'banner_manager` WHERE `id_banner_manager` = "'.$bnr.'"');
		return $result;
	}
}