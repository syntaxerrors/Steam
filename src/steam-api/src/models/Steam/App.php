<?php

class Steam_App extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'steam_apps';

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
		'appId' => 'unique:steam_apps,appId',
		'name'  => 'required'
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'details'      => array('hasOne',	'Steam_App_Detail',			'foreignKey' => 'steam_app_id'),
		'requirements' => array('hasMany',	'Steam_App_Requirement_Pc',	'foreignKey' => 'steam_app_id'),
		'developers'   => array('hasMany',	'Steam_App_Developer',		'foreignKey' => 'steam_app_id'),
		'publishers'   => array('hasMany',	'Steam_App_Publisher',		'foreignKey' => 'steam_app_id'),
		'genres'       => array('hasMany',	'Steam_App_Genre',			'foreignKey' => 'steam_app_id'),
		'categories'   => array('hasMany',	'Steam_App_Category',		'foreignKey' => 'steam_app_id'),
		'metacritic'   => array('hasOne',	'Steam_App_Metacritic',		'foreignKey' => 'steam_app_id'),
		'prices'       => array('hasOne',	'Steam_App_Price',			'foreignKey' => 'steam_app_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}