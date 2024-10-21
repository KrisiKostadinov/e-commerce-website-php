<?php
class MetaTagsGenerator
{
    private $defaultTags = [
        'title' => 'Подаръци и Материали за Подаръци | Перфектните Идеи за Всеки Празник',
        'description' => 'Открийте голямо разнообразие от подаръци и материали за тяхното опаковане. Всякакви идеи и вдъхновение за перфектния подарък.',
        'keywords' => 'подаръци, материали за подаръци, опаковане, изненади, персонализирани подаръци, празници',
        'robots' => 'index, follow',
        'charset' => 'UTF-8',
        'viewport' => 'width=device-width, initial-scale=1.0',
        'author' => 'Магазин за Подаръци',
        'og:title' => 'Магазин за Подаръци и Материали - Идеи за Всеки Празник',
        'og:description' => 'Открийте уникални подаръци и материали за опаковане за всеки специален повод. Направете празниците незабравими.',
        'og:image' => 'https://example.com/default-gift-image.jpg',
        'og:url' => 'https://example.com',
        'og:type' => 'website'
    ];

    public function generate($customTags = [])
    {
        $metaTags = array_merge($this->defaultTags, $customTags);

        $output = '';

        if (isset($metaTags['title'])) {
            $output .= '<title>' . htmlspecialchars($metaTags['title']) . '</title>' . PHP_EOL;
        }

        if (isset($metaTags['charset'])) {
            $output .= '<meta charset="' . htmlspecialchars($metaTags['charset']) . '">' . PHP_EOL;
            unset($metaTags['charset']);
        }

        if (isset($metaTags['viewport'])) {
            $output .= '<meta name="viewport" content="' . htmlspecialchars($metaTags['viewport']) . '">' . PHP_EOL;
            unset($metaTags['viewport']);
        }

        foreach ($metaTags as $name => $content) {
            if (strpos($name, 'og:') === 0) {
                $output .= '<meta property="' . htmlspecialchars($name) . '" content="' . htmlspecialchars($content) . '">' . PHP_EOL;
            } else {
                $output .= '<meta name="' . htmlspecialchars($name) . '" content="' . htmlspecialchars($content) . '">' . PHP_EOL;
            }
        }

        return $output;
    }
}
