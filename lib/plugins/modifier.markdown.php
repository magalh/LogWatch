<?php
/**
 * Smarty modifier to convert markdown to HTML
 * 
 * @param string $input Input Markdown text
 * @param string $subtitleElemType HTML Tag name to use for second-level headings (default: h2)
 * @return string HTML output
 */
function smarty_modifier_markdown($input, $subtitleElemType = 'h2') {
    // Convert code blocks with language (```php or ```)
    $codeBlocks = [];
    $htmlContent = preg_replace_callback(
        '/```(\w*)\n([\s\S]*?)```/',
        function($matches) use (&$codeBlocks) {
            $placeholder = '___CODE_BLOCK_' . count($codeBlocks) . '___';
            $code = htmlspecialchars($matches[2], ENT_QUOTES);
            $codeBlocks[] = '<pre><code>' . $code . '</code></pre>';
            return $placeholder;
        },
        $input
    );
    
    // Convert headings (must be done before paragraphs)
    $htmlContent = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $htmlContent);
    $htmlContent = preg_replace('/^## (.+)$/m', '<' . $subtitleElemType . '>$1</' . $subtitleElemType . '>', $htmlContent);
    $htmlContent = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $htmlContent);
    
    // Convert bold and italic
    $htmlContent = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $htmlContent);
    $htmlContent = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $htmlContent);
    
    // Convert inline code
    $htmlContent = preg_replace('/`(.+?)`/', '<code>$1</code>', $htmlContent);
    
    // Convert lists
    $htmlContent = preg_replace('/^- (.+)$/m', '<li>$1</li>', $htmlContent);
    $htmlContent = preg_replace('/((?:<li>.*<\/li>\n?)+)/', '<ul>$1</ul>', $htmlContent);
    
    // Convert numbered lists
    $htmlContent = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $htmlContent);
    $htmlContent = preg_replace('/((?:<li>.*<\/li>\n?)+)/', '<ol>$1</ol>', $htmlContent);
    
    // Convert links
    $htmlContent = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $htmlContent);
    
    // Convert paragraphs (must be last)
    $htmlContent = preg_replace('/^(?!<[holu]|<li|___CODE)(.+)$/m', '<p>$1</p>', $htmlContent);
    
    // Clean up empty paragraphs
    $htmlContent = preg_replace('/<p>\s*<\/p>/', '', $htmlContent);
    
    // Restore code blocks
    foreach ($codeBlocks as $index => $block) {
        $htmlContent = str_replace('___CODE_BLOCK_' . $index . '___', $block, $htmlContent);
    }
    
    return $htmlContent;
}
