<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class MainController extends Controller
{

    function getDataFromPostServices()
    {
        //$this->soapClientInitDefault();
        
        // $this->soapClientInit("https://internship.arggo.consulting:7047/DynamicsNAV100/WS/CP/Page/Universities",
        // array('login' => 'INTERNSHIP\COSMIN.PETRICA', 'password' => 'WM2b11jbIS22nry/upw9QtsorzIb3NUvwflwjUz2WLA='));

        // $read = $this->Read(['Id' => 5]);
        // dd($read);
        //dd($read->Universities->Id);
        //dd($this->ReadMultiple());
        //$readMultipleResult = $this->ReadMultiple(0, null, null);

        // $createdUniv = $this->Create(['Id' => 5, 'Name' => 'Carol Davila', 'Address' => 'Bucuresti, Centru'], ['Id']);
        // $readMultipleResult1 = $this->ReadMultiple(0, null, null);
        // $updatedUniv = $this->Update(['Id' => 5, 'Name' => 'Carol Davill', 'Address' => 'Bucuresti, Centru'], ['Id']);
        // $readMultipleResult2 = $this->ReadMultiple(0, null, null);
        // $deletedUnivResult = $this->Delete( ['Id' => 5]);
        // $readMultipleResult3 = $this->ReadMultiple(0, null, null);
        // dd($readMultipleResult1, $readMultipleResult2, $readMultipleResult3);
        // //dd($updatedUniv);
        // //dd($createdUniv);
        // //dd($readMultipleResult);
        // dd($this->soapClient->__getFunctions() ,$this->soapClient->__getTypes());
    }
}
