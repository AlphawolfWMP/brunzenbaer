<?php
namespace wcf\page;
use wcf\system\WCF;

/**
 * Shows the Brunzenbaer page.
 * 
 * @author	Oliver Schlöbe
 * @copyright 2014 schloebe.de
 * @license	LGPL
 * @package	de.schloebe.wbb.brunzenbaer
 */
class BrunzenbaerPage extends AbstractPage {
	//const AVAILABLE_DURING_OFFLINE_MODE = BRUNZENBAER_PAGE_ENABLE_OFFLINEMODE;
	
	/**
	 * @see	wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.BrunzenbaerPage.menu';
	
	/**
	 * @see	\wcf\page\AbstractPage::$enableTracking
	 */
	public $enableTracking = true;
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	#public $neededPermissions = array('user.profile.canViewBrunzenbaerPage');
	
	/**
	 * @see	wcf\page\AbstractPage::$neededModules
	 */
	//public $neededModules = array('MODULE_BRUNZENBAER_PAGE');
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$periodform = $this->buildFilterHtml();
		$results = '';
		
		if(isset($_POST) && !empty($_POST)) {
			$url = 'http://api.schloebe.de/brunzenbaer/api.php?action=get&year=' . $_POST['yearPeriod'] . '&month=' . $_POST['monthPeriod'] . '';
			
			$json = file_get_contents($url);
			$json = json_decode($json);
			
			$results = $this->buildResultsHtml($json);
		}
		
		
		WCF::getTPL()->assign(array(
			'periodform' => $periodform,
			'results' => $results
		));
	}
	
	
	private function buildFilterHtml() {
		$html = '';
		$months = range(1, 12);
		$years = range(2014, date('Y'));

		$html .= '<select id="monthPeriod" name="monthPeriod">';
		foreach( $months as $month ) {
			$html .= '<option value="' . $month . '"' . $this->selected('monthPeriod', $month) . '>' . $month . '</option>';
		}
		$html .= '</select>';
		
		$html .= ' <select id="yearPeriod" name="yearPeriod">';
		foreach( $years as $year ) {
			$html .= '<option value="' . $year . '"' . $this->selected('yearPeriod', $year) . '>' . $year . '</option>';
		}
		$html .= '</select>';
		
		return $html;
	}
	
	
	private function buildResultsHtml($json) {
		$html = '';
		
		if( $json->success ) {
			$html .= '<div class="container marginTop">';
			foreach( $json->items as $item ) {
				$postObj = new \wbb\data\post\Post($item->postid);
				$threadObj = $postObj->getThread();
				$boardObj = new \wbb\data\board\Board($threadObj->boardID);
				$userObj = new \wcf\data\user\User($postObj->getUserID());
				$userProfileObj = new \wcf\data\user\UserProfile($userObj);
				$nomineeUserObj = new \wcf\data\user\User($item->userid);
				
				#$html .= "<pre>";
				#$html .= print_r($boardObj, true);
				#$html .= "</pre>";
				
				$html .= '<ul class="containerList messageSearchResultList">';
				$html .= '<li>';
				$html .= '<div class="box48">';
				$html .= $userProfileObj->getUserProfile($postObj->getUserID())->getAvatar()->getImageTag(48);
						
				$html .= '<div>';
				$html .= '<div class="containerHeadline">';
				$html .= '<h3><a href="' . $postObj->getLink() . '">' . $postObj->getTitle() . '</a></h3>';
				$html .= '<p>';
				$html .= 'Erstellt von ' . $userObj->getTitle();
				$html .= ' in <a href="' . $boardObj->getLink() . '">' . $boardObj->getTitle() . '</a>';
				$html .= '</p> ';
				$html .= '<small class="containerContentType">Beitrag wurde <strong>' . $item->count . 'mal</strong> nominiert</small>';
				$html .= '</div>';
							
				$html .= '<p>' . $postObj->getSimplifiedFormattedMessage() . '</p>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</li>';
				$html .= '</ul>';
			}
			$html .= '</div>';
			
			#$html .= "<pre>";
			#$html .= print_r($json, true);
			#$html .= "</pre>";
		} else {
			$html .= '<p class="info">Keine Nominierungen für den angegebenen Zeitraum gefunden.</p>';
		}
		
		return $html;
	}
	
	
	private function selected( $key, $val ) {
		return (isset($_POST) && !empty($_POST) && $_POST[$key] == $val) ? ' selected="selected"' : '';
	}
}