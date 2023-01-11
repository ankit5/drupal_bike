<?php
print __DIR__;

require __DIR__.'/test/vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

function aws_d($secretName){
 $options['credentials'] = [
        'key' => 'AKIAW2ZBEV7BG73JRWJN',
        'secret' => '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',
      ];
    
    $options['region'] = 'ap-south-1';
    $options['version'] = 'latest';
$client = new SecretsManagerClient($options);

// Get secrets stored in the Secrets Manager under the name in Environment variable DB_SECRET
try {
        $result = $client->getSecretValue( [
            'SecretId' => $secretName,
        ] );
    } catch ( AwsException $e ) {
        $error = $e->getAwsErrorCode();
        if ( $error == 'DecryptionFailureException' ) { // Can't decrypt the protected secret text using the provided AWS KMS key.
            throw $e;
        }
        if ( $error == 'InternalServiceErrorException' ) { // An error occurred on the server side.
            throw $e;
        }
        if ( $error == 'InvalidParameterException' ) { // Invalid parameter value.
            throw $e;
        }
        if ( $error == 'InvalidRequestException' ) { // Parameter value is not valid for the current state of the resource.
            throw $e;
        }
        if ( $error == 'ResourceNotFoundException' ) { // Requested resource not found
            throw $e;
        }
    }
    // Decrypts secret using the associated KMS CMK, depends on whether the secret is a string or binary.
    if ( isset( $result[ 'SecretString' ] ) ) {
        $secret = $result[ 'SecretString' ];
    } else {
        $secret = base64_decode( $result[ 'SecretBinary' ] );
    }

    // Decode the secret json
    $secrets = json_decode( $secret, true );
return $secrets;


}

$aws_db = aws_d('MotokaartMPGUAT');

print "<pre>";
print_r($aws_db);
print "</pre>";