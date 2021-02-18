<?php


namespace itnovum\openITCOCKPIT\Agent;


class AgentHttpClientErrors {

    /**
     * No errors
     */
    const ERRNO_OK = 0;

    /**
     * Agent should only response to HTTPS requests because of sucessfull AutoTLS in the past
     * but the agent response to HTTP requests. So the cert file got delete on the Agent.
     */
    const ERRNO_AGENT_RESPONSES_TO_HTTP = 1 << 0;

    /**
     * The Agent is configured to only use HTTP
     */
    const ERRNO_AGENT_USES_ONLY_HTTP = 1 << 1;

    /**
     * AutoTLS certificates got exchanged successfully in the past but now the Cert from the oITC Server
     * mismatches the cert from the Agent. System mybe compromised
     */
    const ERRNO_HTTPS_COMPROMISED = 1 << 2;

    /**
     * Error while sending agent certificate to the Agent
     */
    const ERRNO_EXCHANGE_HTTPS_CERTIFICATE = 1 << 3;

    /**
     * No json response from agent or missing fields
     */
    const ERRNO_BAD_AGENT_RESPONSE = 1 << 4;

    /**
     * Unknown error
     */
    const ERRNO_UNKNOWN = 1 << 5;

    /**
     * Error while creating HTTPS connection
     */
    const ERRNO_HTTPS_ERROR = 1 << 6;

    /**
     * Error while creating insecure HTTP connection
     */
    const ERRNO_HTTP_ERROR = 1 << 7;
}
