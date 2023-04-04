<?php

namespace Blog\Twig;

use Psr\Http\Message\ServerRequestInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension{

	private ServerRequestInterface $request;

	public function __construct(ServerRequestInterface $request){
		$this->request = $request;
	}

	public function getFunctions(): array{
		return [
			new TwigFunction('asset_url', [$this, 'getAsserUrl']),
			new TwigFunction('url', [$this, 'getUrl']),
			new TwigFunction('base_url', [$this, 'getBaseUrl'])
		];
	}
	public function getAsserUrl(string $path): string{
		return $this->getBaseUrl() . $path;
	}
	public function getBaseUrl(): string{
		$params = $this->request->getServerParams();

        $scheme = $params['REQUEST_SCHEME'] ?? 'http';
		return $scheme . '://' . $params['HTTP_HOST'] . '/';
	}
	public function getUrl(string $path): string{
		return $this->getBaseUrl() . $path;
	}
}