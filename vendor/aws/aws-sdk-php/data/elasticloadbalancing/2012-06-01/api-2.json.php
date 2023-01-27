<?php

namespace WP_Cloud_Search;

// This file was auto-generated from sdk-root/src/data/elasticloadbalancing/2012-06-01/api-2.json
return ['version' => '2.0', 'metadata' => ['apiVersion' => '2012-06-01', 'endpointPrefix' => 'elasticloadbalancing', 'protocol' => 'query', 'serviceFullName' => 'Elastic Load Balancing', 'serviceId' => 'Elastic Load Balancing', 'signatureVersion' => 'v4', 'uid' => 'elasticloadbalancing-2012-06-01', 'xmlNamespace' => 'http://elasticloadbalancing.amazonaws.com/doc/2012-06-01/'], 'operations' => ['AddTags' => ['name' => 'AddTags', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'AddTagsInput'], 'output' => ['shape' => 'AddTagsOutput', 'resultWrapper' => 'AddTagsResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'TooManyTagsException'], ['shape' => 'DuplicateTagKeysException']]], 'ApplySecurityGroupsToLoadBalancer' => ['name' => 'ApplySecurityGroupsToLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'ApplySecurityGroupsToLoadBalancerInput'], 'output' => ['shape' => 'ApplySecurityGroupsToLoadBalancerOutput', 'resultWrapper' => 'ApplySecurityGroupsToLoadBalancerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidConfigurationRequestException'], ['shape' => 'InvalidSecurityGroupException']]], 'AttachLoadBalancerToSubnets' => ['name' => 'AttachLoadBalancerToSubnets', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'AttachLoadBalancerToSubnetsInput'], 'output' => ['shape' => 'AttachLoadBalancerToSubnetsOutput', 'resultWrapper' => 'AttachLoadBalancerToSubnetsResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidConfigurationRequestException'], ['shape' => 'SubnetNotFoundException'], ['shape' => 'InvalidSubnetException']]], 'ConfigureHealthCheck' => ['name' => 'ConfigureHealthCheck', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'ConfigureHealthCheckInput'], 'output' => ['shape' => 'ConfigureHealthCheckOutput', 'resultWrapper' => 'ConfigureHealthCheckResult'], 'errors' => [['shape' => 'AccessPointNotFoundException']]], 'CreateAppCookieStickinessPolicy' => ['name' => 'CreateAppCookieStickinessPolicy', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateAppCookieStickinessPolicyInput'], 'output' => ['shape' => 'CreateAppCookieStickinessPolicyOutput', 'resultWrapper' => 'CreateAppCookieStickinessPolicyResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'DuplicatePolicyNameException'], ['shape' => 'TooManyPoliciesException'], ['shape' => 'InvalidConfigurationRequestException']]], 'CreateLBCookieStickinessPolicy' => ['name' => 'CreateLBCookieStickinessPolicy', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateLBCookieStickinessPolicyInput'], 'output' => ['shape' => 'CreateLBCookieStickinessPolicyOutput', 'resultWrapper' => 'CreateLBCookieStickinessPolicyResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'DuplicatePolicyNameException'], ['shape' => 'TooManyPoliciesException'], ['shape' => 'InvalidConfigurationRequestException']]], 'CreateLoadBalancer' => ['name' => 'CreateLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateAccessPointInput'], 'output' => ['shape' => 'CreateAccessPointOutput', 'resultWrapper' => 'CreateLoadBalancerResult'], 'errors' => [['shape' => 'DuplicateAccessPointNameException'], ['shape' => 'TooManyAccessPointsException'], ['shape' => 'CertificateNotFoundException'], ['shape' => 'InvalidConfigurationRequestException'], ['shape' => 'SubnetNotFoundException'], ['shape' => 'InvalidSubnetException'], ['shape' => 'InvalidSecurityGroupException'], ['shape' => 'InvalidSchemeException'], ['shape' => 'TooManyTagsException'], ['shape' => 'DuplicateTagKeysException'], ['shape' => 'UnsupportedProtocolException'], ['shape' => 'OperationNotPermittedException']]], 'CreateLoadBalancerListeners' => ['name' => 'CreateLoadBalancerListeners', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateLoadBalancerListenerInput'], 'output' => ['shape' => 'CreateLoadBalancerListenerOutput', 'resultWrapper' => 'CreateLoadBalancerListenersResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'DuplicateListenerException'], ['shape' => 'CertificateNotFoundException'], ['shape' => 'InvalidConfigurationRequestException'], ['shape' => 'UnsupportedProtocolException']]], 'CreateLoadBalancerPolicy' => ['name' => 'CreateLoadBalancerPolicy', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateLoadBalancerPolicyInput'], 'output' => ['shape' => 'CreateLoadBalancerPolicyOutput', 'resultWrapper' => 'CreateLoadBalancerPolicyResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'PolicyTypeNotFoundException'], ['shape' => 'DuplicatePolicyNameException'], ['shape' => 'TooManyPoliciesException'], ['shape' => 'InvalidConfigurationRequestException']]], 'DeleteLoadBalancer' => ['name' => 'DeleteLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteAccessPointInput'], 'output' => ['shape' => 'DeleteAccessPointOutput', 'resultWrapper' => 'DeleteLoadBalancerResult']], 'DeleteLoadBalancerListeners' => ['name' => 'DeleteLoadBalancerListeners', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteLoadBalancerListenerInput'], 'output' => ['shape' => 'DeleteLoadBalancerListenerOutput', 'resultWrapper' => 'DeleteLoadBalancerListenersResult'], 'errors' => [['shape' => 'AccessPointNotFoundException']]], 'DeleteLoadBalancerPolicy' => ['name' => 'DeleteLoadBalancerPolicy', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteLoadBalancerPolicyInput'], 'output' => ['shape' => 'DeleteLoadBalancerPolicyOutput', 'resultWrapper' => 'DeleteLoadBalancerPolicyResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidConfigurationRequestException']]], 'DeregisterInstancesFromLoadBalancer' => ['name' => 'DeregisterInstancesFromLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeregisterEndPointsInput'], 'output' => ['shape' => 'DeregisterEndPointsOutput', 'resultWrapper' => 'DeregisterInstancesFromLoadBalancerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidEndPointException']]], 'DescribeAccountLimits' => ['name' => 'DescribeAccountLimits', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeAccountLimitsInput'], 'output' => ['shape' => 'DescribeAccountLimitsOutput', 'resultWrapper' => 'DescribeAccountLimitsResult']], 'DescribeInstanceHealth' => ['name' => 'DescribeInstanceHealth', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeEndPointStateInput'], 'output' => ['shape' => 'DescribeEndPointStateOutput', 'resultWrapper' => 'DescribeInstanceHealthResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidEndPointException']]], 'DescribeLoadBalancerAttributes' => ['name' => 'DescribeLoadBalancerAttributes', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeLoadBalancerAttributesInput'], 'output' => ['shape' => 'DescribeLoadBalancerAttributesOutput', 'resultWrapper' => 'DescribeLoadBalancerAttributesResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'LoadBalancerAttributeNotFoundException']]], 'DescribeLoadBalancerPolicies' => ['name' => 'DescribeLoadBalancerPolicies', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeLoadBalancerPoliciesInput'], 'output' => ['shape' => 'DescribeLoadBalancerPoliciesOutput', 'resultWrapper' => 'DescribeLoadBalancerPoliciesResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'PolicyNotFoundException']]], 'DescribeLoadBalancerPolicyTypes' => ['name' => 'DescribeLoadBalancerPolicyTypes', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeLoadBalancerPolicyTypesInput'], 'output' => ['shape' => 'DescribeLoadBalancerPolicyTypesOutput', 'resultWrapper' => 'DescribeLoadBalancerPolicyTypesResult'], 'errors' => [['shape' => 'PolicyTypeNotFoundException']]], 'DescribeLoadBalancers' => ['name' => 'DescribeLoadBalancers', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeAccessPointsInput'], 'output' => ['shape' => 'DescribeAccessPointsOutput', 'resultWrapper' => 'DescribeLoadBalancersResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'DependencyThrottleException']]], 'DescribeTags' => ['name' => 'DescribeTags', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeTagsInput'], 'output' => ['shape' => 'DescribeTagsOutput', 'resultWrapper' => 'DescribeTagsResult'], 'errors' => [['shape' => 'AccessPointNotFoundException']]], 'DetachLoadBalancerFromSubnets' => ['name' => 'DetachLoadBalancerFromSubnets', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DetachLoadBalancerFromSubnetsInput'], 'output' => ['shape' => 'DetachLoadBalancerFromSubnetsOutput', 'resultWrapper' => 'DetachLoadBalancerFromSubnetsResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidConfigurationRequestException']]], 'DisableAvailabilityZonesForLoadBalancer' => ['name' => 'DisableAvailabilityZonesForLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'RemoveAvailabilityZonesInput'], 'output' => ['shape' => 'RemoveAvailabilityZonesOutput', 'resultWrapper' => 'DisableAvailabilityZonesForLoadBalancerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidConfigurationRequestException']]], 'EnableAvailabilityZonesForLoadBalancer' => ['name' => 'EnableAvailabilityZonesForLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'AddAvailabilityZonesInput'], 'output' => ['shape' => 'AddAvailabilityZonesOutput', 'resultWrapper' => 'EnableAvailabilityZonesForLoadBalancerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException']]], 'ModifyLoadBalancerAttributes' => ['name' => 'ModifyLoadBalancerAttributes', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'ModifyLoadBalancerAttributesInput'], 'output' => ['shape' => 'ModifyLoadBalancerAttributesOutput', 'resultWrapper' => 'ModifyLoadBalancerAttributesResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'LoadBalancerAttributeNotFoundException'], ['shape' => 'InvalidConfigurationRequestException']]], 'RegisterInstancesWithLoadBalancer' => ['name' => 'RegisterInstancesWithLoadBalancer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'RegisterEndPointsInput'], 'output' => ['shape' => 'RegisterEndPointsOutput', 'resultWrapper' => 'RegisterInstancesWithLoadBalancerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'InvalidEndPointException']]], 'RemoveTags' => ['name' => 'RemoveTags', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'RemoveTagsInput'], 'output' => ['shape' => 'RemoveTagsOutput', 'resultWrapper' => 'RemoveTagsResult'], 'errors' => [['shape' => 'AccessPointNotFoundException']]], 'SetLoadBalancerListenerSSLCertificate' => ['name' => 'SetLoadBalancerListenerSSLCertificate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SetLoadBalancerListenerSSLCertificateInput'], 'output' => ['shape' => 'SetLoadBalancerListenerSSLCertificateOutput', 'resultWrapper' => 'SetLoadBalancerListenerSSLCertificateResult'], 'errors' => [['shape' => 'CertificateNotFoundException'], ['shape' => 'AccessPointNotFoundException'], ['shape' => 'ListenerNotFoundException'], ['shape' => 'InvalidConfigurationRequestException'], ['shape' => 'UnsupportedProtocolException']]], 'SetLoadBalancerPoliciesForBackendServer' => ['name' => 'SetLoadBalancerPoliciesForBackendServer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SetLoadBalancerPoliciesForBackendServerInput'], 'output' => ['shape' => 'SetLoadBalancerPoliciesForBackendServerOutput', 'resultWrapper' => 'SetLoadBalancerPoliciesForBackendServerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'PolicyNotFoundException'], ['shape' => 'InvalidConfigurationRequestException']]], 'SetLoadBalancerPoliciesOfListener' => ['name' => 'SetLoadBalancerPoliciesOfListener', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SetLoadBalancerPoliciesOfListenerInput'], 'output' => ['shape' => 'SetLoadBalancerPoliciesOfListenerOutput', 'resultWrapper' => 'SetLoadBalancerPoliciesOfListenerResult'], 'errors' => [['shape' => 'AccessPointNotFoundException'], ['shape' => 'PolicyNotFoundException'], ['shape' => 'ListenerNotFoundException'], ['shape' => 'InvalidConfigurationRequestException']]]], 'shapes' => ['AccessLog' => ['type' => 'structure', 'required' => ['Enabled'], 'members' => ['Enabled' => ['shape' => 'AccessLogEnabled'], 'S3BucketName' => ['shape' => 'S3BucketName'], 'EmitInterval' => ['shape' => 'AccessLogInterval'], 'S3BucketPrefix' => ['shape' => 'AccessLogPrefix']]], 'AccessLogEnabled' => ['type' => 'boolean'], 'AccessLogInterval' => ['type' => 'integer'], 'AccessLogPrefix' => ['type' => 'string'], 'AccessPointName' => ['type' => 'string'], 'AccessPointNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'LoadBalancerNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'AccessPointPort' => ['type' => 'integer'], 'AddAvailabilityZonesInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'AvailabilityZones'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'AvailabilityZones' => ['shape' => 'AvailabilityZones']]], 'AddAvailabilityZonesOutput' => ['type' => 'structure', 'members' => ['AvailabilityZones' => ['shape' => 'AvailabilityZones']]], 'AddTagsInput' => ['type' => 'structure', 'required' => ['LoadBalancerNames', 'Tags'], 'members' => ['LoadBalancerNames' => ['shape' => 'LoadBalancerNames'], 'Tags' => ['shape' => 'TagList']]], 'AddTagsOutput' => ['type' => 'structure', 'members' => []], 'AdditionalAttribute' => ['type' => 'structure', 'members' => ['Key' => ['shape' => 'AdditionalAttributeKey'], 'Value' => ['shape' => 'AdditionalAttributeValue']]], 'AdditionalAttributeKey' => ['type' => 'string', 'max' => 256, 'pattern' => '^[a-zA-Z0-9.]+$'], 'AdditionalAttributeValue' => ['type' => 'string', 'max' => 256, 'pattern' => '^[a-zA-Z0-9.]+$'], 'AdditionalAttributes' => ['type' => 'list', 'member' => ['shape' => 'AdditionalAttribute'], 'max' => 10], 'AppCookieStickinessPolicies' => ['type' => 'list', 'member' => ['shape' => 'AppCookieStickinessPolicy']], 'AppCookieStickinessPolicy' => ['type' => 'structure', 'members' => ['PolicyName' => ['shape' => 'PolicyName'], 'CookieName' => ['shape' => 'CookieName']]], 'ApplySecurityGroupsToLoadBalancerInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'SecurityGroups'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'SecurityGroups' => ['shape' => 'SecurityGroups']]], 'ApplySecurityGroupsToLoadBalancerOutput' => ['type' => 'structure', 'members' => ['SecurityGroups' => ['shape' => 'SecurityGroups']]], 'AttachLoadBalancerToSubnetsInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'Subnets'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Subnets' => ['shape' => 'Subnets']]], 'AttachLoadBalancerToSubnetsOutput' => ['type' => 'structure', 'members' => ['Subnets' => ['shape' => 'Subnets']]], 'AttributeName' => ['type' => 'string'], 'AttributeType' => ['type' => 'string'], 'AttributeValue' => ['type' => 'string'], 'AvailabilityZone' => ['type' => 'string'], 'AvailabilityZones' => ['type' => 'list', 'member' => ['shape' => 'AvailabilityZone']], 'BackendServerDescription' => ['type' => 'structure', 'members' => ['InstancePort' => ['shape' => 'InstancePort'], 'PolicyNames' => ['shape' => 'PolicyNames']]], 'BackendServerDescriptions' => ['type' => 'list', 'member' => ['shape' => 'BackendServerDescription']], 'Cardinality' => ['type' => 'string'], 'CertificateNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'CertificateNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'ConfigureHealthCheckInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'HealthCheck'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'HealthCheck' => ['shape' => 'HealthCheck']]], 'ConfigureHealthCheckOutput' => ['type' => 'structure', 'members' => ['HealthCheck' => ['shape' => 'HealthCheck']]], 'ConnectionDraining' => ['type' => 'structure', 'required' => ['Enabled'], 'members' => ['Enabled' => ['shape' => 'ConnectionDrainingEnabled'], 'Timeout' => ['shape' => 'ConnectionDrainingTimeout']]], 'ConnectionDrainingEnabled' => ['type' => 'boolean'], 'ConnectionDrainingTimeout' => ['type' => 'integer'], 'ConnectionSettings' => ['type' => 'structure', 'required' => ['IdleTimeout'], 'members' => ['IdleTimeout' => ['shape' => 'IdleTimeout']]], 'CookieExpirationPeriod' => ['type' => 'long'], 'CookieName' => ['type' => 'string'], 'CreateAccessPointInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'Listeners'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Listeners' => ['shape' => 'Listeners'], 'AvailabilityZones' => ['shape' => 'AvailabilityZones'], 'Subnets' => ['shape' => 'Subnets'], 'SecurityGroups' => ['shape' => 'SecurityGroups'], 'Scheme' => ['shape' => 'LoadBalancerScheme'], 'Tags' => ['shape' => 'TagList']]], 'CreateAccessPointOutput' => ['type' => 'structure', 'members' => ['DNSName' => ['shape' => 'DNSName']]], 'CreateAppCookieStickinessPolicyInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'PolicyName', 'CookieName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'PolicyName' => ['shape' => 'PolicyName'], 'CookieName' => ['shape' => 'CookieName']]], 'CreateAppCookieStickinessPolicyOutput' => ['type' => 'structure', 'members' => []], 'CreateLBCookieStickinessPolicyInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'PolicyName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'PolicyName' => ['shape' => 'PolicyName'], 'CookieExpirationPeriod' => ['shape' => 'CookieExpirationPeriod']]], 'CreateLBCookieStickinessPolicyOutput' => ['type' => 'structure', 'members' => []], 'CreateLoadBalancerListenerInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'Listeners'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Listeners' => ['shape' => 'Listeners']]], 'CreateLoadBalancerListenerOutput' => ['type' => 'structure', 'members' => []], 'CreateLoadBalancerPolicyInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'PolicyName', 'PolicyTypeName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'PolicyName' => ['shape' => 'PolicyName'], 'PolicyTypeName' => ['shape' => 'PolicyTypeName'], 'PolicyAttributes' => ['shape' => 'PolicyAttributes']]], 'CreateLoadBalancerPolicyOutput' => ['type' => 'structure', 'members' => []], 'CreatedTime' => ['type' => 'timestamp'], 'CrossZoneLoadBalancing' => ['type' => 'structure', 'required' => ['Enabled'], 'members' => ['Enabled' => ['shape' => 'CrossZoneLoadBalancingEnabled']]], 'CrossZoneLoadBalancingEnabled' => ['type' => 'boolean'], 'DNSName' => ['type' => 'string'], 'DefaultValue' => ['type' => 'string'], 'DeleteAccessPointInput' => ['type' => 'structure', 'required' => ['LoadBalancerName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName']]], 'DeleteAccessPointOutput' => ['type' => 'structure', 'members' => []], 'DeleteLoadBalancerListenerInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'LoadBalancerPorts'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'LoadBalancerPorts' => ['shape' => 'Ports']]], 'DeleteLoadBalancerListenerOutput' => ['type' => 'structure', 'members' => []], 'DeleteLoadBalancerPolicyInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'PolicyName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'PolicyName' => ['shape' => 'PolicyName']]], 'DeleteLoadBalancerPolicyOutput' => ['type' => 'structure', 'members' => []], 'DependencyThrottleException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'DependencyThrottle', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'DeregisterEndPointsInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'Instances'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Instances' => ['shape' => 'Instances']]], 'DeregisterEndPointsOutput' => ['type' => 'structure', 'members' => ['Instances' => ['shape' => 'Instances']]], 'DescribeAccessPointsInput' => ['type' => 'structure', 'members' => ['LoadBalancerNames' => ['shape' => 'LoadBalancerNames'], 'Marker' => ['shape' => 'Marker'], 'PageSize' => ['shape' => 'PageSize']]], 'DescribeAccessPointsOutput' => ['type' => 'structure', 'members' => ['LoadBalancerDescriptions' => ['shape' => 'LoadBalancerDescriptions'], 'NextMarker' => ['shape' => 'Marker']]], 'DescribeAccountLimitsInput' => ['type' => 'structure', 'members' => ['Marker' => ['shape' => 'Marker'], 'PageSize' => ['shape' => 'PageSize']]], 'DescribeAccountLimitsOutput' => ['type' => 'structure', 'members' => ['Limits' => ['shape' => 'Limits'], 'NextMarker' => ['shape' => 'Marker']]], 'DescribeEndPointStateInput' => ['type' => 'structure', 'required' => ['LoadBalancerName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Instances' => ['shape' => 'Instances']]], 'DescribeEndPointStateOutput' => ['type' => 'structure', 'members' => ['InstanceStates' => ['shape' => 'InstanceStates']]], 'DescribeLoadBalancerAttributesInput' => ['type' => 'structure', 'required' => ['LoadBalancerName'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName']]], 'DescribeLoadBalancerAttributesOutput' => ['type' => 'structure', 'members' => ['LoadBalancerAttributes' => ['shape' => 'LoadBalancerAttributes']]], 'DescribeLoadBalancerPoliciesInput' => ['type' => 'structure', 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'PolicyNames' => ['shape' => 'PolicyNames']]], 'DescribeLoadBalancerPoliciesOutput' => ['type' => 'structure', 'members' => ['PolicyDescriptions' => ['shape' => 'PolicyDescriptions']]], 'DescribeLoadBalancerPolicyTypesInput' => ['type' => 'structure', 'members' => ['PolicyTypeNames' => ['shape' => 'PolicyTypeNames']]], 'DescribeLoadBalancerPolicyTypesOutput' => ['type' => 'structure', 'members' => ['PolicyTypeDescriptions' => ['shape' => 'PolicyTypeDescriptions']]], 'DescribeTagsInput' => ['type' => 'structure', 'required' => ['LoadBalancerNames'], 'members' => ['LoadBalancerNames' => ['shape' => 'LoadBalancerNamesMax20']]], 'DescribeTagsOutput' => ['type' => 'structure', 'members' => ['TagDescriptions' => ['shape' => 'TagDescriptions']]], 'Description' => ['type' => 'string'], 'DetachLoadBalancerFromSubnetsInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'Subnets'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Subnets' => ['shape' => 'Subnets']]], 'DetachLoadBalancerFromSubnetsOutput' => ['type' => 'structure', 'members' => ['Subnets' => ['shape' => 'Subnets']]], 'DuplicateAccessPointNameException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'DuplicateLoadBalancerName', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'DuplicateListenerException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'DuplicateListener', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'DuplicatePolicyNameException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'DuplicatePolicyName', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'DuplicateTagKeysException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'DuplicateTagKeys', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'EndPointPort' => ['type' => 'integer'], 'HealthCheck' => ['type' => 'structure', 'required' => ['Target', 'Interval', 'Timeout', 'UnhealthyThreshold', 'HealthyThreshold'], 'members' => ['Target' => ['shape' => 'HealthCheckTarget'], 'Interval' => ['shape' => 'HealthCheckInterval'], 'Timeout' => ['shape' => 'HealthCheckTimeout'], 'UnhealthyThreshold' => ['shape' => 'UnhealthyThreshold'], 'HealthyThreshold' => ['shape' => 'HealthyThreshold']]], 'HealthCheckInterval' => ['type' => 'integer', 'max' => 300, 'min' => 5], 'HealthCheckTarget' => ['type' => 'string'], 'HealthCheckTimeout' => ['type' => 'integer', 'max' => 60, 'min' => 2], 'HealthyThreshold' => ['type' => 'integer', 'max' => 10, 'min' => 2], 'IdleTimeout' => ['type' => 'integer', 'max' => 3600, 'min' => 1], 'Instance' => ['type' => 'structure', 'members' => ['InstanceId' => ['shape' => 'InstanceId']]], 'InstanceId' => ['type' => 'string'], 'InstancePort' => ['type' => 'integer', 'max' => 65535, 'min' => 1], 'InstanceState' => ['type' => 'structure', 'members' => ['InstanceId' => ['shape' => 'InstanceId'], 'State' => ['shape' => 'State'], 'ReasonCode' => ['shape' => 'ReasonCode'], 'Description' => ['shape' => 'Description']]], 'InstanceStates' => ['type' => 'list', 'member' => ['shape' => 'InstanceState']], 'Instances' => ['type' => 'list', 'member' => ['shape' => 'Instance']], 'InvalidConfigurationRequestException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'InvalidConfigurationRequest', 'httpStatusCode' => 409, 'senderFault' => \true], 'exception' => \true], 'InvalidEndPointException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'InvalidInstance', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'InvalidSchemeException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'InvalidScheme', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'InvalidSecurityGroupException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'InvalidSecurityGroup', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'InvalidSubnetException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'InvalidSubnet', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'LBCookieStickinessPolicies' => ['type' => 'list', 'member' => ['shape' => 'LBCookieStickinessPolicy']], 'LBCookieStickinessPolicy' => ['type' => 'structure', 'members' => ['PolicyName' => ['shape' => 'PolicyName'], 'CookieExpirationPeriod' => ['shape' => 'CookieExpirationPeriod']]], 'Limit' => ['type' => 'structure', 'members' => ['Name' => ['shape' => 'Name'], 'Max' => ['shape' => 'Max']]], 'Limits' => ['type' => 'list', 'member' => ['shape' => 'Limit']], 'Listener' => ['type' => 'structure', 'required' => ['Protocol', 'LoadBalancerPort', 'InstancePort'], 'members' => ['Protocol' => ['shape' => 'Protocol'], 'LoadBalancerPort' => ['shape' => 'AccessPointPort'], 'InstanceProtocol' => ['shape' => 'Protocol'], 'InstancePort' => ['shape' => 'InstancePort'], 'SSLCertificateId' => ['shape' => 'SSLCertificateId']]], 'ListenerDescription' => ['type' => 'structure', 'members' => ['Listener' => ['shape' => 'Listener'], 'PolicyNames' => ['shape' => 'PolicyNames']]], 'ListenerDescriptions' => ['type' => 'list', 'member' => ['shape' => 'ListenerDescription']], 'ListenerNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'ListenerNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'Listeners' => ['type' => 'list', 'member' => ['shape' => 'Listener']], 'LoadBalancerAttributeNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'LoadBalancerAttributeNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'LoadBalancerAttributes' => ['type' => 'structure', 'members' => ['CrossZoneLoadBalancing' => ['shape' => 'CrossZoneLoadBalancing'], 'AccessLog' => ['shape' => 'AccessLog'], 'ConnectionDraining' => ['shape' => 'ConnectionDraining'], 'ConnectionSettings' => ['shape' => 'ConnectionSettings'], 'AdditionalAttributes' => ['shape' => 'AdditionalAttributes']]], 'LoadBalancerDescription' => ['type' => 'structure', 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'DNSName' => ['shape' => 'DNSName'], 'CanonicalHostedZoneName' => ['shape' => 'DNSName'], 'CanonicalHostedZoneNameID' => ['shape' => 'DNSName'], 'ListenerDescriptions' => ['shape' => 'ListenerDescriptions'], 'Policies' => ['shape' => 'Policies'], 'BackendServerDescriptions' => ['shape' => 'BackendServerDescriptions'], 'AvailabilityZones' => ['shape' => 'AvailabilityZones'], 'Subnets' => ['shape' => 'Subnets'], 'VPCId' => ['shape' => 'VPCId'], 'Instances' => ['shape' => 'Instances'], 'HealthCheck' => ['shape' => 'HealthCheck'], 'SourceSecurityGroup' => ['shape' => 'SourceSecurityGroup'], 'SecurityGroups' => ['shape' => 'SecurityGroups'], 'CreatedTime' => ['shape' => 'CreatedTime'], 'Scheme' => ['shape' => 'LoadBalancerScheme']]], 'LoadBalancerDescriptions' => ['type' => 'list', 'member' => ['shape' => 'LoadBalancerDescription']], 'LoadBalancerNames' => ['type' => 'list', 'member' => ['shape' => 'AccessPointName']], 'LoadBalancerNamesMax20' => ['type' => 'list', 'member' => ['shape' => 'AccessPointName'], 'max' => 20, 'min' => 1], 'LoadBalancerScheme' => ['type' => 'string'], 'Marker' => ['type' => 'string'], 'Max' => ['type' => 'string'], 'ModifyLoadBalancerAttributesInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'LoadBalancerAttributes'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'LoadBalancerAttributes' => ['shape' => 'LoadBalancerAttributes']]], 'ModifyLoadBalancerAttributesOutput' => ['type' => 'structure', 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'LoadBalancerAttributes' => ['shape' => 'LoadBalancerAttributes']]], 'Name' => ['type' => 'string'], 'OperationNotPermittedException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'OperationNotPermitted', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'PageSize' => ['type' => 'integer', 'max' => 400, 'min' => 1], 'Policies' => ['type' => 'structure', 'members' => ['AppCookieStickinessPolicies' => ['shape' => 'AppCookieStickinessPolicies'], 'LBCookieStickinessPolicies' => ['shape' => 'LBCookieStickinessPolicies'], 'OtherPolicies' => ['shape' => 'PolicyNames']]], 'PolicyAttribute' => ['type' => 'structure', 'members' => ['AttributeName' => ['shape' => 'AttributeName'], 'AttributeValue' => ['shape' => 'AttributeValue']]], 'PolicyAttributeDescription' => ['type' => 'structure', 'members' => ['AttributeName' => ['shape' => 'AttributeName'], 'AttributeValue' => ['shape' => 'AttributeValue']]], 'PolicyAttributeDescriptions' => ['type' => 'list', 'member' => ['shape' => 'PolicyAttributeDescription']], 'PolicyAttributeTypeDescription' => ['type' => 'structure', 'members' => ['AttributeName' => ['shape' => 'AttributeName'], 'AttributeType' => ['shape' => 'AttributeType'], 'Description' => ['shape' => 'Description'], 'DefaultValue' => ['shape' => 'DefaultValue'], 'Cardinality' => ['shape' => 'Cardinality']]], 'PolicyAttributeTypeDescriptions' => ['type' => 'list', 'member' => ['shape' => 'PolicyAttributeTypeDescription']], 'PolicyAttributes' => ['type' => 'list', 'member' => ['shape' => 'PolicyAttribute']], 'PolicyDescription' => ['type' => 'structure', 'members' => ['PolicyName' => ['shape' => 'PolicyName'], 'PolicyTypeName' => ['shape' => 'PolicyTypeName'], 'PolicyAttributeDescriptions' => ['shape' => 'PolicyAttributeDescriptions']]], 'PolicyDescriptions' => ['type' => 'list', 'member' => ['shape' => 'PolicyDescription']], 'PolicyName' => ['type' => 'string'], 'PolicyNames' => ['type' => 'list', 'member' => ['shape' => 'PolicyName']], 'PolicyNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'PolicyNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'PolicyTypeDescription' => ['type' => 'structure', 'members' => ['PolicyTypeName' => ['shape' => 'PolicyTypeName'], 'Description' => ['shape' => 'Description'], 'PolicyAttributeTypeDescriptions' => ['shape' => 'PolicyAttributeTypeDescriptions']]], 'PolicyTypeDescriptions' => ['type' => 'list', 'member' => ['shape' => 'PolicyTypeDescription']], 'PolicyTypeName' => ['type' => 'string'], 'PolicyTypeNames' => ['type' => 'list', 'member' => ['shape' => 'PolicyTypeName']], 'PolicyTypeNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'PolicyTypeNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'Ports' => ['type' => 'list', 'member' => ['shape' => 'AccessPointPort']], 'Protocol' => ['type' => 'string'], 'ReasonCode' => ['type' => 'string'], 'RegisterEndPointsInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'Instances'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Instances' => ['shape' => 'Instances']]], 'RegisterEndPointsOutput' => ['type' => 'structure', 'members' => ['Instances' => ['shape' => 'Instances']]], 'RemoveAvailabilityZonesInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'AvailabilityZones'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'AvailabilityZones' => ['shape' => 'AvailabilityZones']]], 'RemoveAvailabilityZonesOutput' => ['type' => 'structure', 'members' => ['AvailabilityZones' => ['shape' => 'AvailabilityZones']]], 'RemoveTagsInput' => ['type' => 'structure', 'required' => ['LoadBalancerNames', 'Tags'], 'members' => ['LoadBalancerNames' => ['shape' => 'LoadBalancerNames'], 'Tags' => ['shape' => 'TagKeyList']]], 'RemoveTagsOutput' => ['type' => 'structure', 'members' => []], 'S3BucketName' => ['type' => 'string'], 'SSLCertificateId' => ['type' => 'string'], 'SecurityGroupId' => ['type' => 'string'], 'SecurityGroupName' => ['type' => 'string'], 'SecurityGroupOwnerAlias' => ['type' => 'string'], 'SecurityGroups' => ['type' => 'list', 'member' => ['shape' => 'SecurityGroupId']], 'SetLoadBalancerListenerSSLCertificateInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'LoadBalancerPort', 'SSLCertificateId'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'LoadBalancerPort' => ['shape' => 'AccessPointPort'], 'SSLCertificateId' => ['shape' => 'SSLCertificateId']]], 'SetLoadBalancerListenerSSLCertificateOutput' => ['type' => 'structure', 'members' => []], 'SetLoadBalancerPoliciesForBackendServerInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'InstancePort', 'PolicyNames'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'InstancePort' => ['shape' => 'EndPointPort'], 'PolicyNames' => ['shape' => 'PolicyNames']]], 'SetLoadBalancerPoliciesForBackendServerOutput' => ['type' => 'structure', 'members' => []], 'SetLoadBalancerPoliciesOfListenerInput' => ['type' => 'structure', 'required' => ['LoadBalancerName', 'LoadBalancerPort', 'PolicyNames'], 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'LoadBalancerPort' => ['shape' => 'AccessPointPort'], 'PolicyNames' => ['shape' => 'PolicyNames']]], 'SetLoadBalancerPoliciesOfListenerOutput' => ['type' => 'structure', 'members' => []], 'SourceSecurityGroup' => ['type' => 'structure', 'members' => ['OwnerAlias' => ['shape' => 'SecurityGroupOwnerAlias'], 'GroupName' => ['shape' => 'SecurityGroupName']]], 'State' => ['type' => 'string'], 'SubnetId' => ['type' => 'string'], 'SubnetNotFoundException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'SubnetNotFound', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'Subnets' => ['type' => 'list', 'member' => ['shape' => 'SubnetId']], 'Tag' => ['type' => 'structure', 'required' => ['Key'], 'members' => ['Key' => ['shape' => 'TagKey'], 'Value' => ['shape' => 'TagValue']]], 'TagDescription' => ['type' => 'structure', 'members' => ['LoadBalancerName' => ['shape' => 'AccessPointName'], 'Tags' => ['shape' => 'TagList']]], 'TagDescriptions' => ['type' => 'list', 'member' => ['shape' => 'TagDescription']], 'TagKey' => ['type' => 'string', 'max' => 128, 'min' => 1, 'pattern' => '^([\\p{L}\\p{Z}\\p{N}_.:/=+\\-@]*)$'], 'TagKeyList' => ['type' => 'list', 'member' => ['shape' => 'TagKeyOnly'], 'min' => 1], 'TagKeyOnly' => ['type' => 'structure', 'members' => ['Key' => ['shape' => 'TagKey']]], 'TagList' => ['type' => 'list', 'member' => ['shape' => 'Tag'], 'min' => 1], 'TagValue' => ['type' => 'string', 'max' => 256, 'min' => 0, 'pattern' => '^([\\p{L}\\p{Z}\\p{N}_.:/=+\\-@]*)$'], 'TooManyAccessPointsException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'TooManyLoadBalancers', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'TooManyPoliciesException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'TooManyPolicies', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'TooManyTagsException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'TooManyTags', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'UnhealthyThreshold' => ['type' => 'integer', 'max' => 10, 'min' => 2], 'UnsupportedProtocolException' => ['type' => 'structure', 'members' => [], 'error' => ['code' => 'UnsupportedProtocol', 'httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true], 'VPCId' => ['type' => 'string']]];
