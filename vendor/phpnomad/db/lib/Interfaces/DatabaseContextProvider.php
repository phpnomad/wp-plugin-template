<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Datastore\Interfaces\DataModel;

interface DatabaseContextProvider
{
	/**
	 * @return Table
	 */
	public function getTable(): Table;

	/**
	 * @return ModelAdapter
	 */
	public function getModelAdapter(): ModelAdapter;

	/**
	 * @return class-string<DataModel>
	 */
	public function getModel(): string;
}