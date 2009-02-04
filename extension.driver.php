<?php
	
	class extension_sitename extends Extension {

		public function about() {
			return array(
				'name'			=> 'Site Name',
				'version'		=> '1.0',
				'release-date'	=> '2009-01-02',
				'author'		=> array(
					'name'			=> 'Stephen Bau',
					'website'		=> 'http://www.domain7.com/',
					'email'			=> 'stephen@domain7.com'
				),
				'description'	=> 'Symphony System Preference for modifying the site name'
	 		);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendPreferences'
				),

				array(
					'page' => '/system/preferences/',
					'delegate' => 'Save',
					'callback' => '__SavePreferences'
				),
						
				array(
					'page' => '/backend/',
					'delegate' => 'AppendPageAlert',
					'callback' => '__AppendAlert'
				),
			);
		}

		public function __SavePreferences(){
			$settings = $_POST['settings'];

			$setting_group = 'general';
			$setting_name = 'sitename';
			$setting_value = $settings['general']['sitename'];

			$this->_Parent->Configuration->set($setting_name, $setting_value, $setting_group);
			$this->_Parent->saveConfig();

//			redirect(URL . '/symphony/system/preferences/?action=sitename-saved');

		}
		
		public function __AppendAlert($context){
			if($_REQUEST['action'] == 'sitename-saved'){
				$this->_Parent->Page->pageAlert('The site name was updated successfully.', AdministrationPage::PAGE_ALERT_NOTICE);
			}
		}
		
		public function appendPreferences($context){
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', 'Site Name'));			

			$sitename = $this->_Parent->Configuration->get('sitename', 'general');
			$label = new XMLElement('label', 'Website Name');			
			$label->appendChild(Widget::Input('settings[general][sitename]', $sitename, 'text'));
			
			$group->appendChild($label);						
			$context['wrapper']->appendChild($group);
		}
	}
	
?>