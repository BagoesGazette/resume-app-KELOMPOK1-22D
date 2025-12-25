<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;

class SecureKeyService
{
    protected $keyPrefix = 'secure_api_key_';
    protected $cacheTime = 3600; // 1 hour

    /**
     * Get decrypted API key dengan multiple security layers
     */
    public function getGeminiApiKey(): ?string
    {
        try {
            // Layer 1: Try cache first (untuk performance)
            $cacheKey = $this->keyPrefix . 'gemini';
            
            if (Cache::has($cacheKey)) {
                Log::debug('SecureKeyService: Key retrieved from cache');
                return Cache::get($cacheKey);
            }

            // Layer 2: Get encrypted key dari database atau file
            $encryptedKey = $this->getEncryptedKey('gemini');
            
            if (!$encryptedKey) {
                Log::error('SecureKeyService: Encrypted key not found');
                return null;
            }

            // Layer 3: Decrypt dengan Laravel encryption
            $decryptedKey = Crypt::decryptString($encryptedKey);

            // Layer 4: Validate format API key
            if (!$this->validateApiKeyFormat($decryptedKey)) {
                Log::error('SecureKeyService: Invalid API key format');
                return null;
            }

            // Cache untuk performance (short TTL untuk security)
            Cache::put($cacheKey, $decryptedKey, now()->addSeconds($this->cacheTime));

            Log::info('SecureKeyService: Key retrieved and cached successfully');

            return $decryptedKey;
            
        } catch (DecryptException $e) {
            Log::error('SecureKeyService: Decryption failed', [
                'error' => $e->getMessage()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('SecureKeyService: Failed to get API key', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get encrypted key dari secure storage
     * FIXED: Changed from 'apikeys.use_db_for_keys' to 'app.use_db_for_keys'
     */
    protected function getEncryptedKey(string $service): ?string
    {
        // FIXED: Gunakan config path yang benar
        $useDb = config('app.use_db_for_keys', false);
        
        Log::debug('SecureKeyService: Getting encrypted key', [
            'service' => $service,
            'use_db' => $useDb,
            'storage_method' => $useDb ? 'database' : 'file'
        ]);

        // Method 1: Dari database (recommended untuk production)
        if ($useDb) {
            return $this->getKeyFromDatabase($service);
        }

        // Method 2: Dari secure file (backup method)
        return $this->getKeyFromSecureFile($service);
    }

    /**
     * Get key dari database
     */
    protected function getKeyFromDatabase(string $service): ?string
    {
        try {
            Log::debug('SecureKeyService: Attempting to retrieve from database', [
                'service' => $service
            ]);

            $key = DB::table('api_keys')
                ->where('service', $service)
                ->where('is_active', true)
                ->value('encrypted_key');

            if ($key) {
                Log::info('SecureKeyService: Key found in database', [
                    'service' => $service,
                    'key_length' => strlen($key)
                ]);
            } else {
                Log::warning('SecureKeyService: Key not found in database', [
                    'service' => $service
                ]);
            }

            return $key;
        } catch (\Exception $e) {
            Log::error('SecureKeyService: Database retrieval failed', [
                'service' => $service,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get key dari secure file di luar web root
     */
    protected function getKeyFromSecureFile(string $service): ?string
    {
        $securePath = storage_path('app/secure-keys');
        $filePath = $securePath . '/' . $service . '.key';

        Log::debug('SecureKeyService: Attempting to retrieve from file', [
            'service' => $service,
            'path' => $filePath
        ]);

        if (!file_exists($filePath)) {
            Log::warning('SecureKeyService: Key file not found', [
                'path' => $filePath
            ]);
            return null;
        }

        // File harus memiliki permission 600 (owner read/write only)
        $perms = substr(sprintf('%o', fileperms($filePath)), -4);
        if ($perms !== '0600') {
            Log::warning('SecureKeyService: Insecure file permissions', [
                'path' => $filePath,
                'permissions' => $perms,
                'expected' => '0600'
            ]);
            // Don't fail, just warn for now
        }

        $content = file_get_contents($filePath);
        
        Log::info('SecureKeyService: Key found in file', [
            'service' => $service,
            'key_length' => strlen($content)
        ]);

        return $content;
    }

    /**
     * Validate API key format untuk Gemini
     */
    protected function validateApiKeyFormat(string $key): bool
    {
        // Gemini API keys biasanya format: AIza...
        if (!str_starts_with($key, 'AIza')) {
            Log::warning('SecureKeyService: Key does not start with AIza', [
                'starts_with' => substr($key, 0, 4)
            ]);
            return false;
        }

        // Length check (Gemini keys ~39 characters)
        if (strlen($key) < 35 || strlen($key) > 45) {
            Log::warning('SecureKeyService: Key length invalid', [
                'length' => strlen($key),
                'expected' => '35-45'
            ]);
            return false;
        }

        // Only alphanumeric and some special chars
        if (!preg_match('/^[A-Za-z0-9_-]+$/', $key)) {
            Log::warning('SecureKeyService: Key contains invalid characters');
            return false;
        }

        return true;
    }

    /**
     * Encrypt dan simpan API key (untuk initial setup)
     * FIXED: Changed from 'apikeys.use_db_for_keys' to 'app.use_db_for_keys'
     */
    public function storeApiKey(string $service, string $apiKey): bool
    {
        try {
            // Validate dulu
            if ($service === 'gemini' && !$this->validateApiKeyFormat($apiKey)) {
                Log::error('SecureKeyService: Invalid API key format for storage');
                return false;
            }

            // Encrypt
            $encryptedKey = Crypt::encryptString($apiKey);

            // FIXED: Gunakan config path yang benar
            $useDb = config('app.use_db_for_keys', false);
            
            Log::info('SecureKeyService: Storing API key', [
                'service' => $service,
                'use_db' => $useDb,
                'storage_method' => $useDb ? 'database' : 'file',
                'key_length' => strlen($apiKey)
            ]);

            // Store ke database
            if ($useDb) {
                return $this->storeKeyToDatabase($service, $encryptedKey);
            }

            // Store ke secure file
            return $this->storeKeyToSecureFile($service, $encryptedKey);

        } catch (\Exception $e) {
            Log::error('SecureKeyService: Failed to store API key', [
                'service' => $service,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Store ke database
     */
    protected function storeKeyToDatabase(string $service, string $encryptedKey): bool
    {
        try {
            Log::info('SecureKeyService: Storing to database', [
                'service' => $service,
                'encrypted_key_length' => strlen($encryptedKey)
            ]);

            // Use updateOrInsert untuk handle duplicate
            DB::table('api_keys')->updateOrInsert(
                ['service' => $service],
                [
                    'encrypted_key' => $encryptedKey,
                    'is_active' => true,
                    'environment' => config('app.env', 'production'),
                    'updated_at' => now(),
                    'updated_by' => auth()->id() ?? 'system',
                    'created_at' => now(), // Will be ignored if updating
                ]
            );

            // Verify insertion
            $stored = DB::table('api_keys')
                ->where('service', $service)
                ->first();

            if ($stored) {
                Log::info('SecureKeyService: API key stored to database successfully', [
                    'service' => $service,
                    'id' => $stored->id,
                    'is_active' => $stored->is_active
                ]);
            } else {
                Log::error('SecureKeyService: Verification failed - key not found after insert');
                return false;
            }

            // Clear cache
            Cache::forget($this->keyPrefix . $service);

            return true;
        } catch (\Exception $e) {
            Log::error('SecureKeyService: Database storage failed', [
                'service' => $service,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Store ke secure file
     */
    protected function storeKeyToSecureFile(string $service, string $encryptedKey): bool
    {
        try {
            $securePath = storage_path('app/secure-keys');
            
            Log::info('SecureKeyService: Storing to file', [
                'service' => $service,
                'path' => $securePath
            ]);

            // Create directory jika belum ada
            if (!is_dir($securePath)) {
                mkdir($securePath, 0700, true); // Owner only
                Log::info('SecureKeyService: Created secure directory', [
                    'path' => $securePath
                ]);
            }

            $filePath = $securePath . '/' . $service . '.key';

            // Write file
            file_put_contents($filePath, $encryptedKey);

            // Set permission 600 (owner read/write only)
            chmod($filePath, 0600);

            // Verify
            if (file_exists($filePath)) {
                $perms = substr(sprintf('%o', fileperms($filePath)), -4);
                Log::info('SecureKeyService: API key stored to file successfully', [
                    'service' => $service,
                    'path' => $filePath,
                    'permissions' => $perms,
                    'size' => filesize($filePath)
                ]);
            }

            // Clear cache
            Cache::forget($this->keyPrefix . $service);

            return true;
        } catch (\Exception $e) {
            Log::error('SecureKeyService: File storage failed', [
                'service' => $service,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Rotate API key (untuk security maintenance)
     */
    public function rotateApiKey(string $service, string $newApiKey): bool
    {
        try {
            Log::info('SecureKeyService: Starting key rotation', [
                'service' => $service
            ]);

            // Backup old key first
            $oldKey = $this->getGeminiApiKey();
            
            if ($oldKey) {
                $this->backupApiKey($service, $oldKey);
            }

            // Store new key
            $result = $this->storeApiKey($service, $newApiKey);

            if ($result) {
                Log::info('SecureKeyService: API key rotated successfully', [
                    'service' => $service
                ]);
            } else {
                Log::error('SecureKeyService: Key rotation failed - store returned false', [
                    'service' => $service
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('SecureKeyService: Key rotation failed', [
                'service' => $service,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Backup old API key
     */
    protected function backupApiKey(string $service, string $apiKey): void
    {
        try {
            $backupPath = storage_path('app/secure-keys/backups');
            
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0700, true);
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "{$service}_{$timestamp}.key.backup";
            
            $encryptedKey = Crypt::encryptString($apiKey);
            file_put_contents($backupPath . '/' . $filename, $encryptedKey);
            chmod($backupPath . '/' . $filename, 0600);

            Log::info('SecureKeyService: API key backed up', [
                'service' => $service,
                'backup_file' => $filename
            ]);
        } catch (\Exception $e) {
            Log::error('SecureKeyService: Backup failed', [
                'service' => $service,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear cache (untuk security)
     */
    public function clearKeyCache(string $service = null): void
    {
        if ($service) {
            Cache::forget($this->keyPrefix . $service);
            Log::info('SecureKeyService: Cache cleared for service', [
                'service' => $service
            ]);
        } else {
            // Clear all API key caches
            Cache::flush();
            Log::info('SecureKeyService: All caches cleared');
        }
    }

    /**
     * Check key health (untuk monitoring)
     */
    public function checkKeyHealth(string $service): array
    {
        $health = [
            'service' => $service,
            'status' => 'unknown',
            'has_encrypted_key' => false,
            'can_decrypt' => false,
            'format_valid' => false,
            'cached' => false,
            'storage_method' => config('app.use_db_for_keys', false) ? 'database' : 'file',
            'issues' => []
        ];

        try {
            // Check if encrypted key exists
            $encryptedKey = $this->getEncryptedKey($service);
            $health['has_encrypted_key'] = !empty($encryptedKey);

            if (!$health['has_encrypted_key']) {
                $health['issues'][] = 'Encrypted key not found';
                $health['status'] = 'error';
                return $health;
            }

            // Check if can decrypt
            try {
                $decryptedKey = Crypt::decryptString($encryptedKey);
                $health['can_decrypt'] = true;

                // Check format
                $health['format_valid'] = $this->validateApiKeyFormat($decryptedKey);
                
                if (!$health['format_valid']) {
                    $health['issues'][] = 'Invalid API key format';
                }
            } catch (DecryptException $e) {
                $health['issues'][] = 'Decryption failed: ' . $e->getMessage();
            }

            // Check if cached
            $health['cached'] = Cache::has($this->keyPrefix . $service);

            // Determine status
            if ($health['can_decrypt'] && $health['format_valid']) {
                $health['status'] = 'healthy';
            } elseif ($health['can_decrypt']) {
                $health['status'] = 'warning';
            } else {
                $health['status'] = 'error';
            }

        } catch (\Exception $e) {
            $health['status'] = 'error';
            $health['issues'][] = $e->getMessage();
        }

        return $health;
    }
}