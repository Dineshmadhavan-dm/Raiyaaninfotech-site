<?php

namespace App\Traits;

use App\Models\LoginActivity;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

trait LogActivity
{
    /**
     * Log login/logout activity
     */
    public function logLoginActivity($user, $activityType)
    {
        $agent = new Agent();
        $ip = $this->getClientIp();
        $location = $this->getIpLocation($ip);

        $istTime = Carbon::now('Asia/Kolkata');

        LoginActivity::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'login_time' => ($activityType === 'login') ? $istTime : null,
            'logout_time' => ($activityType === 'logout') ? $istTime : null,
            'ip_address' => $ip,
            'location' => $location,
            'device_info' => $this->getDeviceInfo($agent),
            'activity_type' => $activityType,
        ]);
    }

    /**
     * Get real client IP (works behind Cloudflare/Nginx)
     */
    // protected function getClientIp()
    // {
    //     $ip = Request::ip();

    //     // Check for forwarded IP (common with proxies)
    //     if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //         $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    //         $ip = trim($ips[0]);
    //     }

    //     return $ip;
    // }

    protected function getClientIp()
    {
        $ip = Request::ip();

        // Check all possible proxy headers in order of reliability
        $proxyHeaders = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_REAL_IP', // Nginx
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED'
        ];

        foreach ($proxyHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                break; // Use the first valid header found
            }
        }

        // Validate the IP (basic validation)
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        return '127.0.0.1'; // Fallback to localhost if invalid
    }

    /**
     * Get location from IP in Country|State|City format
     */
    // protected function getIpLocation($ip)
    // {
    //     if ($ip === '127.0.0.1' || $ip === '::1') {
    //         return 'Localhost';
    //     }

    //     // Use caching to avoid repeated API calls
    //     return Cache::remember("ip_location_{$ip}", now()->addHours(24), function () use ($ip) {
    //         try {
    //             $client = new Client();
    //             $response = $client->get("http://ip-api.com/json/{$ip}?fields=status,message,country,regionName,city");

    //             $data = json_decode($response->getBody(), true);

    //             if ($data['status'] === 'success') {
    //                 return sprintf(
    //                     "%s|%s|%s",
    //                     $data['country'] ?? 'Unknown',
    //                     $data['regionName'] ?? 'Unknown',
    //                     $data['city'] ?? 'Unknown'
    //                 );
    //             }
    //         } catch (\Exception $e) {
    //             \Log::error("IP Location Error: " . $e->getMessage());
    //         }

    //         return 'Unknown|Location';
    //     });
    // }

    /**
     * Get detailed device info
     */
    protected function getDeviceInfo($agent)
    {
        return sprintf(
            "%s | %s %s | %s",
            $agent->device(),
            $agent->platform(),
            $agent->version($agent->platform()),
            $agent->browser()
        );
    }
    protected function getIpLocation($ip)
    {
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return 'Localhost';
        }

        return Cache::remember("ip_location_{$ip}", now()->addHours(24), function () use ($ip) {
            // Try ip-api.com first
            $location = $this->getLocationFromIpApi($ip);
            if ($location !== false) {
                return $location;
            }

            // Fallback to ipinfo.io if first attempt fails
            $location = $this->getLocationFromIpInfo($ip);
            if ($location !== false) {
                return $location;
            }

            return 'Unknown|Location';
        });
    }

    protected function getLocationFromIpApi($ip)
    {
        try {
            $client = new Client();
            $response = $client->get("http://ip-api.com/json/{$ip}?fields=status,message,country,regionName,city,isp");

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                return sprintf(
                    "%s|%s|%s|%s",
                    $data['country'] ?? 'Unknown',
                    $data['regionName'] ?? 'Unknown',
                    $data['city'] ?? 'Unknown',
                    $data['isp'] ?? 'Unknown ISP'
                );
            }
        } catch (\Exception $e) {
            \Log::error("IP-API Location Error: " . $e->getMessage());
        }

        return false;
    }

    protected function getLocationFromIpInfo($ip)
    {
        try {
            $client = new Client();
            $response = $client->get("https://ipinfo.io/{$ip}/json?token=YOUR_TOKEN_IF_HAVE_ONE");

            $data = json_decode($response->getBody(), true);

            if (isset($data['country'])) {
                return sprintf(
                    "%s|%s|%s",
                    $data['country'] ?? 'Unknown',
                    $data['region'] ?? 'Unknown',
                    $data['city'] ?? 'Unknown'
                );
            }
        } catch (\Exception $e) {
            \Log::error("IPinfo.io Location Error: " . $e->getMessage());
        }

        return false;
    }
}
