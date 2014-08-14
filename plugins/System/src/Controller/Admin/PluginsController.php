<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace System\Controller\Admin;

use Cake\Error\NotFoundException;
use Cake\Utility\Hash;
use System\Controller\AppController;
use QuickApps\Core\Plugin;

/**
 * Controller for handling plugin tasks.
 *
 * Here is where can install new plugin or remove existing ones.
 */
class PluginsController extends AppController {

/**
 * An array containing the names of components controllers uses.
 *
 * @var array
 */
	public $components = ['Installer.Installer'];

/**
 * Main action.
 *
 * @return void
 */
	public function index() {
		$collection = Plugin::collection(true)->match(['isTheme' => false]);
		$plugins = $collection->match(['status' => true])->toArray();
		$enabled = count($collection->match(['status' => true])->toArray());
		$disabled = count($collection->match(['status' => false])->toArray());
		$this->set(compact('plugins', 'all', 'enabled', 'disabled'));
		$this->Breadcrumb->push('/admin/system/plugins');
	}

/**
 * Install a new theme.
 *
 * @return void
 */
	public function install() {
		if ($this->request->data) {
			if (isset($this->request->data['download'])) {
				$task = $this->Installer
					->task('install', ['active' => true])
					->download($this->request->data['url']);
			} else {
				$task = $this->Installer
					->task('install', ['active' => true])
					->upload($this->request->data['file']);
			}

			$success = $task->run();
			if ($success) {
				$this->Flash->success(__d('system', 'Plugins successfully installed!'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->set(__d('system', 'Plugins could not be installed'), [
					'element' => 'System.installer_errors',
					'params' => ['errors' => $task->errors()],
				]);
			}
		}
	}

/**
 * Install a new theme.
 *
 * @return void Redirects to previous page
 */
	public function delete($pluginName) {
		$plugin = Plugin::info($pluginName, true);
		$task = $this->Installer->task('uninstall', ['plugin' => $pluginName]);
		$success = $task->run();
		if ($success) {
			$this->Flash->success(__d('system', 'Plugin was successfully removed!'));
		} else {
			$this->Flash->set(__d('system', 'Plugins could not be removed'), [
				'element' => 'System.installer_errors',
				'params' => ['errors' => $task->errors()],
			]);
		}

		$this->redirect($this->referer());
	}

/**
 * Handles plugin's specifics settings.
 *
 * When saving plugin's information `PluginsTable` will trigger the following events:
 *
 * - `Plugin.<PluginName>.beforeValidate`
 * - `Plugin.<PluginName>.afterValidate`
 * - `Plugin.<PluginName>.beforeSave`
 * - `Plugin.<PluginName>.afterSave`
 *
 * Check `PluginsTable` documentation for more details.
 *
 * @param string $pluginName
 * @return void
 * @throws \Cake\Error\NotFoundException When plugin do not exists
 */
	public function settings($pluginName) {
		$plugin = Plugin::info($pluginName, true);
		$arrayContext = [
			'schema' => [],
			'defaults' => [],
			'errors' => [],
		];

		if (!$plugin['hasSettings'] || $plugin['isTheme']) {
			throw new NotFoundException(__d('system', 'The requested page was not found.'));
		}

		if (!empty($this->request->data)) {
			$this->loadModel('System.Plugins');
			$pluginEntity = $this->Plugins->get($pluginName);
			$pluginEntity->set('settings', $this->request->data);

			if ($this->Plugins->save($pluginEntity)) {
				$this->Flash->success(__d('system', 'Plugin settings saved!'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('system', 'Plugin settings could not be saved'));
				$errors = $pluginEntity->errors();

				if (!empty($errors)) {
					foreach ($errors as $field => $message) {
						$arrayContext['errors'][$field] = $message;
					}
				}
			}
		} else {
			$this->request->data = $plugin['settings'];
		}

		$this->set(compact('arrayContext', 'plugin'));
		$this->Breadcrumb->push('/admin/system/plugins');
		$this->Breadcrumb->push(__d('system', 'Settings for {0} plugin', $plugin['name']), '#');
	}

}
