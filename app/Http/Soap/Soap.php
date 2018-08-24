<?php

namespace App\Http\Soap;

class Soap
{
    public $soapClient;
    protected $tableName;

    public function __construct(String $wsdnServiceName = null, Array $options = null)
    {
        if ($wsdnServiceName == null) {
            $this->soapClientInitDefault();
        } else {
            if ($options == null) {
                $options = array('login' => 'INTERNSHIP\COSMIN.PETRICA', 'password' => 'ptQ+G8glW1zBoeXzCllmVeLANqXvneMxpgyTA5MZhJ8=');
            }
            $this->soapClientInit($wsdnServiceName, $options);
        }
    }

    function soapClientInitDefault()
    {
        $this->soapClientInit("http://internship.arggo.consulting:7047/DynamicsNAV100/WS/CP/Page/CP_Universities",
        array('login' => 'INTERNSHIP\COSMIN.PETRICA', 'password' => 'ptQ+G8glW1zBoeXzCllmVeLANqXvneMxpgyTA5MZhJ8='));

        $this->tableName = 'Universities';
    }

    function soapClientInit($wsdnServiceName, $options)
    {
        $wsdn = "http://internship.arggo.consulting:7047/DynamicsNAV100/WS/CP/Page/" . $wsdnServiceName;
        $this->soapClient = new \SoapClient($wsdn, $options);

        $this->tableName = $this->getTableName($wsdn);
    }

    function getTableName(String $wsdn)
    {
        $bits = explode('/', $wsdn);
        return $bits[count($bits) - 1];
    }

    function soapCall($functionName, $params)
    {
        return $this->soapClient->__soapCall($functionName, $params);
    }

    function Read(Array $primaryKeyFields)
    {
        return $this->soapCall('Read', array($primaryKeyFields));
    }

    function ReadMultiple(Int $setSize = null, Array $filter = null)
    {
        return $this->soapCall('ReadMultiple', array(array('filter' => $this->newFilter($filter), 'setSize' => $setSize)));
    }

    function Create(Array $fields, Array $primaryKeyFields)
    {
        $fieldsToCheckBy = [];
        foreach ($primaryKeyFields as $primaryKeyField) {
            $fieldsToCheckBy[$primaryKeyField] = $fields[$primaryKeyField];
        }
        $checkExisting = $this->Read($fieldsToCheckBy);
        return $checkExisting ? : $this->soapCall('Create', array(array($this->tableName => $fields)));
    }

    function Update(Array $fields, Array $primaryKeyFields)
    {
        $fieldsToCheckBy = [];
        foreach ($primaryKeyFields as $primaryKeyField) {
            $fieldsToCheckBy[$primaryKeyField] = $fields[$primaryKeyField];
        }
        $checkExisting = $this->Read($fieldsToCheckBy);
        if($checkExisting == null) {
            return null;
        }
        $table = $this->tableName;
        $fields['Key'] = $checkExisting->$table->Key;
        return $this->soapCall('Update', array(array($this->tableName => $fields)));
    }

    function Delete(Array $primaryKeyFields)
    {
        $checkExisting = $this->Read($primaryKeyFields);
        if($checkExisting == null) {
            return false;
        }
        $table = $this->tableName;
        return $this->soapCall('Delete', array(array('Key' => $checkExisting->$table->Key)));
    }

    function newFilter(Array $filter = null)
    {
        if ($filter == null) {
            return;
        }
        $parsedFilter = [];
        foreach ($filter as $field => $criteria) {
            $parsedFilter[] = Array('Field' => $field, 'Criteria' => $criteria);
        }
        return $parsedFilter;
    }
}