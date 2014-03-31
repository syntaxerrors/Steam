<?php

class Steam_App_Publisher extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'steam_app_publishers';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	/**
	 * Validation rules
	 *
	 * @static
	 * @var array $rules All rules this model must follow
	 */
	public static $rules = array(
		'steam_app_id' => 'required|exists:steam_apps,id'
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'app' => array('belongsTo', 'Steam_App', 'foreignKey' => 'steam_app_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}