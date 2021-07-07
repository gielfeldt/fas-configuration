
use Fas\Configuration\ConfigurationInterface;
use Fas\Configuration\NotFoundException;

class <?php print $class; ?> implements ConfigurationInterface
{
    const SETTINGS = <?php print $exportedSettings; ?>;

    public function has(string $key): bool
    {
        return isset(self::SETTINGS[$key]);
    }

    public function get(string $key, $default)
    {
        return self::SETTINGS[$key] ?? $default;
    }

    public function require(string $key)
    {
        if (!$this->has($key)) {
            throw new NotFoundException($key);
        }
        return self::SETTINGS[$key];
    }

    public function all(): array
    {
        return self::SETTINGS;
    }
}
