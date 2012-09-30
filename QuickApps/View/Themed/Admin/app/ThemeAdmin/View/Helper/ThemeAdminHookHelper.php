<?php
/**
 * Theme Helper
 *
 * For core theme `Admin`
 *
 * PHP version 5
 *
 * @package  QuickApps.Themes.Admin.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeAdminHookHelper extends AppHelper {
	public $helpers = array('ThemeAdmin.BootstrapPaginator');

	public function beforeRender($viewFile) {
		if ($this->_View->request->params['plugin'] == 'user' &&
			$this->_View->request->params['controller'] == 'user' &&
			in_array($this->_View->request->params['action'], array('login', 'admin_login'))
		) {
			$this->_View->Layout->script('login.js');
		}
	}

	public function pagination() {
		return $this->BootstrapPaginator->pagination();
	}

	public function html_table_alter(&$info) {
		$info['options']['tableOptions'] = array('class' => 'table table-bordered');

		// styles for nodes table list
		if ($this->request->controller == 'contents' && $this->action == 'admin_index' && strtolower($this->plugin) == 'node') {
			$statusClass = '{php} return ({Node.status} == 0 ? "btn-danger" : "btn-primary"); {/php}';
			$actions = "
				<li><a href='{url}/admin/node/contents/edit/{Node.slug}{/url}'>" . __t('edit') . "</a></li>
				{php} return (!'{Node.translation_of}' && '{Node.language}') ? \"<li><a href='{url}/admin/node/contents/translate/{Node.slug}{/url}'>" . __t('translate') . "</a></li>\" : ''; {/php}
				<li><a href='{url}/admin/node/contents/delete/{Node.slug}{/url}' onclick=\"return confirm('" . __t('Delete selected content ?') . "');\">" . __t('delete') . "</a></li>
			";
			$label = '
				{truncate length=50}{Node.title}{/truncate}
				{php} return ({Node.sticky}) ? \'<i class="icon-star" title="' . __t("Sticky at top") . '"></i>\' : ""; {/php}
				{php} return ({Node.promote}) ? \'<i class="icon-home" title="' . __t("Promoted in front page") . '"></i>\' : ""; {/php}
				{php} return (trim("{Node.cache}") != "") ? \'<i class="icon-hdd" title="' . __t("Cache activated") . ': ' . '{Node.cache}"></i>\' : ""; {/php}
				{php} return (trim("{Node.translation_of}") != "") ? \'<i class="icon-flag" title="' . __t("This node is a translation of other") . '"></i>\' : ""; {/php}
			';
			$info['options']['columns'][__t('Title')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/node/contents/edit/{Node.slug}{/url}\" class=\"btn {$statusClass}\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle {$statusClass}\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";

			unset($info['options']['columns'][__t('Actions')], $info['options']['columns'][__t('Status')]);
		}

		// styles for users table list
		if ($this->request->controller == 'list' && $this->action == 'admin_index' && strtolower($this->plugin) == 'user') {
			$statusClass = '{php} return {User.status} == 1 ? "btn-primary" : "btn-warning"; {/php}';
			$label = '{User.username} ({User.email})';
			$actions = "<li><a href='{url}/admin/user/list/edit/{User.id}{/url}'>" . __t('edit') . "</a></li>";
			$info['options']['columns'][__t('User Name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/user/list/edit/{User.id}{/url}\" class=\"btn {$statusClass}\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle {$statusClass}\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";
			unset($info['options']['columns'][__t('Actions')], $info['options']['columns'][__t('Email')]);
		}

		// styles for languages table list
		if ($this->request->controller == 'languages' && $this->action == 'admin_index' && strtolower($this->plugin) == 'locale') {
			$statusClass = '{php} return {Language.status} == 1 ? "btn-primary" : "btn-danger"; {/php}';
			$actions = '
				<li><a href="{url}/admin/locale/languages/move/{Language.id}/up{/url}">' . __t('move up') . '</a></li>
				<li><a href="{url}/admin/locale/languages/move/{Language.id}/down{/url}">' . __t('move down') . '</a></li>
				{php} return "{Language.code}" != "' . Configure::read('Variable.default_language') . '" ? \'<li><a href="{url}/admin/locale/languages/set_default/{Language.id}{/url}">' . __t('set as default') . '</a></li>\' : \'\'; {/php}
				<li><a href="{url}/admin/locale/languages/edit/{Language.id}{/url}">' . __t('edit') . '</a></li>
			';
			$label = '
				{php}
					$icon = strpos("{Language.icon}", "://") !== false ? "{Language.icon}" : "/locale/img/flags/{Language.icon}";
					return ("{Language.icon}" != "" ? $this->_View->Html->image($icon, array("width" => 16, "class" => "flag-icon")) : "");
				{/php}
				{Language.name}
				{php} return ("{Language.code}" == "' . Configure::read('Variable.default_language') . '" ? \'<i class="icon-star" title="' . __t('Default language') . '"></i>\' : ""); {/php}
			';

			$info['options']['columns'][__t('English name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/locale/languages/edit/{Language.id}{/url}\" class=\"btn {$statusClass}\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle {$statusClass}\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";

			unset($info['options']['columns'][__t('Actions')], $info['options']['columns'][__t('Status')]);
		}

		// styles for translatable entries table list
		if ($this->request->controller == 'translations' && $this->action == 'admin_list' && strtolower($this->plugin) == 'locale') {
			$label = '{truncate length=80}{php} return htmlentities("{Translation.original}", ENT_QUOTES, "UTF-8"); {/php}{/truncate}';
			$actions = "
				<li><a href='{url}/admin/locale/translations/edit/{Translation.id}{/url}'>" . __t('edit') . "</a></li>
				<li><a href='{url}/admin/locale/translations/regenerate/{Translation.id}{/url}' title='" . __t('Regenerate translation cache') . "'>" . __t('regenerate') . "</a></li>
				<li><a href='{url}/admin/locale/translations/delete/{Translation.id}{/url}' onclick='return confirm(\"" . __t('Delete this entry ?') . "\");'>" . __t('delete') . "</a></li>
			";

			$info['options']['columns'][__t('Text')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/locale/translations/edit/{Translation.id}{/url}\" class=\"btn btn-primary\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle btn-primary\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";

			unset($info['options']['columns'][__t('Actions')]);
		}
	}

	public function form_input_alter(&$info) {
		if (isset($info['options']['type']) && $info['options']['type'] == 'submit') {
			$this->__button($info);
		}
	}

	public function menu_toolbar_alter(&$info) {
		$info['options']['class'] = 'nav nav-pills';
	}

	public function form_submit_alter(&$info) {
		$this->__button($info);
	}

	public function form_button_alter(&$info) {
		$this->__button($info);
	}

	private function __button(&$info) {
		$info['options']['label'] = false;
		$info['options']['div'] = false;
		$info['options']['class'] = 'btn btn-primary';
	}
}