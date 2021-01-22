<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace itnovum\openITCOCKPIT\Agent;

use App\Model\Table\AgentconnectorTable;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\System\Health\SystemId;

/**
 * Class AgentCertificateData
 */
class AgentCertificateData {

    private $ECC_CA = false;
    private $days_CA = 36500; //36500 = 100 years (with 365 days)

    private $caCertPath = '/opt/openitc/agent/server_ca.pem';
    private $caKeyPath = '/opt/openitc/agent/server_ca.key';

    //needs: mkdir -p /opt/openitc/agent && chown www-data:www-data -R /opt/openitc/agent


    public function __construct() {
        if (!is_file($this->getCaCertPath())) {
            $this->generateServerCA();
        }
    }

    public function isEccCa(): bool {
        return $this->ECC_CA;
    }

    public function getCaDayLifetime(): int {
        return $this->days_CA;
    }

    public function getCaCertPath(): string {
        return $this->caCertPath;
    }

    public function getCaKeyPath(): string {
        return $this->caKeyPath;
    }

    public function getAgentCsr($hostuuid, $csr, AgentconnectorTable $AgentconnectorTable): array {
        $AgentConnectionEntity = $AgentconnectorTable->getByHostUuid($hostuuid);

        $output = $this->signAgentCsr($csr);
        $checksum = strtoupper(hash('sha512', $output['signed']));
        $ca_checksum = strtoupper(hash('sha512', $output['ca']));
        //$tsb = new TimestampBehavior();

        $AgentConnectionEntity = $AgentconnectorTable->patchEntity($AgentConnectionEntity, [
            'checksum'        => $checksum,
            'ca_checksum'     => $ca_checksum,
            //'generation_date' => $tsb->timestamp()
            'generation_date' => FrozenTime::now()
        ]);
        $AgentconnectorTable->save($AgentConnectionEntity);
        return $output;
    }

    public function signAgentCsr($csr, $updateDatabaseUsingHostUuid = ''): array {
        if (!is_file($this->getCaCertPath())) {
            $this->generateServerCA();
        }

        // Generate signed cert from csr
        $x509 = openssl_csr_sign($csr, file_get_contents($this->getCaCertPath()), file_get_contents($this->getCaKeyPath()), $days = 365, ['digest_alg' => 'sha512', 'x509_extensions' => 'v3_req'], time());

        openssl_x509_export($x509, $signedPublic);
        #openssl_x509_export_to_file($x509, '/var/www/html/testcrts/test_agent_csr_cert.pem');

        $ca = file_get_contents($this->getCaCertPath());

        if ($updateDatabaseUsingHostUuid !== '') {
            /** @var AgentconnectorTable $AgentconnectorTable */
            $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');

            if ($AgentconnectorTable->existsByHostuuid($updateDatabaseUsingHostUuid)) {
                $AgentConnectionEntity = $AgentconnectorTable->getByHostUuid($updateDatabaseUsingHostUuid);
                $AgentConnectionEntity = $AgentconnectorTable->patchEntity($AgentConnectionEntity, [
                    'checksum'        => strtoupper(hash('sha512', $signedPublic)),
                    'ca_checksum'     => strtoupper(hash('sha512', $ca)),
                    //'generation_date' => $tsb->timestamp()
                    'generation_date' => FrozenTime::now()
                ]);
            } else {
                $AgentConnectionEntity = $AgentconnectorTable->newEmptyEntity();
                $AgentConnectionEntity = $AgentconnectorTable->patchEntity($AgentConnectionEntity, [
                    'hostuuid'        => $updateDatabaseUsingHostUuid,
                    'checksum'        => strtoupper(hash('sha512', $signedPublic)),
                    'ca_checksum'     => strtoupper(hash('sha512', $ca)),
                    //'generation_date' => $tsb->timestamp()
                    'generation_date' => FrozenTime::now()
                ]);
            }
            $AgentconnectorTable->save($AgentConnectionEntity);
        }

        return ["signed" => $signedPublic, "ca" => $ca];
    }

    /**
     * @return string
     */
    public function getCaChecksum(){
        $ca = file_get_contents($this->getCaCertPath());
        return strtoupper(hash('sha512', $ca));
    }

    public function generateServerCA() {
        // Generate initial agent server ca certificate
        $SystemId = new SystemId();
        $oitcID = $SystemId->getSystemId();

        $subject = [
            "commonName" => $oitcID . '.agentserver.oitc',
        ];

        $folderpath = dirname($this->getCaCertPath());
        if (!file_exists($folderpath) || !is_dir($folderpath)) {
            mkdir($folderpath, 0777, true);
        }

        // Generate a new private key
        $digest_alg = 'sha512';
        $private_key = openssl_pkey_new([
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "digest_alg"       => $digest_alg,
            "private_key_bits" => 4096,
        ]);
        if ($this->isEccCa()) {
            $digest_alg = 'sha384';
            $private_key = openssl_pkey_new([
                "private_key_type" => OPENSSL_KEYTYPE_EC,
                "curve_name"       => 'prime256v1',
            ]);
        }

        $csr = openssl_csr_new($subject, $private_key, ['digest_alg' => $digest_alg]);

        $x509 = openssl_csr_sign($csr, null, $private_key, $days = $this->getCaDayLifetime(), ['digest_alg' => $digest_alg], time());
        openssl_x509_export_to_file($x509, $this->getCaCertPath());
        openssl_pkey_export_to_file($private_key, $this->getCaKeyPath());
        sleep(1);
    }
}
