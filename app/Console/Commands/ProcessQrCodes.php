<?php

namespace App\Console\Commands;

use App\Repositories\QrProfile\QrProfileRepository;
use App\Repositories\Setting\SettingRepository;
use App\Services\CrudActionsServices\QrProfileService;
use App\Services\FileService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrs:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process regenerating QR codes images from `qr_profiles` table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Получение всех id из таблицы "qr_profiles":
            $qrProfileIds = DB::table('qr_profiles')->orderBy('id', 'asc')->pluck('id');

            if (!empty($qrProfileIds)) {
                $qrProfileService = new QrProfileService();
                $qrProfileRepository = new QrProfileRepository();
                $settingRepository = new SettingRepository();
                $fileService = new FileService();

                $bar = $this->output->createProgressBar(count($qrProfileIds));

                $this->newLine(1);

                // Применение функции processGenerateQrCode для каждого id
                foreach ($qrProfileIds as $id) {
                    $generateQrCode = $qrProfileService->processGenerateQrCode((int) $id, $qrProfileRepository, $settingRepository, $fileService);

                    if ($generateQrCode) {
                        $this->info(sprintf('The image for the QR code #%s has been successfully created.', (int) $id));
                        $this->newLine();
                    }

                    $bar->advance();

                    $this->newLine(2);
                }

                $this->newLine();

                $bar->finish();

                $this->info(sprintf('%s QR codes processed successfully!', count($qrProfileIds)));

                $this->newLine();
            } else {
                $this->error('No records found with QR code ID\'s.');
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
