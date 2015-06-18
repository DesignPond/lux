<?php

include 'Service/Abo.php';
include 'Models/Rjn_abo.php';

class AboTest extends PHPUnit_Framework_TestCase {

	public function testIsActive()
	{
        $abo  = new \Abo();
        $user = new \Rjn_abo();

        $user->date_resiliation = '0000-00-00';

        $abo->aboIsActive($user);

        $this->assertTrue( $abo->aboIsActive($user) );
	}

    public function testIsResilie()
    {
        $abo  = new \Abo();
        $user = new \Rjn_abo();

        $user->date_resiliation = '2013-01-02';

        $abo->aboIsActive($user);

        $this->assertFalse( $abo->aboIsActive($user) );
    }

}
