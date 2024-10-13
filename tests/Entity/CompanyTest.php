<?php

namespace App\Tests\Entity;

use App\Entity\Company;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    public function testCompanyProperties()
    {
        $company = new Company();

 
        $company->setName('Test Company');
        $this->assertEquals('Test Company', $company->getName());


        $company->setSiret('12345678901234');
        $this->assertEquals('12345678901234', $company->getSiret());

        $company->setAddress('123 Rue de Exemple');
        $this->assertEquals('123 Rue de Exemple', $company->getAddress());
    }
}
