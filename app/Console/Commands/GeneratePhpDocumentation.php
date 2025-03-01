<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Str;

class GeneratePhpDocumentation extends Command
{
    protected $signature = 'docs:generate {--output=docs}';
    protected $description = 'Generate documentation from PHPDoc comments';

    public function handle()
    {
        $outputPath = $this->option('output');

        // Create output directory if it doesn't exist
        if (!File::isDirectory($outputPath)) {
            File::makeDirectory($outputPath, 0755, true);
        }

        // Get all PHP files in app directory
        $files = File::allFiles(app_path());
        $documentation = [];

        $this->info('Scanning ' . count($files) . ' files for PHPDoc comments...');

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $namespace = $this->getNamespace($content);
                $className = $this->getClassName($content);

                if ($namespace && $className) {
                    $fullyQualifiedClassName = $namespace . '\\' . $className;

                    // Skip if the class doesn't exist (interfaces, traits, etc.)
                    if (!class_exists($fullyQualifiedClassName)) {
                        continue;
                    }

                    try {
                        $reflection = new ReflectionClass($fullyQualifiedClassName);
                        $classDoc = $reflection->getDocComment() ?: 'No documentation available';

                        $methods = [];
                        foreach ($reflection->getMethods() as $method) {
                            // Skip inherited methods
                            if ($method->getDeclaringClass()->getName() !== $fullyQualifiedClassName) {
                                continue;
                            }

                            $methodDoc = $method->getDocComment() ?: 'No documentation available';

                            $methods[$method->getName()] = [
                                'doc' => $this->formatDocComment($methodDoc),
                                'parameters' => $this->getMethodParameters($method),
                                'return' => $method->hasReturnType() ? (string)$method->getReturnType() : 'mixed',
                                'visibility' => $this->getVisibility($method),
                            ];
                        }

                        $documentation[$fullyQualifiedClassName] = [
                            'doc' => $this->formatDocComment($classDoc),
                            'methods' => $methods,
                            'namespace' => $namespace,
                            'className' => $className,
                            'path' => Str::replaceFirst(app_path(), 'app', $file->getPathname()),
                        ];
                    } catch (\Exception $e) {
                        $this->error("Error processing {$fullyQualifiedClassName}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info('Generating documentation files...');

        // Generate index file
        $indexContent = "# API Documentation\n\n";
        $indexContent .= "Generated on " . date('Y-m-d H:i:s') . "\n\n";
        $indexContent .= "## Classes\n\n";

        foreach ($documentation as $className => $info) {
            $shortClassName = $info['className'];
            $filename = str_replace('\\', '_', $className) . '.md';
            $indexContent .= "- [{$shortClassName}](./{$filename})\n";

            // Create class documentation file
            $classContent = "# {$shortClassName}\n\n";
            $classContent .= "Namespace: `{$info['namespace']}`\n\n";
            $classContent .= "File: `{$info['path']}`\n\n";
            $classContent .= "## Description\n\n";
            $classContent .= "{$info['doc']}\n\n";
            $classContent .= "## Methods\n\n";

            foreach ($info['methods'] as $methodName => $methodInfo) {
                $classContent .= "### {$methodName}\n\n";
                $classContent .= "{$methodInfo['doc']}\n\n";
                $classContent .= "**Visibility:** {$methodInfo['visibility']}\n\n";

                if (!empty($methodInfo['parameters'])) {
                    $classContent .= "**Parameters:**\n\n";
                    foreach ($methodInfo['parameters'] as $param) {
                        $classContent .= "- `{$param['type']} \${$param['name']}`: {$param['doc']}\n";
                    }
                    $classContent .= "\n";
                }

                $classContent .= "**Returns:** `{$methodInfo['return']}`\n\n";
                $classContent .= "---\n\n";
            }

            File::put($outputPath . '/' . $filename, $classContent);
        }

        File::put($outputPath . '/index.md', $indexContent);

        $this->info('Documentation generated successfully at ' . $outputPath);
    }

    private function getNamespace($content)
    {
        preg_match('/namespace\s+([^;]+);/', $content, $matches);
        return $matches[1] ?? null;
    }

    private function getClassName($content)
    {
        preg_match('/class\s+([^\s{]+)/', $content, $matches);
        return $matches[1] ?? null;
    }

    private function formatDocComment($comment)
    {
        if (!$comment) {
            return 'No documentation available';
        }

        // Remove comment markers and asterisks
        $lines = explode("\n", $comment);
        $formatted = [];

        foreach ($lines as $line) {
            $line = preg_replace('/^\s*\/\*\*|\s*\*\/\s*$|\s*\*\s?/', '', $line);
            if (trim($line) !== '') {
                $formatted[] = trim($line);
            }
        }

        // Remove @tags for main description
        $description = [];
        foreach ($formatted as $line) {
            if (!Str::startsWith($line, '@')) {
                $description[] = $line;
            }
        }

        return implode("\n", $description);
    }

    private function getMethodParameters(ReflectionMethod $method)
    {
        $params = [];
        $docComment = $method->getDocComment();
        $paramDocs = [];

        // Extract @param tags from doc comment
        if ($docComment) {
            preg_match_all('/@param\s+([^\s]+)\s+\$([^\s]+)\s+(.*)/', $docComment, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $paramDocs[$match[2]] = [
                    'type' => $match[1],
                    'description' => $match[3]
                ];
            }
        }

        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->hasType() ? (string)$param->getType() : 'mixed';
            $description = $paramDocs[$name]['description'] ?? 'No description available';

            $params[] = [
                'name' => $name,
                'type' => $type,
                'doc' => $description
            ];
        }

        return $params;
    }

    private function getVisibility(ReflectionMethod $method)
    {
        if ($method->isPublic()) {
            return 'public';
        } elseif ($method->isProtected()) {
            return 'protected';
        } elseif ($method->isPrivate()) {
            return 'private';
        }
    }
}
