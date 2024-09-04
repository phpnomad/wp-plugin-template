<?php

namespace PHPNomad\Database\Events;

use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Events\Interfaces\Event;

class RecordUpdated implements Event
{
	protected array  $data;
	protected array  $identity;
	protected string $type;

	public function __construct(string $type, array $identity, array $data)
	{
		$this->identity = $identity;
		$this->data = $data;
		$this->type = $type;
	}

	/**
	 * Gets the data used to store the record in the database.
	 *
	 * @return array
	 */
	public function getData() : array
	{
		return $this->data;
	}

	/**
	 * Gets the identity for the record that was updated.
	 *
	 * @return array
	 */
	public function getIdentity() : array
	{
		return $this->identity;
	}

	/**
	 * Gets the model type for the record that was updated.
	 *
	 * @return string
	 */
	public function getType() : string
	{
		return $this->type;
	}

	public static function getId() : string
	{
		return 'record_created';
	}
}