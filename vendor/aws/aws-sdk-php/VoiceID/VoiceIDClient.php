<?php

namespace WP_Cloud_Search\Aws\VoiceID;

use WP_Cloud_Search\Aws\AwsClient;
/**
 * This client is used to interact with the **Amazon Voice ID** service.
 * @method \Aws\Result createDomain(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDomainAsync(array $args = [])
 * @method \Aws\Result deleteDomain(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDomainAsync(array $args = [])
 * @method \Aws\Result deleteFraudster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteFraudsterAsync(array $args = [])
 * @method \Aws\Result deleteSpeaker(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteSpeakerAsync(array $args = [])
 * @method \Aws\Result describeDomain(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDomainAsync(array $args = [])
 * @method \Aws\Result describeFraudster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFraudsterAsync(array $args = [])
 * @method \Aws\Result describeFraudsterRegistrationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFraudsterRegistrationJobAsync(array $args = [])
 * @method \Aws\Result describeSpeaker(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeSpeakerAsync(array $args = [])
 * @method \Aws\Result describeSpeakerEnrollmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeSpeakerEnrollmentJobAsync(array $args = [])
 * @method \Aws\Result evaluateSession(array $args = [])
 * @method \GuzzleHttp\Promise\Promise evaluateSessionAsync(array $args = [])
 * @method \Aws\Result listDomains(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDomainsAsync(array $args = [])
 * @method \Aws\Result listFraudsterRegistrationJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listFraudsterRegistrationJobsAsync(array $args = [])
 * @method \Aws\Result listSpeakerEnrollmentJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSpeakerEnrollmentJobsAsync(array $args = [])
 * @method \Aws\Result listSpeakers(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSpeakersAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result optOutSpeaker(array $args = [])
 * @method \GuzzleHttp\Promise\Promise optOutSpeakerAsync(array $args = [])
 * @method \Aws\Result startFraudsterRegistrationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startFraudsterRegistrationJobAsync(array $args = [])
 * @method \Aws\Result startSpeakerEnrollmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startSpeakerEnrollmentJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDomain(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateDomainAsync(array $args = [])
 */
class VoiceIDClient extends AwsClient
{
}
