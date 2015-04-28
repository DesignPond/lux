<?php

include 'Service/Event.php';

class EventTest extends PHPUnit_Framework_TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testIsOrganisateur()
	{
        $event    = new \Event();
        $colloque = array('centre_logos' => 'cert,cemaj');
        $centres  = array('cert','cemaj');

        $actual   = $event->isOrganisateur($colloque,$centres);
        $expected = array('cert','cemaj');

        $this->assertEquals($expected, $actual);
	}

}
