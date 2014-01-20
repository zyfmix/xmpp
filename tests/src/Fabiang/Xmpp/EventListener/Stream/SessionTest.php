<?php

/**
 * Copyright 2014 Fabian Grutschus. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those
 * of the authors and should not be interpreted as representing official policies,
 * either expressed or implied, of the copyright holders.
 *
 * @author    Fabian Grutschus <f.grutschus@lubyte.de>
 * @copyright 2014 Fabian Grutschus. All rights reserved.
 * @license   BSD
 * @link      http://github.com/fabiang/xmpp
 */

namespace Fabiang\Xmpp\EventListener\Stream;

use Fabiang\Xmpp\Connection\Test;
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Event\XMLEvent;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-17 at 16:01:32.
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Session
     */
    protected $object;

    /**
     *
     * @var Test
     */
    protected $connection;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->object = new Session;

        $this->connection = new Test;

        $options = new Options;
        $options->setConnection($this->connection);
        $this->object->setOptions($options);
        $this->connection->setReady(true);
    }

    /**
     * Test attaching events.
     *
     * @covers Fabiang\Xmpp\EventListener\Stream\Session::attachEvents
     * @return void
     */
    public function testAttachEvents()
    {
        $this->object->attachEvents();
        $this->assertSame(
            array(
                '*'                                            => array(),
                '{urn:ietf:params:xml:ns:xmpp-session}session' => array(array($this->object, 'session'))
            ),
            $this->connection->getInputStream()->getEventManager()->getEventList()
        );
    }

    /**
     * Test event when session is part of features element.
     *
     * @covers Fabiang\Xmpp\EventListener\Stream\Session::session
     * @covers Fabiang\Xmpp\EventListener\Stream\Session::isBlocking
     * @return void
     */
    public function testSessionAsFeatureElement()
    {
        $document = new \DOMDocument;
        $document->loadXML('<features><session/></features>');

        $event   = new XMLEvent;
        $event->setParameters(array($document->firstChild->firstChild));

        $this->object->session($event);

        $this->assertTrue($this->object->isBlocking());
        $buffer = $this->connection->getbuffer();
        $this->assertRegExp(
            '/<iq type="set" id="[^"]+"><session xmlns="urn:ietf:params:xml:ns:xmpp-session"\/><\/iq>/',
            $buffer[0]
        );
    }

    /**
     * Test session response.
     *
     * @covers Fabiang\Xmpp\EventListener\Stream\Session::session
     * @depends testSessionAsFeatureElement
     * @return void
     */
    public function testSessionResponse()
    {
        $document = new \DOMDocument;
        $document->loadXML('<features><session/></features>');

        $event   = new XMLEvent;
        $event->setParameters(array($document->firstChild->firstChild));

        $this->object->session($event);
        
        $this->assertTrue($this->object->isBlocking());
        
        $document = new \DOMDocument;
        $document->loadXML('<iq><session xmlns="urn:ietf:params:xml:ns:xmpp-session"/></iq>');

        $event   = new XMLEvent;
        $event->setParameters(array($document->firstChild));

        $this->object->session($event);

        $this->assertFalse($this->object->isBlocking());
    }

}