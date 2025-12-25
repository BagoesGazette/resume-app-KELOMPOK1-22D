<?php

namespace App\Console\Commands;

use App\Services\SecureKeyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class ManageApiKey extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:manage 
                            {action : Action to perform: store, rotate, check, clear, backup}
                            {service=gemini : Service name (gemini, openai, etc)}
                            {--key= : API key value (for store and rotate)}
                            {--force : Force action without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Manage API keys securely';

    protected $secureKeyService;

    public function __construct(SecureKeyService $secureKeyService)
    {
        parent::__construct();
        $this->secureKeyService = $secureKeyService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $service = $this->argument('service');

        switch ($action) {
            case 'store':
                return $this->storeKey($service);
            
            case 'rotate':
                return $this->rotateKey($service);
            
            case 'check':
                return $this->checkKey($service);
            
            case 'clear':
                return $this->clearCache($service);
            
            case 'backup':
                return $this->backupKey($service);
            
            default:
                $this->error("Unknown action: {$action}");
                $this->info("Available actions: store, rotate, check, clear, backup");
                return 1;
        }
    }

    /**
     * Store new API key
     */
    protected function storeKey(string $service): int
    {
        $this->info("=== Store API Key for {$service} ===");
        
        // Get API key
        $apiKey = $this->option('key');
        
        if (!$apiKey) {
            $apiKey = $this->secret('Enter API key (input will be hidden):');
        }

        if (empty($apiKey)) {
            $this->error('API key cannot be empty');
            return 1;
        }

        // Confirmation
        if (!$this->option('force')) {
            $confirm = $this->confirm("Store API key for '{$service}'?", true);
            if (!$confirm) {
                $this->info('Cancelled');
                return 0;
            }
        }

        // Store
        $result = $this->secureKeyService->storeApiKey($service, $apiKey);

        if ($result) {
            $this->info("✅ API key stored successfully for '{$service}'");
            
            // Show storage method
            $method = config('app.use_db_for_keys', false) ? 'database' : 'secure file';
            $this->info("Storage method: {$method}");
            
            // Security reminder
            $this->warn("⚠️  IMPORTANT SECURITY REMINDERS:");
            $this->warn("1. Remove API key from .env file");
            $this->warn("2. Clear terminal history: history -c");
            $this->warn("3. Verify file permissions if using file storage");
            
            return 0;
        }

        $this->error('❌ Failed to store API key');
        return 1;
    }

    /**
     * Rotate existing API key
     */
    protected function rotateKey(string $service): int
    {
        $this->info("=== Rotate API Key for {$service} ===");
        
        // Check current key first
        $currentKey = $this->secureKeyService->getGeminiApiKey();
        if (!$currentKey) {
            $this->error('No existing key found');
            return 1;
        }

        $this->info('Current key found (will be backed up)');
        $this->info('Last 4 chars: ' . substr($currentKey, -4));

        // Get new API key
        $newApiKey = $this->option('key');
        
        if (!$newApiKey) {
            $newApiKey = $this->secret('Enter NEW API key:');
        }

        if (empty($newApiKey)) {
            $this->error('New API key cannot be empty');
            return 1;
        }

        // Confirmation
        if (!$this->option('force')) {
            $this->warn('⚠️  This will replace the current API key');
            $confirm = $this->confirm('Continue with rotation?', false);
            if (!$confirm) {
                $this->info('Cancelled');
                return 0;
            }
        }

        // Rotate
        $result = $this->secureKeyService->rotateApiKey($service, $newApiKey);

        if ($result) {
            $this->info("✅ API key rotated successfully");
            $this->info("Old key has been backed up");
            $this->warn("Clear cache: php artisan apikey:manage clear {$service}");
            return 0;
        }

        $this->error('❌ Failed to rotate API key');
        return 1;
    }

    /**
     * Check API key health
     */
    protected function checkKey(string $service): int
    {
        $this->info("=== Check API Key Health for {$service} ===");
        
        $health = $this->secureKeyService->checkKeyHealth($service);

        // Status
        $statusColor = match($health['status']) {
            'healthy' => 'info',
            'warning' => 'warn',
            'error' => 'error',
            default => 'comment'
        };
        
        $this->$statusColor("Status: " . strtoupper($health['status']));
        $this->newLine();

        // Details
        $this->table(
            ['Check', 'Result'],
            [
                ['Has Encrypted Key', $health['has_encrypted_key'] ? '✅ Yes' : '❌ No'],
                ['Can Decrypt', $health['can_decrypt'] ? '✅ Yes' : '❌ No'],
                ['Format Valid', $health['format_valid'] ? '✅ Yes' : '❌ No'],
                ['Cached', $health['cached'] ? '✅ Yes' : '❌ No'],
            ]
        );

        // Issues
        if (!empty($health['issues'])) {
            $this->newLine();
            $this->error('Issues found:');
            foreach ($health['issues'] as $issue) {
                $this->error("  - {$issue}");
            }
        }

        // Usage stats (if DB)
        if (config('app.use_db_for_keys', false)) {
            try {
                $stats = \DB::table('api_keys')
                    ->where('service', $service)
                    ->first(['last_used_at', 'usage_count', 'updated_at']);

                if ($stats) {
                    $this->newLine();
                    $this->info('Usage Statistics:');
                    $this->info("  Last used: " . ($stats->last_used_at ?? 'Never'));
                    $this->info("  Usage count: {$stats->usage_count}");
                    $this->info("  Updated: {$stats->updated_at}");
                }
            } catch (\Exception $e) {
                // Silent fail
            }
        }

        return $health['status'] === 'healthy' ? 0 : 1;
    }

    /**
     * Clear cache
     */
    protected function clearCache(string $service): int
    {
        $this->info("=== Clear Cache for {$service} ===");
        
        if (!$this->option('force')) {
            $confirm = $this->confirm('Clear API key cache?', true);
            if (!$confirm) {
                $this->info('Cancelled');
                return 0;
            }
        }

        $this->secureKeyService->clearKeyCache($service);
        $this->info("✅ Cache cleared for '{$service}'");
        
        return 0;
    }

    /**
     * Backup current key
     */
    protected function backupKey(string $service): int
    {
        $this->info("=== Backup API Key for {$service} ===");
        
        $apiKey = $this->secureKeyService->getGeminiApiKey();
        
        if (!$apiKey) {
            $this->error('No key found to backup');
            return 1;
        }

        // Create backup
        $backupPath = storage_path('app/secure-keys/backups');
        
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0700, true);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "{$service}_{$timestamp}.key.backup";
        
        $encryptedKey = Crypt::encryptString($apiKey);
        file_put_contents($backupPath . '/' . $filename, $encryptedKey);
        chmod($backupPath . '/' . $filename, 0600);

        $this->info("✅ Backup created: {$filename}");
        $this->info("Location: {$backupPath}");
        
        return 0;
    }
}