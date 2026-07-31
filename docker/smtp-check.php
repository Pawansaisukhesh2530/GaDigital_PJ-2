<?php
/**
 * SMTP Connectivity Diagnostic Script
 * Run inside the Docker container to verify outbound SMTP access:
 *   php /var/www/html/docker/smtp-check.php
 *
 * Tests DNS resolution and TCP connectivity to common SMTP ports.
 * Does NOT send any email — purely a network connectivity check.
 */

$host  = $argv[1] ?? 'smtp.gmail.com';
$ports = [587, 465, 25];

echo "=== SMTP Connectivity Check ===\n";
echo "Host: {$host}\n";
echo "Date: " . date('Y-m-d H:i:s T') . "\n\n";

// 1. PHP OpenSSL
echo "1. PHP OpenSSL Extension: ";
if (extension_loaded('openssl')) {
    echo "LOADED (" . OPENSSL_VERSION_TEXT . ")\n";
} else {
    echo "NOT LOADED (CRITICAL - TLS will fail)\n";
}

// 2. CA Certificates
echo "2. CA Certificate Bundle: ";
$caFile = '/etc/ssl/certs/ca-certificates.crt';
if (is_file($caFile)) {
    echo "PRESENT (" . round(filesize($caFile) / 1024) . " KB)\n";
} else {
    echo "MISSING (CRITICAL - TLS verification will fail)\n";
}

// 3. DNS Resolution
echo "3. DNS Resolution: ";
$ips = gethostbynamel($host);
if ($ips) {
    echo "OK -> " . implode(', ', $ips) . "\n";
} else {
    echo "FAILED (cannot resolve {$host})\n";
}

// 4. Port connectivity
echo "\n4. TCP Port Connectivity (5s timeout):\n";
foreach ($ports as $port) {
    echo "   Port {$port}: ";
    $errno  = 0;
    $errstr = '';
    $start  = microtime(true);
    $sock   = @fsockopen($host, $port, $errno, $errstr, 5);
    $elapsed = round((microtime(true) - $start) * 1000);

    if ($sock) {
        echo "OPEN ({$elapsed}ms)\n";
        fclose($sock);
    } else {
        echo "BLOCKED/UNREACHABLE - errno={$errno} {$errstr} ({$elapsed}ms)\n";
    }
}

echo "\n=== Diagnosis ===\n";
if (!extension_loaded('openssl')) {
    echo "CRITICAL: Install PHP openssl extension.\n";
} elseif (!is_file($caFile)) {
    echo "CRITICAL: Install ca-certificates package.\n";
} elseif (empty($ips)) {
    echo "CRITICAL: DNS resolution failed. Check container DNS config.\n";
} else {
    // Check if all ports failed
    $anyOpen = false;
    foreach ($ports as $port) {
        $sock = @fsockopen($host, $port, $errno, $errstr, 5);
        if ($sock) {
            $anyOpen = true;
            fclose($sock);
            break;
        }
    }
    if (!$anyOpen) {
        echo "NETWORK BLOCKED: All SMTP ports are unreachable.\n";
        echo "On Render free tier, outbound SMTP (ports 25/465/587) is blocked.\n";
        echo "SOLUTION: Upgrade to a paid Render instance type.\n";
    } else {
        echo "Network OK. SMTP connections should work.\n";
    }
}
echo "\n";
