<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mock Field.
 *
 * This entity represents a Table column.
 * It holds the following attributes:
 *
 * - name: Machine name of this field. ex. `user-age`. (Schema equivalent: column name)
 * - label: Human readable name of this field e.g.: `User Last name`.
 * - value: Value for this [FieldInstance, Entity] tuple. (Schema equivalent: cell value).
 * - extra: Extra information related to `value` or raw information.
 * - metadata
 *     - field_data_id: ID of the data stored in `field_data` table (from where `value` comes from).
 *     - field_instance_id: ID of field instance (`field_instances` table) attached to Table.
 *     - table_alias: Name of the table this field is attached to. e.g: `users`.
 *     - description: Something about this field: e.g.: `Please enter your name`.
 *     - required: 1|0
 *     - settings: Array of additional information handled by this particular field. ex: `max_len`, `min_len`, etc
 *     - handler: Name of the `Listener Class` a.k.a. `Field Handler`. ex: `Field\Text`
 */
class Field extends Entity {

/**
 * String representation of this field.
 *
 * By default, `data` property.
 *
 * @return string
 */
	public function __toString() {
		return (string)$this->get('data');
	}

}