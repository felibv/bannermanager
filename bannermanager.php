<?php
/*
 *  Prestashop (http://prestashop.com)
 *  Banner Manager Module - Allows to create multiple banners and
 *  	sort them for both right and left column
 *
 *
 *	Module created and modified by Gastón Franzé.
 */

class bannermanager extends Module
{
	private $_html = '';
	private $_postErrors = array();
	public  $path;
	/** @var max image size */
	protected $maxImageSize = 1207200;

	function __construct()
	{
		$this->name = 'bannermanager';
		$this->tab = 'advertising_marketing';
		$this->version = '0.8';

		parent::__construct();

		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Banner Manager');
		$this->description = $this->l('Allows you to add as many banners as you want on both right or left columns, and also from home page');

		parent::__construct();
		/* The parent construct is required for translations */

		$this->_errors = array();
		$this->path = $this->_path;
	}

	function install()
	{
		if (parent::install() == false
				OR !$this->registerHook('leftColumn')
				OR !$this->registerHook('rightColumn')
				OR !$this->registerHook('home')
				OR $this->_createTables() == false
			)
			return false;
		return true;

	}

	function uninstall()
	{
		$db = Db::getInstance();
		$query = 'DROP TABLE `'._DB_PREFIX_.'banner_manager`';
		$result = $db->Execute($query);
		if (!$result)
			return false;

        if (!parent::uninstall())
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
		$this->_html = '<h2>'.$this->displayName.'</h2>';		//Display Header
		if (!empty($_POST)){
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= "<div class='alert error'>{$err}</div>";
		} else
			$this->_html .= "<br />";

		$this->_displayBannerManagerHeader();
		$this->_setConfigurationForm();
		$this->_displayBannersAdd();
		return $this->_html;
	}

	/**
	*	_displayBannerManagerHeader()
	*	Called in Back Office during Module Configuration
	*/
	private function _displayBannerManagerHeader(){
		$modDesc 	= $this->l('This module allows you to include as many banners as you like.');
		$modStatus	= $this->l('You can upload, order, activate or deactivate as many banners and select if you want them in the right or left columns.');
		$this->_html .= "<img src='../modules/bannermanager/bannermanager.gif' style='float:left; margin-right:15px;' />
						<b>{$modDesc}</b><br /><br />
						{$modStatus}<br /><br /><br /><br />";
		//	Add banner link
		$this->_html .= '<a href="" onclick="addBanner();return false;"><img border="0" src="../img/admin/add.gif"> '.$this->l('Add a new banner.').'</a>';
	}

	/**
	*	_setConfigurationForm()
	*	Called upon successful module configuration validation
	*/
	private function _setConfigurationForm(){

		$this->_html .= '
		<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
			<script type="text/javascript">
				var pos_select = '.(($tab = intval(Tools::getValue('tabs'))) ? $tab : '0').';
			</script>
			<script type="text/javascript" src="'._PS_BASE_URL_._PS_JS_DIR_.'tabpane.js"></script>
			<link type="text/css" rel="stylesheet" href="'._PS_BASE_URL_._PS_CSS_DIR_.'tabpane.css" />
			<input type="hidden" name="tabs" id="tabs" value="0" />
			<div class="tab-pane" id="tab-pane-1" style="width:100%;margin:10px">
				 <div class="tab-page" id="step1">
					<h4 class="tab">'.$this->l('General Configuration').'</h4>
					<span>'.$this->l('Your banners are sepparated in different tabs, up there').'</span>
					<br /><br />
					<span>'.$this->l('You can always add a new banner from the top clicking on the + icon').'</span>
				</div>
				<div class="tab-page" id="step2">
					<h4 class="tab">'.$this->l('Left Banners').'</h4>
					'.$this->_displayBannersTab('1', 'Left').'
				</div>
				<div class="tab-page" id="step3">
					<h4 class="tab">'.$this->l('Right Banners').'</h4>
					'.$this->_displayBannersTab('2', 'Right').'
				</div>
				<div class="tab-page" id="step4">
					<h4 class="tab">'.$this->l('Home Banners').'</h4>
					'.$this->_displayBannersTab('3', 'Home').'
				</div>
			</div>
			<div class="clear"></div>
			<script type="text/javascript">
			function loadTab(id){}
			setupAllTabs();
			</script>
		</form>
		';
	}

	private function _displayBannersTab($block, $title){
		global $smarty, $currentIndex;

		$smarty->assign(array(
			'path'			=> $this->path,
			'banners' 		=> $this->getBanners($block),
			'block'			=> $block,
			'title'			=> $title,
			'leftBanners'	=> '2',
			'currentIndex'	=> $currentIndex,
			'rand'			=> rand()
		));
		return $this->display(__FILE__,'bannermanager_form.tpl');
	}

	private function _displayBannersAdd(){
		global $smarty, $currentIndex;

		$smarty->assign(array(
			'path'			=> $this->path
		));

		$this->_html .=  $this->display(__FILE__,'bannermanager_add.tpl');
	}

	/**
	*	_postProcess()
	*	Called upon successful module configuration validation
	*/
	private function _postProcess(){
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
					$this->_html .= "<div class='conf confirm'><img src='../img/admin/ok.gif' alt='{$modOk}' />{$modUpdated}</div>";
				}
				else
					$this->_html .= '<div class="alert error">'.$this->l('There were problems updating banners.').'</div>';
			}
		}
		// Banners add submit
		if (isset($_POST['addBannerSubmit'])){
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
				$path = dirname(__FILE__) . '/banners/'. basename( $_FILES['banner_image']['name']);
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
			$this->_html .= (isset($errors) && $errors != '') ? $errors : $this->displayConfirmation('Banner added successfully');
		}
		// Delete banner
		if (isset($_POST['deleteBannerSubmit'])){
			$bnr = Tools::getValue('bannerDelete');
			if ($this->deleteBanner($bnr)){
				/*Lang Variables*/ $modOk = $this->l('Ok'); $modUpdated = $this->l('Banner succesfully deleted');
				$this->_html .= "<div class='conf confirm'><img src='../img/admin/ok.gif' alt='{$modOk}' />{$modUpdated}</div>";
			} else
				$this->_html .= '<div class="alert error">'.$this->l('Problems deleting the banner.').'</div>';
		}
	}

	function hookHome($params)
	{
		global $cookie, $smarty;
		$smarty->assign(array(
			'banner_class' => 'home',
			'banners' => $this->getBanners('3'),
			'rel_path' => $this->_path,
			'this_path' => $this->_path.'banners/'
		));
		return $this->display(__FILE__, 'bannermanager_all.tpl');
	}


	function hookRightColumn($params)
	{
		global $cookie, $smarty;
		$smarty->assign(array(
			'banner_class' => 'right',
			'banners' => $this->getBanners('2'),
			'rel_path' => $this->_path,
			'this_path' => $this->_path.'banners/'
		));
		return $this->display(__FILE__, 'bannermanager_all.tpl');
	}

	function hookLeftColumn($params)
	{
		global $cookie, $smarty;
		$smarty->assign(array(
			'banner_class' => 'left',
			'banners' => $this->getBanners('1'),
			'rel_path' => $this->_path,
			'this_path' => $this->_path.'banners/'
		));
		return $this->display(__FILE__, 'bannermanager_all.tpl');
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
	public function addBanner($bnr){

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
	public function saveBanners($bnrs){

		foreach ($bnrs AS $bnr){
			$db = Db::getInstance();
			//update existing record
			$sql = 'UPDATE `'._DB_PREFIX_.'banner_manager` SET `active` = "'.$bnr['active'].'", `description` = "'.$bnr['description'].'", `image_name` = "'.$bnr['image_name'].'", `image_link` = "'.$bnr['image_link'].'" , `block_id` = "'.$bnr['block_id'].'", `order` = "'.$bnr['order'].'", `open_blank` = "'.$bnr['blank'].'"  WHERE id_banner_manager = '.$bnr['id'];
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