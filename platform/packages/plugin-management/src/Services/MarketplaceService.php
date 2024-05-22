<?php

namespace Botble\PluginManagement\Services;

use Botble\Base\Exceptions\RequiresLicenseActivatedException;
use Botble\Base\Supports\Core;
use Botble\Base\Supports\Zipper;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Throwable;

class MarketplaceService
{
    protected string $url;

    protected ?string $token;

    protected string $publishedPath;

    protected string $productId;

    protected string $licenseUrl;

    protected string $licenseApiKey;

    public function __construct(string $url = null, string $token = null)
    {
        $core = Core::make()->getCoreFileData();

        $this->url = $url ?? $core['marketplaceUrl'];

        $this->token = $token ?? $core['marketplaceToken'];

        $this->publishedPath = storage_path('app/marketplace/');

        $this->productId = $core['productId'];

        $this->licenseUrl = $core['apiUrl'];

        $this->licenseApiKey = $core['apiKey'];
    }

    public function callApi(string $method, string $path, array $request = []): JsonResponse|Response
    {
        if (! config('packages.plugin-management.general.enable_marketplace_feature')) {
            abort(404);
        }

        try {
            $request = array_merge($request, [
                'product_id' => $this->productId,
                'site_url' => rtrim(url('')),
                'core_version' => get_core_version(),
            ]);

            $response = $this->request()->{$method}($this->url . $path, $request);

            if ($response->status() !== 200) {
                $body = json_decode($response->body(), true);

                throw new Exception(Arr::get($body, 'message') ?: trans('packages/plugin-management::marketplace.could_not_connect'));
            }

            return $response;
        } catch (Throwable $e) {
            report($e);

            throw new Exception(trans('packages/plugin-management::marketplace.could_not_connect'));
        }
    }

    protected function request(): PendingRequest
    {
        return Http::asJson()
            ->withHeaders([
                'Authorization' => 'Token ' . $this->token,
            ])
            ->acceptJson()
            ->withoutVerifying()
            ->connectTimeout(100)
            ->timeout(300);
    }

    public function beginInstall(string $id, string $name): bool|JsonResponse
    {
        $core = Core::make();
        $licenseFilePath = $core->getLicenseFilePath();

        if (! File::exists($licenseFilePath)) {
            throw new RequiresLicenseActivatedException();
        }

        $data = $this->callApi(
            'post',
            '/products/' . $id . '/download',
            [
                'license_url' => $this->licenseUrl,
                'license_api_key' => $this->licenseApiKey,
                'license_file' => $core->getLicenseFile(),
            ]
        );

        if ($data->getStatusCode() != 200) {
            $content = json_decode($data->getContent(), true);

            throw new Exception(Arr::get($content, 'message') ?: $data);
        }

        File::ensureDirectoryExists($this->publishedPath . $id);

        $destination = $this->publishedPath . $id . '/' . $name . '.zip';

        File::cleanDirectory($this->publishedPath . $id);

        File::put($destination, $data);

        $this->extractFile($id, $name);
        $this->copyToPath($id, plugin_path($name));

        return true;
    }

    protected function extractFile(string $id, string $name): string
    {
        $destination = $this->publishedPath . $id . '/' . $name . '.zip';
        $pathTo = $this->publishedPath . $id;

        $zipper = new Zipper();

        if (! $zipper->extract($destination, $pathTo)) {
            throw new Exception(trans('packages/plugin-management::marketplace.unzip_failed'));
        }

        File::delete($destination);

        return $pathTo;
    }

    protected function copyToPath(string $id, string $path): string
    {
        $pathTemp = $this->publishedPath . $id;

        if (File::isDirectory($pathTemp)) {
            File::copyDirectory($pathTemp, $path);
            File::deleteDirectory($pathTemp);
        }

        return $path;
    }
}
