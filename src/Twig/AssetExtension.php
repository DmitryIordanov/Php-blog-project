<?php

namespace Blog\Twig;

use Twig\Extension\AbstractExtension;

class AssetExtension extends AbstractExtension{

	private array $serverParams;
    private TwigFunctionFactory $twigFunctionFactory;

	public function __construct(array $serverParams, TwigFunctionFactory $twigFunctionFactory){
		$this->serverParams = $serverParams;
        $this->twigFunctionFactory = $twigFunctionFactory;
	}

	public function getFunctions(): array{
		return [
			$this->twigFunctionFactory->create('asset_url', [$this, 'getAsserUrl']),
            $this->twigFunctionFactory->create('url', [$this, 'getUrl']),
            $this->twigFunctionFactory->create('base_url', [$this, 'getBaseUrl'])
		];
	}
	public function getAsserUrl(string $path): string{
		return $this->getBaseUrl() . $path;
	}
	public function getBaseUrl(): string{
        $scheme = $this->serverParams['REQUEST_SCHEME'] ?? 'http';
		return $scheme . '://' . $this->serverParams['HTTP_HOST'] . '/';
	}
	public function getUrl(string $path): string{
		return $this->getBaseUrl() . $path;
	}
}