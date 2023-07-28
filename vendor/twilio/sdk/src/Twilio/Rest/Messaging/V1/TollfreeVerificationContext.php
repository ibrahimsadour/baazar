<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Messaging\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class TollfreeVerificationContext extends InstanceContext {
    /**
     * Initialize the TollfreeVerificationContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid Tollfree Verification Sid
     */
    public function __construct(Version $version, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['sid' => $sid, ];

        $this->uri = '/Tollfree/Verifications/' . \rawurlencode($sid) . '';
    }

    /**
     * Fetch the TollfreeVerificationInstance
     *
     * @return TollfreeVerificationInstance Fetched TollfreeVerificationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): TollfreeVerificationInstance {
        $payload = $this->version->fetch('GET', $this->uri);

        return new TollfreeVerificationInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Messaging.V1.TollfreeVerificationContext ' . \implode(' ', $context) . ']';
    }
}