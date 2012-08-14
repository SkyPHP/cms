<?php

class example extends Model
{

    /**
     * Discard cached data older than this date.
     * This is only necessary if the data structure has changed.
     * @var string YYYY-MM-DD
     */
    public static $mod_time = '';

    /**
     * Array of possible errors for this model.
     * = array(
     *     'my_error_code' => array(
     *          'message' => 'The value for this field is invalid',
     *          'fields' => array('my_field') // optional
     *          'type' => 'invalid' // optional
     *          'arbitrary_anything' => '' // optional
     *      )
     * );
     * @var array
     */
    protected static $possible_errors = array(
        'already_exists' => array(
            'message' => 'This record already exists in the database.'
        )
    );

    /**
     * Required Fields
     * This model will not save if these fields are blank. The Field description is used
     * to generate the error message. i.e. "'Field description' is required."
     * = array(
     *      'name' => 'Name'
     *      'birth_date' => 'Birthdate'
     * );
     * @var array
     */
    public $_required_fields = array(

    );

    /**
     * Read-only Fields
     * Specifies which data elements in the aql are read-only
     * = array(
     *     'tables' => array('table_name'),
     *     'objects' => array('another_model')
     *     'subs' => array('subquery_primary_table')
     *     'fields' => array('field1', 'field2')
     * );
     * @var array
     */
    public $_ignore = array(

    );

    /**
     * Foreign Keys
     * If this object has a foreign key that is used to populate a list in the foreign
     * model, then add the foreign models here so the foreign
     * list is refreshed when this model's foreign key changes.
     * = array(
     *     'foreign_model' => array('foreign_model_id')
     * );
     * @var array
     */
    public $_belongs_to = array(

    );

    /**
     * Runs when this object is instantiated. Set calculated properties here.
     * Do not override __construct().
     * This also runs after Model::reload()
     */
    public function construct()
    {

    }

    /**
     * Runs before checking the required fields and before validation.
     * $this->addError('my-error-code') -- validation will not run and the save
            transaction will fail and will roll back.
     * static::error('my-error-code') -- immediately throws ValidationException
     */
    public function beforeCheckRequiredFields()
    {

    }

    /**
     * Runs if $this->my_field is set and is not null.
     * $this->addError('my-error-code') -- validation will continue to run but the save
            transaction will fail and will roll back.
     * static::error('my-error-code') -- immediately throws ValidationException
     */
    public function validate_my_field()
    {

    }

    /**
     * Only runs if all individual fields are valid so far.
     * $this->addError('my-error-code') -- validation will continue to run but the save
            transaction will fail and will roll back.
     * static::error('my-error-code') -- immediately throws ValidationException
     */
    public function validate()
    {

    }

    /**
     * Runs before inserting a new record if there are no errors after all validation.
     * static::error() or $this->addError() will abort and roll back.
     */
    public function beforeInsert()
    {

    }

    /**
     * Runs before updating a record if there are no errors after all validation.
     * static::error() or $this->addError() will abort and roll back.
     */
    public function beforeUpdate()
    {

    }

    /**
     * Runs before deleting a record.
     * static::error() or $this->addError() will abort and roll back.
     */
    public function beforeDelete()
    {

    }

    /**
     * Insert was a success, we now have our new id: $this->getID()
     * But we are still in the transaction, so $this->addError() will abort and roll back.
     */
    public function afterInsert()
    {

    }

    /**
     * Update was a success.
     * But we are still in the transaction, so $this->addError() will abort and roll back.
     */
    public function afterUpdate()
    {

    }

    /**
     * Runs after deleting a record.
     * But we are still in the transaction, so $this->addError() will abort and roll back.
     */
    public function afterDelete()
    {

    }
